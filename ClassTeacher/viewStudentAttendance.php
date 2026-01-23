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
.present { background:#1cc88a; color:#fff; padding:5px 10px; border-radius:20px; }
.absent  { background:#e74a3b; color:#fff; padding:5px 10px; border-radius:20px; }
.na      { color:#999; }
.percent { font-weight:bold; }
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
    SELECT DISTINCT
        s.Id,
        s.firstName,
        s.lastName,
        s.admissionNumber
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

    $where = "
        WHERE a.teacherId = '$teacherId'
        AND a.studentId  = '$studentId'
    ";

    if (!empty($from) && !empty($to)) {
        $where .= " AND a.date BETWEEN '$from' AND '$to'";
    }

    $sql = "
    SELECT a.date, a.period, a.status
    FROM tblattendance_btech a
    $where
    ORDER BY a.date DESC, a.period ASC
    ";

    $res = $conn->query($sql);

    $dates   = [];
    $periods = [];

    while ($row = $res->fetch_assoc()) {
        $dates[$row['date']][$row['period']] = $row['status'];
        $periods[$row['period']] = true;
    }

    ksort($periods);
?>

<!-- ================= TABLE ================= -->
<div class="card shadow">
<div class="card-body table-responsive">

<table class="table table-bordered text-center">
<thead class="thead-light">
<tr>
<th>Date</th>
<?php foreach ($periods as $p => $v) echo "<th>P$p</th>"; ?>
<th>Attendance %</th>
</tr>
</thead>

<tbody>
<?php
foreach ($dates as $date => $pdata) {

    $present = 0;
    $total   = count($periods);

    echo "<tr><td><b>$date</b></td>";

    foreach ($periods as $p => $v) {
        if (isset($pdata[$p])) {
            if ($pdata[$p] == 1) {
                echo "<td><span class='present'>P</span></td>";
                $present++;
            } else {
                echo "<td><span class='absent'>A</span></td>";
            }
        } else {
            echo "<td class='na'>—</td>";
            $total--;
        }
    }

    $percent = ($total > 0) ? round(($present / $total) * 100, 2) : 0;
    echo "<td class='percent'>$percent%</td></tr>";
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
