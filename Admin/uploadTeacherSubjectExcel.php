<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../Includes/dbcon.php';
include '../Includes/session.php';

require_once __DIR__ . '/../vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

$msg = "";

if (isset($_POST['upload'])) {

    /* ===== BASIC FILE VALIDATION ===== */
    if (
        !isset($_FILES['excel']) ||
        $_FILES['excel']['error'] !== UPLOAD_ERR_OK ||
        !is_uploaded_file($_FILES['excel']['tmp_name'])
    ) {
        $msg = "<div class='alert alert-danger'>File upload failed</div>";
    } else {

        $fileName = $_FILES['excel']['name'];
        $fileExt  = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if (!in_array($fileExt, ['xlsx', 'xls'])) {
            $msg = "<div class='alert alert-danger'>
                      Please upload a valid Excel (.xlsx or .xls) file
                    </div>";
        } else {

            try {
                /* ===== LOAD EXCEL SAFELY ===== */
                $spreadsheet = IOFactory::load($_FILES['excel']['tmp_name']);
                $sheet = $spreadsheet->getActiveSheet();
                $rows  = $sheet->toArray(null, true, true, true);

                if (count($rows) <= 1) {
                    throw new Exception("Excel file contains no data");
                }

                $inserted = 0;
                $errors   = 0;

                /* ===== START FROM ROW 2 (SKIP HEADER) ===== */
                foreach ($rows as $rowNum => $row) {

                    if ($rowNum == 1) continue;

                    $teacherEmail = trim($row['A'] ?? '');
                    $subjectCode  = trim($row['C'] ?? '');
                    $subjectName  = trim($row['D'] ?? '');

                    /* Skip empty rows */
                    if ($teacherEmail === "" && $subjectCode === "") {
                        continue;
                    }

                    if ($teacherEmail === "" || $subjectCode === "") {
                        $errors++;
                        continue;
                    }

                    /* ===== GET TEACHER ===== */
                    $stmt = $conn->prepare("
                        SELECT teacher_id 
                        FROM tblteacher 
                        WHERE email=? AND status='Active'
                    ");
                    $stmt->bind_param("s", $teacherEmail);
                    $stmt->execute();
                    $stmt->bind_result($teacherId);

                    if (!$stmt->fetch()) {
                        $stmt->close();
                        $errors++;
                        continue;
                    }
                    $stmt->close();

                    /* ===== GET / CREATE SUBJECT ===== */
                    $stmt = $conn->prepare("
                        SELECT Id FROM tblsubjects WHERE subjectCode=?
                    ");
                    $stmt->bind_param("s", $subjectCode);
                    $stmt->execute();
                    $stmt->bind_result($subjectId);

                    if (!$stmt->fetch()) {
                        $stmt->close();

                        $insSub = $conn->prepare("
                            INSERT INTO tblsubjects (subjectCode, subjectName)
                            VALUES (?, ?)
                        ");
                        $insSub->bind_param("ss", $subjectCode, $subjectName);
                        $insSub->execute();
                        $subjectId = $insSub->insert_id;
                        $insSub->close();
                    } else {
                        $stmt->close();
                    }

                    /* ===== INSERT (DUPLICATES ALLOWED) ===== */
                    $ins = $conn->prepare("
                        INSERT INTO tblfaculty_subject (teacherId, subjectId)
                        VALUES (?, ?)
                    ");
                    $ins->bind_param("ii", $teacherId, $subjectId);

                    if ($ins->execute()) {
                        $inserted++;
                    } else {
                        $errors++;
                    }
                    $ins->close();
                }

                $msg = "<div class='alert alert-success'>
                    <b>Upload Successful</b><br>
                    Inserted Rows: <b>$inserted</b><br>
                    Errors: <b>$errors</b>
                </div>";

            } catch (Throwable $e) {
                $msg = "<div class='alert alert-danger'>
                          Excel Processing Error:<br>
                          <small>{$e->getMessage()}</small>
                        </div>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Upload Teacher → Subject</title>
<link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5 col-md-6">
<div class="card shadow">

<div class="card-header bg-primary text-white text-center">
<h5>Upload Teacher → Subject (Excel)</h5>
</div>

<div class="card-body">

<?= $msg ?>

<form method="post" enctype="multipart/form-data">
<input type="file" name="excel"
       class="form-control mb-3"
       accept=".xlsx,.xls"
       required>

<button name="upload" class="btn btn-primary btn-block">
Upload Excel
</button>
</form>

<hr>
<small class="text-muted">
<b>Excel Columns (MUST MATCH):</b><br>
Column A → teacherEmail<br>
Column B → teacherName (ignored)<br>
Column C → subjectCode<br>
Column D → subjectName
</small>

</div>
</div>
</div>

</body>
</html>
