<?php
// Include the database connection file
//require_once 'db.php'; // Make sure this path is correct
include 'db.php';

// Prepare and execute the query to fetch departments
$sql = "SELECT DepartmentID, DepartmentName FROM Department";
$result = $conn->query($sql);

$departments = [];
if ($result->num_rows > 0) {
    // Fetch each row and add to the departments array
    while ($row = $result->fetch_assoc()) {
        $departments[] = $row;
    }
}

// Return the result as a JSON object
header('Content-Type: application/json');
echo json_encode($departments);

// Close the connection
$conn->close();
?>
