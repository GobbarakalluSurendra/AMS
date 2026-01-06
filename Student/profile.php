<?php
session_start();
if (!isset($_SESSION['studentId'])) {
    header("Location: ../index.php");
    exit();
}

include "../Includes/dbcon.php";

$studentId = $_SESSION['studentId'];
$message = "";

/* ===============================
   UPDATE PROFILE
================================ */
if (isset($_POST['update'])) {

    $firstName = trim($_POST['firstName']);
    $lastName  = trim($_POST['lastName']);
    $password  = trim($_POST['password']);

    if (!empty($password)) {
        // update with password
        $hashed = password_hash($password, PASSWORD_DEFAULT);

        $sql = "
            UPDATE tblstudents 
            SET firstName='$firstName',
                lastName='$lastName',
                password='$hashed'
            WHERE Id='$studentId'
        ";
    } else {
        // update without password
        $sql = "
            UPDATE tblstudents 
            SET firstName='$firstName',
                lastName='$lastName'
            WHERE Id='$studentId'
        ";
    }

    if ($conn->query($sql)) {
        $_SESSION['studentName'] = $firstName . " " . $lastName;
        $message = "<div class='alert alert-success'>
                        <i class='fas fa-check-circle'></i>
                        Profile updated successfully
                    </div>";
    } else {
        $message = "<div class='alert alert-danger'>
                        <i class='fas fa-exclamation-triangle'></i>
                        Error updating profile
                    </div>";
    }
}

/* ===============================
   FETCH STUDENT DATA
================================ */
$result = $conn->query("SELECT firstName, lastName FROM tblstudents WHERE Id='$studentId'");
$student = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>My Profile</title>

<link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
<link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link href="../css/ruang-admin.min.css" rel="stylesheet">

<style>
.profile-card {
    max-width: 500px;
    margin: auto;
}
</style>
</head>

<body id="page-top">
<div id="wrapper">

<!-- SIDEBAR -->
<ul class="navbar-nav sidebar sidebar-light accordion">
  <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
    <div class="sidebar-brand-icon">
      <i class="fas fa-user-graduate"></i>
    </div>
    <div class="sidebar-brand-text mx-3">Student</div>
  </a>

  <hr class="sidebar-divider my-0">

  <li class="nav-item">
    <a class="nav-link" href="index.php">
      <i class="fas fa-tachometer-alt"></i>
      <span>Dashboard</span>
    </a>
  </li>

  <li class="nav-item">
    <a class="nav-link" href="attendance.php">
      <i class="fas fa-calendar-check"></i>
      <span>My Attendance</span>
    </a>
  </li>

  <li class="nav-item active">
    <a class="nav-link" href="profile.php">
      <i class="fas fa-user"></i>
      <span>My Profile</span>
    </a>
  </li>

  <hr class="sidebar-divider">

  <li class="nav-item">
    <a class="nav-link text-danger" href="../logout.php">
      <i class="fas fa-sign-out-alt"></i>
      <span>Logout</span>
    </a>
  </li>
</ul>
<!-- END SIDEBAR -->

<div id="content-wrapper" class="d-flex flex-column">
<div id="content">

<!-- TOPBAR -->
<nav class="navbar navbar-expand navbar-light bg-navbar topbar mb-4 static-top">
  <span class="ml-auto text-white small">
    <?php echo $_SESSION['studentName']; ?>
  </span>
</nav>

<!-- CONTENT -->
<div class="container-fluid">

<h3 class="mb-4 text-primary">
<i class="fas fa-user-edit"></i> Edit Profile
</h3>

<?= $message ?>

<div class="card shadow profile-card">
<div class="card-body">

<form method="POST">

<div class="form-group">
  <label>First Name</label>
  <input type="text" name="firstName" class="form-control"
         value="<?= $student['firstName']; ?>" required>
</div>

<div class="form-group">
  <label>Last Name</label>
  <input type="text" name="lastName" class="form-control"
         value="<?= $student['lastName']; ?>" required>
</div>

<div class="form-group">
  <label>New Password</label>
  <input type="password" name="password" class="form-control"
         placeholder="Leave empty to keep current password">
</div>

<button type="submit" name="update" class="btn btn-primary btn-block">
  <i class="fas fa-save"></i> Update Profile
</button>

</form>

</div>
</div>

</div>
</div>
</div>
</div>

<script src="../vendor/jquery/jquery.min.js"></script>
<script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
