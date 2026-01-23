<?php
include 'Includes/dbcon.php';

/* =========================
   START SESSION SAFELY
========================= */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* =========================
   INITIALIZE VARIABLES
========================= */
$role = "";
$username = "";
$password = "";
$message = "";
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<title>Student Attendance System | Login</title>

<link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
<link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link href="css/ruang-admin.min.css" rel="stylesheet">
</head>

<body class="bg-gradient-login">

<div class="container-login">
<div class="row justify-content-center">
<div class="col-xl-10 col-lg-12 col-md-9">
<div class="card shadow-sm my-5">
<div class="card-body p-0">
<div class="row">
<div class="col-lg-12">
<div class="login-form">

<div class="text-center">
<h4 class="mb-3">STUDENT ATTENDANCE SYSTEM</h4>
<img src="img/logo/attnlg.jpg" style="width:90px;height:90px">
<h5 class="mt-3 mb-4">Login Panel</h5>
</div>

<!-- ================= LOGIN FORM ================= -->
<form method="POST">

<div class="form-group">
<select name="userType" class="form-control" required>
<option value="">-- Select User Role --</option>
<option value="Administrator">Administrator</option>
<option value="Teacher">Teacher</option>
<option value="Student">Student</option>
</select>
</div>

<div class="form-group">
<input type="text" name="username" class="form-control"
placeholder="Enter Email / Admission Number" required>
</div>

<div class="form-group">
<input type="password" name="password" class="form-control"
placeholder="Enter Password" required>
</div>

<div class="form-group">
<button type="submit" name="login"
class="btn btn-success btn-block">
Login
</button>
</div>

</form>

<!-- ================= LOGIN LOGIC ================= -->
<?php
if (isset($_POST['login'])) {

    $role     = $_POST['userType'] ?? "";
    $username = trim($_POST['username'] ?? "");
    $password = trim($_POST['password'] ?? "");

    /* -------- ROLE NOT SELECTED -------- */
    if ($role === "") {
        $message = "<div class='alert alert-danger'>Please select a user role</div>";
    }

    /* ================= ADMIN LOGIN ================= */
    elseif ($role === "Administrator") {

        $stmt = $conn->prepare("SELECT * FROM tbladmin WHERE email=?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res->num_rows === 1) {
            $row = $res->fetch_assoc();

            if ($password === $row['password']) {

                $_SESSION['adminId']   = $row['Id'];
                $_SESSION['adminName'] =
                    $row['firstName']." ".$row['lastName'];

                header("Location: Admin/index.php");
                exit;
            } else {
                $message = "<div class='alert alert-danger'>Invalid Admin Password</div>";
            }
        } else {
            $message = "<div class='alert alert-danger'>Admin account not found</div>";
        }
        $stmt->close();
    }

    /* ================= TEACHER LOGIN ================= */
    elseif ($role === "Teacher") {

        $stmt = $conn->prepare(
            "SELECT * FROM tblteacher
             WHERE email=? AND status='Active'"
        );
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res->num_rows === 1) {
            $row = $res->fetch_assoc();

            if (password_verify($password, $row['password'])) {

                $_SESSION['teacher_id']    = $row['teacher_id'];
                $_SESSION['teacher_name']  = $row['full_name'];
                $_SESSION['teacher_email'] = $row['email'];

                header("Location: ClassTeacher/index.php");
                exit;
            } else {
                $message = "<div class='alert alert-danger'>Invalid Teacher Password</div>";
            }
        } else {
            $message = "<div class='alert alert-danger'>Teacher account not found or inactive</div>";
        }
        $stmt->close();
    }

    /* ================= STUDENT LOGIN ================= */
    elseif ($role === "Student") {

    $stmt = $conn->prepare(
        "SELECT Id, firstName, lastName, password
         FROM tblstudents
         WHERE admissionNumber = ?"
    );
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows === 1) {

        $row = $res->fetch_assoc();

        if (password_verify($password, $row['password'])) {

            $_SESSION['studentId']   = $row['Id'];
            $_SESSION['studentName'] =
                $row['firstName']." ".$row['lastName'];

            header("Location: Student/index.php");
            exit;
        }
    }

    $message = "<div class='alert alert-danger'>Invalid Student Credentials</div>";
}


    echo $message;
}
?>

<hr>

<div class="text-center">
<p class="text-muted mb-2">New User?</p>

<a href="studentRegister.php"
class="btn btn-outline-primary btn-sm mb-2">
<i class="fas fa-user-graduate"></i>
Student Registration
</a>

<br>

<a href="teacherRegister.php"
class="btn btn-outline-success btn-sm">
<i class="fas fa-chalkboard-teacher"></i>
Teacher Registration
</a>
</div>

</div>
</div>
</div>
</div>
</div>
</div>
</div>

<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="js/ruang-admin.min.js"></script>
</body>
</html>
