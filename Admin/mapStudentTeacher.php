<?php
include '../Includes/dbcon.php';
include '../Includes/session.php';

$msg = "";

/* ===========================
   MAP STUDENT TO TEACHER
   RULE:
   - Teacher has exactly ONE subject
   - Student can have multiple teachers
   - BUT only one teacher per subject
=========================== */
if (isset($_POST['save'])) {

    $studentId = (int)$_POST['studentId'];
    $teacherId = (int)$_POST['teacherId'];

    /* ---- GET SUBJECT OF TEACHER ---- */
    $stmt = $conn->prepare(
        "SELECT subjectId FROM tblfaculty_subject WHERE teacherId = ?"
    );
    $stmt->bind_param("i", $teacherId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        $msg = "<div class='alert alert-danger'>
                  This teacher is not assigned to any subject.
                </div>";
    } else {

        $row = $result->fetch_assoc();
        $subjectId = $row['subjectId'];

        /* ---- CHECK: STUDENT + SUBJECT ---- */
        $stmt = $conn->prepare(
            "SELECT Id FROM tblstudent_teacher
             WHERE studentId = ? AND subjectId = ?"
        );
        $stmt->bind_param("ii", $studentId, $subjectId);
        $stmt->execute();
        $check = $stmt->get_result();

        if ($check->num_rows > 0) {
            $msg = "<div class='alert alert-danger'>
                      Student already assigned to a teacher for this subject.
                    </div>";
        } else {

            /* ---- INSERT MAPPING ---- */
            $stmt = $conn->prepare(
                "INSERT INTO tblstudent_teacher (studentId, teacherId, subjectId)
                 VALUES (?, ?, ?)"
            );
            $stmt->bind_param("iii", $studentId, $teacherId, $subjectId);

            if ($stmt->execute()) {
                $msg = "<div class='alert alert-success'>
                          Student mapped to teacher successfully.
                        </div>";
            } else {
                $msg = "<div class='alert alert-danger'>
                          Failed to map student.
                        </div>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Map Student to Teacher</title>

<link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link href="../css/global-ui.css" rel="stylesheet">
</head>

<body>
<div class="container mt-4">

<h4 class="text-primary mb-3">
  Map Student → Teacher
</h4>

<?php echo $msg; ?>

<form method="post" class="card p-4 shadow">

<!-- ================= STUDENT ================= -->
<div class="form-group">
  <label><b>Select Student</b></label>
  <select name="studentId" class="form-control" required>
    <option value="">-- Select Student --</option>
    <?php
    $students = $conn->query(
        "SELECT Id, firstName, lastName
         FROM tblstudents
         ORDER BY firstName"
    );
    while ($s = $students->fetch_assoc()) {
        echo "<option value='{$s['Id']}'>
                {$s['firstName']} {$s['lastName']}
              </option>";
    }
    ?>
  </select>
</div>

<!-- ================= TEACHER ================= -->
<div class="form-group">
  <label><b>Select Teacher</b></label>
  <select name="teacherId" class="form-control" required>
    <option value="">-- Select Teacher --</option>
    <?php
    $teachers = $conn->query(
        "SELECT t.teacher_id, t.full_name
         FROM tblteacher t
         INNER JOIN tblfaculty_subject fs
           ON fs.teacherId = t.teacher_id
         ORDER BY t.full_name"
    );
    while ($t = $teachers->fetch_assoc()) {
        echo "<option value='{$t['teacher_id']}'>
                {$t['full_name']}
              </option>";
    }
    ?>
  </select>
</div>

<button type="submit" name="save" class="btn btn-primary">
  Map Student to Teacher
</button>

</form>

</div>
</body>
</html>
