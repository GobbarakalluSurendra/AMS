<?php
session_start();

if (!isset($_SESSION['studentId'])) {
    header("Location: ../index.php");
    exit();
}

if (!isset($_GET['file'])) {
    die("Invalid file request.");
}

$filename = basename($_GET['file']);
$filepath = "../uploads/chapters/" . $filename;

/* CHECK FILE EXISTS */
if (!file_exists($filepath)) {
    die("File not found on server.");
}

/* FORCE DOWNLOAD */
header("Content-Description: File Transfer");
header("Content-Type: application/octet-stream");
header("Content-Disposition: attachment; filename=\"" . basename($filepath) . "\"");
header("Expires: 0");
header("Cache-Control: must-revalidate");
header("Pragma: public");
header("Content-Length: " . filesize($filepath));

readfile($filepath);
exit();
