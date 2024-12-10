<?php
// Include your database connection file
include('db.php');

// Get parameters from the URL
$employeeId = $_GET['employeeId'];
$fromDate = $_GET['fromDate'];
$toDate = $_GET['toDate'];

// Prepare the query to get attendance records from the TimeRecord table
$query = "SELECT tr.Date, tr.CheckInTime, tr.CheckOutTime, lf.LeaveStartDate, lf.LeaveEndDate, lf.indicator 
          FROM TimeRecord tr
          LEFT JOIN LeaveFile lf ON tr.EmployeeID = lf.EmployeeID 
          AND tr.Date BETWEEN lf.LeaveStartDate AND lf.LeaveEndDate
          WHERE tr.EmployeeID = ? 
          AND tr.Date BETWEEN ? AND ?";

// Prepare the statement and bind the parameters
$stmt = $conn->prepare($query);
$stmt->bind_param("iss", $employeeId, $fromDate, $toDate);
$stmt->execute();
$result = $stmt->get_result();

// Prepare an array to hold the attendance records
$attendanceRecords = [];

while ($row = $result->fetch_assoc()) {
    $status = 'On Time'; // Default status
    if ($row['indicator'] == 'APPROVED') {
        $status = 'On Leave';
    } else {
        $checkInTime = strtotime($row['CheckInTime']);
        $eightAM = strtotime('08:00:00');
        if ($checkInTime > $eightAM) {
            $status = 'Late';
        }
    }

    // Calculate hours worked
    $hoursWorked = 0;
    $overtimePay = 0;

    if ($row['CheckInTime'] && $row['CheckOutTime']) {
        $checkIn = strtotime($row['CheckInTime']);
        $checkOut = strtotime($row['CheckOutTime']);
        $hoursWorked = ($checkOut - $checkIn) / 3600;

        // Calculate OvertimePay if CheckOutTime exceeds 5:00 PM
        $fivePM = strtotime('17:00:00');
        if ($checkOut > $fivePM) {
            $exceedingTime = $checkOut - $fivePM;
            $exceedingTimeMinutes = $exceedingTime / 60;
            $overtimePay = floor($exceedingTimeMinutes / 60);
        }
    }

    $attendanceRecords[] = [
        'Date' => $row['Date'],
        'Status' => $status,
        'HoursWorked' => round($hoursWorked, 2),
        'OvertimePay' => $overtimePay
    ];
}

// Return the data as JSON
echo json_encode($attendanceRecords);

// Close the database connection
$stmt->close();
$conn->close();
?>
