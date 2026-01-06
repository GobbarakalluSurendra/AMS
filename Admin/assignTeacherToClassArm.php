<?php
// INCLUDE FILES (CORRECT PATHS)
include '../Includes/dbcon.php';
include '../Includes/session.php';   // ✅ FIXED PATH

$msg = "";

/*
 RULES:
 - Same teacher CAN teach different classes
 - Same teacher CANNOT teach two arms of same class
*/
if (isset($_POST['assign'])) {

    $teacherId  = (int)$_POST['teacherId'];
    $classId    = (int)$_POST['classId'];
    $classArmId = (int)$_POST['classArmId'];

    // CHECK DUPLICATE
    $stmt = $conn->prepare(
        "SELECT Id FROM tblteacher_classarm
         WHERE teacherId = ? AND classId = ?"
    );
    $stmt->bind_param("ii", $teacherId, $classId);
    $stmt->execute();

    if ($stmt->get_result()->num_rows > 0) {

        $msg = "<div class='alert alert-danger'>
                  This teacher is already assigned to an arm of this class.
                </div>";

    } else {

        // INSERT ASSIGNMENT
        $stmt = $conn->prepare(
            "INSERT INTO tblteacher_classarm
             (teacherId, classId, classArmId, dateAssigned)
             VALUES (?, ?, ?, CURDATE())"
        );
        $stmt->bind_param("iii", $teacherId, $classId, $classArmId);

        if ($stmt->execute()) {
            $msg = "<div class='alert alert-success'>
                      Teacher assigned successfully!
                    </div>";
        } else {
            $msg = "<div class='alert alert-danger'>
                      Assignment failed. Please try again.
                    </div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Assign Teacher</title>

<link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
<link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link href="css/ruang-admin.min.css" rel="stylesheet">
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

<h3 class="mb-4 text-primary">
  <i class="fas fa-chalkboard-teacher"></i>
  Assign Teacher to Class Arm
</h3>

<?= $msg ?>

<div class="card shadow mb-4">
<div class="card-body">

<form method="post">

<div class="row">

<!-- TEACHER -->
<div class="col-md-4">
<label class="font-weight-bold">Teacher</label>
<select name="teacherId" class="form-control" required>
<option value="">-- Select Teacher --</option>
<?php
$teachers = $conn->query(
    "SELECT teacher_id, full_name FROM tblteacher ORDER BY full_name"
);
while ($t = $teachers->fetch_assoc()) {
    echo "<option value='{$t['teacher_id']}'>
            {$t['full_name']}
          </option>";
}
?>
</select>
</div>

<!-- CLASS -->
<div class="col-md-4">
<label class="font-weight-bold">Class</label>
<select name="classId" class="form-control" required>
<option value="">-- Select Class --</option>
<?php
$classes = $conn->query(
    "SELECT Id, className FROM tblclass ORDER BY className"
);
while ($c = $classes->fetch_assoc()) {
    echo "<option value='{$c['Id']}'>
            {$c['className']}
          </option>";
}
?>
</select>
</div>

<!-- CLASS ARM -->
<div class="col-md-4">
<label class="font-weight-bold">Class Arm</label>
<select name="classArmId" class="form-control" required>
<option value="">-- Select Class Arm --</option>
<?php
$arms = $conn->query(
    "SELECT Id, classArmName FROM tblclassarms ORDER BY classArmName"
);
while ($a = $arms->fetch_assoc()) {
    echo "<option value='{$a['Id']}'>
            {$a['classArmName']}
          </option>";
}
?>
</select>
</div>

</div>

<hr>

<button name="assign" class="btn btn-primary">
  <i class="fas fa-save"></i> Assign Teacher
</button>

<a href="index.php" class="btn btn-secondary ml-2">
  Back to Dashboard
</a>

</form>

</div>
</div>

</div>
</div>
</div>

<script src="../vendor/jquery/jquery.min.js"></script>
<script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="js/ruang-admin.min.js"></script>

</body>
</html>
