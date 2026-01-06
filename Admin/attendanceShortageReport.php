<?php
session_start();
if (!isset($_SESSION['adminId'])) {
    header("Location: ../index.php");
    exit();
}

include "../Includes/dbcon.php";

/* FETCH STUDENTS */
$students = [];
$stuQ = $conn->query("
    SELECT Id, admissionNumber, firstName, lastName
    FROM tblstudents
    ORDER BY admissionNumber
");
while ($s = $stuQ->fetch_assoc()) {
    $students[$s['Id']] = $s;
}

/* AGGREGATE ATTENDANCE */
$attendance = [];
$attQ = $conn->query("
    SELECT studentId, COUNT(*) AS cd, SUM(status) AS ad
    FROM tblattendance_btech
    GROUP BY studentId
");
while ($a = $attQ->fetch_assoc()) {
    $attendance[$a['studentId']] = [
        'cd' => $a['cd'],
        'ad' => $a['ad']
    ];
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Attendance Analytics</title>
<link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

<style>
.green  { background:#d4edda; }
.yellow { background:#fff3cd; }
.red    { background:#f8d7da; }
</style>
</head>

<body>
<div class="container-fluid mt-4">

<h4 class="mb-3"><b>ATTENDANCE ANALYTICS (COLOR ONLY)</b></h4>

<div class="table-responsive">
<table class="table table-bordered text-center">

<thead class="thead-dark">
<tr>
<th>Sl</th>
<th>Regd No</th>
<th>Student Name</th>
<th>Conducted</th>
<th>Attended</th>
<th>%</th>
</tr>
</thead>

<tbody>
<?php
$sl = 1;
foreach ($students as $sid => $stu) {

    $cd = $attendance[$sid]['cd'] ?? 0;
    $ad = $attendance[$sid]['ad'] ?? 0;
    $percent = ($cd > 0) ? round(($ad / $cd) * 100, 2) : 0;

    if ($percent >= 75) {
        $rowClass = "green";
    } elseif ($percent >= 65) {
        $rowClass = "yellow";
    } else {
        $rowClass = "red";
    }

    echo "
    <tr class='$rowClass'>
        <td>$sl</td>
        <td>{$stu['admissionNumber']}</td>
        <td>{$stu['firstName']} {$stu['lastName']}</td>
        <td>$cd</td>
        <td>$ad</td>
        <td>$percent%</td>
    </tr>
    ";

    $sl++;
}
?>
</tbody>

</table>
</div>

<!-- COLOR LEGEND -->
<div class="mt-3">
<span class="badge badge-success">≥ 75%</span>
<span class="badge badge-warning ml-2">65 – 74%</span>
<span class="badge badge-danger ml-2">&lt; 65%</span>
</div>

</div>
</body>
</html>
