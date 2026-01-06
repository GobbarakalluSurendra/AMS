<?php
session_start();
if (!isset($_SESSION['studentId'])) {
    header("Location: ../index.php");
    exit();
}

include "../Includes/dbcon.php";

$studentId   = $_SESSION['studentId'];
$studentName = $_SESSION['studentName'];

/* ===============================
   SUBJECT-WISE ATTENDANCE
================================ */
$stmt = $conn->prepare("
    SELECT 
        s.subjectName,
        COUNT(a.Id) AS totalPeriods,
        SUM(a.status) AS attendedPeriods
    FROM tblattendance_btech a
    INNER JOIN tblsubjects s ON s.Id = a.subjectId
    WHERE a.studentId = ?
    GROUP BY a.subjectId
");
$stmt->bind_param("i", $studentId);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Student Dashboard</title>

<link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
<link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link href="../css/ruang-admin.min.css" rel="stylesheet">
<link href="../css/global-ui.css" rel="stylesheet">

<style>
.progress { height: 10px; }
.badge { font-size: 0.85rem; }
</style>
</head>

<body id="page-top">
<div id="wrapper">

<!-- ================= SIDEBAR ================= -->
<ul class="navbar-nav sidebar sidebar-light accordion">

  <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
    <div class="sidebar-brand-icon">
      <i class="fas fa-user-graduate"></i>
    </div>
    <div class="sidebar-brand-text mx-3">Student</div>
  </a>

  <hr class="sidebar-divider">

  <li class="nav-item active">
    <a class="nav-link" href="index.php">
      <i class="fas fa-tachometer-alt"></i>
      <span>Dashboard</span>
    </a>
  </li>

  <li class="nav-item">
    <a class="nav-link" href="attendance.php">
      <i class="fas fa-calendar-check"></i>
      <span>My Attendance</span>
    </a>
  </li>

  <li class="nav-item">
    <a class="nav-link" href="chapters.php">
      <i class="fas fa-book"></i>
      <span>Notes</span>
    </a>
  </li>

  <li class="nav-item">
    <a class="nav-link" href="profile.php">
      <i class="fas fa-user"></i>
      <span>My Profile</span>
    </a>
  </li>

  <!-- LOGOUT -->
<li class="nav-item">
  <a class="nav-link logout-link" href="logout.php"
     onclick="return confirm('Are you sure you want to logout?');">
    <i class="fas fa-sign-out-alt"></i>
    <span>Logout</span>
  </a>
</li>


</ul>
<!-- ================= END SIDEBAR ================= -->

<div id="content-wrapper" class="d-flex flex-column">
<div id="content">

<!-- ================= TOPBAR ================= -->
<nav class="navbar navbar-expand navbar-light bg-navbar topbar mb-4">
  <span class="text-white ml-3 font-weight-bold">
    Welcome, <?= htmlspecialchars($studentName); ?>
  </span>
</nav>

<!-- ================= MAIN CONTENT ================= -->
<div class="container-fluid">

<h1 class="h3 mb-4 text-gray-800">
<i class="fas fa-chart-pie text-primary"></i> Attendance Overview
</h1>

<div class="row">

<?php
if ($result->num_rows > 0) {
while ($row = $result->fetch_assoc()) {

    $total   = (int)$row['totalPeriods'];
    $present = (int)$row['attendedPeriods'];
    $percentage = ($total > 0) ? round(($present / $total) * 100, 2) : 0;

    /* COLOR RULES */
    if ($percentage >= 75) {
        $bar = "bg-success";
        $badge = "badge-success";
        $label = "Good Standing";
    } elseif ($percentage >= 65) {
        $bar = "bg-warning";
        $badge = "badge-warning";
        $label = "Warning Zone";
    } else {
        $bar = "bg-danger";
        $badge = "badge-danger";
        $label = "Attendance Shortage";
    }
?>

<!-- ================= SUBJECT CARD ================= -->
<div class="col-xl-4 col-md-6 mb-4">
<div class="card shadow h-100">
<div class="card-body">

<div class="text-xs font-weight-bold text-uppercase text-primary mb-1">
<?= htmlspecialchars($row['subjectName']); ?>
</div>

<div class="h4 font-weight-bold text-gray-800">
<?= $percentage ?>%
</div>

<div class="progress mb-2">
  <div class="progress-bar <?= $bar ?>" style="width: <?= $percentage ?>%"></div>
</div>

<small>
Attended <?= $present ?> / <?= $total ?> periods
</small>

<div class="mt-2">
<span class="badge <?= $badge ?>">
<?= $label ?>
</span>
</div>

</div>
</div>
</div>

<?php } } else { ?>

<div class="col-12">
<div class="alert alert-info text-center">
<i class="fas fa-info-circle"></i>
No attendance records available yet.
</div>
</div>

<?php } ?>

</div>
</div>

</div>

<!-- ================= FOOTER ================= -->
<footer class="sticky-footer bg-white">
        <div class="container my-auto">
          <div class="copyright text-center my-auto">
            <span> &copy; <script> document.write(new Date().getFullYear()); </script> - Developed by Surendra G
            </span>
          </div>
        </div>
      </footer>
<!-- ================= END FOOTER ================= -->

</div>
</div>

<script src="../vendor/jquery/jquery.min.js"></script>
<script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../js/ruang-admin.min.js"></script>
</body>
</html>
