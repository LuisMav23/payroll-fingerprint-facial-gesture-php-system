<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection settings
$host = 'localhost';
$dbname = 'payroll_mdb';
$username = 'root';
$password = '';

try {
    // Create a new PDO instance
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if the request method is POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Check if the 'fileData' is present in the POST request
        if (isset($_POST['fileData'])) {
            // Retrieve the file data
            $fileData = $_POST['fileData'];

            // Split the content into lines
            $lines = explode("\n", trim($fileData));

            // Remove the header row
            array_shift($lines);

            // Prepare the SQL statement for checking existence
            $checkStmt = $pdo->prepare("SELECT COUNT(*) FROM wy_attendance WHERE emp_code = ? AND attendance_date = ? AND action_time = ?");

            // Prepare the SQL statement for inserting data
            $insertStmt = $pdo->prepare("INSERT INTO wy_attendance (emp_code, attendance_date, action_time) VALUES (?, ?, ?)");

            // Output for debugging
            echo "<pre>Processing the following data:\n";

            // Process each line of the file data
            foreach ($lines as $line) {
                // Skip empty lines
                if (empty(trim($line))) {
                    continue;
                }

                // Split the line into columns using tab delimiter
                $columns = str_getcsv($line, "\t");

                // Output the parsed columns
                echo "Parsed Columns: " . print_r($columns, true) . "\n";

                // Check if we have the expected number of columns
                if (count($columns) === 5) {
                    // Format emp_code based on the example you provided
                    $emp_code = sprintf('WY%02d', intval($columns[0]));

                    // Correctly format the attendance_date and action_time
                    $date_time = trim($columns[3]); // Date and Time combined in one column

                    // Debug log the raw date_time value
                    echo "Raw Date and Time: $date_time\n";

                    // Extract date and time from the combined string
                    $date_time_parts = preg_split('/\s+/', $date_time);
                    $attendance_date = isset($date_time_parts[0]) ? $date_time_parts[0] : '';
                    $action_time = isset($date_time_parts[1]) ? $date_time_parts[1] : '';

                    // Debug logs for date and time extraction
                    echo "Extracted Date Part: $attendance_date\n";
                    echo "Extracted Time Part: $action_time\n";

                    // Convert date format to 'Y-m-d'
                    $formatted_date = date('Y-m-d', strtotime($attendance_date));

                    // Debug log the formatted date value
                    echo "Formatted Date: $formatted_date\n";

                    // Check if the date and time already exist for the emp_code
                    $checkStmt->execute([$emp_code, $formatted_date, $action_time]);
                    $exists = $checkStmt->fetchColumn();

                    if ($exists > 0) {
                        echo "Error: Record already exists for emp_code = $emp_code, date = $formatted_date, time = $action_time\n";
                    } else {
                        // Log the values to be inserted
                        echo "Inserting Values: emp_code = $emp_code, attendance_date = $formatted_date, action_time = $action_time\n";

                        // Bind the parameters and execute the statement
                        $insertStmt->execute([
                            $emp_code,           // emp_code
                            $formatted_date,    // attendance_date
                            $action_time        // action_time (should be HH:MM:SS)
                        ]);
                    }
                } else {
                    echo "Error: Line does not have the expected number of columns: $line\n";
                }
            }

            // Respond with a success message
            echo "\nFile data processed and inserted into the database.";
        } else {
            // Respond with an error message if 'fileData' is not present
            echo 'No file data received.';
        }
    } else {
        // Respond with an error message if the request method is not POST
        echo 'Invalid request method.';
    }
} catch (PDOException $e) {
    // Respond with an error message for database-related issues
    echo 'Database error: ' . $e->getMessage();
} catch (Exception $e) {
    // Respond with an error message for general issues
    echo 'Error: ' . $e->getMessage();
}
?>