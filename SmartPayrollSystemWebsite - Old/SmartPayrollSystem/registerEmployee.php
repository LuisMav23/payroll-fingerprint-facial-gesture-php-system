<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = $conn->real_escape_string($_POST['firstName']);
    $middleName = isset($_POST['middleName']) && !empty($_POST['middleName']) ? $conn->real_escape_string($_POST['middleName']) : null;
    $lastName = $conn->real_escape_string($_POST['lastName']);
    $birthDate = $conn->real_escape_string($_POST['birthdate']);
    $age = intval($_POST['age']);
    $status = $conn->real_escape_string($_POST['status']);
    $sex = $conn->real_escape_string($_POST['sex']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $barangay = $conn->real_escape_string($_POST['barangay']);
    $streetNumber = $conn->real_escape_string($_POST['streetnumber']);
    $city = $conn->real_escape_string($_POST['city']);
    $postalZipCode = intval($_POST['postalzipcode']);
    $positionID = intval($_POST['position']);
    $maxicaretype = $conn->real_escape_string($_POST['maxicaretype']);
    $salaryLoanInd = $conn->real_escape_string($_POST['salaryloanoption']);
    $username = $conn->real_escape_string($_POST['username']);
    $password = $conn->real_escape_string($_POST['password']);
    $role = $conn->real_escape_string($_POST['role']);
    $faceData = isset($_FILES['faceData']) ? $_FILES['faceData'] : null;

    // Check for duplicate email
    $checkEmailStmt = $conn->prepare("SELECT Email FROM Employee WHERE Email = ?");
    $checkEmailStmt->bind_param("s", $email);
    $checkEmailStmt->execute();
    $checkEmailStmt->store_result();

    if ($checkEmailStmt->num_rows > 0) {
        echo "<script>alert('The email is already registered. Please use a different email.');</script>";
    }
    $checkEmailStmt->close();

    // Check for duplicate username
    $checkUsernameStmt = $conn->prepare("SELECT Username FROM User WHERE Username = ?");
    $checkUsernameStmt->bind_param("s", $username);
    $checkUsernameStmt->execute();
    $checkUsernameStmt->store_result();

    if ($checkUsernameStmt->num_rows > 0) {
        echo "<script>alert('The username is already taken. Please choose a different username.');</script>";
    }
    $checkUsernameStmt->close();

    // Process face data (multiple images)
    if ($faceData) {
        $faceImages = [];
        $targetDir = "model/training-images/"; // Define absolute path for image storage
        $userDir = $targetDir . $username;

        // Create directory if it doesn't exist
        if (!file_exists($userDir)) {
            mkdir($userDir, 0777, true); // Create directory with permissions
        }

        // Upload the images and store paths
        foreach ($faceData['name'] as $key => $imageName) {
            $targetFile = $userDir . "/" . basename($faceData['name'][$key]);
            if (move_uploaded_file($faceData['tmp_name'][$key], $targetFile)) {
                $faceImages[] = $targetFile; // Store the path to each uploaded image
            } else {
                echo "<script>alert('Error uploading face data image: " . $faceData['name'][$key] . "');</script>";
            }
        }
    }

    // Prepare SQL statement for the Employee table
    $stmt = $conn->prepare("INSERT INTO Employee (FirstName, MiddleName, LastName, BirthDate, Age, Status, Sex, Email, Phone, Barangay, StreetNBuildingHouseNo, City, Postal_zip_code, PositionID, MaxicareType, SalaryLoan_ind) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssisisssssisss", $firstName, $middleName, $lastName, $birthDate, $age, $status, $sex, $email, $phone, $barangay, $streetNumber, $city, $postalZipCode, $positionID, $maxicaretype, $salaryLoanInd);

    if ($stmt->execute()) {
        // Get the last inserted EmployeeID
        $employeeID = $conn->insert_id;

        // Prepare SQL statement for the User table
        $stmtUser = $conn->prepare("INSERT INTO User (Username, PasswordHash, Role, EmployeeID) VALUES (?, ?, ?, ?)");
        $stmtUser->bind_param("sssi", $username, $password, $role, $employeeID);

        if ($stmtUser->execute()) {
            // Insert appropriate leave types based on employee sex
            $leaveTypes = [
                'Sick Leave' => 10,
                'Vacation Leave' => 10,
                'Emergency Leave' => 10,
                'Maternity Leave' => ($sex === 'Female' ? 105 : 0),
                'Paternity Leave' => ($sex === 'Male' ? 7 : 0)
            ];

            foreach ($leaveTypes as $leaveType => $daysAvailable) {
                if ($daysAvailable > 0) {
                    $stmtLeave = $conn->prepare("INSERT INTO LeaveBalance (EmployeeID, LeaveType, DaysAvailable) VALUES (?, ?, ?)");
                    $stmtLeave->bind_param("isi", $employeeID, $leaveType, $daysAvailable);
                    $stmtLeave->execute();
                    $stmtLeave->close();
                }
            }

            $url = 'http://127.0.0.1:5000/train'; // Flask endpoint
            $data = array('username' => $username); // Data to send

            $ch = curl_init($url);

            // Set cURL options
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return the response as a string
            curl_setopt($ch, CURLOPT_POST, true); // Send POST request
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data)); // Send data

            $response = curl_exec($ch);

            // Check for errors
            if ($response === false) {
                echo "<script>alert('Error: " . curl_error($ch) . "');</script>";
            } else {
                $response_data = json_decode($response, true);

                if (isset($response_data['success']) && $response_data['success'] == true) {
                    echo "<script>alert('Employee Registered Successfully!');</script>";
                } else {
                    echo "<script>alert('Failed to train model.');</script>";

                }
            }

        } else {
            echo "<script>alert('Error inserting into User table: " . $stmtUser->error . "');</script>";
        }

    } else {
        echo "<script>alert('Error inserting into Employee table: " . $stmt->error . "');</script>";
    }

} else {
    echo "<script>alert('Invalid request method.');</script>";
}

?>