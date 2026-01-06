<?php
session_start();

if (!isset($_SESSION['studentId'])) {
    header("Location: ../index.php");
    exit();
}

include '../Includes/dbcon.php';

/* FETCH CHAPTERS */
$result = $conn->query("
    SELECT subject, chapterName, fileName, uploadedOn
    FROM tblchapters
    ORDER BY uploadedOn DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Chapter Notes</title>

<link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet">

<style>
body {
    background: #f4f6fb;
}

.chapter-card {
    background: #fff;
    border-radius: 16px;
    padding: 22px;
    transition: 0.3s ease;
    height: 100%;
}

.chapter-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 18px 40px rgba(0,0,0,0.12);
}

.subject-badge {
    background: linear-gradient(135deg,#4facfe,#00f2fe);
    color: #fff;
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 600;
}

.chapter-title {
    font-size: 22px;
    font-weight: 700;
    color: #4338ca;
    margin-top: 15px;
}

.upload-date {
    color: #6b7280;
    font-size: 14px;
}

.download-btn {
    margin-top: 16px;
    border-radius: 10px;
    font-weight: 600;
}
</style>
</head>

<body>

<div class="container mt-5">

    <div class="text-center mb-5">
        <h2 class="font-weight-bold">📘 Chapter Notes</h2>
        <p class="text-muted">View & download notes uploaded by teachers</p>
    </div>

    <div class="row">

    <?php if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) { ?>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="chapter-card">

                <span class="subject-badge">
                    <?php echo htmlspecialchars($row['subject']); ?>
                </span>

                <div class="chapter-title">
                    <?php echo htmlspecialchars($row['chapterName']); ?>
                </div>

                <div class="upload-date mt-1">
                    Uploaded on: <?php echo htmlspecialchars($row['uploadedOn']); ?>
                </div>

                <a href="download.php?file=<?php echo urlencode($row['fileName']); ?>"
                   class="btn btn-success btn-sm download-btn">
                   <i class="fas fa-download"></i> View / Download
                </a>

            </div>
        </div>

    <?php } } else { ?>

        <div class="col-12">
            <div class="alert alert-info text-center">
                No chapter notes uploaded yet.
            </div>
        </div>

    <?php } ?>

    </div>

</div>

<script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
