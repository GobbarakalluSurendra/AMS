<?php
include '../Includes/dbcon.php';
include '../Includes/session.php';

$message = "";

if (isset($_POST['assign'])) {

    $teacherId = $_POST['teacherId'];
    $subjectId = $_POST['subjectId'];

    // Prevent duplicate assignment
    $check = $conn->query("SELECT * FROM tblfaculty_subject 
                           WHERE teacherId='$teacherId' AND subjectId='$subjectId'");

    if ($check->num_rows > 0) {
        $message = "<div class='alert alert-warning'>This subject is already assigned to the teacher</div>";
    } else {
        $query = "INSERT INTO tblfaculty_subject (teacherId, subjectId)
                  VALUES ('$teacherId', '$subjectId')";

        if ($conn->query($query)) {
            $message = "<div class='alert alert-success'>Subject assigned successfully</div>";
        } else {
            $message = "<div class='alert alert-danger'>Assignment failed</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Assign Subject</title>

  <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="../css/ruang-admin.min.css" rel="stylesheet">
</head>

<body id="page-top">
<div id="wrapper">

  <!-- SIDEBAR -->
  <?php include "Includes/sidebar.php"; ?>
  <!-- SIDEBAR -->

  <div id="content-wrapper" class="d-flex flex-column">
    <div id="content">

      <!-- TOPBAR -->
      <?php include "Includes/topbar.php"; ?>
      <!-- TOPBAR -->

      <div class="container-fluid">

        <h1 class="h3 mb-4 text-gray-800">Assign Subject to Teacher</h1>

        <?php echo $message; ?>

        <div class="card shadow mb-4">
          <div class="card-body">

            <form method="POST">

              <div class="form-group">
                <label>Select Teacher</label>
                <select name="teacherId" class="form-control" required>
                  <option value="">Select Teacher</option>
                  <?php
                  $teachers = $conn->query("SELECT * FROM tblclassteacher");
                  while ($t = $teachers->fetch_assoc()) {
                      echo "<option value='{$t['Id']}'>
                              {$t['firstName']} {$t['lastName']}
                            </option>";
                  }
                  ?>
                </select>
              </div>

              <div class="form-group">
                <label>Select Subject</label>
                <select name="subjectId" class="form-control" required>
                  <option value="">Select Subject</option>
                  <?php
                  $subjects = $conn->query("SELECT * FROM tblsubjects");
                  while ($s = $subjects->fetch_assoc()) {
                      echo "<option value='{$s['Id']}'>
                              {$s['subjectCode']} - {$s['subjectName']}
                            </option>";
                  }
                  ?>
                </select>
              </div>

              <button type="submit" name="assign" class="btn btn-primary">
                Assign Subject
              </button>

            </form>

          </div>
        </div>

      </div>

    </div>
  </div>
</div>
</body>
</html>
