<?php
include '../Includes/dbcon.php';
include '../Includes/session.php';

require_once __DIR__ . '/../vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

$msg = "";

if (isset($_POST['upload'])) {

    if ($_FILES['excel']['error'] != 0) {
        $msg = "<div class='alert alert-danger'>Invalid Excel file</div>";
    } else {

        $sheet = IOFactory::load($_FILES['excel']['tmp_name']);
        $rows  = $sheet->getActiveSheet()->toArray();

        $inserted = 0;

        for ($i = 1; $i < count($rows); $i++) {

            list($className,$section,$day,$startP,$endP,$startT,$endT,$subjectCode,$teacherEmail)
                = $rows[$i];

            /* class */
            $c = $conn->query("SELECT Id FROM tblclass WHERE className='$className'");
            if ($c->num_rows == 0) continue;
            $classId = $c->fetch_row()[0];

            /* section */
            $a = $conn->query("
                SELECT Id FROM tblclassarms
                WHERE classArmName='$section' AND classId='$classId'
            ");
            if ($a->num_rows == 0) continue;
            $classArmId = $a->fetch_row()[0];

            /* subject */
            $s = $conn->query("SELECT Id FROM tblsubjects WHERE subjectCode='$subjectCode'");
            if ($s->num_rows == 0) continue;
            $subjectId = $s->fetch_row()[0];

            /* teacher */
            $t = $conn->query("SELECT teacher_id FROM tblteacher WHERE email='$teacherEmail'");
            if ($t->num_rows == 0) continue;
            $teacherId = $t->fetch_row()[0];

            $stmt = $conn->prepare("
                INSERT IGNORE INTO tbltimetable
                (classId,classArmId,subjectId,teacherId,
                 dayOfWeek,startPeriod,endPeriod,startTime,endTime)
                VALUES (?,?,?,?,?,?,?,?,?)
            ");

            $stmt->bind_param(
                "iiiisiiss",
                $classId,$classArmId,$subjectId,$teacherId,
                $day,$startP,$endP,$startT,$endT
            );

            if ($stmt->execute()) $inserted++;
        }

        $msg = "<div class='alert alert-success'>
                Timetable uploaded successfully ($inserted records)
                </div>";
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
<h4 class="text-primary">Upload Timetable (Excel)</h4>

<?= $msg ?>

<form method="post" enctype="multipart/form-data">
<input type="file" name="excel" class="form-control mb-3" accept=".xlsx" required>
<button name="upload" class="btn btn-primary">
Upload Timetable
</button>
</form>

<p class="mt-3 text-muted">
Excel Columns:<br>
class | section | day | startPeriod | endPeriod | startTime | endTime | subjectCode | teacherEmail
</p>
</div>

</body>
</html>
