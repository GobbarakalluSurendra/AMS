<?php
include "Includes/dbcon.php";
$msg = "";

if (isset($_POST['register'])) {

    $name  = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $pass  = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Check duplicate email
    $check = $conn->prepare(
        "SELECT request_id FROM teacher_requests WHERE email=?"
    );
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        $msg = "<div class='alert alert-danger'>
                  Email already submitted. Please wait for admin approval.
                </div>";
    } else {

        $stmt = $conn->prepare("
            INSERT INTO teacher_requests
            (full_name, email, phone, password)
            VALUES (?,?,?,?)
        ");
        $stmt->bind_param("ssss", $name, $email, $phone, $pass);

        if ($stmt->execute()) {
            $msg = "<div class='alert alert-success'>
                      Registration submitted successfully.<br>
                      <b>Please wait for admin approval.</b>
                    </div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Teacher Registration</title>

<link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet">

<style>
body {
    background: linear-gradient(to right, #4e73df, #1cc88a);
    min-height: 100vh;
}
.card {
    border-radius: 15px;
}
</style>
</head>

<body>

<div class="container d-flex justify-content-center align-items-center" style="min-height:100vh">

<div class="col-md-5">

<div class="card shadow-lg">
<div class="card-header bg-primary text-white text-center">
    <h4>
        <i class="fas fa-chalkboard-teacher"></i>
        Teacher Self Registration
    </h4>
</div>

<div class="card-body">

<?= $msg ?>

<form method="post">

<div class="form-group">
<label>Full Name</label>
<input type="text" name="full_name" class="form-control" required>
</div>

<div class="form-group">
<label>Email</label>
<input type="email" name="email" class="form-control" required>
</div>

<div class="form-group">
<label>Phone</label>
<input type="text" name="phone" class="form-control" required>
</div>

<div class="form-group">
<label>Password</label>
<input type="password" name="password" class="form-control" required>
</div>

<button name="register" class="btn btn-success btn-block mt-3">
<i class="fas fa-paper-plane"></i> Submit Request
</button>

<a href="index.php" class="btn btn-link btn-block">
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
