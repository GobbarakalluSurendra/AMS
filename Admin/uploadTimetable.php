<?php
include '../Includes/dbcon.php';
include '../Includes/session.php';

require_once __DIR__ . '/../vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

$msg = "";

if (isset($_POST['upload'])) {

    if (!isset($_FILES['excel']) || $_FILES['excel']['error'] != 0) {
        $msg = "<div class='alert alert-danger'>❌ Invalid Excel file</div>";
    } else {

        try {

            /* LOAD EXCEL */
            $spreadsheet = IOFactory::load($_FILES['excel']['tmp_name']);
            $rows = $spreadsheet->getActiveSheet()->toArray();

            $inserted = 0;

            /* LOOP EXCEL ROWS (SKIP HEADER) */
            for ($i = 1; $i < count($rows); $i++) {

                list(
                    $className,
                    $day,
                    $startPeriod,
                    $endPeriod,
                    $startTime,
                    $endTime,
                    $subjectCode,
                    $teacherEmail
                ) = $rows[$i];

                if (
                    empty($className) || empty($day) ||
                    empty($startPeriod) || empty($endPeriod) ||
                    empty($startTime) || empty($endTime) ||
                    empty($subjectCode) || empty($teacherEmail)
                ) {
                    continue;
                }

                /* GET CLASS ID */
                $c = $conn->prepare("SELECT Id FROM tblclass WHERE className=?");
                $c->bind_param("s", $className);
                $c->execute();
                $cRes = $c->get_result();
                if ($cRes->num_rows == 0) continue;
                $classId = $cRes->fetch_row()[0];

                /* GET SUBJECT ID */
                $s = $conn->prepare("SELECT Id FROM tblsubjects WHERE subjectCode=?");
                $s->bind_param("s", $subjectCode);
                $s->execute();
                $sRes = $s->get_result();
                if ($sRes->num_rows == 0) continue;
                $subjectId = $sRes->fetch_row()[0];

                /* GET TEACHER ID */
                $t = $conn->prepare("SELECT teacher_id FROM tblteacher WHERE email=?");
                $t->bind_param("s", $teacherEmail);
                $t->execute();
                $tRes = $t->get_result();
                if ($tRes->num_rows == 0) continue;
                $teacherId = $tRes->fetch_row()[0];

                /* SPLIT PERIODS (LAB / MULTI PERIOD SUPPORT) */
                for ($p = (int)$startPeriod; $p <= (int)$endPeriod; $p++) {

                    $stmt = $conn->prepare("
                        INSERT IGNORE INTO timetable
                        (teacher_id, subject_id, class_id,
                         day_of_week, period_no, start_time, end_time)
                        VALUES (?, ?, ?, ?, ?, ?, ?)
                    ");

                    $stmt->bind_param(
                        "iiisiss",
                        $teacherId,
                        $subjectId,
                        $classId,
                        $day,
                        $p,
                        $startTime,
                        $endTime
                    );

                    if ($stmt->execute()) {
                        $inserted++;
                    }
                }
            }

            $msg = "<div class='alert alert-success'>
                        ✅ Timetable uploaded successfully <br>
                        <b>$inserted periods inserted</b>
                    </div>";

        } catch (Exception $e) {
            $msg = "<div class='alert alert-danger'>
                        ❌ Error reading Excel file
                    </div>";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Upload Timetable</title>
<link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
<div class="container mt-4">

<h4 class="text-primary mb-3">📤 Upload Timetable (Excel)</h4>

<?= $msg ?>

<div class="card shadow">
<div class="card-body">

<form method="post" enctype="multipart/form-data">
    <input type="file" name="excel" class="form-control mb-3" accept=".xlsx" required>
    <button name="upload" class="btn btn-primary">
        Upload Timetable
    </button>
</form>

</div>
</div>

<div class="mt-3 text-muted">
<b>Excel Column Order:</b><br>
Class | Day | StartPeriod | EndPeriod | StartTime | EndTime | SubjectCode | TeacherEmail
</div>

</div>
</body>
</html>
