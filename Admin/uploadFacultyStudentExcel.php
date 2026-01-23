<?php
include '../Includes/dbcon.php';
include '../Includes/session.php';

require_once __DIR__ . '/../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$message = "";
$errorRows = [];

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
                $subjectCode     = trim($rows[$i][2] ?? '');
                $subjectName     = trim($rows[$i][3] ?? '');
                $teacherEmail    = trim($rows[$i][4] ?? '');

                /* ===== REQUIRED CHECK ===== */
                if ($admissionNumber == "" || $subjectCode == "" || $teacherEmail == "") {
                    $errorRows[] = [
                        $i + 1,
                        $admissionNumber,
                        $subjectCode,
                        $teacherEmail,
                        "Missing admissionNumber / subjectCode / teacherEmail"
                    ];
                    $errors++;
                    continue;
                }

                /* ===== STUDENT ===== */
                $stu = $conn->prepare(
                    "SELECT Id FROM tblstudents WHERE LOWER(admissionNumber)=LOWER(?)"
                );
                $stu->bind_param("s", $admissionNumber);
                $stu->execute();
                $stu->bind_result($studentId);

                if (!$stu->fetch()) {
                    $errorRows[] = [
                        $i + 1,
                        $admissionNumber,
                        $subjectCode,
                        $teacherEmail,
                        "Student not found"
                    ];
                    $stu->close();
                    $errors++;
                    continue;
                }
                $stu->close();

                /* ===== TEACHER ===== */
                $tch = $conn->prepare(
                    "SELECT teacher_id FROM tblteacher
                     WHERE LOWER(email)=LOWER(?) AND status='Active'"
                );
                $tch->bind_param("s", $teacherEmail);
                $tch->execute();
                $tch->bind_result($teacherId);

                if (!$tch->fetch()) {
                    $errorRows[] = [
                        $i + 1,
                        $admissionNumber,
                        $subjectCode,
                        $teacherEmail,
                        "Teacher not found or inactive"
                    ];
                    $tch->close();
                    $errors++;
                    continue;
                }
                $tch->close();

                /* ===== SUBJECT ===== */
                $sub = $conn->prepare(
                    "SELECT Id FROM tblsubjects WHERE subjectCode=?"
                );
                $sub->bind_param("s", $subjectCode);
                $sub->execute();
                $sub->bind_result($subjectId);

                if (!$sub->fetch()) {
                    $sub->close();
                    $insSub = $conn->prepare(
                        "INSERT INTO tblsubjects (subjectCode, subjectName)
                         VALUES (?, ?)"
                    );
                    $insSub->bind_param("ss", $subjectCode, $subjectName);
                    $insSub->execute();
                    $subjectId = $insSub->insert_id;
                    $insSub->close();
                } else {
                    $sub->close();
                }

                /* ===== DUPLICATE CHECK ===== */
                $chk = $conn->prepare(
                    "SELECT Id FROM tblstudent_teacher
                     WHERE studentId=? AND subjectId=?"
                );
                $chk->bind_param("ii", $studentId, $subjectId);
                $chk->execute();
                $chk->store_result();

                if ($chk->num_rows > 0) {
                    $skipped++;
                    $chk->close();
                    continue;
                }
                $chk->close();

                /* ===== INSERT ===== */
                $map = $conn->prepare(
                    "INSERT INTO tblstudent_teacher
                     (studentId, teacherId, subjectId)
                     VALUES (?, ?, ?)"
                );
                $map->bind_param("iii", $studentId, $teacherId, $subjectId);

                if ($map->execute()) {
                    $inserted++;
                } else {
                    $errorRows[] = [
                        $i + 1,
                        $admissionNumber,
                        $subjectCode,
                        $teacherEmail,
                        "Database insert failed"
                    ];
                    $errors++;
                }
                $map->close();
            }

            /* ===== CREATE ERROR EXCEL ===== */
            $errorFile = "";

            if (count($errorRows) > 0) {

                $errSheet = new Spreadsheet();
                $sheet = $errSheet->getActiveSheet();

                $sheet->fromArray([
                    "Excel Row",
                    "Admission Number",
                    "Subject Code",
                    "Teacher Email",
                    "Error Reason"
                ], NULL, "A1");

                $sheet->fromArray($errorRows, NULL, "A2");

                $errorFile = "student_teacher_upload_errors_" . time() . ".xlsx";
                $writer = new Xlsx($errSheet);
                $writer->save(__DIR__ . "/" . $errorFile);
            }

            /* ===== RESULT MESSAGE ===== */
            $message = "<div class='alert alert-success'>
                <b>Upload Completed</b><br>
                Inserted: <b>$inserted</b><br>
                Skipped: <b>$skipped</b><br>
                Errors: <b>$errors</b>";

            if ($errorFile != "") {
                $message .= "<br>
                <a href='$errorFile' class='btn btn-danger btn-sm mt-2'>
                    <i class='fas fa-file-excel'></i> Download Error Rows
                </a>";
            }

            $message .= "</div>";

        } catch (Exception $e) {
            $message = "<div class='alert alert-danger'>Invalid Excel file</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Upload Faculty–Student Mapping</title>
<link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5 col-md-6">
<div class="card shadow">

<div class="card-header bg-primary text-white text-center">
<h5>Upload Faculty–Student Mapping</h5>
</div>

<div class="card-body">

<?= $message ?>

<form method="post" enctype="multipart/form-data">
<input type="file" name="excel" class="form-control mb-3"
       accept=".xlsx" required>
<button name="upload" class="btn btn-primary btn-block">
Upload Excel
</button>
</form>

<hr>
<small>
<b>Excel Columns:</b><br>
admissionNumber | studentName | subjectCode | subjectName | teacherEmail | teacherName
</small>

</div>
</div>
</div>

</body>
</html>
