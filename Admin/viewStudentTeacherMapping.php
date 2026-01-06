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

    header("Location: viewStudentTeacherMapping.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Student → Teacher Mapping</title>

<link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
<link href="../css/global-ui.css" rel="stylesheet">

<style>
.badge-teacher {
  background: #4e73df;
  color: #fff;
}
</style>
</head>

<body>

<div class="container-fluid mt-4">

<div class="d-flex justify-content-between align-items-center mb-3">
  <h3 class="text-primary">
    <i class="fas fa-user-friends"></i>
    Student → Teacher (Subject-wise)
  </h3>
</div>

<div class="card shadow">
<div class="card-body table-responsive">

<table class="table table-bordered table-hover">
<thead class="thead-light">
<tr>
  <th>#</th>
  <th>Student Name</th>
  <th>Admission No</th>
  <th>Subject</th>
  <th>Teacher</th>
  <th>Action</th>
</tr>
</thead>

<tbody>
<?php
$query = "
SELECT
    stm.Id,
    st.firstName,
    st.lastName,
    st.admissionNumber,
    sub.subjectName,
    t.full_name AS teacherName
FROM tblstudent_teacher stm
INNER JOIN tblstudents st
    ON st.Id = stm.studentId
INNER JOIN tblsubjects sub
    ON sub.Id = stm.subjectId
INNER JOIN tblteacher t
    ON t.teacher_id = stm.teacherId
ORDER BY st.firstName ASC
";

$result = $conn->query($query);
$sn = 1;

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
?>
<tr>
  <td><?php echo $sn++; ?></td>

  <td>
    <?php echo htmlspecialchars($row['firstName']." ".$row['lastName']); ?>
  </td>

  <td>
    <?php echo htmlspecialchars($row['admissionNumber']); ?>
  </td>

  <td>
    <span class="badge badge-info">
      <?php echo htmlspecialchars($row['subjectName']); ?>
    </span>
  </td>

  <td>
    <span class="badge badge-teacher">
      <?php echo htmlspecialchars($row['teacherName']); ?>
    </span>
  </td>

  <td>
    <a href="?delete=<?php echo $row['Id']; ?>"
       onclick="return confirm('Delete this student-teacher mapping?')"
       class="btn btn-danger btn-sm">
       <i class="fas fa-trash"></i> Delete
    </a>
  </td>
</tr>
<?php
    }
} else {
    echo "
    <tr>
      <td colspan='6' class='text-center text-muted'>
        No student-teacher mappings found
      </td>
    </tr>
    ";
}
?>
</tbody>
</table>

</div>
</div>

</div>

<script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
