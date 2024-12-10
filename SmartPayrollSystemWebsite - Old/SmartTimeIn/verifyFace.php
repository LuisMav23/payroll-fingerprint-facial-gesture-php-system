<?php
error_reporting(0);
ini_set('display_errors', 0);
header('Content-Type: application/json');

// Include the database connection file
include 'db.php';

// Get the posted face data
$input = json_decode(file_get_contents('php://input'), true);
$faceData = isset($input['faceData']) ? $input['faceData'] : null;

if (!$faceData) {
    echo json_encode(['success' => false, 'message' => 'Invalid face data received']);
    exit;
}

// Query the database for users
$sql = "SELECT UserID, EmployeeID, FaceData FROM User";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $storedFaceData = json_decode($row['FaceData'], true);

        if ($storedFaceData && compareFaces($faceData, $storedFaceData)) {
            $employeeID = $row['EmployeeID'];
            $date = date("Y-m-d");
            $hoursWorked = 8.0; // Default value, adjust as needed
            $status = 'Present';

            // Insert into the Attendance table
            $stmt = $conn->prepare("INSERT INTO Attendance (EmployeeID, Date, HoursWorked, Status) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("isds", $employeeID, $date, $hoursWorked, $status);

            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Attendance logged successfully']);
                $stmt->close();
                $conn->close();
                exit;
            } else {
                echo json_encode(['success' => false, 'message' => 'Error logging attendance']);
                $stmt->close();
                $conn->close();
                exit;
            }
        }
    }
}

// If no match is found
echo json_encode(['success' => false, 'message' => 'Face not recognized']);
$conn->close();

function compareFaces($face1, $face2) {
    if (empty($face1) || empty($face2)) {
        return false;
    }

    // Compute Euclidean distance between the two sets of landmarks
    $distance = 0;
    for ($i = 0; $i < count($face1); $i++) {
        $distance += pow($face1[$i][0] - $face2[$i][0], 2) + pow($face1[$i][1] - $face2[$i][1], 2);
    }

    $threshold = 2000; // Adjust this threshold based on your dataset and testing
    return sqrt($distance) < $threshold;
}
?>
