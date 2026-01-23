<?php
include '../Includes/dbcon.php';
session_start();

/* ===== TEACHER SESSION CHECK ===== */
if (!isset($_SESSION['teacher_id'])) {
    header("Location: ../index.php");
    exit();
}

$teacherId   = (int)$_SESSION['teacher_id'];
$teacherName = $_SESSION['teacher_name'] ?? "Teacher";
$today       = date('Y-m-d');

/* ==========================
   DASHBOARD COUNTS (FIXED)
========================== */

/* 1️⃣ TOTAL STUDENTS (FROM MAPPING TABLE) */
$stmt = $conn->prepare("
    SELECT COUNT(DISTINCT studentId)
    FROM tblstudent_teacher
    WHERE teacherId = ?
");
$stmt->bind_param("i", $teacherId);
$stmt->execute();
$stmt->bind_result($totalStudents);
$stmt->fetch();
$stmt->close();
$totalStudents = $totalStudents ?? 0;

/* 2️⃣ TOTAL SUBJECTS (FROM MAPPING TABLE) */
$stmt = $conn->prepare("
    SELECT COUNT(DISTINCT subjectId)
    FROM tblstudent_teacher
    WHERE teacherId = ?
");
$stmt->bind_param("i", $teacherId);
$stmt->execute();
$stmt->bind_result($totalSubjects);
$stmt->fetch();
$stmt->close();
$totalSubjects = $totalSubjects ?? 0;

/* 3️⃣ TOTAL SESSIONS (FROM ATTENDANCE TABLE) */
$stmt = $conn->prepare("
    SELECT COUNT(DISTINCT CONCAT(date,'-',period,'-',subjectId))
    FROM tblattendance_btech
    WHERE teacherId = ?
");
$stmt->bind_param("i", $teacherId);
$stmt->execute();
$stmt->bind_result($totalSessions);
$stmt->fetch();
$stmt->close();
$totalSessions = $totalSessions ?? 0;

/* 4️⃣ TODAY PERIODS (FROM ATTENDANCE TABLE) */
$stmt = $conn->prepare("
    SELECT COUNT(DISTINCT CONCAT(period,'-',subjectId))
    FROM tblattendance_btech
    WHERE teacherId = ? AND date = ?
");
$stmt->bind_param("is", $teacherId, $today);
$stmt->execute();
$stmt->bind_result($todayPeriods);
$stmt->fetch();
$stmt->close();
$todayPeriods = $todayPeriods ?? 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Teacher Dashboard</title>

<link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
<link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link href="../css/ruang-admin.min.css" rel="stylesheet">
</head>

<body id="page-top">
<div id="wrapper">

<!-- SIDEBAR -->
<?php include "Includes/sidebar.php"; ?>

<!-- CONTENT WRAPPER -->
<div id="content-wrapper" class="d-flex flex-column">

<!-- MAIN CONTENT -->
<div id="content">

<!-- TOPBAR -->
<?php include "Includes/topbar.php"; ?>

<div class="container-fluid" id="container-wrapper">

<!-- HEADER -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 text-gray-800">
    <i class="fas fa-chalkboard-teacher text-primary"></i>
    Welcome, <?= htmlspecialchars($teacherName); ?>
  </h1>
  <span class="badge badge-info p-2"><?= $today; ?></span>
</div>

<!-- DASHBOARD CARDS -->
<div class="row">

<!-- STUDENTS -->
<div class="col-xl-3 col-md-6 mb-4">
  <div class="card shadow h-100 border-left-primary">
    <div class="card-body">
      <div class="row align-items-center">
        <div class="col">
          <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
            Students
          </div>
          <div class="h5 font-weight-bold"><?= $totalStudents; ?></div>
        </div>
        <div class="col-auto">
          <i class="fas fa-user-graduate fa-2x text-primary"></i>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- SUBJECTS -->
<div class="col-xl-3 col-md-6 mb-4">
  <div class="card shadow h-100 border-left-success">
    <div class="card-body">
      <div class="row align-items-center">
        <div class="col">
          <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
            Subjects
          </div>
          <div class="h5 font-weight-bold"><?= $totalSubjects; ?></div>
        </div>
        <div class="col-auto">
          <i class="fas fa-book fa-2x text-success"></i>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- SESSIONS -->
<div class="col-xl-3 col-md-6 mb-4">
  <div class="card shadow h-100 border-left-warning">
    <div class="card-body">
      <div class="row align-items-center">
        <div class="col">
          <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
            Sessions
          </div>
          <div class="h5 font-weight-bold"><?= $totalSessions; ?></div>
        </div>
        <div class="col-auto">
          <i class="fas fa-calendar-check fa-2x text-warning"></i>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- TODAY -->
<div class="col-xl-3 col-md-6 mb-4">
  <div class="card shadow h-100 border-left-info">
    <div class="card-body">
      <div class="row align-items-center">
        <div class="col">
          <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
            Today
          </div>
          <div class="h5 font-weight-bold"><?= $todayPeriods; ?></div>
        </div>
        <div class="col-auto">
          <i class="fas fa-clock fa-2x text-info"></i>
        </div>
      </div>
    </div>
  </div>
</div>

</div><!-- row -->

</div><!-- container-wrapper -->

</div><!-- content -->

<?php include "Includes/footer.php"; ?>

</div><!-- content-wrapper -->
</div><!-- wrapper -->

<script src="../vendor/jquery/jquery.min.js"></script>
<script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../js/ruang-admin.min.js"></script>
</body>
</html>
