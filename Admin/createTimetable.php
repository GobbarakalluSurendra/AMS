<?php
include '../Includes/dbcon.php';
include '../Includes/session.php';

$msg = "";

/* ================= SAVE TIMETABLE ================= */
if (isset($_POST['save'])) {

    $teacher_id = (int)$_POST['teacherId'];
    $subject_id = (int)$_POST['subjectId'];
    $class_id   = (int)$_POST['classId'];
    $day        = $_POST['dayOfWeek'];
    $period_no  = (int)$_POST['period'];
    $start_time = $_POST['startTime'];
    $end_time   = $_POST['endTime'];

    /* -------- TEACHER CLASH CHECK -------- */
    $checkTeacher = $conn->prepare("
        SELECT id FROM timetable
        WHERE teacher_id = ? 
          AND day_of_week = ? 
          AND period_no = ?
    ");
    $checkTeacher->bind_param("isi", $teacher_id, $day, $period_no);
    $checkTeacher->execute();
    $resTeacher = $checkTeacher->get_result();

    /* -------- CLASS CLASH CHECK -------- */
    $checkClass = $conn->prepare("
        SELECT id FROM timetable
        WHERE class_id = ? 
          AND day_of_week = ? 
          AND period_no = ?
    ");
    $checkClass->bind_param("isi", $class_id, $day, $period_no);
    $checkClass->execute();
    $resClass = $checkClass->get_result();

    if ($resTeacher->num_rows > 0) {

        $msg = "<div class='alert alert-danger'>
                ❌ Teacher already has a class in this period
                </div>";

    } elseif ($resClass->num_rows > 0) {

        $msg = "<div class='alert alert-danger'>
                ❌ This class already has a subject in this period
                </div>";

    } else {

        /* -------- INSERT TIMETABLE -------- */
        $stmt = $conn->prepare("
            INSERT INTO timetable
            (teacher_id, subject_id, class_id,
             day_of_week, period_no, start_time, end_time)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->bind_param(
            "iiisiss",
            $teacher_id,
            $subject_id,
            $class_id,
            $day,
            $period_no,
            $start_time,
            $end_time
        );

        if ($stmt->execute()) {
            $msg = "<div class='alert alert-success'>
                    ✅ Timetable added successfully
                    </div>";
        } else {
            $msg = "<div class='alert alert-danger'>
                    ❌ Error saving timetable
                    </div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Create Timetable</title>

<link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
<div class="container mt-4">

<h4 class="text-primary mb-3">Create Timetable</h4>
<?= $msg ?>

<div class="card shadow">
<div class="card-body">

<form method="post">

<div class="row">

<div class="col-md-4">
<label>Teacher</label>
<select name="teacherId" class="form-control" required>
<option value="">Select Teacher</option>
<?php
$teachers = $conn->query("SELECT teacher_id, full_name FROM tblteacher");
while ($t = $teachers->fetch_assoc()) {
    echo "<option value='{$t['teacher_id']}'>
            {$t['full_name']}
          </option>";
}
?>
</select>
</div>

<div class="col-md-4">
<label>Subject</label>
<select name="subjectId" class="form-control" required>
<option value="">Select Subject</option>
<?php
$subs = $conn->query("SELECT Id, subjectName FROM tblsubjects");
while ($s = $subs->fetch_assoc()) {
    echo "<option value='{$s['Id']}'>
            {$s['subjectName']}
          </option>";
}
?>
</select>
</div>

<div class="col-md-4">
<label>Class</label>
<select name="classId" class="form-control" required>
<option value="">Select Class</option>
<?php
$cls = $conn->query("SELECT Id, className FROM tblclass");
while ($c = $cls->fetch_assoc()) {
    echo "<option value='{$c['Id']}'>
            {$c['className']}
          </option>";
}
?>
</select>
</div>

</div>

<br>

<div class="row">

<div class="col-md-4">
<label>Day</label>
<select name="dayOfWeek" class="form-control" required>
<option value="">Select Day</option>
<option>Monday</option>
<option>Tuesday</option>
<option>Wednesday</option>
<option>Thursday</option>
<option>Friday</option>
<option>Saturday</option>
</select>
</div>

<div class="col-md-2">
<label>Period</label>
<select name="period" class="form-control" required>
<?php
for ($i = 1; $i <= 8; $i++) {
    echo "<option value='$i'>P$i</option>";
}
?>
</select>
</div>

<div class="col-md-3">
<label>Start Time</label>
<input type="time" name="startTime" class="form-control" required>
</div>

<div class="col-md-3">
<label>End Time</label>
<input type="time" name="endTime" class="form-control" required>
</div>

</div>

<br>

<button type="submit" name="save" class="btn btn-primary">
Save Timetable
</button>

</form>

</div>
</div>

</div>
</body>
</html>
