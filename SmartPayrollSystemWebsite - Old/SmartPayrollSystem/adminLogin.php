<?php
session_start();
include('db.php'); // Database connection file

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare and execute the SQL query to get the user details
    $stmt = $conn->prepare('SELECT * FROM User WHERE Username = ? AND Role = "Admin"');
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        
        // Verify the password (assuming passwords are stored as plain text for now)
        if ($user['PasswordHash'] == $password) {
            // Set session variables
            $_SESSION['username'] = $user['Username'];
            $_SESSION['role'] = $user['Role'];
			$_SESSION['employee_id'] = $user['EmployeeID']; // Add this line to store EmployeeID
            
            // Send success response
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid password.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'User not found.']);
    }
}
?>