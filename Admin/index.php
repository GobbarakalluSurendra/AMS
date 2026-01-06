<?php
// SESSION & DB
include '../Includes/dbcon.php';
include '../Includes/session.php';

// DASHBOARD COUNTS
$students    = mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM tblstudents"));
$classes     = mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM tblclass"));
$classArms   = mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM tblclassarms"));
$teacherArm  = mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM tblteacher_classarm"));
$attendance  = mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM tblattendance_btech"));
$sessions    = mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM tblsessionterm"));
$terms       = mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM tblterm"));
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Admin Dashboard</title>

<link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
<link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link href="css/ruang-admin.min.css" rel="stylesheet">
<link href="../css/global-ui.css" rel="stylesheet">

<style>
.dashboard-card {
    border-left: 5px solid;
}
.border-students { border-color: #36b9cc; }
.border-classes { border-color: #4e73df; }
.border-arms { border-color: #1cc88a; }
.border-teacher { border-color: #e74a3b; }
.border-attendance { border-color: #858796; }
.border-session { border-color: #f6c23e; }
.border-terms { border-color: #20c9a6; }
</style>
</head>

<body id="page-top">
<div id="wrapper">

<!-- SIDEBAR -->
<?php include "Includes/sidebar.php"; ?>
<!-- END SIDEBAR -->

<div id="content-wrapper" class="d-flex flex-column">
<div id="content">

<!-- TOPBAR -->
<?php include "Includes/topbar.php"; ?>
<!-- END TOPBAR -->

<!-- CONTAINER -->
<div class="container-fluid" id="container-wrapper">

<!-- HEADER -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 text-gray-800">Administrator Dashboard</h1>
    <a href="export_attendance.php" class="btn btn-success">
        <i class="fas fa-file-excel"></i> Export Attendance
    </a>
</div>

<div class="row">

<!-- STUDENTS -->
<div class="col-xl-3 col-md-6 mb-4">
<div class="card dashboard-card border-students shadow h-100 py-2">
<div class="card-body">
<div class="row align-items-center">
<div class="col">
<div class="text-xs font-weight-bold text-info text-uppercase mb-1">Students</div>
<div class="h5 mb-0 font-weight-bold"><?= $students ?></div>
</div>
<div class="col-auto">
<i class="fas fa-users fa-2x text-info"></i>
</div>
</div>
</div>
</div>
</div>

<!-- CLASSES -->
<div class="col-xl-3 col-md-6 mb-4">
<div class="card dashboard-card border-classes shadow h-100 py-2">
<div class="card-body">
<div class="row align-items-center">
<div class="col">
<div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Classes</div>
<div class="h5 mb-0 font-weight-bold"><?= $classes ?></div>
</div>
<div class="col-auto">
<i class="fas fa-chalkboard fa-2x text-primary"></i>
</div>
</div>
</div>
</div>
</div>

<!-- CLASS ARMS -->
<div class="col-xl-3 col-md-6 mb-4">
<div class="card dashboard-card border-arms shadow h-100 py-2">
<div class="card-body">
<div class="row align-items-center">
<div class="col">
<div class="text-xs font-weight-bold text-success text-uppercase mb-1">Class Arms</div>
<div class="h5 mb-0 font-weight-bold"><?= $classArms ?></div>
</div>
<div class="col-auto">
<i class="fas fa-code-branch fa-2x text-success"></i>
</div>
</div>
</div>
</div>
</div>

<!-- TEACHER ASSIGNMENTS -->
<div class="col-xl-3 col-md-6 mb-4">
<div class="card dashboard-card border-teacher shadow h-100 py-2">
<div class="card-body">
<div class="row align-items-center">
<div class="col">
<div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Teacher Assignments</div>
<div class="h5 mb-0 font-weight-bold"><?= $teacherArm ?></div>
</div>
<div class="col-auto">
<i class="fas fa-chalkboard-teacher fa-2x text-danger"></i>
</div>
</div>
</div>
</div>
</div>

<!-- ATTENDANCE -->
<div class="col-xl-3 col-md-6 mb-4">
<div class="card dashboard-card border-attendance shadow h-100 py-2">
<div class="card-body">
<div class="row align-items-center">
<div class="col">
<div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">Total Attendance</div>
<div class="h5 mb-0 font-weight-bold"><?= $attendance ?></div>
</div>
<div class="col-auto">
<i class="fas fa-calendar-check fa-2x text-secondary"></i>
</div>
</div>
</div>
</div>
</div>

<!-- SESSIONS -->
<div class="col-xl-3 col-md-6 mb-4">
<div class="card dashboard-card border-session shadow h-100 py-2">
<div class="card-body">
<div class="row align-items-center">
<div class="col">
<div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Sessions & Terms</div>
<div class="h5 mb-0 font-weight-bold"><?= $sessions ?></div>
</div>
<div class="col-auto">
<i class="fas fa-calendar-alt fa-2x text-warning"></i>
</div>
</div>
</div>
</div>
</div>

<!-- TERMS -->
<div class="col-xl-3 col-md-6 mb-4">
<div class="card dashboard-card border-terms shadow h-100 py-2">
<div class="card-body">
<div class="row align-items-center">
<div class="col">
<div class="text-xs font-weight-bold text-info text-uppercase mb-1">Terms</div>
<div class="h5 mb-0 font-weight-bold"><?= $terms ?></div>
</div>
<div class="col-auto">
<i class="fas fa-th fa-2x text-info"></i>
</div>
</div>
</div>
</div>
</div>

</div> <!-- row -->

</div> <!-- container -->

</div>

<!-- FOOTER -->
<?php include "Includes/footer.php"; ?>
<!-- END FOOTER -->

</div>
</div>

<script src="../vendor/jquery/jquery.min.js"></script>
<script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
<script src="js/ruang-admin.min.js"></script>

</body>
</html>
