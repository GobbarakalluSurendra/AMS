<ul class="navbar-nav sidebar sidebar-light accordion shadow-sm" id="accordionSidebar">

  <!-- BRAND -->
  <a class="sidebar-brand d-flex align-items-center justify-content-center bg-gradient-primary" href="index.php">
    <div class="sidebar-brand-icon">
      <img src="../img/logo/attnlg.jpg" width="42">
    </div>
    <div class="sidebar-brand-text mx-3 font-weight-bold">AMS</div>
  </a>

  <hr class="sidebar-divider my-0">

  <!-- DASHBOARD -->
  <li class="nav-item active">
    <a class="nav-link" href="index.php">
      <i class="fas fa-fw fa-tachometer-alt text-primary"></i>
      <span class="font-weight-bold">Dashboard</span>
    </a>
  </li>

  <hr class="sidebar-divider">

  <!-- PROFILE -->
  <div class="sidebar-heading text-uppercase">
    Account
  </div>

  <li class="nav-item">
    <a class="nav-link" href="profile.php">
      <i class="fas fa-user-circle text-info"></i>
      <span>My Profile</span>
    </a>
  </li>

  <li class="nav-item">
    <a class="nav-link" href="upload_chapter.php">
      <i class="fas fa-book-open text-success"></i>
      <span>Upload Notes</span>
    </a>
  </li>

  <hr class="sidebar-divider">

  <!-- STUDENTS -->
  <div class="sidebar-heading text-uppercase">
    Students
  </div>

  <li class="nav-item">
    <a class="nav-link" href="viewStudents.php">
      <i class="fas fa-user-graduate text-primary"></i>
      <span>View Students</span>
    </a>
  </li>

  <hr class="sidebar-divider">

  <!-- ATTENDANCE -->
  <div class="sidebar-heading text-uppercase">
    Attendance
  </div>

  <li class="nav-item">
    <a class="nav-link" href="takeAttendance.php">
      <i class="fas fa-calendar-plus text-warning"></i>
      <span>Take Attendance</span>
    </a>
  </li>

  <li class="nav-item">
    <a class="nav-link" href="viewAttendance.php">
      <i class="fas fa-list-alt text-info"></i>
      <span>Class Attendance</span>
    </a>
  </li>

  <li class="nav-item">
    <a class="nav-link" href="viewStudentAttendance.php">
      <i class="fas fa-user-check text-success"></i>
      <span>Student Attendance</span>
    </a>
  </li>

  <li class="nav-item">
    <a class="nav-link" href="todaysReport.php">
      <i class="fas fa-file-excel text-success"></i>
      <span>Today’s Report</span>
    </a>
  </li>

  <hr class="sidebar-divider">

   <!-- LOGOUT -->
<li class="nav-item">
  <a class="nav-link logout-link" href="logout.php"
     onclick="return confirm('Are you sure you want to logout?');">
    <i class="fas fa-sign-out-alt"></i>
    <span>Logout</span>
  </a>
</li>


</ul>
