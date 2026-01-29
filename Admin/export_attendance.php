<?php
session_start();

/* ===== ADMIN AUTH CHECK ===== */
if (!isset($_SESSION['adminId'])) {
    header("Location: ../index.php");
    exit();
}

include "../Includes/dbcon.php";

/* ===============================
   FETCH BASE SUBJECTS (ALL)
================================ */
$subjects = [];
$subQ = $conn->query("
    SELECT DISTINCT baseSubject
    FROM tblsubjects
    WHERE baseSubject IS NOT NULL AND baseSubject != ''
    ORDER BY baseSubject
");
while ($s = $subQ->fetch_assoc()) {
    $subjects[] = $s['baseSubject'];
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
   BUILD COMPLETE ATTENDANCE MATRIX
   (NO SUBJECT MISSING)
================================ */
$attendance = [];

$attQ = $conn->query("
    SELECT 
        st.Id AS studentId,
        bs.baseSubject,
        COUNT(a.Id) AS cd,
        SUM(CASE WHEN a.status = 1 THEN 1 ELSE 0 END) AS ad
    FROM tblstudents st
    CROSS JOIN (
        SELECT DISTINCT baseSubject
        FROM tblsubjects
        WHERE baseSubject IS NOT NULL AND baseSubject != ''
    ) bs
    LEFT JOIN tblsubjects sub
        ON sub.baseSubject = bs.baseSubject
    LEFT JOIN tblattendance_btech a
        ON a.studentId = st.Id
       AND a.subjectId = sub.Id
    GROUP BY st.Id, bs.baseSubject
");

while ($a = $attQ->fetch_assoc()) {
    $attendance[$a['studentId']][$a['baseSubject']] = [
        'cd' => (int)$a['cd'],
        'ad' => (int)$a['ad']
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

        echo "{$sl}\t{$stu['admissionNumber']}\t{$stu['firstName']} {$stu['lastName']}";

        foreach ($subjects as $sub) {
            $cd = $attendance[$sid][$sub]['cd'] ?? 0;
            $ad = $attendance[$sid][$sub]['ad'] ?? 0;

            $totalCd += $cd;
            $totalAd += $ad;

            echo "\t{$cd}\t{$ad}";
        }

        $percent = ($totalCd > 0)
            ? round(($totalAd / $totalCd) * 100, 2)
            : 0;

        echo "\t{$totalCd}\t{$totalAd}\t{$percent}\n";

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
/* ===== GENERAL ===== */
body {
    background: #f8f9fa;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

h4 {
    color: #2c3e50;
    letter-spacing: 1px;
}

/* ===== REPORT BOX ===== */
.report-box {
    position: relative;
    background: #ffffff;
    border: 1px solid #dee2e6;
    border-radius: 6px;
    padding: 15px;
    margin-bottom: 15px;
}

/* ===== DOWNLOAD BUTTON ===== */
.download-btn {
    position: absolute;
    top: 10px;
    right: 10px;
    border-radius: 25px;
    padding: 8px 22px;
    font-weight: 600;
    box-shadow: 0 3px 6px rgba(0,0,0,0.15);
}

.download-btn:hover {
    transform: scale(1.05);
    transition: 0.2s;
}

/* ===== TABLE ===== */
.table {
    background: #ffffff;
    border-radius: 8px;
    overflow: hidden;
}

.table th,
.table td {
    vertical-align: middle;
    padding: 10px;
    font-size: 14px;
    text-align: center;
    border: 1px solid #dee2e6;
}

/* Hover row */
.table tbody tr:hover {
    background-color: #f1f7ff;
    transition: 0.3s;
}

/* ===== TABLE HEADER ===== */
th {
    background: linear-gradient(135deg, #ffe600, #ffcc00);
    color: #000;
    font-weight: bold;
    text-transform: uppercase;
}

/* Sticky header */
thead th {
    position: sticky;
    top: 0;
    z-index: 2;
}

/* ===== STUDENT COLUMN ===== */
.student {
    text-align: left;
    font-weight: 500;
    color: #34495e;
}

/* ===== TOTAL CELLS ===== */
.total {
    background: #f4cccc;
    font-weight: bold;
    color: #7a1c1c;
}

/* ===== PERCENT CELL ===== */
.percent {
    background: #fff2cc;
    font-weight: bold;
}

/* ===== PERCENT CONDITIONS ===== */
.low {
    background: #f8d7da;
    color: #721c24;
    font-weight: bold;
}

.medium {
    background: #fff3cd;
    color: #856404;
    font-weight: bold;
}

.high {
    background: #d4edda;
    color: #155724;
    font-weight: bold;
}

/* ===== MOBILE RESPONSIVE ===== */
@media (max-width: 768px) {
    .table th,
    .table td {
        font-size: 12px;
        padding: 6px;
    }

    .download-btn {
        position: static;
        display: block;
        margin: 10px auto 0;
    }
}
</style>

</head>

<body>

<div class="report-box mb-3">
    <h4 class="mb-0 text-center"><b>ATTENDANCE REPORT</b></h4>
    <a href="?export=1" class="btn btn-success download-btn">Download Excel</a>
</div>

<div class="table-responsive">
<table class="table table-bordered">

<thead>
<tr>
    <th rowspan="2">Sl</th>
    <th rowspan="2">Regd No</th>
    <th rowspan="2">Student Name</th>

    <?php foreach ($subjects as $s) echo "<th colspan='2'>".strtoupper($s)."</th>"; ?>

    <th colspan="2">Total</th>
    <th rowspan="2">%</th>
</tr>
<tr>
    <?php foreach ($subjects as $s) echo "<th>Cd</th><th>Ad</th>"; ?>
    <th>Cd</th>
    <th>Ad</th>
</tr>
</thead>

<tbody>
<?php
$sl = 1;

foreach ($students as $sid => $stu) {

    $totalCd = 0;
    $totalAd = 0;

    echo "<tr>
        <td>{$sl}</td>
        <td>{$stu['admissionNumber']}</td>
        <td class='student'>{$stu['firstName']} {$stu['lastName']}</td>";

    foreach ($subjects as $sub) {

        $cd = $attendance[$sid][$sub]['cd'] ?? 0;
        $ad = $attendance[$sid][$sub]['ad'] ?? 0;

        $totalCd += $cd;
        $totalAd += $ad;

        echo "<td>{$cd}</td><td>{$ad}</td>";
    }

    $percent = ($totalCd > 0)
        ? round(($totalAd / $totalCd) * 100, 2)
        : 0;

    $class = ($percent < 75) ? "low" : (($percent < 85) ? "medium" : "high");

    echo "
        <td class='total'>{$totalCd}</td>
        <td class='total'>{$totalAd}</td>
        <td class='{$class}'>{$percent}%</td>
    </tr>";

    $sl++;
}
?>
</tbody>

</table>
</div>

</body>
</html>
