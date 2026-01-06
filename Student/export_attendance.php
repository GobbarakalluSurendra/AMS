<?php
session_start();
if (!isset($_SESSION['studentId'])) {
    header("Location: ../index.php");
    exit();
}

include "../Includes/dbcon.php";

$admissionNo = $_SESSION['admissionNumber'];

// Fetch attendance
$query = "SELECT dateTimeTaken, status 
          FROM tblattendance 
          WHERE admissionNo='$admissionNo'
          ORDER BY dateTimeTaken DESC";

$result = $conn->query($query);

// Force download as Excel/CSV
header("Content-Type: text/csv");
header("Content-Disposition: attachment; filename=attendance_report.csv");

// Open output stream
$output = fopen("php://output", "w");

// Column headings
fputcsv($output, ['Date', 'Status']);

// Data rows
while ($row = $result->fetch_assoc()) {
    $status = ($row['status'] == 1) ? "Present" : "Absent";
    fputcsv($output, [$row['dateTimeTaken'], $status]);
}

fclose($output);
exit();
