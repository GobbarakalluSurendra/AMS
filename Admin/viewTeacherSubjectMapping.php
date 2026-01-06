<?php
include '../Includes/dbcon.php';
include '../Includes/session.php';

/* ================= DELETE MAPPING ================= */
if (isset($_GET['delete'])) {

    $id = (int)$_GET['delete'];

    $stmt = $conn->prepare(
        "DELETE FROM tblfaculty_subject WHERE Id = ?"
    );
    $stmt->bind_param("i", $id);
    $stmt->execute();

    header("Location: viewTeacherSubjectMapping.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Teacher → Subject Mapping</title>

<link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
<div class="container mt-4">

<h3 class="mb-3 text-primary">
  Teacher → Subject Mapping
</h3>

<div class="card shadow">
<div class="card-body table-responsive">

<table class="table table-bordered table-hover">
<thead class="thead-light">
<tr>
  <th>#</th>
  <th>Teacher</th>
  <th>Subject</th>
  <th>Action</th>
</tr>
</thead>

<tbody>
<?php
$query = "
SELECT 
    fs.Id,
    t.full_name AS teacherName,
    s.subjectName
FROM tblfaculty_subject fs
INNER JOIN tblteacher t 
    ON t.teacher_id = fs.teacherId
INNER JOIN tblsubjects s 
    ON s.Id = fs.subjectId
ORDER BY t.full_name
";

$result = $conn->query($query);
$sn = 1;

if ($result->num_rows == 0) {
    echo "
    <tr>
      <td colspan='4' class='text-center text-muted'>
        No teacher–subject mappings found
      </td>
    </tr>";
}

while ($row = $result->fetch_assoc()) {
?>
<tr>
  <td><?php echo $sn++; ?></td>
  <td><?php echo htmlspecialchars($row['teacherName']); ?></td>
  <td><?php echo htmlspecialchars($row['subjectName']); ?></td>
  <td>
    <a href="?delete=<?php echo $row['Id']; ?>"
       onclick="return confirm('Delete this mapping?')"
       class="btn btn-danger btn-sm">
       Delete
    </a>
  </td>
</tr>
<?php } ?>
</tbody>
</table>

</div>
</div>

</div>
</body>
</html>
