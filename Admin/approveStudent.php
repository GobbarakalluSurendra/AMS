<?php
include "../Includes/dbcon.php";
include "../Includes/session.php";

if (!isset($_GET['id'])) {
    header("Location: pendingRequests.php");
    exit();
}

$requestId = (int)$_GET['id'];

/* FETCH REQUEST DATA */
$stmt = $conn->prepare(
    "SELECT * FROM student_requests WHERE request_id = ? AND status = 'Pending'"
);
$stmt->bind_param("i", $requestId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Invalid or already processed request");
}

$student = $result->fetch_assoc();

/* APPROVE STUDENT */
if (isset($_POST['approve'])) {

    $classId    = $_POST['classId'];
    $classArmId = $_POST['classArmId'];

    // Generate Admission Number
    $admissionNumber = "ADM" . rand(10000, 99999);
    $dateCreated = date("Y-m-d");

    /* INSERT INTO MAIN STUDENT TABLE */
    $insert = $conn->prepare("
        INSERT INTO tblstudents
        (firstName, lastName, admissionNumber, password, classId, classArmId, dateCreated)
        VALUES (?,?,?,?,?,?,?)
    ");

    $insert->bind_param(
        "sssssss",
        $student['firstName'],
        $student['lastName'],
        $admissionNumber,
        $student['password'],
        $classId,
        $classArmId,
        $dateCreated
    );

    if ($insert->execute()) {

        /* UPDATE REQUEST STATUS */
        $update = $conn->prepare("
            UPDATE student_requests 
            SET status = 'Approved' 
            WHERE request_id = ?
        ");
        $update->bind_param("i", $requestId);
        $update->execute();

        header("Location: pendingRequests.php?approved=1");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Approve Student</title>

<link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet">

<style>
body {
    background: #f8f9fc;
}
.card {
    border-radius: 15px;
}
</style>
</head>

<body>

<div class="container mt-5">
<div class="row justify-content-center">
<div class="col-md-7">

<div class="card shadow">
<div class="card-header bg-success text-white text-center">
    <h4>
        <i class="fas fa-user-check"></i>
        Approve Student Request
    </h4>
</div>

<div class="card-body">

<table class="table table-bordered">
<tr>
<th>First Name</th>
<td><?= htmlspecialchars($student['firstName']) ?></td>
</tr>
<tr>
<th>Last Name</th>
<td><?= htmlspecialchars($student['lastName']) ?></td>
</tr>
<tr>
<th>Email</th>
<td><?= htmlspecialchars($student['email']) ?></td>
</tr>
<tr>
<th>Phone</th>
<td><?= htmlspecialchars($student['phone']) ?></td>
</tr>
</table>

<form method="post">

<div class="row">
<div class="col-md-6 mb-3">
<label>Assign Class</label>
<input type="text" name="classId" class="form-control" required placeholder="Eg: CSE-2">
</div>

<div class="col-md-6 mb-3">
<label>Assign Section / Arm</label>
<input type="text" name="classArmId" class="form-control" required placeholder="Eg: A">
</div>
</div>

<button name="approve" class="btn btn-success btn-block">
<i class="fas fa-check-circle"></i> Approve Student
</button>

<a href="pendingRequests.php" class="btn btn-secondary btn-block mt-2">
Cancel
</a>

</form>

</div>
</div>

</div>
</div>
</div>

<script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
