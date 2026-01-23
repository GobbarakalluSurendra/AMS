<?php
include "Includes/dbcon.php";
$msg = "";

if (isset($_POST['register'])) {

    $firstName       = trim($_POST['firstName']);
    $lastName        = trim($_POST['lastName']);
    $admissionNumber = trim($_POST['admissionNumber']);
    $email           = trim($_POST['email']);
    $phone           = trim($_POST['phone']);
    $classId         = trim($_POST['classId']);
    $classArmId      = trim($_POST['classArmId']);
    $dateCreated     = date("Y-m-d");

    // ✅ FIX: DEFAULT PASSWORD ONLY
    $password = password_hash("rgmcet123", PASSWORD_DEFAULT);

    $check = $conn->prepare("
        SELECT request_id 
        FROM student_requests 
        WHERE email = ? OR admissionNumber = ?
    ");
    $check->bind_param("ss", $email, $admissionNumber);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        $msg = "<div class='alert alert-danger'>
                  Email or Admission Number already registered.<br>
                  Please wait for admin approval.
                </div>";
    } else {

        $stmt = $conn->prepare("
            INSERT INTO student_requests
            (firstName, lastName, admissionNumber, email, phone, password,
             classId, classArmId, dateCreated)
            VALUES (?,?,?,?,?,?,?,?,?)
        ");

        $stmt->bind_param(
            "sssssssss",
            $firstName,
            $lastName,
            $admissionNumber,
            $email,
            $phone,
            $password,
            $classId,
            $classArmId,
            $dateCreated
        );

        if ($stmt->execute()) {
            $msg = "<div class='alert alert-success'>
                      Registration submitted successfully!<br>
                      Default password: <b>rgmcet123</b>
                    </div>";
        } else {
            $msg = "<div class='alert alert-danger'>
                      Something went wrong. Please try again.
                    </div>";
        }
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Student Registration</title>

<link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet">

<style>
body {
    background: linear-gradient(to right, #4e73df, #1cc88a);
    min-height: 100vh;
}
.card {
    border-radius: 18px;
}
.form-control, .form-select {
    border-radius: 10px;
}
.btn {
    border-radius: 30px;
}
</style>
</head>

<body>

<div class="container d-flex justify-content-center align-items-center" style="min-height:100vh">
<div class="col-md-7">

<div class="card shadow-lg">
<div class="card-header bg-primary text-white text-center">
    <h4>
        <i class="fas fa-user-graduate"></i>
        Student Self Registration
    </h4>
</div>

<div class="card-body">

<?= $msg ?>

<form method="post">

<div class="row">
<div class="col-md-6 mb-3">
<label>First Name</label>
<input type="text" name="firstName" class="form-control" required>
</div>

<div class="col-md-6 mb-3">
<label>Last Name</label>
<input type="text" name="lastName" class="form-control" required>
</div>
</div>

<div class="mb-3">
<label>Admission Number</label>
<input type="text" name="admissionNumber" class="form-control" required>
</div>

<div class="row">
<div class="col-md-6 mb-3">
<label>Email</label>
<input type="email" name="email" class="form-control" required>
</div>

<div class="col-md-6 mb-3">
<label>Phone</label>
<input type="text" name="phone" class="form-control" required>
</div>
</div>

<div class="row">
<div class="col-md-6 mb-3">
<label>Class</label>
<input type="text" name="classId" class="form-control" placeholder="Eg: CSE-2" required>
</div>

<div class="col-md-6 mb-3">
<label>Class Arm / Section</label>
<input type="text" name="classArmId" class="form-control" placeholder="Eg: A" required>
</div>
</div>

<div class="mb-3">
<label>Password</label>
<input type="password" name="password" class="form-control" required>
</div>

<button name="register" class="btn btn-success btn-block mt-3">
<i class="fas fa-paper-plane"></i> Submit Request
</button>

<a href="index.php" class="btn btn-link btn-block mt-2">
Back to Login
</a>

</form>

</div>
</div>

</div>
</div>

<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
