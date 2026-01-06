<?php
include '../Includes/dbcon.php';
include '../Includes/session.php';

$msg = "";

/* SAVE SUBJECT */
if (isset($_POST['save'])) {
    $subjectName = $_POST['subjectName'];
    $classId = $_POST['classId'];

    $check = $conn->query("SELECT * FROM tblsubjects 
                           WHERE subjectName='$subjectName' AND classId='$classId'");
    if ($check->num_rows > 0) {
        $msg = "<div class='alert alert-danger'>Subject already exists</div>";
    } else {
        $conn->query("INSERT INTO tblsubjects (subjectName, classId)
                      VALUES ('$subjectName','$classId')");
        $msg = "<div class='alert alert-success'>Subject added successfully</div>";
    }
}

/* FETCH CLASSES */
$classes = $conn->query("SELECT * FROM tblclass");
?>

<!DOCTYPE html>
<html>
<head>
<title>Add Subject</title>
<link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
<div class="container mt-4">
<h3>Add Subject</h3>
<?php echo $msg; ?>

<form method="post">
  <div class="form-group">
    <label>Subject Name</label>
    <input type="text" name="subjectName" class="form-control" required>
  </div>

  <div class="form-group">
    <label>Class</label>
    <select name="classId" class="form-control" required>
      <option value="">Select Class</option>
      <?php while ($c = $classes->fetch_assoc()) { ?>
        <option value="<?php echo $c['Id']; ?>">
          <?php echo $c['className']; ?>
        </option>
      <?php } ?>
    </select>
  </div>

  <button name="save" class="btn btn-primary">Add Subject</button>
</form>
</div>
</body>
</html>
