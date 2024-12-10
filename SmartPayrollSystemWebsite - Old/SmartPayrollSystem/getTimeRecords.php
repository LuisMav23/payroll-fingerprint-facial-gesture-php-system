<?php
include 'db.php';

// Retrieve query parameters
$employeeId = $_GET['employeeId'];
$month = $_GET['month'] ?? null;
$year = $_GET['year'] ?? null;

// Base query
$query = "SELECT Date, CheckInTime, CheckOutTime FROM TimeRecord WHERE EmployeeID = ?";

// Add filters for month and year if provided
$params = [$employeeId];
$paramTypes = "i";

if ($month) {
    $query .= " AND MONTH(Date) = ?";
    $params[] = $month;
    $paramTypes .= "i";
}
if ($year) {
    $query .= " AND YEAR(Date) = ?";
    $params[] = $year;
    $paramTypes .= "i";
}

$query .= " ORDER BY Date ASC";

// Prepare and execute query
$stmt = $conn->prepare($query);
$stmt->bind_param($paramTypes, ...$params);
$stmt->execute();
$result = $stmt->get_result();

$records = [];
while ($row = $result->fetch_assoc()) {
    $records[] = $row;
}

// Return records as JSON
echo json_encode($records);

// Close connections
$stmt->close();
$conn->close();
?>
