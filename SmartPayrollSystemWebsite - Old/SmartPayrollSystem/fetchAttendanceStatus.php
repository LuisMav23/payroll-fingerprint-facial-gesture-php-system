<?php
include 'db.php';

// Define the threshold time for being late (8:00 AM)
$thresholdTime = '08:00:00';
$currentDate = date('Y-m-d');

// Query to get the count of "On Time" employees
$queryOnTime = "
    SELECT COUNT(*) AS onTimeCount
    FROM TimeRecord
    WHERE Date = '$currentDate' AND CheckInTime <= '$thresholdTime'
";
$resultOnTime = $conn->query($queryOnTime);
$onTimeCount = ($resultOnTime && $resultOnTime->num_rows > 0) ? $resultOnTime->fetch_assoc()['onTimeCount'] : 0;

// Query to get the count of "Late" employees
$queryLate = "
    SELECT COUNT(*) AS lateCount
    FROM TimeRecord
    WHERE Date = '$currentDate' AND CheckInTime > '$thresholdTime'
";
$resultLate = $conn->query($queryLate);
$lateCount = ($resultLate && $resultLate->num_rows > 0) ? $resultLate->fetch_assoc()['lateCount'] : 0;

echo json_encode([
    'onTime' => $onTimeCount,
    'late' => $lateCount
]);

$conn->close();
?>
