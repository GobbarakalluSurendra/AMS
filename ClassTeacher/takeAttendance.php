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
$msg = "";

/* ===============================
   FETCH SUBJECTS ASSIGNED TO TEACHER
================================ */
$subjects = $conn->query("
    SELECT s.Id, s.subjectName
    FROM tblsubjects s
    INNER JOIN tblfaculty_subject fs ON fs.subjectId = s.Id
    WHERE fs.teacherId = '$teacherId'
");

/* ===============================
   LOAD ONLY ASSIGNED STUDENTS
   (ADMISSION NUMBER WISE)
================================ */
$students = null;

if (isset($_POST['loadStudents'])) {

    $subjectId = $_POST['subjectId'];

    $students = $conn->query("
        SELECT 
            s.Id,
            s.firstName,
            s.lastName,
            s.admissionNumber
        FROM tblstudent_teacher stt
        INNER JOIN tblstudents s ON s.Id = stt.studentId
        WHERE stt.teacherId = '$teacherId'
          AND stt.subjectId = '$subjectId'
        ORDER BY s.admissionNumber
    ");
}

/* ===============================
   SAVE ATTENDANCE (MULTI-PERIOD SAFE)
================================ */
if (isset($_POST['saveAttendance'])) {

    $subjectId    = $_POST['subjectId'];
    $date         = $_POST['date'];
    $startPeriod  = (int)$_POST['startPeriod'];
    $periodCount  = (int)$_POST['periodCount'];

    $savedPeriods = 0;

    for ($p = $startPeriod; $p < ($startPeriod + $periodCount); $p++) {

        if ($p > 7) break;

        $check = $conn->query("
            SELECT Id FROM tblattendance_btech
            WHERE subjectId='$subjectId'
              AND teacherId='$teacherId'
              AND date='$date'
              AND period='$p'
        ");

        if ($check->num_rows > 0) {
            continue;
        }

        foreach ($_POST['status'] as $studentId => $status) {
            $conn->query("
                INSERT INTO tblattendance_btech
                (studentId, subjectId, teacherId, period, date, status)
                VALUES
                ('$studentId','$subjectId','$teacherId','$p','$date','$status')
            ");
        }

        $savedPeriods++;
    }

    if ($savedPeriods > 0) {
        $msg = "<div class='alert alert-success'>
                  Attendance saved successfully for <b>$savedPeriods</b> period(s)
                </div>";
    } else {
        $msg = "<div class='alert alert-warning'>
                  Attendance already taken for selected period(s)
                </div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Take Attendance</title>

<link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
<link href="../css/ruang-admin.min.css" rel="stylesheet">
</head>

<body id="page-top">
<div id="wrapper">

<?php include "Includes/sidebar.php"; ?>

<div id="content-wrapper" class="d-flex flex-column">
<div id="content">

<?php include "Includes/topbar.php"; ?>

<div class="container-fluid">

<h3 class="mb-4 text-primary">
<i class="fas fa-calendar-check"></i> Take Attendance
</h3>

<?= $msg ?>

<!-- STEP 1 -->
<div class="card shadow mb-4">
<div class="card-body">

<form method="post">
<div class="row">

<div class="col-md-3">
<label>Subject</label>
<select name="subjectId" class="form-control" required>
<option value="">Select Subject</option>
<?php while ($s = $subjects->fetch_assoc()) { ?>
<option value="<?= $s['Id'] ?>"><?= $s['subjectName'] ?></option>
<?php } ?>
</select>
</div>

<div class="col-md-3">
<label>Date</label>
<input type="date" name="date" class="form-control" required>
</div>

<div class="col-md-3">
<label>Start Period</label>
<select name="startPeriod" class="form-control" required>
<?php for ($i = 1; $i <= 7; $i++) { ?>
<option value="<?= $i ?>">Period <?= $i ?></option>
<?php } ?>
</select>
</div>

<div class="col-md-3">
<label>No. of Periods</label>
<select name="periodCount" class="form-control" required>
<option value="1">1 Period</option>
<option value="2">2 Periods</option>
<option value="3">3 Periods</option>
</select>
</div>

</div>

<br>
<button type="submit" name="loadStudents" class="btn btn-primary">
<i class="fas fa-users"></i> Load Students
</button>
</form>

</div>
</div>

<!-- STEP 2 -->
<?php if ($students && $students->num_rows > 0) { ?>

<div class="card shadow mb-4">
<div class="card-header bg-primary text-white">
Mark Attendance
</div>

<div class="card-body">
<form method="post">

<input type="hidden" name="subjectId" value="<?= $_POST['subjectId'] ?>">
<input type="hidden" name="date" value="<?= $_POST['date'] ?>">
<input type="hidden" name="startPeriod" value="<?= $_POST['startPeriod'] ?>">
<input type="hidden" name="periodCount" value="<?= $_POST['periodCount'] ?>">

<table class="table table-bordered">
<thead>
<tr>
<th>Admission No</th>
<th>Student Name</th>
<th>Status</th>
</tr>
</thead>

<tbody>
<?php while ($st = $students->fetch_assoc()) { ?>
<tr>
<td><?= htmlspecialchars($st['admissionNumber']) ?></td>
<td><?= htmlspecialchars($st['firstName']." ".$st['lastName']) ?></td>
<td>
<input type="radio" name="status[<?= $st['Id'] ?>]" value="1" checked> Present
<input type="radio" name="status[<?= $st['Id'] ?>]" value="0"> Absent
</td>
</tr>
<?php } ?>
</tbody>
</table>

<button type="submit" name="saveAttendance" class="btn btn-success">
Save Attendance
</button>

</form>
</div>
</div>

<?php } ?>

</div>
</div>
</div>
</div>

<script src="../vendor/jquery/jquery.min.js"></script>
<script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
