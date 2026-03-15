<?php
include '../Includes/dbcon.php';
include '../Includes/session.php';

if (!isset($_SESSION['teacher_id'])) {
    header("Location: ../index.php");
    exit();
}

$teacherId = $_SESSION['teacher_id'];
$msg = "";

date_default_timezone_set('Asia/Kolkata');

$currentDay  = date('l');
$currentTime = date('H:i:s');
$currentDate = date('Y-m-d');

/* ===============================
   AUTO DETECT CURRENT CLASS
================================ */
$allowAttendance = false;
$startPeriod = 0;
$periodCount = 0;
$subjectId = 0;
$classId = 0;

$cur = $conn->prepare("
    SELECT *
    FROM timetable
    WHERE teacher_id = ?
      AND day_of_week = ?
      AND TIME(?) BETWEEN start_time AND end_time
    ORDER BY period_no ASC
");
$cur->bind_param("iss", $teacherId, $currentDay, $currentTime);
$cur->execute();
$resCur = $cur->get_result();

if ($resCur->num_rows > 0) {
    $allowAttendance = true;
    $row = $resCur->fetch_assoc();

    $startPeriod = (int)$row['period_no'];
    $subjectId   = (int)$row['subject_id'];
    $classId     = (int)$row['class_id'];

    $cnt = $conn->prepare("
        SELECT COUNT(*) AS total
        FROM timetable
        WHERE teacher_id = ?
          AND subject_id = ?
          AND class_id = ?
          AND day_of_week = ?
          AND period_no >= ?
    ");
    $cnt->bind_param("iiisi",
        $teacherId,
        $subjectId,
        $classId,
        $currentDay,
        $startPeriod
    );
    $cnt->execute();
    $periodCount = (int)$cnt->get_result()->fetch_assoc()['total'];
}

/* ===============================
   LOAD STUDENTS
================================ */
$students = null;
if ($allowAttendance) {
    $students = $conn->query("
        SELECT s.Id, s.firstName, s.lastName, s.admissionNumber
        FROM tblstudent_teacher stt
        INNER JOIN tblstudents s ON s.Id = stt.studentId
        WHERE stt.teacherId = '$teacherId'
          AND stt.subjectId = '$subjectId'
        ORDER BY s.admissionNumber
    ");
}

/* ===============================
   SAVE ATTENDANCE
================================ */
if (isset($_POST['saveAttendance']) && $allowAttendance) {

    $savedPeriods = 0;
    $presentArr = isset($_POST['present']) ? $_POST['present'] : [];

    for ($p = $startPeriod; $p < ($startPeriod + $periodCount); $p++) {

        $check = $conn->query("
            SELECT id FROM tblattendance_btech
            WHERE subjectId='$subjectId'
              AND teacherId='$teacherId'
              AND date='$currentDate'
              AND period='$p'
        ");

        if ($check->num_rows > 0) continue;

        foreach ($_POST['students'] as $studentId) {
            $status = isset($presentArr[$studentId]) ? 1 : 0;

            $conn->query("
                INSERT INTO tblattendance_btech
                (studentId, subjectId, teacherId, period, date, status)
                VALUES
                ('$studentId','$subjectId','$teacherId','$p','$currentDate','$status')
            ");
        }
        $savedPeriods++;
    }

    $msg = $savedPeriods > 0
        ? "<div class='alert alert-success text-center fw-bold'>
            Attendance saved for $savedPeriods period(s)
           </div>"
        : "<div class='alert alert-warning text-center'>
            Attendance already taken
           </div>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Take Attendance</title>

<link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    background: #f4f6f9;
}
.page-header {
    background: #ffffff;
    padding: 20px;
    border-radius: 10px;
    margin-bottom: 20px;
}
.info-chip {
    background: #eef2ff;
    padding: 8px 14px;
    border-radius: 20px;
    font-weight: 600;
    margin-right: 10px;
    display: inline-block;
}
.att-card {
    background: #ffffff;
    border-radius: 12px;
}
.toggle-btn {
    min-width: 130px;
}
.table thead th {
    background: #f1f5f9;
}
.presentChk {
    width: 20px;
    height: 20px;
}
.sticky-footer {
    position: sticky;
    bottom: 0;
    background: #ffffff;
    padding: 15px;
    border-top: 1px solid #dee2e6;
}
</style>
</head>

<body>

<div class="container mt-4">

<div class="page-header">
    <h3 class="text-primary mb-1">📝 Take Attendance</h3>
    <small class="text-muted"><?= $currentDate ?> | <?= $currentTime ?></small>
</div>

<?= $msg ?>

<?php if (!$allowAttendance) { ?>

<div class="alert alert-danger text-center">
❌ No class scheduled right now
<br>
<b><?= $currentDay ?></b> | <?= $currentTime ?>
</div>

<?php } else { ?>

<div class="mb-3">
    <span class="info-chip">Day: <?= $currentDay ?></span>
    <span class="info-chip">Start Period: <?= $startPeriod ?></span>
    <span class="info-chip">Total Periods: <?= $periodCount ?></span>
</div>

<div class="card shadow-sm att-card">

<div class="card-header d-flex justify-content-between align-items-center bg-primary text-white">
    <b>Student Attendance</b>
    <div>
        <button type="button" class="btn btn-light btn-sm toggle-btn" onclick="markAll(true)">
            All Present
        </button>
        <button type="button" class="btn btn-light btn-sm toggle-btn" onclick="markAll(false)">
            All Absent
        </button>
    </div>
</div>

<form method="post">

<div class="card-body p-0">

<table class="table table-hover mb-0">
<thead>
<tr>
<th>Admission No</th>
<th>Student Name</th>
<th class="text-center">Present</th>
</tr>
</thead>

<tbody>
<?php while ($st = $students->fetch_assoc()) { ?>
<tr>
<td><?= $st['admissionNumber'] ?></td>
<td><?= $st['firstName']." ".$st['lastName'] ?></td>
<td class="text-center">
<input type="hidden" name="students[]" value="<?= $st['Id'] ?>">
<input type="checkbox" class="presentChk" name="present[<?= $st['Id'] ?>]">
</td>
</tr>
<?php } ?>
</tbody>
</table>

</div>

<div class="sticky-footer text-end">
<button type="submit" name="saveAttendance" class="btn btn-success px-4">
💾 Save Attendance
</button>
</div>

</form>

</div>

<?php } ?>

</div>

<script>
function markAll(status) {
    document.querySelectorAll('.presentChk').forEach(chk => chk.checked = status);
}
</script>

</body>
</html>
