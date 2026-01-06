<?php
include "../Includes/dbcon.php";
include "../Includes/session.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Pending Requests</title>

<link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet">

<style>
.badge-pending { background:#f6c23e; }
</style>
</head>

<body>

<div class="container-fluid mt-4">

<h2 class="mb-4 text-primary">
<i class="fas fa-clock"></i> Pending Requests
</h2>

<?php if (isset($_GET['approved'])) { ?>
<div class="alert alert-success">
✅ Teacher approved successfully!
</div>
<?php } ?>

<!-- ================= STUDENT REQUESTS ================= -->
<div class="card shadow mb-4">
<div class="card-header bg-primary text-white">
<h5><i class="fas fa-user-graduate"></i> Student Requests</h5>
</div>

<div class="card-body table-responsive">
<table class="table table-bordered">
<thead>
<tr>
<th>#</th>
<th>Name</th>
<th>Email</th>
<th>Status</th>
<th>Action</th>
</tr>
</thead>
<tbody>

<?php
$sn = 1;
$q = $conn->query("SELECT * FROM student_requests WHERE status='Pending'");
while ($r = $q->fetch_assoc()) {
?>
<tr>
<td><?= $sn++ ?></td>
<td><?= htmlspecialchars($r['firstName']." ".$r['lastName']) ?></td>
<td><?= htmlspecialchars($r['email']) ?></td>
<td><span class="badge badge-pending">Pending</span></td>
<td>
<a href="approveStudent.php?id=<?= $r['request_id'] ?>"
   class="btn btn-success btn-sm">
Approve
</a>
</td>
</tr>
<?php } ?>

</tbody>
</table>
</div>
</div>

<!-- ================= TEACHER REQUESTS ================= -->
<div class="card shadow mb-4">
<div class="card-header bg-success text-white">
<h5><i class="fas fa-chalkboard-teacher"></i> Teacher Requests</h5>
</div>

<div class="card-body table-responsive">
<table class="table table-bordered">
<thead>
<tr>
<th>#</th>
<th>Name</th>
<th>Email</th>
<th>Status</th>
<th>Action</th>
</tr>
</thead>
<tbody>

<?php
$sn = 1;
$q = $conn->query("SELECT * FROM teacher_requests WHERE status='Pending'");
while ($r = $q->fetch_assoc()) {
?>
<tr>
<td><?= $sn++ ?></td>
<td><?= htmlspecialchars($r['full_name']) ?></td>
<td><?= htmlspecialchars($r['email']) ?></td>
<td><span class="badge badge-pending">Pending</span></td>
<td>
<a href="approveTeacher.php?id=<?= $r['request_id'] ?>"
   class="btn btn-success btn-sm">
Approve
</a>
</td>
</tr>
<?php } ?>

</tbody>
</table>
</div>
</div>

</div>

<script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
