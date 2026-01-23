<?php
include '../Includes/dbcon.php';
include '../Includes/session.php';

/* ================= DELETE MAPPING ================= */
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];

    $stmt = $conn->prepare(
        "DELETE FROM tblstudent_teacher WHERE Id = ?"
    );
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    header("Location: viewStudentTeacherMapping.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Student–Faculty Mapping</title>

<link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
<link href="../css/global-ui.css" rel="stylesheet">

<style>
.badge-teacher {
  background: #4e73df;
  color: #fff;
}
.student-block {
  background: #f8f9fc;
  border-left: 5px solid #4e73df;
}
</style>
</head>

<body>

<div class="container-fluid mt-4">

<h3 class="text-primary mb-4">
  <i class="fas fa-id-card"></i>
  Student → Faculty 
</h3>

<?php
$query = "
SELECT
    stm.Id AS mapId,
    st.admissionNumber,
    st.firstName,
    st.lastName,
    sub.subjectName,
    t.full_name AS teacherName
FROM tblstudent_teacher stm
INNER JOIN tblstudents st ON st.Id = stm.studentId
INNER JOIN tblsubjects sub ON sub.Id = stm.subjectId
INNER JOIN tblteacher t ON t.teacher_id = stm.teacherId
ORDER BY st.admissionNumber, sub.subjectName
";

$result = $conn->query($query);

$currentAdmission = "";
$sn = 1;

if ($result && $result->num_rows > 0) {

    while ($row = $result->fetch_assoc()) {

        /* ===== NEW STUDENT BLOCK ===== */
        if ($currentAdmission !== $row['admissionNumber']) {

            if ($currentAdmission !== "") {
                echo "</tbody></table></div></div>";
            }

            $currentAdmission = $row['admissionNumber'];
            $studentName = $row['firstName'] . " " . $row['lastName'];
            ?>

            <div class="card shadow mb-4 student-block">
            <div class="card-header bg-white">
                <h5 class="mb-0 text-primary">
                    <?= htmlspecialchars($studentName); ?>
                    <small class="text-muted">
                        (<?= htmlspecialchars($currentAdmission); ?>)
                    </small>
                </h5>
            </div>

            <div class="card-body table-responsive">
            <table class="table table-bordered">
            <thead class="thead-light">
            <tr>
                <th>#</th>
                <th>Subject</th>
                <th>Teacher</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>

            <?php
            $sn = 1;
        }
        ?>

        <tr>
            <td><?= $sn++; ?></td>

            <td>
                <span class="badge badge-info">
                    <?= htmlspecialchars($row['subjectName']); ?>
                </span>
            </td>

            <td>
                <span class="badge badge-teacher">
                    <?= htmlspecialchars($row['teacherName']); ?>
                </span>
            </td>

            <td>
                <a href="?delete=<?= $row['mapId']; ?>"
                   onclick="return confirm('Delete this mapping?')"
                   class="btn btn-danger btn-sm">
                   <i class="fas fa-trash"></i>
                </a>
            </td>
        </tr>

        <?php
    }

    echo "</tbody></table></div></div>";

} else {
?>
<div class="alert alert-warning text-center">
    No student–faculty mappings found
</div>
<?php } ?>

</div>

<script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
