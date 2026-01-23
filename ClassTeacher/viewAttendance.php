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
$subjectId = $_POST['subjectId'] ?? '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Class Attendance</title>

<link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
<link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link href="../css/ruang-admin.min.css" rel="stylesheet">

<style>
.present { background:#1cc88a;color:#fff;padding:5px 8px;border-radius:6px }
.absent { background:#e74a3b;color:#fff;padding:5px 8px;border-radius:6px }
.na { color:#aaa }
.percent { font-weight:bold }
th { white-space:nowrap }
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
<i class="fas fa-layer-group text-primary"></i>
Class Attendance (Admission No Wise)
</h3>

<!-- FILTER -->
<div class="card shadow mb-4">
<div class="card-body">
<form method="POST" class="row">

<div class="col-md-6">
<label>Select Subject</label>
<select name="subjectId" class="form-control" required>
<option value="">-- Select Subject --</option>

<?php
$sub = $conn->query("
    SELECT s.Id, s.subjectName
    FROM tblsubjects s
    INNER JOIN tblfaculty_subject fs ON fs.subjectId = s.Id
    WHERE fs.teacherId = '$teacherId'
");

while ($s = $sub->fetch_assoc()) {
    $sel = ($subjectId == $s['Id']) ? 'selected' : '';
    echo "<option value='{$s['Id']}' $sel>{$s['subjectName']}</option>";
}
?>
</select>
</div>

<div class="col-md-6 d-flex align-items-end">
<button class="btn btn-primary btn-block">
<i class="fas fa-search"></i> View Attendance
</button>
</div>

</form>
</div>
</div>

<?php
if ($subjectId) {

$query = $conn->query("
SELECT 
    s.Id AS studentId,
    s.firstName,
    s.lastName,
    s.admissionNumber,
    a.date,
    a.period,
    a.status
FROM tblattendance_btech a
INNER JOIN tblstudents s ON s.Id = a.studentId
WHERE a.teacherId = '$teacherId'
  AND a.subjectId = '$subjectId'
ORDER BY s.admissionNumber, a.date, a.period
");

$students = [];
$dates = [];

while ($row = $query->fetch_assoc()) {

    $sid    = $row['studentId'];
    $date   = $row['date'];
    $period = $row['period'];

    $students[$sid]['adm']  = $row['admissionNumber'];
    $students[$sid]['name'] = $row['firstName'].' '.$row['lastName'];
    $students[$sid]['data'][$date][$period] = $row['status'];

    $dates[$date][$period] = true;
}

ksort($dates);
?>

<!-- TABLE -->
<div class="card shadow mb-4">
<div class="card-body table-responsive">

<table class="table table-bordered text-center">

<thead class="thead-light">

<tr>
<th rowspan="2">#</th>
<th rowspan="2">Adm No</th>
<th rowspan="2">Student</th>

<?php
foreach ($dates as $date => $ps) {
    echo "<th colspan='".count($ps)."'>$date</th>";
}
?>
<th rowspan="2">%</th>
</tr>

<tr>
<?php
foreach ($dates as $ps) {
    ksort($ps);
    foreach ($ps as $p => $v) echo "<th>P$p</th>";
}
?>
</tr>

</thead>

<tbody>
<?php
$sn = 1;
foreach ($students as $stu) {

    $present = 0;
    $total   = 0;

    echo "<tr>
        <td>$sn</td>
        <td>{$stu['adm']}</td>
        <td>{$stu['name']}</td>";

    foreach ($dates as $date => $ps) {
        foreach ($ps as $p => $v) {

            if (isset($stu['data'][$date][$p])) {
                $total++;
                if ($stu['data'][$date][$p] == 1) {
                    echo "<td><span class='present'>P</span></td>";
                    $present++;
                } else {
                    echo "<td><span class='absent'>A</span></td>";
                }
            } else {
                echo "<td class='na'>—</td>";
            }
        }
    }

    $percent = ($total > 0) ? round(($present / $total) * 100, 2) : 0;
    echo "<td class='percent'>$percent%</td></tr>";

    $sn++;
}
?>
</tbody>

</table>

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
