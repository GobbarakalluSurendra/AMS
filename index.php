<?php
/* =========================
   DB + SESSION
========================= */
include 'Includes/dbcon.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$message = "";

/* =========================
   LOGIN LOGIC (MUST BE ON TOP)
========================= */
if (isset($_POST['login'])) {

    $role     = $_POST['userType'] ?? '';
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    /* -------- ADMIN -------- */
    if ($role === "Administrator") {

        $stmt = $conn->prepare("SELECT * FROM tbladmin WHERE email=?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res->num_rows === 1) {
            $row = $res->fetch_assoc();
            if ($password === $row['password']) {
                $_SESSION['adminId'] = $row['Id'];
                header("Location: Admin/index.php");
                exit;
            }
        }
        $message = "<div class='alert alert-danger'>Invalid Admin Credentials</div>";
    }

    /* -------- TEACHER -------- */
    elseif ($role === "Teacher") {

        $stmt = $conn->prepare(
            "SELECT * FROM tblteacher WHERE email=? AND status='Active'"
        );
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res->num_rows === 1) {
            $row = $res->fetch_assoc();
            if (password_verify($password, $row['password'])) {
                $_SESSION['teacher_id'] = $row['teacher_id'];
                header("Location: ClassTeacher/index.php");
                exit;
            }
        }
        $message = "<div class='alert alert-danger'>Invalid Teacher Credentials</div>";
    }

    /* -------- STUDENT -------- */
    elseif ($role === "Student") {

        $stmt = $conn->prepare(
            "SELECT * FROM tblstudents WHERE admissionNumber=?"
        );
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res->num_rows === 1) {
            $row = $res->fetch_assoc();
            if (password_verify($password, $row['password'])) {
                $_SESSION['studentId'] = $row['Id'];
                header("Location: Student/index.php");
                exit;
            }
        }
        $message = "<div class='alert alert-danger'>Invalid Student Credentials</div>";
    }

    else {
        $message = "<div class='alert alert-danger'>Please select a role</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>EduTrack | Login</title>

<link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
<link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

<style>
body{
    min-height:100vh;
    background:linear-gradient(135deg,#4e73df,#1cc88a);
    display:flex;
    align-items:center;
    justify-content:center;
    font-family:'Segoe UI',sans-serif;
}
.login-card{
    background:#fff;
    border-radius:18px;
    box-shadow:0 25px 50px rgba(0,0,0,0.2);
    overflow:hidden;
}
.login-left{
    background:linear-gradient(160deg,#224abe,#4e73df);
    color:#fff;
    padding:50px 40px;
}
.login-left img{
    width:80px;
    border-radius:14px;
    margin-bottom:15px;
}
.login-right{
    padding:50px 40px;
}
.role-select{
    display:flex;
    justify-content:space-between;
    margin-bottom:25px;
}
.role-card{
    width:30%;
    padding:16px 10px;
    border-radius:14px;
    text-align:center;
    cursor:pointer;
    border:2px solid #e0e0e0;
    transition:.3s;
    color:#555;
}
.role-card i{
    font-size:26px;
    display:block;
    margin-bottom:5px;
}
.role-card.active,
.role-card:hover{
    background:#4e73df;
    color:#fff;
    border-color:#4e73df;
    box-shadow:0 10px 25px rgba(78,115,223,.4);
}
.form-control{
    height:46px;
    border-radius:12px;
}
.show-pass{
    position:absolute;
    right:15px;
    top:13px;
    cursor:pointer;
    color:#6c757d;
}
.btn-login{
    height:46px;
    border-radius:25px;
    font-weight:600;
}
@media(max-width:768px){
    .login-left{display:none;}
}
</style>
</head>

<body>

<div class="container">
<div class="row justify-content-center">
<div class="col-lg-9">
<div class="row login-card">

<!-- LEFT -->
<div class="col-md-5 login-left d-flex flex-column justify-content-center">
    <img src="img/logo/attnlg.jpg">
    <h2>EduTrack</h2>
    <p>Welcome back 👋</p>
</div>

<!-- RIGHT -->
<div class="col-md-7 login-right">

<div class="text-center mb-4">
    <i class="fas fa-user-lock fa-3x text-primary mb-2"></i>
    <h4 class="font-weight-bold">Sign in</h4>
</div>

<?= $message ?>

<form method="POST">

<div class="role-select">
    <div class="role-card" onclick="selectRole('Administrator',this)">
        <i class="fas fa-user-shield"></i>Admin
    </div>
    <div class="role-card" onclick="selectRole('Teacher',this)">
        <i class="fas fa-chalkboard-teacher"></i>Teacher
    </div>
    <div class="role-card" onclick="selectRole('Student',this)">
        <i class="fas fa-user-graduate"></i>Student
    </div>
</div>

<input type="hidden" name="userType" id="userType" required>

<div class="form-group">
<input type="text" name="username" class="form-control"
placeholder="Email / Admission Number" required>
</div>

<div class="form-group position-relative">
<input type="password" name="password" id="password"
class="form-control" placeholder="Password" required>
<i class="fas fa-eye show-pass" onclick="togglePassword()"></i>
</div>

<button type="submit" name="login"
class="btn btn-primary btn-block btn-login">
Login
</button>

</form>

<hr>

<div class="text-center">
<a href="studentRegister.php">Student Registration</a> |
<a href="teacherRegister.php">Teacher Registration</a>
</div>

</div>
</div>
</div>
</div>
</div>

<script>
function selectRole(role,el){
    document.getElementById('userType').value=role;
    document.querySelectorAll('.role-card')
      .forEach(c=>c.classList.remove('active'));
    el.classList.add('active');
}
function togglePassword(){
    const p=document.getElementById("password");
    p.type=p.type==="password"?"text":"password";
}
</script>

</body>
</html>
