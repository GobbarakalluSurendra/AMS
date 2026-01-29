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
<title>View Student Attendance</title>

<link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
<link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link href="../css/ruang-admin.min.css" rel="stylesheet">

<style>
.bg-soft {
    background: #f8f9fc;
}
.summary-card h4 {
    margin-top: 6px;
    font-weight: bold;
}
.summary-card i {
    opacity: 0.85;
}
</style>
</head>

<body id="page-top">
<div id="wrapper">

<?php include "Includes/sidebar.php"; ?>

<div id="content-wrapper" class="d-flex flex-column">
<div id="content">

<?php include "Includes/topbar.php"; ?>

<div class="container-fluid">

<h3 class="mb-4 text-gray-800">
<i class="fas fa-calendar-check text-primary"></i>
View Student Attendance
</h3>

<!-- ================= FILTER ================= -->
<div class="card mb-4 shadow">
<div class="card-body">
<form method="POST" class="row">

<div class="col-md-4">
<label>Student</label>
<select name="studentId" class="form-control" required>
<option value="">-- Select Student --</option>

<?php
$students = $conn->query("
    SELECT DISTINCT s.Id, s.firstName, s.lastName, s.admissionNumber
    FROM tblstudent_teacher stt
    INNER JOIN tblstudents s ON s.Id = stt.studentId
    WHERE stt.teacherId = '$teacherId'
    ORDER BY s.admissionNumber
");

while ($r = $students->fetch_assoc()) {
    echo "<option value='{$r['Id']}'>
            {$r['admissionNumber']} - {$r['firstName']} {$r['lastName']}
          </option>";
}
?>
</select>
</div>

<div class="col-md-3">
<label>From Date</label>
<input type="date" name="fromDate" class="form-control">
</div>

<div class="col-md-3">
<label>To Date</label>
<input type="date" name="toDate" class="form-control">
</div>

<div class="col-md-2 d-flex align-items-end">
<button name="view" class="btn btn-primary btn-block">
<i class="fas fa-search"></i> View
</button>
</div>

</form>
</div>
</div>

<?php
if (isset($_POST['view'])) {

    $studentId = $_POST['studentId'];
    $from      = $_POST['fromDate'];
    $to        = $_POST['toDate'];

    /* ===== STUDENT DETAILS ===== */
    $stu = $conn->query("
        SELECT firstName, lastName, admissionNumber
        FROM tblstudents
        WHERE Id = '$studentId'
    ")->fetch_assoc();

    /* ===== ATTENDANCE CALCULATION ===== */
    $where = "
        WHERE teacherId = '$teacherId'
        AND studentId  = '$studentId'
    ";

    if (!empty($from) && !empty($to)) {
        $where .= " AND date BETWEEN '$from' AND '$to'";
    }

    $res = $conn->query("
        SELECT status FROM tblattendance_btech
        $where
    ");

    $totalClasses = $res->num_rows;
    $totalPresent = 0;

    while ($r = $res->fetch_assoc()) {
        if ($r['status'] == 1) {
            $totalPresent++;
        }
    }

    $attendancePercent = ($totalClasses > 0)
        ? round(($totalPresent / $totalClasses) * 100, 2)
        : 0;
?>

<!-- ================= STUDENT SUMMARY ================= -->
<div class="card shadow mb-4">
<div class="card-body">

<div class="text-center mb-3">
<h5 class="font-weight-bold mb-0">
<?= $stu['firstName'].' '.$stu['lastName'] ?>
</h5>
<small class="text-muted">
Admission No: <?= $stu['admissionNumber'] ?>
</small>
</div>

<hr>

<div class="row text-center">

<div class="col-md-4 mb-3">
<div class="p-3 rounded bg-soft summary-card">
<i class="fas fa-chalkboard-teacher fa-2x text-primary mb-2"></i>
<h6>Classes Conducted</h6>
<h4><?= $totalClasses ?></h4>
</div>
</div>

<div class="col-md-4 mb-3">
<div class="p-3 rounded bg-soft summary-card">
<i class="fas fa-user-check fa-2x text-success mb-2"></i>
<h6>Classes Present</h6>
<h4><?= $totalPresent ?></h4>
</div>
</div>

<div class="col-md-4 mb-3">
<div class="p-3 rounded bg-soft summary-card">
<i class="fas fa-percentage fa-2x text-warning mb-2"></i>
<h6>Attendance %</h6>
<h4><?= $attendancePercent ?>%</h4>
</div>
</div>

</div>

</div>
</div>

<?php } ?>

</div>
</div>

<?php include "Includes/footer.php"; ?>

</div>
</div>

<script src="../vendor/jquery/jquery.min.js"></script>
<script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
