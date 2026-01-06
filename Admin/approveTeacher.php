<?php
include "../Includes/dbcon.php";
include "../Includes/session.php";

/* =====================
   VALIDATE REQUEST ID
===================== */
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: pendingRequests.php");
    exit();
}

$requestId = (int)$_GET['id'];

/* =====================
   FETCH PENDING REQUEST
===================== */
$stmt = $conn->prepare("
    SELECT * FROM teacher_requests
    WHERE request_id = ? AND status = 'Pending'
");
$stmt->bind_param("i", $requestId);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows === 0) {
    die("<h4 style='color:red;text-align:center'>
        Invalid request or already approved
    </h4>");
}

$teacher = $res->fetch_assoc();
$error = "";

/* =====================
   APPROVE TEACHER
===================== */
if (isset($_POST['approve'])) {

    $department    = trim($_POST['department']);
    $qualification = trim($_POST['qualification']);
    $gender        = $_POST['gender'];

    if ($department == "" || $qualification == "" || $gender == "") {
        $error = "All fields are required";
    } else {

        /* CHECK DUPLICATE EMAIL */
        $check = $conn->prepare(
            "SELECT teacher_id FROM tblteacher WHERE email = ?"
        );
        $check->bind_param("s", $teacher['email']);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $error = "Teacher already exists with this email!";
        } else {

            $employeeId = "EMP" . rand(1000, 9999);

            /* INSERT INTO MAIN TEACHER TABLE */
            $insert = $conn->prepare("
                INSERT INTO tblteacher
                (employee_id, full_name, email, phone, gender, qualification, department, password, status)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'Active')
            ");

            $insert->bind_param(
                "ssssssss",
                $employeeId,
                $teacher['full_name'],
                $teacher['email'],
                $teacher['phone'],
                $gender,
                $qualification,
                $department,
                $teacher['password']
            );

            if ($insert->execute()) {

                /* UPDATE REQUEST STATUS */
                $upd = $conn->prepare("
                    UPDATE teacher_requests
                    SET status = 'Approved'
                    WHERE request_id = ?
                ");
                $upd->bind_param("i", $requestId);
                $upd->execute();

                /* 🔥 FIXED REDIRECT */
                header("Location: pendingRequests.php?approved=1");
                exit();
            } else {
                $error = "Failed to approve teacher";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Approve Teacher</title>
<link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5">
<div class="card shadow">

<div class="card-header bg-success text-white">
    <h4 class="mb-0">Approve Teacher</h4>
</div>

<div class="card-body">

<?php if ($error != "") { ?>
<div class="alert alert-danger"><?= $error ?></div>
<?php } ?>

<p><b>Name:</b> <?= htmlspecialchars($teacher['full_name']) ?></p>
<p><b>Email:</b> <?= htmlspecialchars($teacher['email']) ?></p>
<p><b>Phone:</b> <?= htmlspecialchars($teacher['phone']) ?></p>

<hr>

<form method="post">

<div class="row">

<div class="col-md-6 mb-3">
<label>Department</label>
<input type="text" name="department" class="form-control" required>
</div>

<div class="col-md-6 mb-3">
<label>Qualification</label>
<input type="text" name="qualification" class="form-control" required>
</div>

<div class="col-md-6 mb-3">
<label>Gender</label>
<select name="gender" class="form-control" required>
<option value="">-- Select Gender --</option>
<option value="Male">Male</option>
<option value="Female">Female</option>
</select>
</div>

</div>

<button type="submit" name="approve" class="btn btn-success">
Approve Teacher
</button>

<a href="pendingRequests.php" class="btn btn-secondary ml-2">
Cancel
</a>

</form>

</div>
</div>
</div>

</body>
</html>
