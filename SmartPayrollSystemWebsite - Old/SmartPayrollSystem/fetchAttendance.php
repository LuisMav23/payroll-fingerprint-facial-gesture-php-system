<?php
include 'db.php';

$date = isset($_GET['date']) ? $conn->real_escape_string($_GET['date']) : '';
$status = isset($_GET['status']) ? $conn->real_escape_string($_GET['status']) : '';

$query = "
    SELECT 
        e.EmployeeID, 
        CONCAT(e.FirstName, ' ', COALESCE(e.MiddleName, ''), ' ', e.LastName) AS Name, 
        tr.Date, 
        tr.CheckInTime, 
        tr.CheckOutTime,
        p.PositionName,
        CASE 
            WHEN tr.CheckInTime <= '08:00:00' THEN 'On Time'
            WHEN tr.CheckInTime > '08:00:00' THEN 'Late'
            ELSE 'Absent'
        END AS Status
    FROM TimeRecord tr
    INNER JOIN Employee e ON tr.EmployeeID = e.EmployeeID
    LEFT JOIN Position p ON e.PositionID = p.PositionID
    WHERE 1=1
";

if ($date) {
    $query .= " AND tr.Date = '$date'";
}

if ($status) {
    $query .= " AND (CASE 
                       WHEN tr.CheckInTime <= '08:00:00' THEN 'On Time'
                       WHEN tr.CheckInTime > '08:00:00' THEN 'Late'
                       ELSE 'Absent'
                     END) = '$status'";
}

$result = $conn->query($query);
$attendanceRecords = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $attendanceRecords[] = [
            'Name' => $row['Name'],
            'Date' => $row['Date'],
            'CheckInTime' => $row['CheckInTime'],
            'CheckOutTime' => $row['CheckOutTime'],
            'PositionName' => $row['PositionName'], // Include the position name
            'Status' => $row['Status']
        ];
    }
}

echo json_encode($attendanceRecords);
$conn->close();
?>