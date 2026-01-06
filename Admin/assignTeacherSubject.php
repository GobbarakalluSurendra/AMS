<?php
include '../Includes/dbcon.php';
include '../Includes/session.php';

$msg = "";

/* ===========================
   ASSIGN ONE SUBJECT TO TEACHER
   RULE:
   - One teacher → only one subject
=========================== */
if (isset($_POST['assign'])) {

    $teacherId = (int)$_POST['teacherId'];
    $subjectId = (int)$_POST['subjectId'];

    /* ---- CHECK: TEACHER ALREADY HAS SUBJECT ---- */
    $stmt = $conn->prepare(
        "SELECT Id FROM tblfaculty_subject
         WHERE teacherId = ?"
    );
    $stmt->bind_param("i", $teacherId);
    $stmt->execute();
    $check = $stmt->get_result();

    if ($check->num_rows > 0) {
        $msg = "<div class='alert alert-danger'>
                  This teacher is already assigned to a subject.
                </div>";
    } else {

        /* ---- INSERT ASSIGNMENT ---- */
        $stmt = $conn->prepare(
            "INSERT INTO tblfaculty_subject (teacherId, subjectId)
             VALUES (?, ?)"
        );
        $stmt->bind_param("ii", $teacherId, $subjectId);

        if ($stmt->execute()) {
            $msg = "<div class='alert alert-success'>
                      Subject assigned to teacher successfully!
                    </div>";
        } else {
            $msg = "<div class='alert alert-danger'>
                      Assignment failed. Please try again.
                    </div>";
        }
    }
}

/* ===========================
   FETCH TEACHERS
=========================== */
$teachers = $conn->query(
    "SELECT teacher_id, full_name
     FROM tblteacher
     ORDER BY full_name"
);

/* ===========================
   FETCH SUBJECTS
=========================== */
$subjects = $conn->query(
    "SELECT Id, subjectName
     FROM tblsubjects
     ORDER BY subjectName"
);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Assign Teacher to Subject</title>

<link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet">

<style>
.card {
    border-radius: 12px;
}
</style>
</head>

<body class="bg-light">

<div class="container mt-5">

<div class="card shadow">
<div class="card-header bg-primary text-white">
    <h5 class="mb-0">
        <i class="fas fa-book-reader"></i>
        Assign Subject to Teacher
    </h5>
</div>

<div class="card-body">

<?php echo $msg; ?>

<form method="post">

<div class="form-group">
    <label><b>Select Teacher</b></label>
    <select name="teacherId" class="form-control" required>
        <option value="">-- Select Teacher --</option>
        <?php while ($t = $teachers->fetch_assoc()) { ?>
            <option value="<?php echo $t['teacher_id']; ?>">
                <?php echo htmlspecialchars($t['full_name']); ?>
            </option>
        <?php } ?>
    </select>
</div>

<div class="form-group">
    <label><b>Select Subject</b></label>
    <select name="subjectId" class="form-control" required>
        <option value="">-- Select Subject --</option>
        <?php while ($s = $subjects->fetch_assoc()) { ?>
            <option value="<?php echo $s['Id']; ?>">
                <?php echo htmlspecialchars($s['subjectName']); ?>
            </option>
        <?php } ?>
    </select>
</div>

<button type="submit" name="assign" class="btn btn-success">
    <i class="fas fa-check"></i> Assign Subject
</button>

<a href="index.php" class="btn btn-secondary ml-2">
    Back
</a>

</form>

</div>
</div>

</div>

<script src="../vendor/jquery/jquery.min.js"></script>
<script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

</body>
</html>
