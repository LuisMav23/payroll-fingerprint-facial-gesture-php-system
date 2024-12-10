<?php
// Start session and include the database connection file
session_start();
include('db.php');

$employeeID = $_SESSION['employee_id'] ?? null;

// Check if EmployeeID is provided
if (!$employeeID) {
    echo json_encode(['error' => 'Employee ID not found']);
    exit;
}

// Prepare the SQL query to fetch employee information
$query = "
    SELECT 
        Employee.EmployeeID,
        Employee.FirstName,
        Employee.MiddleName,
        Employee.LastName,
        Employee.Age,
        Employee.Status,
        Employee.Email,
        Employee.Phone,
        Employee.Address,
        Position.SalaryPosition,
        Department.DepartmentName AS Department,
        Position.PositionName AS Position,
        Employee.MaxicareType as MaxicareType
    FROM 
        Employee
    LEFT JOIN 
        Department ON Employee.DepartmentID = Department.DepartmentID
    LEFT JOIN 
        Position ON Employee.PositionID = Position.PositionID
    WHERE 
        Employee.EmployeeID = ?
";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $employeeID);

if ($stmt->execute()) {
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $employeeData = $result->fetch_assoc();
        echo json_encode($employeeData);
    } else {
        echo json_encode(['error' => 'Employee not found']);
    }
} else {
    echo json_encode(['error' => 'Failed to execute query: ' . $stmt->error]);
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>
