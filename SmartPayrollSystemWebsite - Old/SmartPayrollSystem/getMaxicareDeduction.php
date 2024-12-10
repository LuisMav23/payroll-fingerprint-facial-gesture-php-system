<?php
include('db.php');

// Ensure employeeId is provided in the request
if (isset($_GET['employeeId']) && isset($_GET['maxicareType']) && isset($_GET['age'])) {
    $employeeId = $_GET['employeeId'];
    $maxicareType = $_GET['maxicareType'];
    $age = $_GET['age'];

    // Query to fetch the Maxicare annual amount
    $sqlMaxicare = "SELECT maxicare_annual_amt FROM tbl_maxicare WHERE maxicare_type = ? AND maxicare_age >= ? ORDER BY maxicare_age ASC LIMIT 1";
    $stmtMaxicare = $conn->prepare($sqlMaxicare);
    $stmtMaxicare->bind_param("si", $maxicareType, $age);
    $stmtMaxicare->execute();
    $stmtMaxicare->bind_result($maxicareAnnualAmount);
    $stmtMaxicare->fetch();
    $stmtMaxicare->close();

    // If no match is found for Maxicare, default it to 0 or handle error
    if (!$maxicareAnnualAmount) {
        $maxicareAnnualAmount = 0;
    }

    // Calculate the monthly deduction (annual amount divided by 12, then divide by 2 as per your requirement)
    $monthlyMaxicare = ($maxicareAnnualAmount / 12) / 2;

    // Return the Maxicare deduction as JSON without formatting
    echo json_encode(['maxicare_annual_amt' => $monthlyMaxicare]);
} else {
    // If required parameters are missing, return an error message
    echo json_encode(['error' => 'employeeId, maxicareType, or age parameter is missing']);
}
?>
