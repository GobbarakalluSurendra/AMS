<?php
include '../Includes/dbcon.php';
include '../Includes/session.php';

require_once __DIR__ . '/../vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

$message = "";

if (isset($_POST['upload'])) {

    if (!isset($_FILES['excel']) || $_FILES['excel']['error'] != 0) {
        $message = "<div class='alert alert-danger'>Please select a valid Excel file</div>";
    } else {

        try {
            $spreadsheet = IOFactory::load($_FILES['excel']['tmp_name']);
            $rows = $spreadsheet->getActiveSheet()->toArray();

            $inserted = $skipped = $errors = 0;

            for ($i = 1; $i < count($rows); $i++) {

                $admissionNumber = trim($rows[$i][0] ?? '');
                $firstName       = trim($rows[$i][1] ?? '');
                $lastName        = trim($rows[$i][2] ?? '');
                $className       = trim($rows[$i][3] ?? '');
                $classArmName    = trim($rows[$i][4] ?? '');

                if ($admissionNumber == "" || $className == "" || $classArmName == "") {
                    $errors++; continue;
                }

                /* CLASS */
                $cls = $conn->prepare("SELECT Id FROM tblclass WHERE className=?");
                $cls->bind_param("s", $className);
                $cls->execute();
                $cls->bind_result($classId);

                if (!$cls->fetch()) {
                    $cls->close();
                    $ins = $conn->prepare("INSERT INTO tblclass(className) VALUES(?)");
                    $ins->bind_param("s", $className);
                    $ins->execute();
                    $classId = $ins->insert_id;
                    $ins->close();
                } else $cls->close();

                /* CLASS ARM */
                $arm = $conn->prepare("SELECT Id FROM tblclassarms WHERE classArmName=? AND classId=?");
                $arm->bind_param("si", $classArmName, $classId);
                $arm->execute();
                $arm->bind_result($classArmId);

                if (!$arm->fetch()) {
                    $arm->close();
                    $ins = $conn->prepare("INSERT INTO tblclassarms(classArmName,classId) VALUES(?,?)");
                    $ins->bind_param("si", $classArmName, $classId);
                    $ins->execute();
                    $classArmId = $ins->insert_id;
                    $ins->close();
                } else $arm->close();

                /* DUPLICATE CHECK */
                $check = $conn->prepare("SELECT Id FROM tblstudents WHERE admissionNumber=?");
                $check->bind_param("s", $admissionNumber);
                $check->execute();
                $check->store_result();

                if ($check->num_rows > 0) {
                    $skipped++; $check->close(); continue;
                }
                $check->close();

                /* DEFAULT PASSWORD */
                $password = password_hash("rgmcet123", PASSWORD_DEFAULT);

                /* INSERT STUDENT */
                $stmt = $conn->prepare("
                    INSERT INTO tblstudents
                    (firstName,lastName,admissionNumber,password,classId,classArmId,dateCreated)
                    VALUES (?,?,?,?,?,?,CURDATE())
                ");
                $stmt->bind_param(
                    "ssssii",
                    $firstName,
                    $lastName,
                    $admissionNumber,
                    $password,
                    $classId,
                    $classArmId
                );

                if ($stmt->execute()) $inserted++; else $errors++;
                $stmt->close();
            }

            $message = "<div class='alert alert-success'>
                Inserted: <b>$inserted</b> |
                Skipped: <b>$skipped</b> |
                Errors: <b>$errors</b>
            </div>";

        } catch (Exception $e) {
            $message = "<div class='alert alert-danger'>Invalid Excel file</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Upload Students</title>
<link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5 col-md-6">
<div class="card shadow">
<div class="card-header bg-success text-white text-center">
<h5>Upload Students (Excel)</h5>
</div>
<div class="card-body">

<?= $message ?>

<form method="post" enctype="multipart/form-data">
<input type="file" name="excel" class="form-control mb-3" accept=".xlsx" required>
<button class="btn btn-success btn-block" name="upload">Upload</button>
</form>

<hr>
<small>
<b>Excel Format:</b><br>
admissionNumber | firstName | lastName | className | classArmName
</small>

</div>
</div>
</div>

</body>
</html>
