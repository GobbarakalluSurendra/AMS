<?php
include '../Includes/dbcon.php';
include '../Includes/session.php';

$teacherId = $_SESSION['userId'];

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=Period_Wise_Attendance.xls");

echo "<table border='1'>";
echo "<tr>
        <th>#</th>
        <th>Student Name</th>
        <th>Admission No</th>
        <th>Subject</th>
        <th>Period</th>
        <th>Status</th>
        <th>Date</th>
      </tr>";

$query = "
SELECT 
  st.firstName,
  st.lastName,
  st.admissionNumber,
  sub.subjectName,
  a.period,
  a.status,
  a.date
FROM tblattendance_btech a
INNER JOIN tblstudents st ON st.Id = a.studentId
INNER JOIN tblsubjects sub ON sub.Id = a.subjectId
WHERE a.teacherId = '$teacherId'
ORDER BY a.date DESC, a.period ASC
";

$result = mysqli_query($conn, $query);
$sn = 0;

while ($row = mysqli_fetch_assoc($result)) {
  $sn++;
  $status = ($row['status'] == 1) ? "Present" : "Absent";

  echo "<tr>
          <td>$sn</td>
          <td>{$row['firstName']} {$row['lastName']}</td>
          <td>{$row['admissionNumber']}</td>
          <td>{$row['subjectName']}</td>
          <td>{$row['period']}</td>
          <td>$status</td>
          <td>{$row['date']}</td>
        </tr>";
}

echo "</table>";
?>
