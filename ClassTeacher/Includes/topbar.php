<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

  <span class="ml-3 font-weight-bold text-primary">
    Teacher Panel
  </span>

  <ul class="navbar-nav ml-auto">
    <li class="nav-item dropdown no-arrow">
      <a class="nav-link dropdown-toggle" href="#" role="button">
        <span class="mr-2 text-gray-600 small">
          <?php echo $_SESSION['teacher_name'] ?? 'Teacher'; ?>
        </span>
      </a>
    </li>
  </ul>

</nav>
