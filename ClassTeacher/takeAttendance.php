<?php
include '../Includes/dbcon.php';
include '../Includes/session.php';

if (!isset($_SESSION['teacher_id'])) {
    header("Location: ../index.php");
    exit();
}

$teacherId = $_SESSION['teacher_id'];
$msg = "";

/* ===============================
   TIMEZONE
================================ */
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

    /* COUNT CONTINUOUS PERIODS */
    $cnt = $conn->prepare("
        SELECT COUNT(*) AS total
        FROM timetable
        WHERE teacher_id = ?
          AND subject_id = ?
          AND class_id = ?
          AND day_of_week = ?
          AND period_no >= ?
    ");
    $cnt->bind_param(
        "iiisi",
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

            // ✅ checkbox checked → present (1)
            // ❌ unchecked → absent (0)
            $status = isset($_POST['present'][$studentId]) ? 1 : 0;

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
        ? "<div class='alert alert-success'>
            ✅ Attendance saved for <b>$savedPeriods</b> period(s)
           </div>"
        : "<div class='alert alert-warning'>
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
.att-card { background:#f8f9fc; }
.toggle-btn { min-width:140px; }
</style>
</head>

<body>
<div class="container mt-4">

<h3 class="text-primary mb-3">📝 Take Attendance</h3>

<?= $msg ?>

<?php if (!$allowAttendance) { ?>

<div class="alert alert-danger">
❌ You have no class scheduled at this time.<br>
<b>Today:</b> <?= $currentDay ?><br>
<b>Time:</b> <?= $currentTime ?>
</div>

<?php } else { ?>

<div class="alert alert-info">
<b>Day:</b> <?= $currentDay ?> |
<b>Start Period:</b> <?= $startPeriod ?> |
<b>No. of Periods:</b> <?= $periodCount ?>
</div>

<div class="card shadow">
<div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
<span>👩‍🎓 Student Attendance</span>
<div>
<button type="button" class="btn btn-light btn-sm toggle-btn" onclick="markAll(true)">
All Present
</button>
<button type="button" class="btn btn-light btn-sm toggle-btn" onclick="markAll(false)">
All Absent
</button>
</div>
</div>

<div class="card-body att-card">

<form method="post">

<table class="table table-bordered table-hover">
<thead class="thead-light">
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
<input type="checkbox"
       class="presentChk"
       name="present[<?= $st['Id'] ?>]"
       value="1">
</td>
</tr>
<?php } ?>
</tbody>
</table>

<button type="submit" name="saveAttendance" class="btn btn-success">
💾 Save Attendance
</button>

</form>

</div>
</div>

<?php } ?>

</div>

<script>
function markAll(status) {
    document.querySelectorAll('.presentChk').forEach(chk => {
        chk.checked = status;
    });
}
</script>

</body>
</html>
