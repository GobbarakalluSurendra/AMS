<?php
session_start();
include '../Includes/dbcon.php';

if (!isset($_SESSION['teacher_id'])) {
    header("Location: ../index.php");
    exit();
}

$teacherId = $_SESSION['teacher_id'];
$message = "";

if (isset($_POST['upload'])) {

    $subject = trim($_POST['subject']);
    $chapter = trim($_POST['chapter']);

    $file = $_FILES['file'];
    $fileName = time() . "_" . basename($file['name']);
    $fileTmp  = $file['tmp_name'];
    $fileExt  = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    $allowed = ['pdf', 'ppt', 'pptx'];

    if (!in_array($fileExt, $allowed)) {
        $message = "❌ Only PDF or PPT files allowed!";
    } else {

        $uploadDir = $_SERVER['DOCUMENT_ROOT'] . "/attendance-php/uploads/chapters/";

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        if (move_uploaded_file($fileTmp, $uploadDir . $fileName)) {

            $stmt = $conn->prepare("
                INSERT INTO tblchapters
                (teacherId, subject, chapterName, fileName, fileType, uploadedOn)
                VALUES (?, ?, ?, ?, ?, CURDATE())
            ");
            $stmt->bind_param("issss", $teacherId, $subject, $chapter, $fileName, $fileExt);

            if ($stmt->execute()) {
                $message = "✅ Chapter uploaded successfully!";
            } else {
                $message = "❌ Database error!";
            }
        } else {
            $message = "❌ File upload failed!";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Upload Chapter</title>
<link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
<div class="container mt-5">
<h3 class="mb-4">📤 Upload Chapter Notes</h3>

<?php if ($message): ?>
<div class="alert alert-info"><?= $message ?></div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data" class="card p-4 shadow">

<div class="form-group">
<label>Subject</label>
<input type="text" name="subject" class="form-control" required>
</div>

<div class="form-group">
<label>Chapter Name</label>
<input type="text" name="chapter" class="form-control" required>
</div>

<div class="form-group">
<label>File (PDF / PPT)</label>
<input type="file" name="file" class="form-control" required>
</div>

<button type="submit" name="upload" class="btn btn-primary">
<i class="fas fa-upload"></i> Upload
</button>

</form>
</div>
</body>
</html>
