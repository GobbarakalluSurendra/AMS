<?php
include '../Includes/dbcon.php';
include '../Includes/session.php';

/* ======================
   TEACHER SESSION CHECK
====================== */
if (!isset($_SESSION['teacher_id'])) {
    header("Location: ../index.php");
    exit();
}

$teacherId = $_SESSION['teacher_id'];
$message   = "";

/* ======================
   UPDATE PROFILE
====================== */
if (isset($_POST['update'])) {

    $fullName = trim($_POST['full_name']);
    $email    = trim($_POST['email']);
    $phone    = trim($_POST['phone']);
    $password = $_POST['password'];

    if (!empty($password)) {
        $hashed = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare(
            "UPDATE tblteacher
             SET full_name = ?, email = ?, phone = ?, password = ?
             WHERE teacher_id = ?"
        );
        $stmt->bind_param("ssssi", $fullName, $email, $phone, $hashed, $teacherId);
    } else {
        $stmt = $conn->prepare(
            "UPDATE tblteacher
             SET full_name = ?, email = ?, phone = ?
             WHERE teacher_id = ?"
        );
        $stmt->bind_param("sssi", $fullName, $email, $phone, $teacherId);
    }

    if ($stmt->execute()) {
        $_SESSION['teacher_name']  = $fullName;
        $_SESSION['teacher_email'] = $email;
        $message = "Profile updated successfully!";
    } else {
        $message = "Error updating profile!";
    }

    $stmt->close();
}

/* ======================
   FETCH TEACHER DATA
====================== */
$stmt = $conn->prepare(
    "SELECT full_name, email, phone
     FROM tblteacher
     WHERE teacher_id = ?"
);
$stmt->bind_param("i", $teacherId);
$stmt->execute();
$result  = $stmt->get_result();
$teacher = $result->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Teacher Profile</title>

<link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
<link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link href="../css/ruang-admin.min.css" rel="stylesheet">
<link href="../css/global-ui.css" rel="stylesheet">
</head>

<body id="page-top">
<div id="wrapper">

<!-- SIDEBAR -->
<?php include "Includes/sidebar.php"; ?>

<div id="content-wrapper" class="d-flex flex-column">
<div id="content">

<!-- TOPBAR -->
<?php include "Includes/topbar.php"; ?>

<div class="container-fluid">

<h1 class="h3 mb-4 text-gray-800">My Profile</h1>

<?php if ($message): ?>
<div class="alert alert-success"><?php echo $message; ?></div>
<?php endif; ?>

<div class="card shadow mb-4">
<div class="card-body">

<form method="post">

<div class="form-group">
<label>Full Name</label>
<input type="text" name="full_name"
       class="form-control"
       value="<?php echo htmlspecialchars($teacher['full_name']); ?>"
       required>
</div>

<div class="form-group">
<label>Email Address</label>
<input type="email" name="email"
       class="form-control"
       value="<?php echo htmlspecialchars($teacher['email']); ?>"
       required>
</div>

<div class="form-group">
<label>Phone Number</label>
<input type="text" name="phone"
       class="form-control"
       value="<?php echo htmlspecialchars($teacher['phone']); ?>">
</div>

<div class="form-group">
<label>New Password (leave blank to keep old)</label>
<input type="password" name="password" class="form-control">
</div>

<button type="submit" name="update" class="btn btn-primary">
Update Profile
</button>

</form>

</div>
</div>

</div>
</div>

<?php include "Includes/footer.php"; ?>
</div>
</div>

<script src="../vendor/jquery/jquery.min.js"></script>
<script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../js/ruang-admin.min.js"></script>
</body>
</html>
