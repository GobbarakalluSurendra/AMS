<?php
include '../Includes/dbcon.php';
include '../Includes/session.php';

$msg = "";

/* ================= SAVE TIMETABLE ================= */
if (isset($_POST['save'])) {

    $teacherId  = $_POST['teacherId'];
    $subjectId  = $_POST['subjectId'];
    $classId    = $_POST['classId'];
    $classArmId = $_POST['classArmId'];
    $dayOfWeek  = $_POST['dayOfWeek'];
    $period     = $_POST['period'];
    $startTime  = $_POST['startTime'];
    $endTime    = $_POST['endTime'];

    // Check duplicate slot
    $check = $conn->prepare("
        SELECT Id FROM tbltimetable
        WHERE teacherId=? AND dayOfWeek=? AND period=?
    ");
    $check->bind_param("isi", $teacherId, $dayOfWeek, $period);
    $check->execute();
    $res = $check->get_result();

    if ($res->num_rows > 0) {
        $msg = "<div class='alert alert-danger'>
                Timetable already exists for this teacher, day & period
                </div>";
    } else {

        $stmt = $conn->prepare("
            INSERT INTO tbltimetable
            (teacherId, subjectId, classId, classArmId,
             dayOfWeek, period, startTime, endTime)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param(
            "iiiisiss",
            $teacherId,
            $subjectId,
            $classId,
            $classArmId,
            $dayOfWeek,
            $period,
            $startTime,
            $endTime
        );

        if ($stmt->execute()) {
            $msg = "<div class='alert alert-success'>
                    Timetable added successfully
                    </div>";
        } else {
            $msg = "<div class='alert alert-danger'>
                    Error saving timetable
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
<link href="../css/global-ui.css" rel="stylesheet">
</head>

<body>
<div class="container mt-4">

<h4 class="text-primary mb-3">Create Timetable</h4>
<?= $msg ?>

<div class="card shadow mb-4">
<div class="card-body">

<form method="post">

<div class="row">

<div class="col-md-4">
<label>Teacher</label>
<select name="teacherId" class="form-control" required>
<option value="">Select Teacher</option>
<?php
$teachers = $conn->query("SELECT teacher_id, full_name FROM tblteacher WHERE status='Active'");
while ($t = $teachers->fetch_assoc()) {
    echo "<option value='{$t['teacher_id']}'>{$t['full_name']}</option>";
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
    echo "<option value='{$s['Id']}'>{$s['subjectName']}</option>";
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
    echo "<option value='{$c['Id']}'>{$c['className']}</option>";
}
?>
</select>
</div>

</div>

<br>

<div class="row">

<div class="col-md-4">
<label>Section</label>
<select name="classArmId" class="form-control" required>
<option value="">Select Section</option>
<?php
$arms = $conn->query("SELECT Id, classArmName FROM tblclassarms");
while ($a = $arms->fetch_assoc()) {
    echo "<option value='{$a['Id']}'>{$a['classArmName']}</option>";
}
?>
</select>
</div>

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
<?php for ($i=1;$i<=7;$i++) echo "<option value='$i'>P$i</option>"; ?>
</select>
</div>

<div class="col-md-2">
<label>Start Time</label>
<input type="time" name="startTime" class="form-control" required>
</div>

</div>

<br>

<div class="row">
<div class="col-md-2">
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
