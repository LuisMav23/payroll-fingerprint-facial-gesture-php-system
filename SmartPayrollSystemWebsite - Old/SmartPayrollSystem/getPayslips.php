<?php
// Database connection
include 'db.php';

session_start();
$employeeId = $_SESSION['employee_id'];

if (!$employeeId) {
    echo json_encode([]);
    exit;
}

$month = isset($_GET['month']) ? $_GET['month'] : '';
$year = isset($_GET['year']) ? $_GET['year'] : '';

// Base query to get payroll and employee information with proper aliases
$sql = "SELECT Payroll.PayrollID, Payroll.PayDate, Payroll.TotalHours, Payroll.GrossPay, Payroll.NetPay, Payroll.OvertimePay,
               Payroll.SocialSecuritySystem, Payroll.PagIbig, Payroll.PhilHealth, Payroll.Tax,
               CONCAT(Employee.FirstName, ' ', COALESCE(Employee.MiddleName, ''), ' ', Employee.LastName) AS EmployeeName,
               Position.SalaryPosition AS Salary,
               Department.DepartmentName AS Department,
               Position.PositionName AS Position,
               Payroll.OvertimeHours,
               Payroll.SalaryLoan,
               Payroll.Maxicare as Maxicare
        FROM Payroll 
        JOIN Employee ON Payroll.EmployeeID = Employee.EmployeeID
        LEFT JOIN Department ON Employee.DepartmentID = Department.DepartmentID
        LEFT JOIN Position ON Employee.PositionID = Position.PositionID
        WHERE Payroll.EmployeeID = ?
        AND Payroll.Flag = TRUE";

// Add filtering for month and year if provided
if ($month && $year) {
    $sql .= " AND MONTH(Payroll.PayDate) = ? AND YEAR(Payroll.PayDate) = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iii", $employeeId, $month, $year);
} elseif ($year) {
    $sql .= " AND YEAR(Payroll.PayDate) = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $employeeId, $year);
} else {
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $employeeId);
}

$stmt->execute();
$result = $stmt->get_result();

$payslips = [];
while ($row = $result->fetch_assoc()) {
    $payslips[] = $row;
}

echo json_encode($payslips);
$conn->close();
?>
