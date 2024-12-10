<?php
// Include the database connection
include 'db.php';

// Fetch all leave requests from the LeaveFile table
$query = "
    SELECT lf.LeaveFileID, CONCAT(e.FirstName, ' ', e.LastName) as Name, lf.LeaveType, lf.LeaveStartDate, lf.LeaveEndDate, lf.indicator, lf.Reason, lf.Attachment
    FROM LeaveFile lf
    JOIN Employee e ON lf.EmployeeID = e.EmployeeID
    WHERE lf.indicator = 'PENDING'
";

$result = $conn->query($query);

if ($result->num_rows > 0) {
    $leaveRequests = [];

    while ($row = $result->fetch_assoc()) {
        $leaveRequests[] = $row;
    }

    echo json_encode($leaveRequests);
} else {
    echo json_encode(['error' => 'No leave requests found']);
}

$conn->close();
?>
