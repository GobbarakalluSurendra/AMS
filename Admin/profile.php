<?php
include '../Includes/dbcon.php';
include '../Includes/session.php';

/* ================= ADMIN SESSION ================= */
if (!isset($_SESSION['adminId'])) {
    header("Location: ../index.php");
    exit();
}

$adminId = $_SESSION['adminId'];
$message = "";

/* ================= UPDATE PROFILE ================= */
if (isset($_POST['update'])) {

    $firstName = trim($_POST['firstName']);
    $lastName  = trim($_POST['lastName']);
    $email     = trim($_POST['email']);
    $password  = trim($_POST['password']);

    if (!empty($password)) {
        // Plain password (matches your current login system)
        $stmt = $conn->prepare(
            "UPDATE tbladmin 
             SET firstName=?, lastName=?, email=?, password=? 
             WHERE Id=?"
        );
        $stmt->bind_param(
            "ssssi",
            $firstName,
            $lastName,
            $email,
            $password,
            $adminId
        );
    } else {
        // Update without password
        $stmt = $conn->prepare(
            "UPDATE tbladmin 
             SET firstName=?, lastName=?, email=? 
             WHERE Id=?"
        );
        $stmt->bind_param(
            "sssi",
            $firstName,
            $lastName,
            $email,
            $adminId
        );
    }

    if ($stmt->execute()) {
        $_SESSION['adminName'] = $firstName . " " . $lastName;
        $message = "Profile updated successfully!";
    } else {
        $message = "Error updating profile!";
    }
}

/* ================= FETCH ADMIN DATA ================= */
$stmt = $conn->prepare(
    "SELECT firstName, lastName, email 
     FROM tbladmin 
     WHERE Id=?"
);
$stmt->bind_param("i", $adminId);
$stmt->execute();
$result = $stmt->get_result();
$admin = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Admin Profile</title>

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

      <!-- CONTENT -->
      <div class="container-fluid" id="container-wrapper">

        <h1 class="h3 mb-4 text-gray-800">My Profile</h1>

        <?php if (!empty($message)) { ?>
          <div class="alert alert-success">
            <?php echo htmlspecialchars($message); ?>
          </div>
        <?php } ?>

        <div class="card shadow mb-4">
          <div class="card-body">

            <form method="POST">

              <div class="form-group">
                <label>First Name</label>
                <input type="text" name="firstName"
                       class="form-control"
                       value="<?php echo htmlspecialchars($admin['firstName']); ?>"
                       required>
              </div>

              <div class="form-group">
                <label>Last Name</label>
                <input type="text" name="lastName"
                       class="form-control"
                       value="<?php echo htmlspecialchars($admin['lastName']); ?>"
                       required>
              </div>

              <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email"
                       class="form-control"
                       value="<?php echo htmlspecialchars($admin['email']); ?>"
                       required>
              </div>

              <div class="form-group">
                <label>New Password (leave blank to keep old)</label>
                <input type="password" name="password"
                       class="form-control">
              </div>

              <button type="submit" name="update"
                      class="btn btn-primary">
                Update Profile
              </button>

            </form>

          </div>
        </div>

      </div>
    </div>

    <!-- FOOTER -->
    <?php include 'Includes/footer.php'; ?>

  </div>
</div>

<script src="../vendor/jquery/jquery.min.js"></script>
<script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="js/ruang-admin.min.js"></script>

</body>
</html>
