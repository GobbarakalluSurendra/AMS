<?php
// Start session safely
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Allow Admin / Teacher / Student
if (
    !isset($_SESSION['adminId']) &&
    !isset($_SESSION['teacher_id']) &&
    !isset($_SESSION['studentId'])
) {
    header("Location: ../index.php");
    exit();
}
