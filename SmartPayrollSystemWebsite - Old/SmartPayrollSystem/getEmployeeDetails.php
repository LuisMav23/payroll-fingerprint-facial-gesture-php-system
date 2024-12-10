<?php
include('db.php');

// Check if employeeId is provided in the request
if (isset($_GET['employeeId'])) {
    $employeeId = $_GET['employeeId']; // Get employee ID from the request

    // Query to fetch employee details
    $query = "
        SELECT e.FirstName, e.LastName, p.SalaryPosition AS Salary, p.Indicator, e.MaxicareType, e.Age
        FROM Employee e
        JOIN Position p ON e.PositionID = p.PositionID
        WHERE e.EmployeeID = ?
    ";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $employeeId);  // Bind the employeeId as an integer
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the employee exists
    if ($result->num_rows > 0) {
        $employee = $result->fetch_assoc();
        echo json_encode($employee);  // Return employee data as JSON
    } else {
        echo json_encode(['error' => 'Employee not found']);  // Return error if no employee is found
    }

    $stmt->close();  // Close the statement
} else {
    echo json_encode(['error' => 'employeeId parameter is missing']);  // Return error if employeeId is not provided
}

$conn->close();  // Close the connection
?>
