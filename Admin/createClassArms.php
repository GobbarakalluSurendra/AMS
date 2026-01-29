<?php 
// 🔧 Enable errors while testing (disable later if needed)
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../Includes/dbcon.php';
include '../Includes/session.php';

// ========================
// SAVE CLASS ARM
// ========================
if(isset($_POST['save'])){
    
    $classId = $_POST['classId'];
    $classArmName = $_POST['classArmName'];
   
    $query = mysqli_query(
        $conn,
        "SELECT * FROM tblclassarms 
         WHERE classArmName='$classArmName' 
         AND classId='$classId'"
    );
    $ret = mysqli_fetch_array($query);

    if($ret){
        $statusMsg = "<div class='alert alert-danger'>This Class Arm Already Exists!</div>";
    }
    else{
        $query = mysqli_query(
            $conn,
            "INSERT INTO tblclassarms(classId, classArmName, isAssigned)
             VALUES('$classId','$classArmName','0')"
        );

        if ($query){
            $statusMsg = "<div class='alert alert-success'>Created Successfully!</div>";
        } else {
            $statusMsg = "<div class='alert alert-danger'>An error Occurred!</div>";
        }
    }
}

// ========================
// EDIT CLASS ARM
// ========================
if (isset($_GET['Id']) && $_GET['action'] == "edit"){
    $Id = $_GET['Id'];

    $query = mysqli_query($conn,"SELECT * FROM tblclassarms WHERE Id='$Id'");
    $row = mysqli_fetch_array($query);

    if(isset($_POST['update'])){
        $classId = $_POST['classId'];
        $classArmName = $_POST['classArmName'];

        $query = mysqli_query(
            $conn,
            "UPDATE tblclassarms 
             SET classId='$classId', classArmName='$classArmName' 
             WHERE Id='$Id'"
        );

        if ($query){
            echo "<script>window.location='createClassArms.php'</script>";
        } else {
            $statusMsg = "<div class='alert alert-danger'>An error Occurred!</div>";
        }
    }
}

// ========================
// DELETE CLASS ARM
// ========================
if (isset($_GET['Id']) && $_GET['action'] == "delete"){
    $Id = $_GET['Id'];

    $query = mysqli_query($conn,"DELETE FROM tblclassarms WHERE Id='$Id'");
    if ($query){
        echo "<script>window.location='createClassArms.php'</script>";
    } else {
        $statusMsg = "<div class='alert alert-danger'>An error Occurred!</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Create Class Arms</title>

<link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
<link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link href="css/ruang-admin.min.css" rel="stylesheet">
</head>

<body id="page-top">
<div id="wrapper">

<?php include "Includes/sidebar.php"; ?>

<div id="content-wrapper" class="d-flex flex-column">
<div id="content">

<?php include "Includes/topbar.php"; ?>

<div class="container-fluid" id="container-wrapper">

<h1 class="h3 mb-4 text-gray-800">Create Class Arms</h1>

<div class="card mb-4">
<div class="card-header">
<h6 class="m-0 font-weight-bold text-primary">Create Class Arm</h6>
<?= $statusMsg ?? "" ?>
</div>

<div class="card-body">
<form method="post">
<div class="row">

<div class="col-md-6">
<label>Select Class *</label>
<select name="classId" class="form-control" required>
<option value="">--Select Class--</option>
<?php
$qry = "SELECT * FROM tblclass ORDER BY className ASC";
$result = $conn->query($qry);
while ($rows = $result->fetch_assoc()){
    $selected = (isset($row) && $row['classId'] == $rows['Id']) ? "selected" : "";
    echo "<option value='{$rows['Id']}' $selected>{$rows['className']}</option>";
}
?>
</select>
</div>

<div class="col-md-6">
<label>Class Arm Name *</label>
<input type="text" name="classArmName" class="form-control"
value="<?= $row['classArmName'] ?? '' ?>" required>
</div>

</div>

<br>

<?php if (isset($Id)) { ?>
<button type="submit" name="update" class="btn btn-warning">Update</button>
<?php } else { ?>
<button type="submit" name="save" class="btn btn-primary">Save</button>
<?php } ?>

</form>
</div>
</div>

<!-- ========================
     CLASS ARMS TABLE
========================= -->

<div class="card mb-4">
<div class="card-header">
<h6 class="m-0 font-weight-bold text-primary">All Class Arms</h6>
</div>

<div class="table-responsive p-3">
<table class="table table-hover" id="dataTableHover">
<thead class="thead-light">
<tr>
<th>#</th>
<th>Class Name</th>
<th>Class Arm Name</th>
<th>Status</th>
<th>Edit</th>
<th>Delete</th>
</tr>
</thead>

<tbody>
<?php
$query = "
SELECT 
    ca.Id,
    ca.classArmName,
    ca.isAssigned,
    c.className
FROM tblclassarms ca
LEFT JOIN tblclass c 
    ON c.Id = ca.classId
ORDER BY ca.Id DESC
";

$rs = $conn->query($query);
$sn = 0;

while ($rows = $rs->fetch_assoc()){
    $sn++;
    $status = ($rows['isAssigned'] == 1) ? "Assigned" : "UnAssigned";
    $className = $rows['className'] ?? "Not Linked";

    echo "
    <tr>
        <td>$sn</td>
        <td>$className</td>
        <td>{$rows['classArmName']}</td>
        <td>$status</td>
        <td><a href='?action=edit&Id={$rows['Id']}' class='btn btn-sm btn-warning'>Edit</a></td>
        <td><a href='?action=delete&Id={$rows['Id']}' class='btn btn-sm btn-danger'
            onclick=\"return confirm('Delete this class arm?')\">Delete</a></td>
    </tr>";
}
?>
</tbody>
</table>
</div>
</div>

</div>

<?php include "Includes/footer.php"; ?>

</div>
</div>

<script src="../vendor/jquery/jquery.min.js"></script>
<script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../vendor/datatables/jquery.dataTables.min.js"></script>
<script src="../vendor/datatables/dataTables.bootstrap4.min.js"></script>

<script>
$(document).ready(function () {
    $('#dataTableHover').DataTable();
});
</script>

</body>
</html>
