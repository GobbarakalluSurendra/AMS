<?php
include '../Includes/dbcon.php';
include '../Includes/session.php';

/* ======================
   TEACHER SESSION CHECK
====================== */
if (!isset($_SESSION['teacher_id'])) {
    header("Location: ../index.php");
    exit();
}

$teacherId = $_SESSION['teacher_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>My Assigned Students</title>

<link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
<link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link href="css/ruang-admin.min.css" rel="stylesheet">
</head>

<body id="page-top">
<div id="wrapper">

<!-- SIDEBAR -->
<?php include "Includes/sidebar.php"; ?>

<div id="content-wrapper" class="d-flex flex-column">
<div id="content">

<!-- TOPBAR -->
<?php include "Includes/topbar.php"; ?>

<div class="container-fluid">

<h1 class="h3 mb-4 text-gray-800">
<i class="fas fa-user-graduate text-primary"></i>
My Assigned Students
</h1>

<div class="card shadow mb-4">
<div class="card-body table-responsive">

<table class="table table-bordered table-hover">
<thead class="thead-light">
<tr>
<th>#</th>
<th>First Name</th>
<th>Last Name</th>
<th>Admission No</th>
</tr>
</thead>

<tbody>
<?php
$stmt = $conn->prepare("
    SELECT 
        s.firstName,
        s.lastName,
        s.admissionNumber
    FROM tblstudent_teacher stt
    INNER JOIN tblstudents s ON s.Id = stt.studentId
    WHERE stt.teacherId = ?
    GROUP BY s.admissionNumber, s.firstName, s.lastName
    ORDER BY s.admissionNumber
");

$stmt->bind_param("i", $teacherId);
$stmt->execute();
$result = $stmt->get_result();

$sn = 1;

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "
        <tr>
            <td>{$sn}</td>
            <td>{$row['firstName']}</td>
            <td>{$row['lastName']}</td>
            <td>{$row['admissionNumber']}</td>
        </tr>";
        $sn++;
    }
} else {
    echo "
    <tr>
        <td colspan='4' class='text-center text-danger'>
            No students assigned to you
        </td>
    </tr>";
}
?>
</tbody>
</table>

</div>
</div>

</div>
</div>

<?php include "Includes/footer.php"; ?>
</div>
</div>

<script src="../vendor/jquery/jquery.min.js"></script>
<script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
