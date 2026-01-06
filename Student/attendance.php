<?php
session_start();
if (!isset($_SESSION['studentId'])) {
    header("Location: ../index.php");
    exit();
}

include "../Includes/dbcon.php";

$studentId = $_SESSION['studentId'];

/* QUERY: DAY-WISE ATTENDANCE */
$sql = "
SELECT 
    date,
    COUNT(*) AS totalPeriods,
    SUM(status) AS presentPeriods
FROM tblattendance_btech
WHERE studentId = '$studentId'
GROUP BY date
ORDER BY date DESC
";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>My Attendance</title>

<link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
<link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link href="../css/ruang-admin.min.css" rel="stylesheet">
<link href="../css/global-ui.css" rel="stylesheet">
</head>

<body id="page-top">

<!-- ===== STUDENT NAVBAR ===== -->
<nav class="navbar navbar-expand navbar-dark bg-primary shadow mb-4">
  <span class="navbar-brand">Student Panel</span>
  <ul class="navbar-nav ml-auto">
    <li class="nav-item text-white mt-2 mr-3">
      <?php echo $_SESSION['studentName']; ?>
    </li>
    <li class="nav-item">
      <a class="btn btn-light btn-sm" href="../logout.php">
        <i class="fas fa-sign-out-alt"></i> Logout
      </a>
    </li>
  </ul>
</nav>

<div class="container-fluid">

<h3 class="text-primary mb-4">
<i class="fas fa-calendar-check"></i> My Attendance
</h3>

<!-- ATTENDANCE TABLE -->
<div class="card shadow mb-4">
<div class="card-header bg-light">
<strong>Day-wise Attendance Summary</strong>
</div>

<div class="card-body table-responsive">
<table class="table table-bordered table-hover">

<thead class="thead-light">
<tr>
<th>#</th>
<th>Date</th>
<th>Present Periods</th>
<th>Total Periods</th>
<th>Percentage</th>
<th>Status</th>
</tr>
</thead>

<tbody>
<?php
$sn = 1;
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {

        $percentage = round(($row['presentPeriods'] / $row['totalPeriods']) * 100, 2);

        $status = ($row['presentPeriods'] > 0)
            ? "<span class='badge badge-success'>Present</span>"
            : "<span class='badge badge-danger'>Absent</span>";

        echo "
        <tr>
            <td>{$sn}</td>
            <td>{$row['date']}</td>
            <td>{$row['presentPeriods']}</td>
            <td>{$row['totalPeriods']}</td>
            <td>{$percentage}%</td>
            <td>{$status}</td>
        </tr>";
        $sn++;
    }
} else {
    echo "
    <tr>
      <td colspan='6' class='text-center text-muted'>
        No attendance records found
      </td>
    </tr>";
}
?>
</tbody>

</table>
</div>
</div>

</div>

<script src="../vendor/jquery/jquery.min.js"></script>
<script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
