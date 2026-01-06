<ul class="navbar-nav sidebar sidebar-light accordion shadow-sm" id="accordionSidebar">

  <!-- BRAND -->
  <a class="sidebar-brand d-flex align-items-center justify-content-center bg-gradient-primary" href="index.php">
    <div class="sidebar-brand-icon">
      <img src="img/logo/attnlg.jpg" width="40" style="border-radius:10px;">
    </div>
    <div class="sidebar-brand-text mx-3 font-weight-bold text-white">
      AMS
    </div>
  </a>

  <hr class="sidebar-divider my-0">

  <!-- DASHBOARD -->
  <li class="nav-item active">
    <a class="nav-link" href="index.php">
      <i class="fas fa-fw fa-tachometer-alt"></i>
      <span>Dashboard</span>
    </a>
  </li>

  <hr class="sidebar-divider">

  <!-- ACCOUNT -->
  <div class="sidebar-heading">
    Account
  </div>

  <li class="nav-item">
    <a class="nav-link" href="profile.php">
      <i class="fas fa-user-cog"></i>
      <span>My Profile</span>
    </a>
  </li>

  <li class="nav-item">
    <a class="nav-link" href="pendingRequests.php">
      <i class="fas fa-user-check"></i>
      <span>Approve</span>
    </a>
  </li>

  <hr class="sidebar-divider">

  <!-- CLASS MANAGEMENT -->
  <div class="sidebar-heading">
    Class Management
  </div>

  <li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseClass">
      <i class="fas fa-chalkboard"></i>
      <span>Classes</span>
    </a>
    <div id="collapseClass" class="collapse">
      <div class="bg-white py-2 collapse-inner rounded">
        <a class="collapse-item" href="createClass.php">
          <i class="fas fa-plus-circle mr-1"></i> Create Class
        </a>
      </div>
    </div>
  </li>

  <li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseClassArm">
      <i class="fas fa-code-branch"></i>
      <span>Class Arms</span>
    </a>
    <div id="collapseClassArm" class="collapse">
      <div class="bg-white py-2 collapse-inner rounded">
        <a class="collapse-item" href="createClassArms.php">
          <i class="fas fa-plus-circle mr-1"></i> Create Arms
        </a>
      </div>
    </div>
  </li>

  <hr class="sidebar-divider">

  <!-- TEACHERS -->
  <div class="sidebar-heading">
    Teachers
  </div>

  <li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTeacher">
      <i class="fas fa-chalkboard-teacher"></i>
      <span>Manage Teachers</span>
    </a>
    <div id="collapseTeacher" class="collapse">
      <div class="bg-white py-2 collapse-inner rounded">
        <a class="collapse-item" href="createClassTeacher.php">
          <i class="fas fa-user-plus mr-1"></i> Create Teacher
        </a>
      </div>
    </div>
  </li>

  <hr class="sidebar-divider">

  <!-- ACADEMIC MAPPING -->
  <div class="sidebar-heading">
    Academic Mapping
  </div>

  <li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseMapping">
      <i class="fas fa-book-reader"></i>
      <span>Subject Mapping</span>
    </a>
    <div id="collapseMapping" class="collapse">
      <div class="bg-white py-2 collapse-inner rounded">
        <a class="collapse-item" href="addSubject.php">
          <i class="fas fa-book mr-1"></i> Add Subject
        </a>
        <a class="collapse-item" href="assignTeacherSubject.php">
          <i class="fas fa-user-tie mr-1"></i> Assign Teacher
        </a>
        <a class="collapse-item" href="mapStudentTeacher.php">
          <i class="fas fa-user-graduate mr-1"></i> Assign Student
        </a>
        <a class="collapse-item" href="viewTeacherSubjectMapping.php">
          <i class="fas fa-eye mr-1"></i> View Teacher Mapping
        </a>
        <a class="collapse-item" href="viewStudentTeacherMapping.php">
          <i class="fas fa-eye mr-1"></i> View Student Mapping
        </a>
      </div>
    </div>
  </li>

  <hr class="sidebar-divider">

  <!-- STUDENTS -->
  <div class="sidebar-heading">
    Students
  </div>

  <li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseStudent">
      <i class="fas fa-user-graduate"></i>
      <span>Manage Students</span>
    </a>
    <div id="collapseStudent" class="collapse">
      <div class="bg-white py-2 collapse-inner rounded">
        <a class="collapse-item" href="createStudents.php">
          <i class="fas fa-user-plus mr-1"></i> Create Student
        </a>
      </div>
    </div>
  </li>

  <hr class="sidebar-divider">

  <!-- SESSION -->
  <div class="sidebar-heading">
    Academic Session
  </div>

  <li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseSession">
      <i class="fas fa-calendar-alt"></i>
      <span>Session & Term</span>
    </a>
    <div id="collapseSession" class="collapse">
      <div class="bg-white py-2 collapse-inner rounded">
        <a class="collapse-item" href="createSessionTerm.php">
          <i class="fas fa-calendar-plus mr-1"></i> Create Session
        </a>
      </div>
    </div>
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
