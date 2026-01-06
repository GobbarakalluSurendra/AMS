<?php
// Safety check (extra protection)
if (!isset($_SESSION['adminId'])) {
    header("Location: ../index.php");
    exit();
}

$adminId = $_SESSION['adminId'];

/* Fetch admin details */
$stmt = $conn->prepare("SELECT firstName, lastName FROM tbladmin WHERE Id = ?");
$stmt->bind_param("i", $adminId);
$stmt->execute();
$result = $stmt->get_result();

$fullName = "Administrator";
if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();
    $fullName = $row['firstName'] . " " . $row['lastName'];
}
?>
<nav class="navbar navbar-expand navbar-light bg-gradient-primary topbar mb-4 static-top">
  <button id="sidebarToggleTop" class="btn btn-link rounded-circle mr-3">
    <i class="fa fa-bars"></i>
  </button>

  <ul class="navbar-nav ml-auto">

    <!-- SEARCH -->
    <li class="nav-item dropdown no-arrow">
      <a class="nav-link dropdown-toggle" href="#" id="searchDropdown"
         role="button" data-toggle="dropdown">
        <i class="fas fa-search fa-fw"></i>
      </a>
      <div class="dropdown-menu dropdown-menu-right p-3 shadow">
        <form class="navbar-search">
          <div class="input-group">
            <input type="text" class="form-control bg-light border-1 small"
                   placeholder="Search...">
            <div class="input-group-append">
              <button class="btn btn-primary" type="button">
                <i class="fas fa-search fa-sm"></i>
              </button>
            </div>
          </div>
        </form>
      </div>
    </li>

    <div class="topbar-divider d-none d-sm-block"></div>

    <!-- USER -->
    <li class="nav-item dropdown no-arrow">
      <a class="nav-link dropdown-toggle" href="#" id="userDropdown"
         role="button" data-toggle="dropdown">
        <img class="img-profile rounded-circle"
             src="img/user-icn.png" style="max-width: 40px">
        <span class="ml-2 d-none d-lg-inline text-white small">
          <b>Welcome <?php echo htmlspecialchars($fullName); ?></b>
        </span>
      </a>

      <div class="dropdown-menu dropdown-menu-right shadow">
        <div class="dropdown-divider"></div>
        <a class="dropdown-item" href="../logout.php">
          <i class="fas fa-power-off fa-fw mr-2 text-danger"></i>
          Logout
        </a>
      </div>
    </li>

  </ul>
</nav>
