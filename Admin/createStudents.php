<?php 
error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session.php';

/* ================= SAVE STUDENT ================= */
if (isset($_POST['save'])) {

    $firstName = $_POST['firstName'];
    $lastName  = $_POST['lastName'];
    $admissionNumber = $_POST['admissionNumber'];
    $classId   = $_POST['classId'];
    $classArmId = $_POST['classArmId'];
    $dateCreated = date("Y-m-d");

    // Check duplicate admission number
    $check = mysqli_query($conn, "SELECT * FROM tblstudents WHERE admissionNumber='$admissionNumber'");
    if (mysqli_num_rows($check) > 0) {
        $statusMsg = "<div class='alert alert-danger'>Admission Number already exists!</div>";
    } else {

        $query = mysqli_query($conn, "
            INSERT INTO tblstudents
            (firstName, lastName, admissionNumber, password, classId, classArmId, dateCreated)
            VALUES
            ('$firstName','$lastName','$admissionNumber','12345','$classId','$classArmId','$dateCreated')
        ");

        if ($query) {
            $statusMsg = "<div class='alert alert-success'>Student created successfully!</div>";
        } else {
            $statusMsg = "<div class='alert alert-danger'>Error creating student!</div>";
        }
    }
}

/* ================= EDIT STUDENT ================= */
if (isset($_GET['Id']) && $_GET['action'] == "edit") {
    $Id = $_GET['Id'];
    $editQuery = mysqli_query($conn, "SELECT * FROM tblstudents WHERE Id='$Id'");
    $row = mysqli_fetch_assoc($editQuery);

    if (isset($_POST['update'])) {
        $firstName = $_POST['firstName'];
        $lastName  = $_POST['lastName'];
        $admissionNumber = $_POST['admissionNumber'];
        $classId   = $_POST['classId'];
        $classArmId = $_POST['classArmId'];

        $update = mysqli_query($conn, "
            UPDATE tblstudents SET
            firstName='$firstName',
            lastName='$lastName',
            admissionNumber='$admissionNumber',
            password='12345',
            classId='$classId',
            classArmId='$classArmId'
            WHERE Id='$Id'
        ");

        if ($update) {
            header("Location: createStudents.php");
        } else {
            $statusMsg = "<div class='alert alert-danger'>Update failed!</div>";
        }
    }
}

/* ================= DELETE STUDENT ================= */
if (isset($_GET['Id']) && $_GET['action'] == "delete") {
    $Id = $_GET['Id'];
    mysqli_query($conn, "DELETE FROM tblstudents WHERE Id='$Id'");
    header("Location: createStudents.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Create Students</title>

<link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
<link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link href="css/ruang-admin.min.css" rel="stylesheet">

<script>
function classArmDropdown(str) {
    if (str == "") return;
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("txtHint").innerHTML = this.responseText;
        }
    };
    xmlhttp.open("GET","ajaxClassArms2.php?cid="+str,true);
    xmlhttp.send();
}
</script>
</head>

<body id="page-top">
<div id="wrapper">

<?php include "Includes/sidebar.php"; ?>

<div id="content-wrapper" class="d-flex flex-column">
<div id="content">

<?php include "Includes/topbar.php"; ?>

<div class="container-fluid">

<h1 class="h3 mb-4 text-gray-800">Create Students</h1>
<?php echo $statusMsg; ?>

<!-- FORM -->
<div class="card mb-4 shadow">
<div class="card-body">
<form method="post">

<div class="form-row">
<div class="form-group col-md-6">
<label>First Name</label>
<input type="text" class="form-control" name="firstName"
value="<?php echo $row['firstName'] ?? ''; ?>" required>
</div>

<div class="form-group col-md-6">
<label>Last Name</label>
<input type="text" class="form-control" name="lastName"
value="<?php echo $row['lastName'] ?? ''; ?>" required>
</div>
</div>

<div class="form-row">
<div class="form-group col-md-6">
<label>Admission Number</label>
<input type="text" class="form-control" name="admissionNumber"
value="<?php echo $row['admissionNumber'] ?? ''; ?>" required>
</div>
</div>

<div class="form-row">
<div class="form-group col-md-6">
<label>Class</label>
<select name="classId" class="form-control" onchange="classArmDropdown(this.value)" required>
<option value="">Select Class</option>
<?php
$qry = mysqli_query($conn,"SELECT * FROM tblclass");
while ($c = mysqli_fetch_assoc($qry)) {
    echo "<option value='{$c['Id']}'>{$c['className']}</option>";
}
?>
</select>
</div>

<div class="form-group col-md-6">
<label>Class Arm</label>
<div id="txtHint"></div>
</div>
</div>

<?php if (isset($Id)) { ?>
<button type="submit" name="update" class="btn btn-warning">Update</button>
<?php } else { ?>
<button type="submit" name="save" class="btn btn-primary">Save</button>
<?php } ?>

</form>
</div>
</div>

<!-- STUDENT LIST -->
<div class="card shadow">
<div class="card-header">
<h6 class="m-0 font-weight-bold text-primary">All Students</h6>
</div>

<div class="card-body table-responsive">
<table class="table table-bordered table-hover">
<thead class="thead-light">
<tr>
<th>#</th>
<th>First Name</th>
<th>Last Name</th>
<th>Admission No</th>
<th>Class</th>
<th>Class Arm</th>
<th>Date</th>
<th>Edit</th>
<th>Delete</th>
</tr>
</thead>

<tbody>
<?php
$sn = 1;
$list = mysqli_query($conn,"
SELECT s.*, c.className, a.classArmName
FROM tblstudents s
JOIN tblclass c ON c.Id=s.classId
JOIN tblclassarms a ON a.Id=s.classArmId
");

while ($r = mysqli_fetch_assoc($list)) {
?>
<tr>
<td><?php echo $sn++; ?></td>
<td><?php echo $r['firstName']; ?></td>
<td><?php echo $r['lastName']; ?></td>
<td><?php echo $r['admissionNumber']; ?></td>
<td><?php echo $r['className']; ?></td>
<td><?php echo $r['classArmName']; ?></td>
<td><?php echo $r['dateCreated']; ?></td>
<td><a href="?action=edit&Id=<?php echo $r['Id']; ?>"><i class="fas fa-edit text-warning"></i></a></td>
<td><a href="?action=delete&Id=<?php echo $r['Id']; ?>" onclick="return confirm('Delete student?')">
<i class="fas fa-trash text-danger"></i></a></td>
</tr>
<?php } ?>
</tbody>
</table>
</div>
</div>

</div>
</div>

<?php include "Includes/footer.php"; ?>

</div>
</div>

<script src="../vendor/jquery/jquery.min.js"></script>
<script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="js/ruang-admin.min.js"></script>

</body>
</html>
