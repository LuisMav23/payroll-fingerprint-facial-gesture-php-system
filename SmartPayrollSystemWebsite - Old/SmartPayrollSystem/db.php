<?php
// db.php - Database connection
//$host = 'sql12.freesqldatabase.com';
//$db = 'sql12737713';  // Your database name
//$user = 'sql12737713';
//$pass = 'uCJHx2u744'; // Your password

$host = 'localhost';
$db = 'smartpayrollsystem';  // Your database name
$user = 'root';
$pass = ''; // Your password

// Create connection
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
