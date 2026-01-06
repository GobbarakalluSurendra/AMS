<?php
include '../Includes/dbcon.php';
include '../Includes/session.php';

if (!isset($_SESSION['teacher_id'])) {
    header("Location: ../index.php");
    exit();
}

$teacherId = $_SESSION['teacher_id'];
$today     = date('Y-m-d');

/* =====================
   DOWNLOAD ATTENDANCE
===================== */
if (isset($_GET['download'])) {

    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=todays_attendance_$today.xls");

    echo "Student Name\tAdmission No\tSubject\tPeriod\tStatus\n";

    $stmt = $conn->prepare("
        SELECT 
            st.firstName,
            st.lastName,
            st.admissionNumber,
            sb.subjectName,
            a.period,
            a.status
        FROM tblattendance_btech a
        JOIN tblstudents st ON st.Id = a.studentId
        JOIN tblsubjects sb ON sb.Id = a.subjectId
        WHERE a.teacherId = ?
          AND a.date = ?
        ORDER BY a.period
    ");
    $stmt->bind_param("is", $teacherId, $today);
    $stmt->execute();
    $res = $stmt->get_result();

    while ($r = $res->fetch_assoc()) {
        $status = ($r['status'] == 1) ? 'Present' : 'Absent';

        echo
            $r['firstName']." ".$r['lastName']."\t".
            $r['admissionNumber']."\t".
            $r['subjectName']."\t".
            "P".$r['period']."\t".
            $status."\n";
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Today's Attendance</title>

<link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
<link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link href="../css/ruang-admin.min.css" rel="stylesheet">
</head>

<body id="page-top">
<div id="wrapper">

<?php include "Includes/sidebar.php"; ?>

<div id="content-wrapper" class="d-flex flex-column">
<div id="content">

<?php include "Includes/topbar.php"; ?>

<div class="container-fluid">

<h3 class="mb-4 text-gray-800">
<i class="fas fa-calendar-day text-primary"></i>
Today's Attendance (<?= $today ?>)
</h3>

<!-- DOWNLOAD BUTTON -->
<a href="?download=1" class="btn btn-success mb-3">
<i class="fas fa-file-excel"></i> Download Excel
</a>

<div class="card shadow mb-4">
<div class="card-body table-responsive">

<table class="table table-bordered">
<thead class="thead-light">
<tr>
<th>#</th>
<th>Period</th>
<th>Subject</th>
<th>Total</th>
<th>Present</th>
<th>Absent</th>
</tr>
</thead>

<tbody>
<?php
$stmt = $conn->prepare("
    SELECT 
        a.period,
        sb.subjectName,
        COUNT(a.studentId) AS total,
        SUM(a.status = 1) AS present
    FROM tblattendance_btech a
    JOIN tblsubjects sb ON sb.Id = a.subjectId
    WHERE a.teacherId = ?
      AND a.date = ?
    GROUP BY a.period, a.subjectId
    ORDER BY a.period
");
$stmt->bind_param("is", $teacherId, $today);
$stmt->execute();
$res = $stmt->get_result();

$sn = 1;
if ($res->num_rows > 0) {
    while ($row = $res->fetch_assoc()) {
        $absent = $row['total'] - $row['present'];

        echo "
        <tr>
            <td>{$sn}</td>
            <td>Period {$row['period']}</td>
            <td>{$row['subjectName']}</td>
            <td>{$row['total']}</td>
            <td class='text-success'>{$row['present']}</td>
            <td class='text-danger'>{$absent}</td>
        </tr>";
        $sn++;
    }
} else {
    echo "<tr><td colspan='6' class='text-center text-danger'>No data</td></tr>";
}
?>
</tbody>
</table>

</div>
</div>

</div>
</div>

<?php include "Includes/footer.php"; ?>
</div>
</div>

<script src="../vendor/jquery/jquery.min.js"></script>
<script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
