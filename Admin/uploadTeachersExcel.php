<?php
include '../Includes/dbcon.php';
include '../Includes/session.php';

require_once __DIR__ . '/../vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

$msg = "";

if (isset($_POST['upload'])) {

    if (!isset($_FILES['excel']) || $_FILES['excel']['error'] != 0) {
        $msg = "<div class='alert alert-danger'>Please select a valid Excel file</div>";
    } else {

        try {
            $spreadsheet = IOFactory::load($_FILES['excel']['tmp_name']);
            $rows = $spreadsheet->getActiveSheet()->toArray();

            $inserted = $skipped = $errors = 0;

            for ($i = 1; $i < count($rows); $i++) {

                $employeeId   = trim($rows[$i][0] ?? '');
                $fullName     = trim($rows[$i][1] ?? '');
                $email        = trim($rows[$i][2] ?? '');
                $phone        = trim($rows[$i][3] ?? '');
                $gender       = trim($rows[$i][4] ?? '');
                $qualification= trim($rows[$i][5] ?? '');
                $department   = trim($rows[$i][6] ?? '');

                if ($employeeId == "" || $fullName == "" || $email == "") {
                    $errors++; 
                    continue;
                }

                /* DUPLICATE CHECK */
                $check = $conn->prepare(
                    "SELECT teacher_id FROM tblteacher 
                     WHERE email=? OR employee_id=?"
                );
                $check->bind_param("ss", $email, $employeeId);
                $check->execute();
                $check->store_result();

                if ($check->num_rows > 0) {
                    $skipped++;
                    $check->close();
                    continue;
                }
                $check->close();

                /* DEFAULT PASSWORD */
                $password = password_hash("rgmcet123", PASSWORD_DEFAULT);

                /* INSERT TEACHER */
                $stmt = $conn->prepare(
                    "INSERT INTO tblteacher
                    (employee_id, full_name, email, phone, gender,
                     qualification, department, password, status)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'Active')"
                );

                $stmt->bind_param(
                    "ssssssss",
                    $employeeId,
                    $fullName,
                    $email,
                    $phone,
                    $gender,
                    $qualification,
                    $department,
                    $password
                );

                if ($stmt->execute()) $inserted++;
                else $errors++;

                $stmt->close();
            }

            $msg = "<div class='alert alert-success'>
                <b>Upload Completed</b><br>
                Inserted: <b>$inserted</b><br>
                Skipped: <b>$skipped</b><br>
                Errors: <b>$errors</b>
            </div>";

        } catch (Exception $e) {
            $msg = "<div class='alert alert-danger'>Invalid Excel file</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Upload Teachers</title>
<link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5 col-md-6">
<div class="card shadow">

<div class="card-header bg-success text-white text-center">
<h5><i class="fas fa-file-excel"></i> Upload Teachers (Excel)</h5>
</div>

<div class="card-body">

<?= $msg ?>

<form method="post" enctype="multipart/form-data">
<input type="file" name="excel" class="form-control mb-3"
       accept=".xlsx" required>
<button name="upload" class="btn btn-success btn-block">
Upload Excel
</button>
</form>

<hr>
<small>
<b>Excel Columns:</b><br>
employee_id | full_name | email | phone | gender | qualification | department
<br>
<b>Default Password:</b> rgmcet123
</small>

</div>
</div>
</div>

</body>
</html>
