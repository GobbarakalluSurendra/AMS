<?php
include '../Includes/dbcon.php';
include '../Includes/session.php';

$msg = "";

/* ================= CREATE TEACHER ================= */
if (isset($_POST['save'])) {

    $employeeId    = trim($_POST['employee_id']);
    $fullName      = trim($_POST['full_name']);
    $email         = trim($_POST['email']);
    $phone         = trim($_POST['phone']);
    $gender        = $_POST['gender'];
    $qualification = trim($_POST['qualification']);
    $department    = trim($_POST['department']);
    $password      = password_hash($_POST['password'], PASSWORD_DEFAULT);

    /* ---- DUPLICATE CHECK ---- */
    $stmt = $conn->prepare("
        SELECT teacher_id 
        FROM tblteacher 
        WHERE email=? OR employee_id=?
    ");
    $stmt->bind_param("ss", $email, $employeeId);
    $stmt->execute();
    $check = $stmt->get_result();

    if ($check->num_rows > 0) {
        $msg = "<div class='alert alert-danger'>
                  Email or Employee ID already exists!
                </div>";
    } else {

        $stmt = $conn->prepare("
            INSERT INTO tblteacher
            (employee_id, full_name, email, phone, gender,
             qualification, department, password, status)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'Active')
        ");

        $stmt->bind_param(
            "ssssssss",
            $employeeId,
            $fullName,
            $email,
            $phone,
            $gender,
            $qualification,
            $department,
            $password
        );

        if ($stmt->execute()) {
            $msg = "<div class='alert alert-success'>
                      Teacher created successfully
                    </div>";
        } else {
            $msg = "<div class='alert alert-danger'>
                      Error creating teacher
                    </div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Create Teacher</title>

<link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link href="../css/global-ui.css" rel="stylesheet">
</head>

<body>
<div class="container mt-4">

<h4 class="text-primary mb-3">Create Teacher</h4>

<?= $msg ?>

<!-- ================= CREATE FORM ================= -->
<div class="card shadow mb-4">
<div class="card-body">

<form method="post">

<div class="form-group">
<label>Employee ID</label>
<input type="text" name="employee_id" class="form-control" required>
</div>

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
<input type="text" name="phone" class="form-control">
</div>

<div class="form-group">
<label>Gender</label>
<select name="gender" class="form-control" required>
<option value="">-- Select Gender --</option>
<option value="Male">Male</option>
<option value="Female">Female</option>
</select>
</div>

<div class="form-group">
<label>Qualification</label>
<input type="text" name="qualification" class="form-control">
</div>

<div class="form-group">
<label>Department</label>
<input type="text" name="department" class="form-control">
</div>

<div class="form-group">
<label>Password</label>
<input type="password" name="password" class="form-control" required>
</div>

<button type="submit" name="save" class="btn btn-primary">
Create Teacher
</button>

<a href="assignTeacherToClassArm.php" class="btn btn-secondary ml-2">
Assign Class / Subject →
</a>

</form>

</div>
</div>

<!-- ================= TEACHER LIST ================= -->
<div class="card shadow">
<div class="card-header bg-light">
<h6 class="m-0 font-weight-bold text-primary">
All Teachers (Employee ID Wise)
</h6>
</div>

<div class="card-body table-responsive">
<table class="table table-bordered table-hover">
<thead class="thead-light">
<tr>
<th>#</th>
<th>Employee ID</th>
<th>Name</th>
<th>Email</th>
<th>Phone</th>
<th>Gender</th>
<th>Qualification</th>
<th>Department</th>
<th>Status</th>
</tr>
</thead>

<tbody>
<?php
$sn = 1;
$list = $conn->query("
    SELECT *
    FROM tblteacher
    ORDER BY employee_id
");

while ($t = $list->fetch_assoc()) {
?>
<tr>
<td><?= $sn++ ?></td>
<td><?= htmlspecialchars($t['employee_id']) ?></td>
<td><?= htmlspecialchars($t['full_name']) ?></td>
<td><?= htmlspecialchars($t['email']) ?></td>
<td><?= htmlspecialchars($t['phone']) ?></td>
<td><?= htmlspecialchars($t['gender']) ?></td>
<td><?= htmlspecialchars($t['qualification']) ?></td>
<td><?= htmlspecialchars($t['department']) ?></td>
<td>
<span class="badge badge-<?= $t['status']=='Active'?'success':'secondary' ?>">
<?= $t['status'] ?>
</span>
</td>
</tr>
<?php } ?>
</tbody>
</table>
</div>
</div>

</div>
</body>
</html>
