<?php
session_start();

/* ===== ADMIN AUTH CHECK ===== */
if (!isset($_SESSION['adminId'])) {
    header("Location: ../index.php");
    exit();
}

include "../Includes/dbcon.php";

/* ===============================
   FETCH SUBJECTS
================================ */
$subjects = [];
$subQ = $conn->query("
    SELECT Id, subjectName
    FROM tblsubjects
    ORDER BY Id
");
while ($s = $subQ->fetch_assoc()) {
    $subjects[$s['Id']] = $s['subjectName'];
}

/* ===============================
   FETCH STUDENTS (ORDER BY ADM NO)
================================ */
$students = [];
$stuQ = $conn->query("
    SELECT Id, admissionNumber, firstName, lastName
    FROM tblstudents
    ORDER BY admissionNumber ASC
");
while ($st = $stuQ->fetch_assoc()) {
    $students[$st['Id']] = $st;
}

/* ===============================
   AGGREGATE ATTENDANCE
================================ */
$attendance = [];
$attQ = $conn->query("
    SELECT studentId, subjectId,
           COUNT(*) AS cd,
           SUM(status) AS ad
    FROM tblattendance_btech
    GROUP BY studentId, subjectId
");
while ($a = $attQ->fetch_assoc()) {
    $attendance[$a['studentId']][$a['subjectId']] = [
        'cd' => $a['cd'],
        'ad' => $a['ad']
    ];
}

/* ===============================
   EXPORT EXCEL
================================ */
if (isset($_GET['export'])) {

    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=Consolidated_Attendance.xls");

    echo "Sl\tRegd No\tStudent Name";

    foreach ($subjects as $sub) {
        echo "\t".strtoupper($sub)." Cd\t".strtoupper($sub)." Ad";
    }
    echo "\tTotal Cd\tTotal Ad\t%\n";

    $sl = 1;
    foreach ($students as $sid => $stu) {

        $totalCd = 0;
        $totalAd = 0;

        echo $sl."\t".$stu['admissionNumber']."\t".$stu['firstName']." ".$stu['lastName'];

        foreach ($subjects as $subId => $subName) {
            $cd = $attendance[$sid][$subId]['cd'] ?? 0;
            $ad = $attendance[$sid][$subId]['ad'] ?? 0;
            $totalCd += $cd;
            $totalAd += $ad;
            echo "\t$cd\t$ad";
        }

        $percent = ($totalCd > 0) ? round(($totalAd / $totalCd) * 100, 2) : 0;
        echo "\t$totalCd\t$totalAd\t$percent\n";
        $sl++;
    }
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Consolidated Attendance</title>
<link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<style>
th { background:#ffe600; text-align:center; }
td { text-align:center; }
.student { text-align:left; }
.total { background:#f4cccc; font-weight:bold; }
.percent { background:#fff2cc; font-weight:bold; }
</style>
</head>

<body>
<div class="container-fluid mt-4">

<div class="d-flex justify-content-between mb-3">
<h4><b>CONSOLIDATED ATTENDANCE REPORT</b></h4>
<a href="?export=1" class="btn btn-success">Download Excel</a>
</div>

<div class="table-responsive">
<table class="table table-bordered">
<thead>
<tr>
<th rowspan="2">Sl</th>
<th rowspan="2">Regd No</th>
<th rowspan="2">Student Name</th>
<?php foreach ($subjects as $s) echo "<th colspan='2'>".strtoupper($s)."</th>"; ?>
<th colspan="2">TOTAL</th>
<th rowspan="2">%</th>
</tr>
<tr>
<?php foreach ($subjects as $s) echo "<th>Cd</th><th>Ad</th>"; ?>
<th>Cd</th><th>Ad</th>
</tr>
</thead>

<tbody>
<?php
$sl=1;
foreach ($students as $sid=>$stu) {

$totalCd=0; $totalAd=0;

echo "<tr>
<td>$sl</td>
<td>{$stu['admissionNumber']}</td>
<td class='student'>{$stu['firstName']} {$stu['lastName']}</td>";

foreach ($subjects as $subId=>$s) {
$cd=$attendance[$sid][$subId]['cd']??0;
$ad=$attendance[$sid][$subId]['ad']??0;
$totalCd+=$cd; $totalAd+=$ad;
echo "<td>$cd</td><td>$ad</td>";
}

$percent=($totalCd>0)?round(($totalAd/$totalCd)*100,2):0;
echo "<td class='total'>$totalCd</td>
<td class='total'>$totalAd</td>
<td class='percent'>$percent%</td></tr>";

$sl++;
}
?>
</tbody>
</table>
</div>
</div>
</body>
</html>
