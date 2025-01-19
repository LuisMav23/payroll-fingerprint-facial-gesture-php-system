<?php
include(dirname(dirname(__FILE__)) . '/config.php');

$case = $_GET['case'];
switch ($case) {
	case 'LoginProcessHandler':
		LoginProcessHandler();
		break;
	case 'GetAllOvertimes':
		GetAllOvertimes();
		break;
	case 'GetAllEmployees':
		GetAllEmployees();
		break;
	case 'ApproveOvertime':
		ApproveOvertime();
		break;
	case 'RejectOvertime':
		RejectOvertime();
		break;
	case 'AttendanceProcessHandler':
		AttendanceProcessHandler();
		break;
	case 'LoadingAttendance':
		LoadingAttendance();
		break;
	case 'LoadingAttendanceByEmpCode':
		LoadingAttendanceByEmpCode();
		break;
	case 'LoadingAttendanceByEmpId':
		LoadingAttendanceByEmpId();
		break;
	case 'LoadingSalaries':
		LoadingSalaries();
		break;
	case 'LoadingEmployees':
		LoadingEmployees();
		break;
	case 'AssignPayheadsToEmployee':
		AssignPayheadsToEmployee();
		break;
	case 'InsertUpdateHolidays':
		InsertUpdateHolidays();
		break;
	case 'GetHolidayByID':
		GetHolidayByID();
		break;
	case 'DeleteHolidayByID':
		DeleteHolidayByID();
		break;
	case 'LoadingHolidays':
		LoadingHolidays();
		break;
	case 'InsertUpdatePayheads':
		InsertUpdatePayheads();
		break;
	case 'GetPayheadByID':
		GetPayheadByID();
		break;
	case 'DeletePayheadByID':
		DeletePayheadByID();
		break;
	case 'LoadingPayheads':
		LoadingPayheads();
		break;
	case 'GetAllPayheadsExceptEmployeeHave':
		GetAllPayheadsExceptEmployeeHave();
		break;
	case 'GetEmployeePayheadsByID':
		GetEmployeePayheadsByID();
		break;
	case 'GetEmployeeByID':
		GetEmployeeByID();
		break;
	case 'DeleteEmployeeByID':
		DeleteEmployeeByID();
		break;
	case 'EditEmployeeDetailsByID':
		EditEmployeeDetailsByID();
		break;
	case 'GeneratePaySlip':
		GeneratePaySlip();
		break;
	case 'SendPaySlipByMail':
		SendPaySlipByMail();
		break;
	case 'EditProfileByID':
		EditProfileByID();
		break;
	case 'EditLoginDataByID':
		EditLoginDataByID();
		break;
	case 'LoadingAllLeaves':
		LoadingAllLeaves();
		break;
	case 'LoadingMyLeaves':
		LoadingMyLeaves();
		break;
	case 'ApplyLeaveToAdminApproval':
		ApplyLeaveToAdminApproval();
		break;
	case 'ApproveLeaveApplication':
		ApproveLeaveApplication();
		break;
	case 'RejectLeaveApplication':
		RejectLeaveApplication();
		break;
	default:
		echo '404! Page Not Found.';
		break;
}

function LoginProcessHandler()
{
	$result = array();
	global $db;

	$code = addslashes($_POST['code']);
	$password = addslashes($_POST['password']);
	if (!empty($code) && !empty($password)) {
		$adminCheck = mysqli_query($db, "SELECT * FROM `" . DB_PREFIX . "admin` WHERE `admin_code` = '$code' AND `admin_password` = '" . sha1($password) . "' LIMIT 0, 1");
		if ($adminCheck) {
			if (mysqli_num_rows($adminCheck) == 1) {
				$adminData = mysqli_fetch_assoc($adminCheck);
				$_SESSION['Admin_ID'] = $adminData['admin_id'];
				$_SESSION['Login_Type'] = 'admin';
				$result['result'] = BASE_URL . 'attendance/';
				$result['code'] = 0;
			} else {
				$empCheck = mysqli_query($db, "SELECT * FROM `" . DB_PREFIX . "employees` WHERE `emp_code` = '$code' AND `emp_password` = '" . sha1($password) . "' LIMIT 0, 1");
				if ($empCheck) {
					if (mysqli_num_rows($empCheck) == 1) {
						$empData = mysqli_fetch_assoc($empCheck);
						$_SESSION['Admin_ID'] = $empData['emp_id'];
						$_SESSION['Login_Type'] = 'emp';
						$result['result'] = BASE_URL . 'profile/';
						$result['code'] = 0;
					} else {
						$result['result'] = 'Invalid Login Details.';
						$result['code'] = 1;
					}
				} else {
					$result['result'] = 'Something went wrong, please try again.';
					$result['code'] = 2;
				}
			}
		} else {
			$result['result'] = 'Something went wrong, please try again.';
			$result['code'] = 2;
		}
	} else {
		$result['result'] = 'Login Details should not be blank.';
		$result['code'] = 3;
	}

	echo json_encode($result);
}

function AttendanceProcessHandler()
{
	global $userData, $db;
	$result = array();

	$emp_code = $userData['emp_code'];
	$attendance_date = date('Y-m-d');
	$attendanceSQL = mysqli_query($db, "SELECT * FROM `" . DB_PREFIX . "attendance` WHERE `emp_code` = '$emp_code' AND `attendance_date` = '$attendance_date'");
	if ($attendanceSQL) {
		$attendanceROW = mysqli_num_rows($attendanceSQL);
		if ($attendanceROW == 0) {
			$action_name = 'punchin';
		} else {
			$attendanceDATA = mysqli_fetch_assoc($attendanceSQL);
			if ($attendanceDATA['action_name'] == 'punchin') {
				$action_name = 'punchout';
			} else {
				$action_name = 'punchin';
			}
		}
	} else {
		$attendanceROW = 0;
		$action_name = 'punchin';
	}
	$action_time = date('H:i:s');
	$emp_desc = addslashes($_POST['desc']);

	$insertSQL = mysqli_query($db, "INSERT INTO `" . DB_PREFIX . "attendance`(`emp_code`, `attendance_date`, `action_name`, `action_time`, `emp_desc`) VALUES ('$emp_code', '$attendance_date', '$action_name', '$action_time', '$emp_desc')");
	if ($insertSQL) {
		$result['next'] = ($action_name == 'punchin' ? 'Punch Out' : 'Punch In');
		$result['complete'] = $attendanceROW + 1;
		$result['result'] = 'You have successfully punched in.';
		$result['code'] = 0;
	} else {
		$result['result'] = 'Something went wrong, please try again.';
		$result['code'] = 1;
	}

	echo json_encode($result);
}

function GetAllOvertimes()
{
    global $db;

    $sql = "SELECT * FROM `" . DB_PREFIX . "overtimes`";
    $query = mysqli_query($db, $sql);
    $data = array();

    while ($row = mysqli_fetch_assoc($query)) {
        $nestedData = array();
        $nestedData[] = $row["id"];
        $nestedData[] = $row["emp_code"];
        $nestedData[] = date('d-m-Y', strtotime($row["overtime_date"])); // Format date
        $nestedData[] = $row["overtime_out_time"]; // Add time out
        $nestedData[] = $row["status"];
        
        $data[] = $nestedData;
    }
    header('Content-Type: application/json');
    echo json_encode(array('data' => $data));
}

function GetAllEmployees()
{
    global $db;

    $sql = "SELECT * FROM `" . DB_PREFIX . "employees`";
    $query = mysqli_query($db, $sql);
    $data = array();

    while ($row = mysqli_fetch_assoc($query)) {
        $nestedData = array();
        $nestedData[] = $row["emp_code"];
        $nestedData[] = $row["first_name"];
        $nestedData[] = $row["last_name"];
        $nestedData[] = $row["designation"];
        $nestedData[] = $row["department"];
        $nestedData[] = date('d-m-Y', strtotime($row["hire_date"])); // Format hire date
        $nestedData[] = '<img width="75" src="' . REG_URL . 'photos/' . $row["photo"] . '" alt="' . $row["emp_code"] . '" />';
        
        $data[] = $nestedData;
    }
    header('Content-Type: application/json');
    echo json_encode(array('data' => $data));
}

function GetEmployeeAttendance() {
	global $db;
	$sql = ''. DB_PREFIX . '';
}

function ApproveOvertime()
{
    global $db;

    $overtime_id = $_POST['overtime_id'];
    if (empty($overtime_id)) {
        echo json_encode(['code' => 1, 'result' => 'Overtime ID is required.']);
        return;
    }

    $sql = "UPDATE `" . DB_PREFIX . "overtimes` SET `status` = 'approved' WHERE `id` = $overtime_id";
    $query = mysqli_query($db, $sql);

    if ($query) {
        echo json_encode(['code' => 0, 'result' => 'Overtime approved successfully.']);
    } else {
        echo json_encode(['code' => 1, 'result' => 'Failed to approve overtime.']);
    }
}

function RejectOvertime()
{
    global $db;

    $overtime_id = $_POST['overtime_id'];
    if (empty($overtime_id)) {
        echo json_encode(['code' => 1, 'result' => 'Overtime ID is required.']);
        return;
    }

    $sql = "UPDATE `" . DB_PREFIX . "overtimes` SET `status` = 'rejected' WHERE `id` = $overtime_id";
    $query = mysqli_query($db, $sql);

    if ($query) {
        echo json_encode(['code' => 0, 'result' => 'Overtime rejected successfully.']);
    } else {
        echo json_encode(['code' => 1, 'result' => 'Failed to reject overtime.']);
    }
}

function LoadingAttendance()
{
	global $db;

	$sql = "SELECT `emp`.`emp_code`, `emp`.`first_name`, `emp`.`last_name`, `att`.`attendance_date`, 
            GROUP_CONCAT(`att`.`action_time`) AS `times`, GROUP_CONCAT(`att`.`emp_desc`) AS `descs`
            FROM `" . DB_PREFIX . "employees` AS `emp`
            JOIN `" . DB_PREFIX . "attendance` AS `att` ON `emp`.`emp_code` = `att`.`emp_code`
            GROUP BY `emp`.`emp_code`, `att`.`attendance_date`";

	$query = mysqli_query($db, $sql);
	$data = array();
	
	while ($row = mysqli_fetch_assoc($query)) {
		$nestedData = array();
		$nestedData[] = date('d-m-Y', strtotime($row['attendance_date']));
		$nestedData[] = $row["emp_code"];
		$nestedData[] = '<a target="_blank" href="' . REG_URL . 'reports/' . $row["emp_code"] . '/">' . $row["first_name"] . ' ' . $row["last_name"] . '</a>';
		$times = explode(',', $row["times"]);
		$descs = explode(',', $row["descs"]);
		$nestedData[] = isset($times[0]) ? date('h:i:s A', strtotime($times[0])) : '';
		$nestedData[] = isset($times[1]) ? date('h:i:s A', strtotime($times[1])) : '';
		$datetime1 = new DateTime($times[0]);
		$datetime2 = new DateTime($times[1]);
		$interval = $datetime1->diff($datetime2);
		$nestedData[] = (isset($times[0]) && isset($times[1])) ? $interval->format('%h') . " Hrs  |" . $interval->format('%i') . " Min" : '0 Hrs';
		$nestedData[] = isset($descs[0]) ? $descs[0] : '';
		$nestedData[] = isset($descs[1]) ? $descs[1] : '';

		$data[] = $nestedData;
	}

	header('Content-Type: application/json');
	echo json_encode(array('data' => $data));
}

function LoadingAttendanceByEmpCode()
{
    global $db;

    // Validate and sanitize emp_code to prevent SQL injection
    if (!isset($_GET['emp_code'])) {
        echo json_encode(['error' => 'Employee code is required']);
        return;
    }

    $emp_code = mysqli_real_escape_string($db, $_GET['emp_code']);

    // SQL query to fetch attendance records
    $sql = "SELECT 
                `att`.`attendance_date`, 
                `emp`.`emp_code`, 
                `emp`.`first_name`, 
                `emp`.`last_name`, 
                `att`.`action_time`, 
                `att`.`emp_desc`
            FROM `" . DB_PREFIX . "attendance` AS `att`
            JOIN `" . DB_PREFIX . "employees` AS `emp` 
                ON `emp`.`emp_code` = `att`.`emp_code`
            WHERE `emp`.`emp_code` = '$emp_code'
            ORDER BY `att`.`attendance_date`, `att`.`action_time`";

    $query = mysqli_query($db, $sql);

    if (!$query) {
        echo json_encode(['error' => 'Database query failed: ' . mysqli_error($db)]);
        return;
    }

    $data = [];
    $attendance = [];

    while ($row = mysqli_fetch_assoc($query)) {
        $date = $row['attendance_date'];
        if (!isset($attendance[$date])) {
            // Initialize new attendance entry for the date
            $attendance[$date] = [
                'date' => date('d-m-Y', strtotime($date)),
                'emp_code' => $row['emp_code'],
                'emp_name' => '<a target="_blank" href="' . REG_URL . 'reports/' . $row['emp_code'] . '/">' . $row['first_name'] . ' ' . $row['last_name'] . '</a>',
                'punch_in' => '',
                'punch_out' => '',
                'work_hours' => '0 Hrs',
                'desc_in' => '',
                'desc_out' => ''
            ];
        }

        // Assign punch-in or punch-out based on existing data
        $time = $row['action_time'];
        $desc = $row['emp_desc'];
        if (empty($attendance[$date]['punch_in'])) {
            $attendance[$date]['punch_in'] = date('h:i:s A', strtotime($time));
            $attendance[$date]['desc_in'] = $desc;
        } elseif (empty($attendance[$date]['punch_out'])) {
            $attendance[$date]['punch_out'] = date('h:i:s A', strtotime($time));
            $attendance[$date]['desc_out'] = $desc;

            // Calculate work hours
            $datetime1 = new DateTime($attendance[$date]['punch_in']);
            $datetime2 = new DateTime($attendance[$date]['punch_out']);
            $interval = $datetime1->diff($datetime2);
            $attendance[$date]['work_hours'] = $interval->format('%h') . " Hrs | " . $interval->format('%i') . " Min";
        }
    }

    // Convert associative array to indexed array for DataTables
    foreach ($attendance as $record) {
        $data[] = [
            $record['date'],        // Date
            $record['emp_code'],   // Employee Code
            $record['emp_name'],   // Employee Name
            $record['punch_in'],   // Punch-in Time
            $record['punch_out'],  // Punch-out Time
            $record['work_hours'], // Work Hours
            $record['desc_in'],    // Description for Punch-in
            $record['desc_out']    // Description for Punch-out
        ];
    }

    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode(['data' => $data]);
}

function LoadingAttendanceByEmpId()
{
    global $db;

    // Validate and sanitize emp_code to prevent SQL injection
    if (!isset($_GET['emp_id'])) {
        echo json_encode(['error' => 'Employee id is required']);
        return;
    }

    $emp_id = mysqli_real_escape_string($db, $_GET['emp_id']);

    // SQL query to fetch attendance records
    $sql = "SELECT 
                `att`.`attendance_date`, 
                `emp`.`emp_code`, 
                `emp`.`first_name`, 
                `emp`.`last_name`, 
                `att`.`action_time`, 
                `att`.`emp_desc`
            FROM `" . DB_PREFIX . "attendance` AS `att`
            JOIN `" . DB_PREFIX . "employees` AS `emp` 
                ON `emp`.`emp_code` = `att`.`emp_code`
            WHERE `emp`.`emp_id` = '$emp_id'
            ORDER BY `att`.`attendance_date`, `att`.`action_time`";

    $query = mysqli_query($db, $sql);

    if (!$query) {
        echo json_encode(['error' => 'Database query failed: ' . mysqli_error($db)]);
        return;
    }

    $data = [];
    $attendance = [];

    while ($row = mysqli_fetch_assoc($query)) {
        $date = $row['attendance_date'];
        if (!isset($attendance[$date])) {
            // Initialize new attendance entry for the date
            $attendance[$date] = [
                'date' => date('d-m-Y', strtotime($date)),
                'emp_code' => $row['emp_code'],
                'emp_name' => '<a target="_blank" href="' . REG_URL . 'reports/' . $row['emp_code'] . '/">' . $row['first_name'] . ' ' . $row['last_name'] . '</a>',
                'punch_in' => '',
                'punch_out' => '',
                'work_hours' => '0 Hrs',
                'desc_in' => '',
                'desc_out' => ''
            ];
        }

        // Assign punch-in or punch-out based on existing data
        $time = $row['action_time'];
        $desc = $row['emp_desc'];
        if (empty($attendance[$date]['punch_in'])) {
            $attendance[$date]['punch_in'] = date('h:i:s A', strtotime($time));
            $attendance[$date]['desc_in'] = $desc;
        } elseif (empty($attendance[$date]['punch_out'])) {
            $attendance[$date]['punch_out'] = date('h:i:s A', strtotime($time));
            $attendance[$date]['desc_out'] = $desc;

            // Calculate work hours
            $datetime1 = new DateTime($attendance[$date]['punch_in']);
            $datetime2 = new DateTime($attendance[$date]['punch_out']);
            $interval = $datetime1->diff($datetime2);
            $attendance[$date]['work_hours'] = $interval->format('%h') . " Hrs | " . $interval->format('%i') . " Min";
        }
    }

    // Convert associative array to indexed array for DataTables
    foreach ($attendance as $record) {
        $data[] = [
            $record['date'],        // Date
            $record['emp_code'],   // Employee Code
            $record['emp_name'],   // Employee Name
            $record['punch_in'],   // Punch-in Time
            $record['punch_out'],  // Punch-out Time
            $record['work_hours'], // Work Hours
            $record['desc_in'],    // Description for Punch-in
            $record['desc_out']    // Description for Punch-out
        ];
    }

    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode(['data' => $data]);
}

function LoadingSalaries()
{
	global $db;
	$requestData = $_REQUEST;
	if ($_SESSION['Login_Type'] == 'admin') {
		$columns = array(
			0 => 'emp_code',
			1 => 'first_name',
			2 => 'last_name',
			3 => 'pay_month',
			4 => 'earning_total',
			5 => 'deduction_total',
			6 => 'net_salary'
		);

		$sql = "SELECT * FROM `" . DB_PREFIX . "salaries` GROUP BY `emp_code`, `pay_month`";
		$query = mysqli_query($db, $sql);
		$totalData = mysqli_num_rows($query);
		$totalFiltered = $totalData;

		$sql = "SELECT `emp`.`emp_code`, `emp`.`first_name`, `emp`.`last_name`, `salary`.*";
		$sql .= " FROM `" . DB_PREFIX . "salaries` AS `salary`, `" . DB_PREFIX . "employees` AS `emp` WHERE `emp`.`emp_code` = `salary`.`emp_code`";
		if (!empty($requestData['search']['value'])) {
			$sql .= " AND (`salary`.`emp_code` LIKE '" . $requestData['search']['value'] . "%'";
			$sql .= " OR CONCAT(TRIM(`emp`.`first_name`), ' ', TRIM(`emp`.`last_name`)) LIKE '" . $requestData['search']['value'] . "%'";
			$sql .= " OR `salary`.`pay_month` LIKE '" . $requestData['search']['value'] . "%'";
			$sql .= " OR `salary`.`earning_total` LIKE '" . $requestData['search']['value'] . "%'";
			$sql .= " OR `salary`.`deduction_total` LIKE '" . $requestData['search']['value'] . "%'";
			$sql .= " OR `salary`.`net_salary` LIKE '" . $requestData['search']['value'] . "%')";
		}
		$sql .= " GROUP BY `salary`.`emp_code`, `salary`.`pay_month`";

		$query = mysqli_query($db, $sql);
		$totalFiltered = mysqli_num_rows($query);

		$data = array();
		$i = 1 + $requestData['start'];
		while ($row = mysqli_fetch_assoc($query)) {
			$nestedData = array();
			$nestedData[] = $row['emp_code'];
			$nestedData[] = '<a target="_blank" href="' . REG_URL . 'reports/' . $row["emp_code"] . '/">' . $row["first_name"] . ' ' . $row["last_name"] . '</a>';
			$nestedData[] = $row['pay_month'];
			$nestedData[] = number_format($row['earning_total'], 2, '.', ',');
			$nestedData[] = number_format($row['deduction_total'], 2, '.', ',');
			$nestedData[] = number_format($row['net_salary'], 2, '.', ',');
			$nestedData[] = '<button type="button" class="btn btn-success btn-xs" onclick="openInNewTab(\'' . BASE_URL . 'payslips/' . $row['emp_code'] . '/' . str_replace(', ', '-', $row['pay_month']) . '/' . str_replace(', ', '-', $row['pay_month']) . '.pdf\');"><i class="fa fa-download"></i></button>';
			// <button type="button" class="btn btn-info btn-xs" onclick="sendPaySlipByMail(\'' . $row['emp_code'] . '\', \'' . $row['pay_month'] . '\');"><i class="fa fa-envelope"></i></button>
			$data[] = $nestedData;
			$i++;
		}
		$response = array(
			"draw" => intval($requestData['draw']),
			"recordsTotal" => intval($totalData),
			"recordsFiltered" => intval($totalFiltered),
			"data" => $data
		);
		header('Content-Type: application/json');
		echo json_encode($response);
	} else {
		try {
			// Your existing code
			$columns = array(
				0 => 'pay_month',
				1 => 'earning_total',
				2 => 'deduction_total',
				3 => 'net_salary',
				4 => 'actions'
			);

			$empData = GetDataByIDAndType($_SESSION['Admin_ID'], $_SESSION['Login_Type']);

			// Correct the SQL query by moving the GROUP BY clause after the WHERE clause
			$sql = "SELECT * FROM `" . DB_PREFIX . "salaries` WHERE `emp_code` = '" . $empData['emp_code'] . "' GROUP BY `emp_code`, `pay_month`";
			$query = mysqli_query($db, $sql);

			if (!$query) {
				throw new Exception("Database Query Failed: " . mysqli_error($db));
			}

			$totalData = mysqli_num_rows($query);
			$totalFiltered = $totalData;

			$sql = "SELECT `emp`.`emp_code`, `emp`.`first_name`, `emp`.`last_name`, `salary`.*";
			$sql .= " FROM `" . DB_PREFIX . "salaries` AS `salary`";
			$sql .= " JOIN `" . DB_PREFIX . "employees` AS `emp` ON `emp`.`emp_code` = `salary`.`emp_code`";
			$sql .= " WHERE `salary`.`emp_code` = '" . $empData['emp_code'] . "'";

			if (!empty($requestData['search']['value'])) {
				$sql .= " AND (`salary`.`emp_code` LIKE '" . $requestData['search']['value'] . "%'";
				$sql .= " OR CONCAT(TRIM(`emp`.`first_name`), ' ', TRIM(`emp`.`last_name`)) LIKE '" . $requestData['search']['value'] . "%'";
				$sql .= " OR `salary`.`pay_month` LIKE '" . $requestData['search']['value'] . "%'";
				$sql .= " OR `salary`.`earning_total` LIKE '" . $requestData['search']['value'] . "%'";
				$sql .= " OR `salary`.`deduction_total` LIKE '" . $requestData['search']['value'] . "%'";
				$sql .= " OR `salary`.`net_salary` LIKE '" . $requestData['search']['value'] . "%')";
			}

			$sql .= " GROUP BY `salary`.`emp_code`, `salary`.`pay_month`";

			$query = mysqli_query($db, $sql);
			if (!$query) {
				throw new Exception("Database Query Failed: " . mysqli_error($db));
			}

			$totalFiltered = mysqli_num_rows($query);

			$data = array();
			$i = 1 + $requestData['start'];
			while ($row = mysqli_fetch_assoc($query)) {
				$nestedData = array();
				$nestedData[] = $row['pay_month'];
				$nestedData[] = number_format($row['earning_total'], 2, '.', ',');
				$nestedData[] = number_format($row['deduction_total'], 2, '.', ',');
				$nestedData[] = number_format($row['net_salary'], 2, '.', ',');
				$nestedData[] = '<button type="button" class="btn btn-success btn-xs" onclick="openInNewTab(\'' . BASE_URL . 'payslips/' . $empData['emp_code'] . '/' . str_replace(', ', '-', $row['pay_month']) . '/' . str_replace(', ', '-', $row['pay_month']) . '.pdf\');"><i class="fa fa-download"></i></button>';

				$data[] = $nestedData;
				$i++;
			}

			$json_data = array(
				"draw" => intval($requestData['draw']),
				"recordsTotal" => intval($totalData),
				"recordsFiltered" => intval($totalFiltered),
				"data" => $data
			);

			echo json_encode($json_data);

		} catch (Exception $e) {
			// Return error in JSON format
			$error_data = array(
				"error" => true,
				"message" => $e->getMessage(),
				"trace" => $e->getTraceAsString()
			);
			echo json_encode($error_data);
			http_response_code(500); // Set the response code to 500
			exit();
		}
	}


}

function LoadingEmployees()
{
	global $db;
	$requestData = $_REQUEST;
	$columns = array(
		0 => 'emp_code',
		1 => 'photo',
		2 => 'first_name',
		3 => 'last_name',
		4 => 'email',
		5 => 'mobile',
		6 => 'identity_doc',
		7 => 'identity_no',
		8 => 'dob',
		9 => 'joining_date',
		10 => 'blood_group',
		11 => 'emp_type'
	);

	$sql = "SELECT `emp_id` ";
	$sql .= " FROM `" . DB_PREFIX . "employees`";
	$query = mysqli_query($db, $sql);
	$totalData = mysqli_num_rows($query);
	$totalFiltered = $totalData;

	$sql = "SELECT *";
	$sql .= " FROM `" . DB_PREFIX . "employees` WHERE 1 = 1";
	if (!empty($requestData['search']['value'])) {
		$sql .= " AND (`emp_id` LIKE '" . $requestData['search']['value'] . "%'";
		$sql .= " OR CONCAT(TRIM(`first_name`), ' ', TRIM(`last_name`)) LIKE '" . $requestData['search']['value'] . "%'";
		$sql .= " OR `email` LIKE '" . $requestData['search']['value'] . "%'";
		$sql .= " OR `mobile` LIKE '" . $requestData['search']['value'] . "%'";
		$sql .= " OR CONCAT(TRIM(`identity_doc`), ' - ', TRIM(`identity_no`)) LIKE '" . $requestData['search']['value'] . "%'";
		$sql .= " OR `dob` LIKE '" . $requestData['search']['value'] . "%'";
		$sql .= " OR `joining_date` LIKE '" . $requestData['search']['value'] . "%'";
		$sql .= " OR `blood_group` LIKE '" . $requestData['search']['value'] . "%'";
		$sql .= " OR `emp_type` LIKE '" . $requestData['search']['value'] . "%')";
	}
	$query = mysqli_query($db, $sql);
	$totalFiltered = mysqli_num_rows($query);
	$sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . " " . $requestData['order'][0]['dir'] . " LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "";
	$query = mysqli_query($db, $sql);

	$data = array();
	$i = 1 + $requestData['start'];
	while ($row = mysqli_fetch_assoc($query)) {
		$nestedData = array();
		$nestedData[] = $row["emp_code"];
		$nestedData[] = '<img width="50" src="' . REG_URL . 'photos/' . $row["photo"] . '" alt="' . $row["emp_code"] . '" />';
		$nestedData[] = '<a target="_blank" href="' . REG_URL . 'reports/' . $row["emp_code"] . '/">' . $row["first_name"] . ' ' . $row["last_name"] . '</a>';
		$nestedData[] = $row["email"];
		$nestedData[] = $row["mobile"];
		$nestedData[] = $row["identity_doc"] . ' - ' . $row["identity_no"];
		$nestedData[] = $row["dob"];
		$nestedData[] = $row["joining_date"];
		$nestedData[] = strtoupper($row["blood_group"]);
		$nestedData[] = ucwords($row["emp_type"]);
		$data[] = $nestedData;
		$i++;
	}
	$json_data = array(
		"draw" => intval($requestData['draw']),
		"recordsTotal" => intval($totalData),
		"recordsFiltered" => intval($totalFiltered),
		"data" => $data
	);

	echo json_encode($json_data);
}


function AssignPayheadsToEmployee()
{
	$result = array();
	global $db;

	$payheads = $_POST['selected_payheads'];
	$default_salary = $_POST['pay_amounts'];
	$emp_code = $_POST['empcode'];
	$checkSQL = mysqli_query($db, "SELECT * FROM `" . DB_PREFIX . "pay_structure` WHERE `emp_code` = '$emp_code'");
	if ($checkSQL) {
		if (!empty($payheads) && !empty($emp_code)) {
			if (mysqli_num_rows($checkSQL) == 0) {
				foreach ($payheads as $payhead) {
					mysqli_query($db, "INSERT INTO `" . DB_PREFIX . "pay_structure`(`emp_code`, `payhead_id`, `default_salary`) VALUES ('$emp_code', $payhead, " . (!empty($default_salary[$payhead]) ? $default_salary[$payhead] : 0) . ")");
				}
				$result['result'] = 'Payheads are successfully assigned to employee.';
				$result['code'] = 0;
			} else {
				mysqli_query($db, "DELETE FROM `" . DB_PREFIX . "pay_structure` WHERE `emp_code` = '$emp_code'");
				foreach ($payheads as $payhead) {
					mysqli_query($db, "INSERT INTO `" . DB_PREFIX . "pay_structure`(`emp_code`, `payhead_id`, `default_salary`) VALUES ('$emp_code', $payhead, " . (!empty($default_salary[$payhead]) ? $default_salary[$payhead] : 0) . ")");
				}
				$result['result'] = 'Payheads are successfully re-assigned to employee.';
				$result['code'] = 0;
			}
		} else {
			$result['result'] = 'Please select payheads and employee to assign.';
			$result['code'] = 2;
		}
	} else {
		$result['result'] = 'Something went wrong, please try again.';
		$result['code'] = 1;
	}

	echo json_encode($result);
}

function InsertUpdateHolidays()
{
	$result = array();
	global $db;

	$holiday_title = stripslashes($_POST['holiday_title']);
	$holiday_desc = stripslashes($_POST['holiday_desc']);
	$holiday_date = stripslashes($_POST['holiday_date']);
	$holiday_type = stripslashes($_POST['holiday_type']);
	if (!empty($holiday_title) && !empty($holiday_desc) && !empty($holiday_date) && !empty($holiday_type)) {
		if (!empty($_POST['holiday_id'])) {
			$holiday_id = addslashes($_POST['holiday_id']);
			$updateHoliday = mysqli_query($db, "UPDATE `" . DB_PREFIX . "holidays` SET `holiday_title` = '$holiday_title', `holiday_desc` = '$holiday_desc', `holiday_date` = '$holiday_date', `holiday_type` = '$holiday_type' WHERE `holiday_id` = $holiday_id");
			if ($updateHoliday) {
				$result['result'] = 'Holiday record has been successfully updated.';
				$result['code'] = 0;
			} else {
				$result['result'] = 'Something went wrong, please try again.';
				$result['code'] = 1;
			}
		} else {
			$insertHoliday = mysqli_query($db, "INSERT INTO `" . DB_PREFIX . "holidays`(`holiday_title`, `holiday_desc`, `holiday_date`, `holiday_type`) VALUES ('$holiday_title', '$holiday_desc', '$holiday_date', '$holiday_type')");
			if ($insertHoliday) {
				$result['result'] = 'Holiday record has been successfully inserted.';
				$result['code'] = 0;
			} else {
				$result['result'] = 'Something went wrong, please try again.';
				$result['code'] = 1;
			}
		}
	} else {
		$result['result'] = 'Holiday details should not be blank.';
		$result['code'] = 2;
	}

	echo json_encode($result);
}

function GetHolidayByID()
{
	$result = array();
	global $db;

	$id = $_POST['id'];
	$holiSQL = mysqli_query($db, "SELECT * FROM `" . DB_PREFIX . "holidays` WHERE `holiday_id` = $id LIMIT 0, 1");
	if ($holiSQL) {
		if (mysqli_num_rows($holiSQL) == 1) {
			$result['result'] = mysqli_fetch_assoc($holiSQL);
			$result['code'] = 0;
		} else {
			$result['result'] = 'Holiday record is not found.';
			$result['code'] = 1;
		}
	} else {
		$result['result'] = 'Something went wrong, please try again.';
		$result['code'] = 2;
	}

	echo json_encode($result);
}

function DeleteHolidayByID()
{
	$result = array();
	global $db;

	$id = $_POST['id'];
	$holiSQL = mysqli_query($db, "DELETE FROM `" . DB_PREFIX . "holidays` WHERE `holiday_id` = $id");
	if ($holiSQL) {
		$result['result'] = 'Holiday record is successfully deleted.';
		$result['code'] = 0;
	} else {
		$result['result'] = 'Something went wrong, please try again.';
		$result['code'] = 1;
	}

	echo json_encode($result);
}

function LoadingHolidays()
{
	global $db;
	$requestData = $_REQUEST;
	$columns = array(
		0 => 'holiday_id',
		1 => 'holiday_title',
		2 => 'holiday_desc',
		3 => 'holiday_date',
		4 => 'holiday_type',
	);

	$sql = "SELECT `holiday_id` ";
	$sql .= " FROM `" . DB_PREFIX . "holidays`";
	$query = mysqli_query($db, $sql);
	$totalData = mysqli_num_rows($query);
	$totalFiltered = $totalData;

	$sql = "SELECT *";
	$sql .= " FROM `" . DB_PREFIX . "holidays` WHERE 1 = 1";
	if (!empty($requestData['search']['value'])) {
		$sql .= " AND (`holiday_id` LIKE '" . $requestData['search']['value'] . "%'";
		$sql .= " OR `holiday_title` LIKE '" . $requestData['search']['value'] . "%'";
		$sql .= " OR `holiday_desc` LIKE '" . $requestData['search']['value'] . "%'";
		$sql .= " OR `holiday_date` LIKE '" . $requestData['search']['value'] . "%'";
		$sql .= " OR `holiday_type` LIKE '" . $requestData['search']['value'] . "%')";
	}
	$query = mysqli_query($db, $sql);
	$totalFiltered = mysqli_num_rows($query);
	$sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . " " . $requestData['order'][0]['dir'] . " LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "";
	$query = mysqli_query($db, $sql);

	$data = array();
	$i = 1 + $requestData['start'];
	while ($row = mysqli_fetch_assoc($query)) {
		$nestedData = array();
		$nestedData[] = $row["holiday_id"];
		$nestedData[] = $row["holiday_title"];
		$nestedData[] = $row["holiday_desc"];
		$nestedData[] = date('d-m-Y', strtotime($row["holiday_date"]));
		if ($row["holiday_type"] == 'compulsory') {
			$nestedData[] = '<span class="label label-success">' . ucwords($row["holiday_type"]) . '</span>';
		} else {
			$nestedData[] = '<span class="label label-danger">' . ucwords($row["holiday_type"]) . '</span>';
		}
		$data[] = $nestedData;
		$i++;
	}
	$json_data = array(
		"draw" => intval($requestData['draw']),
		"recordsTotal" => intval($totalData),
		"recordsFiltered" => intval($totalFiltered),
		"data" => $data
	);

	echo json_encode($json_data);
}

function InsertUpdatePayheads()
{
	$result = array();
	global $db;

	$payhead_name = stripslashes($_POST['payhead_name']);
	$payhead_desc = stripslashes($_POST['payhead_desc']);
	$payhead_type = stripslashes($_POST['payhead_type']);
	if (!empty($payhead_name) && !empty($payhead_desc) && !empty($payhead_type)) {
		if (!empty($_POST['payhead_id'])) {
			$payhead_id = addslashes($_POST['payhead_id']);
			$updatePayhead = mysqli_query($db, "UPDATE `" . DB_PREFIX . "payheads` SET `payhead_name` = '$payhead_name', `payhead_desc` = '$payhead_desc', `payhead_type` = '$payhead_type' WHERE `payhead_id` = $payhead_id");
			if ($updatePayhead) {
				$result['result'] = 'Payhead record has been successfully updated.';
				$result['code'] = 0;
			} else {
				$result['result'] = 'Something went wrong, please try again.';
				$result['code'] = 1;
			}
		} else {
			$insertPayhead = mysqli_query($db, "INSERT INTO `" . DB_PREFIX . "payheads`(`payhead_name`, `payhead_desc`, `payhead_type`) VALUES ('$payhead_name', '$payhead_desc', '$payhead_type')");
			if ($insertPayhead) {
				$result['result'] = 'Payhead record has been successfully inserted.';
				$result['code'] = 0;
			} else {
				$result['result'] = 'Something went wrong, please try again.';
				$result['code'] = 1;
			}
		}
	} else {
		$result['result'] = 'Payhead details should not be blank.';
		$result['code'] = 2;
	}

	echo json_encode($result);
}

function GetPayheadByID()
{
	$result = array();
	global $db;

	$id = $_POST['id'];
	$holiSQL = mysqli_query($db, "SELECT * FROM `" . DB_PREFIX . "payheads` WHERE `payhead_id` = $id LIMIT 0, 1");
	if ($holiSQL) {
		if (mysqli_num_rows($holiSQL) == 1) {
			$result['result'] = mysqli_fetch_assoc($holiSQL);
			$result['code'] = 0;
		} else {
			$result['result'] = 'Payhead record is not found.';
			$result['code'] = 1;
		}
	} else {
		$result['result'] = 'Something went wrong, please try again.';
		$result['code'] = 2;
	}

	echo json_encode($result);
}

function DeletePayheadByID()
{
	$result = array();
	global $db;

	$id = $_POST['id'];
	$holiSQL = mysqli_query($db, "DELETE FROM `" . DB_PREFIX . "payheads` WHERE `payhead_id` = $id ");
	if ($holiSQL) {
		$result['result'] = 'Payhead record is successfully deleted.';
		$result['code'] = 0;
	} else {
		$result['result'] = 'Something went wrong, please try again.';
		$result['code'] = 1;
	}

	echo json_encode($result);
}

function LoadingPayheads()
{
	global $db;
	$requestData = $_REQUEST;
	$columns = array(
		0 => 'payhead_id',
		1 => 'payhead_name',
		2 => 'payhead_desc',
		3 => 'payhead_type'
	);

	$sql = "SELECT `payhead_id` FROM `" . DB_PREFIX . "payheads`";
	$query = mysqli_query($db, $sql);
	$totalData = mysqli_num_rows($query);
	$totalFiltered = $totalData;

	$sql = "SELECT * FROM `" . DB_PREFIX . "payheads` WHERE 1 = 1";
	if (!empty($requestData['search']['value'])) {
		$sql .= " AND (`payhead_id` LIKE '" . $requestData['search']['value'] . "%'";
		$sql .= " OR `payhead_name` LIKE '" . $requestData['search']['value'] . "%'";
		$sql .= " OR `payhead_desc` LIKE '" . $requestData['search']['value'] . "%'";
		$sql .= " OR `payhead_type` LIKE '" . $requestData['search']['value'] . "%')";
	}
	$query = mysqli_query($db, $sql);
	$totalFiltered = mysqli_num_rows($query);

	$sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . " " . $requestData['order'][0]['dir'] . " LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "";
	$query = mysqli_query($db, $sql);

	$data = array();
	$arr = $requestData['start'] + 1; // Set arr to start from the correct index based on pagination
	while ($row = mysqli_fetch_assoc($query)) {
		$nestedData = array();
		$nestedData[] = $row["payhead_id"]; // First column is payhead_id
		$nestedData[] = $arr; // Second column is the sequential number (1, 2, 3, ...)
		$nestedData[] = $row["payhead_name"];
		$nestedData[] = $row["payhead_desc"];
		if ($row["payhead_type"] == 'earnings') {
			$nestedData[] = '<span class="label label-success">' . ucwords($row["payhead_type"]) . '</span>';
		} else {
			$nestedData[] = '<span class="label label-danger">' . ucwords($row["payhead_type"]) . '</span>';
		}
		$data[] = $nestedData;
		$arr++; // Increment arr to get the next sequential number
	}
	$json_data = array(
		"draw" => intval($requestData['draw']),
		"recordsTotal" => intval($totalData),
		"recordsFiltered" => intval($totalFiltered),
		"data" => $data
	);

	echo json_encode($json_data);
}

function GetAllPayheadsExceptEmployeeHave()
{
	$result = array();
	global $db;

	$emp_code = $_POST['emp_code'];
	$salarySQL = mysqli_query($db, "SELECT * FROM `" . DB_PREFIX . "payheads` WHERE `payhead_id` NOT IN (SELECT `payhead_id` FROM `" . DB_PREFIX . "pay_structure` WHERE `emp_code` = '$emp_code')");
	if ($salarySQL) {
		if (mysqli_num_rows($salarySQL) > 0) {
			while ($data = mysqli_fetch_assoc($salarySQL)) {
				$result['result'][] = $data;
			}
			$result['code'] = 0;
		} else {
			$result['result'] = 'Salary record is not found.';
			$result['code'] = 1;
		}
	} else {
		$result['result'] = 'Something went wrong, please try again.';
		$result['code'] = 2;
	}

	echo json_encode($result);
}

function GetEmployeePayheadsByID()
{
	$result = array();
	global $db;

	$emp_code = $_POST['emp_code'];
	$salarySQL = mysqli_query($db, "SELECT * FROM `" . DB_PREFIX . "pay_structure` AS `pay`, `" . DB_PREFIX . "payheads` AS `head` WHERE `head`.`payhead_id` = `pay`.`payhead_id` AND `pay`.`emp_code` = '$emp_code'");
	if ($salarySQL) {
		if (mysqli_num_rows($salarySQL) > 0) {
			while ($data = mysqli_fetch_assoc($salarySQL)) {
				$result['result'][] = $data;
			}
			$result['code'] = 0;
		} else {
			$result['result'] = 'Salary record is not found.';
			$result['code'] = 1;
		}
	} else {
		$result['result'] = 'Something went wrong, please try again.';
		$result['code'] = 2;
	}

	echo json_encode($result);
}

function GetEmployeeByID()
{
	$result = array();
	global $db;

	$emp_code = $_POST['emp_code'];
	$empSQL = mysqli_query($db, "SELECT * FROM `" . DB_PREFIX . "employees` WHERE `emp_code` = '$emp_code' LIMIT 0, 1");
	if ($empSQL) {
		if (mysqli_num_rows($empSQL) == 1) {
			$result['result'] = mysqli_fetch_assoc($empSQL);
			$result['code'] = 0;
		} else {
			$result['result'] = 'Employee record is not found.';
			$result['code'] = 1;
		}
	} else {
		$result['result'] = 'Something went wrong, please try again.';
		$result['code'] = 2;
	}

	echo json_encode($result);
}

function DeleteEmployeeByID()
{
    $result = array();
    global $db;

    $emp_code = $_POST['emp_code'];

    // Retrieve the employee record to archive
    $getEmpSQL = mysqli_query($db, "SELECT * FROM `" . DB_PREFIX . "employees` WHERE `emp_code` = '$emp_code'");
    if ($getEmpSQL && mysqli_num_rows($getEmpSQL) > 0) {
        $employeeData = mysqli_fetch_assoc($getEmpSQL);

        // Build the INSERT query for the archive table
        $columns = array_keys($employeeData);
        $values = array_map(function ($value) use ($db) {
            return "'" . mysqli_real_escape_string($db, $value) . "'";
        }, array_values($employeeData));

        $archiveSQL = "INSERT INTO `" . DB_PREFIX . "deleted_employees` (" . implode(", ", $columns) . ") VALUES (" . implode(", ", $values) . ")";

        // Execute the INSERT query
        if (mysqli_query($db, $archiveSQL)) {
            // If the archiving is successful, delete the employee record
            $deleteSQL = "DELETE FROM `" . DB_PREFIX . "employees` WHERE `emp_code` = '$emp_code'";
            if (mysqli_query($db, $deleteSQL)) {
                $result['result'] = 'Employee record is successfully archived and deleted.';
                $result['code'] = 0;
            } else {
                $result['result'] = 'Archiving succeeded, but deletion failed.';
                $result['code'] = 1;
            }
        } else {
            $result['result'] = 'Failed to archive the employee record. Deletion aborted.';
            $result['code'] = 1;
        }
    } else {
        $result['result'] = 'Employee record not found.';
        $result['code'] = 1;
    }

    echo json_encode($result);
}


function EditEmployeeDetailsByID()
{
	$result = array();
	global $db;

	$emp_id = stripslashes($_POST['emp_id']);
	$first_name = stripslashes($_POST['first_name']);
	$last_name = stripslashes($_POST['last_name']);
	$dob = stripslashes($_POST['dob']);
	$gender = stripslashes($_POST['gender']);
	$merital_status = stripslashes($_POST['merital_status']);
	$nationality = stripslashes($_POST['nationality']);
	$address = stripslashes($_POST['address']);
	$city = stripslashes($_POST['city']);
	$state = stripslashes($_POST['state']);
	$country = stripslashes($_POST['country']);
	$email = stripslashes($_POST['email']);
	$mobile = stripslashes($_POST['mobile']);
	$telephone = stripslashes($_POST['telephone']);
	$identity_doc = stripslashes($_POST['identity_doc']);
	$identity_no = stripslashes($_POST['identity_no']);
	$emp_type = stripslashes($_POST['emp_type']);
	$joining_date = stripslashes($_POST['joining_date']);
	$blood_group = stripslashes($_POST['blood_group']);
	$designation = stripslashes($_POST['designation']);
	$department = stripslashes($_POST['department']);
	$pan_no = stripslashes($_POST['pan_no']);
	$bank_name = stripslashes($_POST['bank_name']);
	$account_no = stripslashes($_POST['account_no']);
	$ifsc_code = stripslashes($_POST['ifsc_code']);
	$pf_account = stripslashes($_POST['pf_account']);
	if (!empty($first_name) && !empty($last_name) && !empty($dob) && !empty($gender) && !empty($merital_status) && !empty($nationality) && !empty($address) && !empty($city) && !empty($state) && !empty($country) && !empty($email) && !empty($mobile) && !empty($identity_doc) && !empty($identity_no) && !empty($emp_type) && !empty($joining_date) && !empty($blood_group) && !empty($designation) && !empty($department) && !empty($pan_no) && !empty($bank_name) && !empty($account_no) && !empty($ifsc_code) && !empty($pf_account)) {
		$updateEmp = mysqli_query($db, "UPDATE `" . DB_PREFIX . "employees` SET `first_name` = '$first_name', `last_name` = '$last_name', `dob` = '$dob', `gender` = '$gender', `merital_status` = '$merital_status', `nationality` = '$nationality', `address` = '$address', `city` = '$city', `state` = '$state', `country` = '$country', `email` = '$email', `mobile` = '$mobile', `telephone` = '$telephone', `identity_doc` = '$identity_doc', `identity_no` = '$identity_no', `emp_type` = '$emp_type', `joining_date` = '$joining_date', `blood_group` = '$blood_group', `designation` = '$designation', `department` = '$department', `pan_no` = '$pan_no', `bank_name` = '$bank_name', `account_no` = '$account_no', `ifsc_code` = '$ifsc_code', `pf_account` = '$pf_account' WHERE `emp_id` = $emp_id");
		if ($updateEmp) {
			$result['result'] = 'Employee details has been successfully updated.';
			$result['code'] = 0;
		} else {
			$result['result'] = 'Something went wrong, please try again.';
			$result['code'] = 1;
		}
	} else {
		$result['result'] = 'All fields are mandatory except Telephone.';
		$result['code'] = 2;
	}

	echo json_encode($result);
}

function GeneratePaySlip()
{
	global $mpdf, $db;
	$result = array();
	$errorMessages = array();

	$emp_code = $_POST['emp_code'];
	$pay_month = $_POST['pay_month'];
	$earnings_heads = $_POST['earnings_heads'];
	$earnings_amounts = $_POST['earnings_amounts'];
	$deductions_heads = $_POST['deductions_heads'];
	$deductions_amounts = $_POST['deductions_amounts'];

	$overtime_rate = 1.5; // Example overtime rate per hour (can be customized)

	if (!empty($emp_code) && !empty($pay_month)) {
		try {
			// Query for approved overtime hours
			$overtimeQuery = "
				SELECT SUM(overtime_hours) AS total_overtime_hours 
				FROM " . DB_PREFIX . "overtime 
				WHERE emp_code = '$emp_code' 
				  AND DATE_FORMAT(overtime_date, '%Y-%m') = '$pay_month' 
				  AND status = 'approved'";
			$overtimeResult = mysqli_query($db, $overtimeQuery);

			$overtimeHours = 0;
			if ($overtimeResult && mysqli_num_rows($overtimeResult) > 0) {
				$overtimeData = mysqli_fetch_assoc($overtimeResult);
				$overtimeHours = floatval($overtimeData['total_overtime_hours']);
			}

			$overtimeEarnings = $overtimeHours * $overtime_rate;

			// Add overtime to earnings
			$earnings_heads[] = "Overtime Pay";
			$earnings_amounts[] = $overtimeEarnings;

			// Process earnings
			for ($i = 0; $i < count($earnings_heads); $i++) {
				$earnings_head = mysqli_real_escape_string($db, $earnings_heads[$i]);
				$earnings_amount = number_format($earnings_amounts[$i], 2, '.', '');
				$checkSalSQL = mysqli_query($db, "SELECT * FROM " . DB_PREFIX . "salaries WHERE emp_code = '$emp_code' AND payhead_name = '$earnings_head' AND pay_month = '$pay_month' AND pay_type = 'earnings' LIMIT 0, 1");

				if (!$checkSalSQL) {
					$errorMessages[] = 'Error checking earnings: ' . mysqli_error($db);
				} else if (mysqli_num_rows($checkSalSQL) == 0) {
					$insertEarningsSQL = "INSERT INTO " . DB_PREFIX . "salaries(emp_code, payhead_name, pay_amount, earning_total, deduction_total, net_salary, pay_type, pay_month, generate_date) VALUES ('$emp_code', '$earnings_head', $earnings_amount, " . number_format(array_sum($earnings_amounts), 2, '.', '') . ", " . number_format(array_sum($deductions_amounts), 2, '.', '') . ", " . number_format((array_sum($earnings_amounts) - array_sum($deductions_amounts)), 2, '.', '') . ", 'earnings', '$pay_month', '" . date('Y-m-d H:i:s') . "')";

					if (!mysqli_query($db, $insertEarningsSQL)) {
						$errorMessages[] = 'Error inserting earnings: ' . mysqli_error($db);
					}
				}
			}

			// Process deductions (no changes needed here)
			for ($i = 0; $i < count($deductions_heads); $i++) {
				$deductions_head = mysqli_real_escape_string($db, $deductions_heads[$i]);
				$deductions_amount = number_format($deductions_amounts[$i], 2, '.', '');
				$checkSalSQL = mysqli_query($db, "SELECT * FROM " . DB_PREFIX . "salaries WHERE emp_code = '$emp_code' AND payhead_name = '$deductions_head' AND pay_month = '$pay_month' AND pay_type = 'deductions' LIMIT 0, 1");

				if (!$checkSalSQL) {
					$errorMessages[] = 'Error checking deductions: ' . mysqli_error($db);
				} else if (mysqli_num_rows($checkSalSQL) == 0) {
					$insertDeductionsSQL = "INSERT INTO " . DB_PREFIX . "salaries(emp_code, payhead_name, pay_amount, earning_total, deduction_total, net_salary, pay_type, pay_month, generate_date) VALUES ('$emp_code', '$deductions_head', $deductions_amount, " . number_format(array_sum($earnings_amounts), 2, '.', '') . ", " . number_format(array_sum($deductions_amounts), 2, '.', '') . ", " . number_format((array_sum($earnings_amounts) - array_sum($deductions_amounts)), 2, '.', '') . ", 'deductions', '$pay_month', '" . date('Y-m-d H:i:s') . "')";

					if (!mysqli_query($db, $insertDeductionsSQL)) {
						$errorMessages[] = 'Error inserting deductions: ' . mysqli_error($db);
					}
				}
			}

			// Retrieve employee data and salary
			$empData = GetEmployeeDataByEmpCode($emp_code);
			$empSalary = GetEmployeeSalaryByEmpCodeAndMonth($emp_code, $pay_month);
			$empLeave = GetEmployeeLWPDataByEmpCodeAndMonth($emp_code, $pay_month);

			// Generate HTML for payslip
			$totalEarnings = 0;
			$totalDeductions = 0;
			$html = '<style>
                @page{margin:20px 20px;font-family:Arial;font-size:14px;}
                .div_half{float:left;margin:0 0 30px 0;width:50%;}
                .logo{width:250px;padding:0;}
                .com_title{text-align:center;font-size:16px;margin:0;}
                .reg_no{text-align:center;font-size:12px;margin:5px 0;}
                .subject{text-align:center;font-size:20px;font-weight:bold;}
                .emp_info{width:100%;margin:0 0 30px 0;}
                .table{border:1px solid #ccc;margin:0 0 30px 0;}
                .salary_info{width:100%;margin:0;}
                .salary_info th,.salary_info td{border:1px solid #ccc;margin:0;padding:5px;vertical-align:middle;}
                .net_payable{margin:0;color:#050;}
                .in_word{text-align:right;font-size:12px;margin:5px 0;}
                .signature{margin:0 0 30px 0;}
                .signature strong{font-size:12px;padding:5px 0 0 0;border-top:1px solid #000;}
                .com_info{font-size:12px;text-align:center;margin:0 0 30px 0;}
                .noseal{text-align:center;font-size:11px;}
            </style>';
			$html .= '<div class="div_half">';
			$html .= '<img class="logo" src="' . BASE_URL . 'dist/img/logo.png" alt="Smith Brother\'s Corporation Limited" />';
			$html .= '</div>';
			$html .= '<div class="div_half">';
			$html .= '<h2 class="com_title">Smith Brother\'s Corporation Limited</h2>';
			$html .= '<p class="reg_no"></p>';
			$html .= '</div>';

			$html .= '<p class="subject">Salary Slip for ' . $pay_month . '</p>';

			$html .= '<table class="emp_info">';
			$html .= '<tr>';
			$html .= '<td width="25%">Employee Code</td>';
			$html .= '<td width="25%">: ' . strtoupper($emp_code) . '</td>';
			$html .= '<td width="25%">Bank Name</td>';
			$html .= '<td width="25%">: ' . ucwords($empData['bank_name']) . '</td>';
			$html .= '</tr>';

			$html .= '<tr>';
			$html .= '<td>Employee Name</td>';
			$html .= '<td>: ' . ucwords($empData['first_name'] . ' ' . $empData['last_name']) . '</td>';
			$html .= '<td>Bank Account</td>';
			$html .= '<td>: ' . $empData['account_no'] . '</td>';
			$html .= '</tr>';

			$html .= '<tr>';
			$html .= '<td>Designation</td>';
			$html .= '<td>: ' . ucwords($empData['designation']) . '</td>';
			$html .= '<td>IFSC Code</td>';
			$html .= '<td>: ' . strtoupper($empData['ifsc_code']) . '</td>';
			$html .= '</tr>';

			$html .= '<tr>';
			$html .= '<td>Gender</td>';
			$html .= '<td>: ' . ucwords($empData['gender']) . '</td>';
			$html .= '<td>PAN</td>';
			$html .= '<td>: ' . strtoupper($empData['pan_no']) . '</td>';
			$html .= '</tr>';

			$html .= '<tr>';
			$html .= '<td>Location</td>';
			$html .= '<td>: ' . ucwords($empData['city']) . '</td>';
			$html .= '<td>PF Account</td>';
			$html .= '<td>: ' . strtoupper($empData['pf_account']) . '</td>';
			$html .= '</tr>';

			$html .= '<tr>';
			$html .= '<td>Department</td>';
			$html .= '<td>: ' . ucwords($empData['department']) . '</td>';
			$html .= '<td>Payable/Working Days</td>';
			$html .= '<td>: ' . ($empLeave['workingDays'] - $empLeave['withoutPay']) . '/' . $empLeave['workingDays'] . ' Days</td>';
			$html .= '</tr>';

			$html .= '<tr>';
			$html .= '<td>Date of Joining</td>';
			$html .= '<td>: ' . date('d-m-Y', strtotime($empData['joining_date'])) . '</td>';
			$html .= '<td>Payment Type</td>';
			$html .= '<td>: ' . ucfirst($empSalary['payment_type']) . '</td>';
			$html .= '</tr>';

			$html .= '</table>';

			$html .= '<table class="table salary_info">';
			$html .= '<thead>';
			$html .= '<tr>';
			$html .= '<th>Earnings Heads</th>';
			$html .= '<th>Amount</th>';
			$html .= '</tr>';
			$html .= '</thead>';
			$html .= '<tbody>';
			foreach ($earnings_heads as $i => $head) {
				$html .= '<tr>';
				$html .= '<td>' . $head . '</td>';
				$html .= '<td>' . number_format($earnings_amounts[$i], 2, '.', '') . '</td>';
				$html .= '</tr>';
				$totalEarnings += $earnings_amounts[$i];
			}
			$html .= '</tbody>';
			$html .= '</table>';

			$html .= '<table class="table salary_info">';
			$html .= '<thead>';
			$html .= '<tr>';
			$html .= '<th>Deductions Heads</th>';
			$html .= '<th>Amount</th>';
			$html .= '</tr>';
			$html .= '</thead>';
			$html .= '<tbody>';
			foreach ($deductions_heads as $i => $head) {
				$html .= '<tr>';
				$html .= '<td>' . $head . '</td>';
				$html .= '<td>' . number_format($deductions_amounts[$i], 2, '.', '') . '</td>';
				$html .= '</tr>';
				$totalDeductions += $deductions_amounts[$i];
			}
			$html .= '</tbody>';
			$html .= '</table>';

			$html .= '<div class="net_payable">Net Payable: ' . number_format($totalEarnings - $totalDeductions, 2, '.', '') . '</div>';
			$html .= '<div class="in_word">In words: ' . convertNumberToWords($totalEarnings - $totalDeductions) . '</div>';
			$html .= '<div class="signature">';
			$html .= '<strong>Signature</strong>';
			$html .= '</div>';
			$html .= '<div class="com_info">';
			$html .= 'Address: 5 Cambridge, Cubao, Quezon City, 1109 Metro Manila';
			$html .= '</div>';
			$html .= '<div class="noseal">This is a system generated document and does not require a seal.</div>';

			// Create directories if they do not exist
			$dirPath = '../payslips/' . $emp_code . '/' . date('F-Y', strtotime($pay_month));
			if (!is_dir($dirPath)) {
				mkdir($dirPath, 0777, true);
			}

			$pdfFilePath = $dirPath . '/' . date('F-Y', strtotime($pay_month)) . '.pdf';
			$mpdf->WriteHTML($html);
			$mpdf->Output($pdfFilePath, \Mpdf\Output\Destination::FILE);

			$result['success'] = true;
			$result['message'] = 'Pay slip generated and saved successfully.';

		} catch (Exception $e) {
			$result['success'] = false;
			$result['message'] = 'An error occurred: ' . $e->getMessage();
		}
	} else {
		$result['success'] = false;
		$result['message'] = 'Invalid employee code or pay month.';
	}

	var_dump($result);
	return json_encode($result);
}

function SendPaySlipByMail()
{
	$result = array();
	global $db;

	try {
		$emp_code = $_POST['emp_code'];
		$month = $_POST['month'];
		$empData = GetEmployeeDataByEmpCode($emp_code);
		if ($empData) {
			$empName = $empData['first_name'] . ' ' . $empData['last_name'];
			$empEmail = $empData['email'];
			$subject = 'PaySlip for ' . $month;
			$message = '<p>Hi ' . $empData['first_name'] . '</p>';
			$message .= '<p>Here is your attached Salary Slip for the period of ' . $month . '.</p>';
			$message .= '<hr/>';
			$message .= '<p>Thank You,<br/>Smith Brother\'s Corporation Limited</p>';

			$attachmentPath = dirname(dirname(__FILE__)) . '/payslips/' . $emp_code . '/' . str_replace(', ', '-', $month) . '/' . str_replace(', ', '-', $month) . '.pdf';
			$attachment[0]['src'] = $attachmentPath;
			$attachment[0]['name'] = str_replace(', ', '-', $month);

			$sendResult = Send_Mail($subject, $message, $empName, $empEmail, FALSE, FALSE, FALSE, FALSE, $attachment);

			if ($sendResult === 0) {
				$result['code'] = 0;
				$result['result'] = 'PaySlip for ' . $month . ' has been successfully sent to ' . $empName;
				$result['attachment_path'] = $attachmentPath; // Include the attachment path in the result
			} else {
				$result['code'] = 1;
				$result['result'] = 'PaySlip was not sent. Error: ' . $sendResult; // Display the error message
			}
		} else {
			$result['code'] = 2;
			$result['result'] = 'Employee data not found for code: ' . $emp_code;
		}
	} catch (Exception $e) {
		$result['code'] = 3;
		$result['result'] = 'An error occurred: ' . $e->getMessage();
	}

	echo json_encode($result);
}

function EditProfileByID()
{
	$result = array();
	global $db;

	if ($_SESSION['Login_Type'] == 'admin') {
		$admin_id = $_SESSION['Admin_ID'];
		$admin_name = addslashes($_POST['admin_name']);
		$admin_email = addslashes($_POST['admin_email']);
		if (!empty($admin_name) && !empty($admin_email)) {
			$editSQL = mysqli_query($db, "UPDATE `" . DB_PREFIX . "admin` SET `admin_name` = '$admin_name', `admin_email` = '$admin_email' WHERE `admin_id` = $admin_id");
			if ($editSQL) {
				$result['code'] = 0;
				$result['result'] = 'Profile data has been successfully updated.';
			} else {
				$result['code'] = 1;
				$result['result'] = 'Something went wrong, please try again.';
			}
		} else {
			$result['code'] = 2;
			$result['result'] = 'All fields are mandatory.';
		}
	} else {
		$emp_id = stripslashes($_SESSION['Admin_ID']);
		$first_name = stripslashes($_POST['first_name']);
		$last_name = stripslashes($_POST['last_name']);
		$dob = stripslashes($_POST['dob']);
		$gender = stripslashes($_POST['gender']);
		$merital_status = stripslashes($_POST['merital_status']);
		$nationality = stripslashes($_POST['nationality']);
		$address = stripslashes($_POST['address']);
		$city = stripslashes($_POST['city']);
		$state = stripslashes($_POST['state']);
		$country = stripslashes($_POST['country']);
		$email = stripslashes($_POST['email']);
		$mobile = stripslashes($_POST['mobile']);
		$telephone = stripslashes($_POST['telephone']);
		$identity_doc = stripslashes($_POST['identity_doc']);
		$identity_no = stripslashes($_POST['identity_no']);
		$emp_type = stripslashes($_POST['emp_type']);
		$joining_date = stripslashes($_POST['joining_date']);
		$blood_group = stripslashes($_POST['blood_group']);
		$designation = stripslashes($_POST['designation']);
		$department = stripslashes($_POST['department']);
		$pan_no = stripslashes($_POST['pan_no']);
		$bank_name = stripslashes($_POST['bank_name']);
		$account_no = stripslashes($_POST['account_no']);
		$ifsc_code = stripslashes($_POST['ifsc_code']);
		$pf_account = stripslashes($_POST['pf_account']);
		if (!empty($first_name) && !empty($last_name) && !empty($dob) && !empty($gender) && !empty($merital_status) && !empty($nationality) && !empty($address) && !empty($city) && !empty($state) && !empty($country) && !empty($email) && !empty($mobile) && !empty($identity_doc) && !empty($identity_no) && !empty($emp_type) && !empty($joining_date) && !empty($blood_group) && !empty($designation) && !empty($department) && !empty($pan_no) && !empty($bank_name) && !empty($account_no) && !empty($ifsc_code) && !empty($pf_account)) {
			$updateEmp = mysqli_query($db, "UPDATE `" . DB_PREFIX . "employees` SET `first_name` = '$first_name', `last_name` = '$last_name', `dob` = '$dob', `gender` = '$gender', `merital_status` = '$merital_status', `nationality` = '$nationality', `address` = '$address', `city` = '$city', `state` = '$state', `country` = '$country', `email` = '$email', `mobile` = '$mobile', `telephone` = '$telephone', `identity_doc` = '$identity_doc', `identity_no` = '$identity_no', `emp_type` = '$emp_type', `joining_date` = '$joining_date', `blood_group` = '$blood_group', `designation` = '$designation', `department` = '$department', `pan_no` = '$pan_no', `bank_name` = '$bank_name', `account_no` = '$account_no', `ifsc_code` = '$ifsc_code', `pf_account` = '$pf_account' WHERE `emp_id` = $emp_id");
			if ($updateEmp) {
				$result['result'] = 'Profile data has been successfully updated.';
				$result['code'] = 0;
			} else {
				$result['result'] = 'Something went wrong, please try again.';
				$result['code'] = 1;
			}
		} else {
			$result['result'] = 'All fields are mandatory except Telephone.';
			$result['code'] = 2;
		}
	}

	echo json_encode($result);
}

function EditLoginDataByID()
{
	$result = array();
	global $db;

	if ($_SESSION['Login_Type'] == 'admin') {
		$admin_id = $_SESSION['Admin_ID'];
		$admin_code = addslashes($_POST['admin_code']);
		$admin_password = addslashes($_POST['admin_password']);
		$admin_password_conf = addslashes($_POST['admin_password_conf']);
		if (!empty($admin_code) && !empty($admin_password) && !empty($admin_password_conf)) {
			if ($admin_password == $admin_password_conf) {
				$editSQL = mysqli_query($db, "UPDATE `" . DB_PREFIX . "admin` SET `admin_code` = '$admin_code', `admin_password` = '" . sha1($admin_password) . "' WHERE `admin_id` = $admin_id");
				if ($editSQL) {
					$result['code'] = 0;
					$result['result'] = 'Login data has been successfully updated.';
				} else {
					$result['code'] = 1;
					$result['result'] = 'Something went wrong, please try again.';
				}
			} else {
				$result['code'] = 2;
				$result['result'] = 'Confirm password does not match.';
			}
		} else {
			$result['code'] = 3;
			$result['result'] = 'All fields are mandatory.';
		}
	} else {
		$emp_id = $_SESSION['Admin_ID'];
		$old_password = addslashes($_POST['old_password']);
		$new_password = addslashes($_POST['new_password']);
		$password_conf = addslashes($_POST['password_conf']);
		if (!empty($old_password) && !empty($new_password) && !empty($password_conf)) {
			$checkPassSQL = mysqli_query($db, "SELECT * FROM `" . DB_PREFIX . "employees` WHERE `emp_id` = $emp_id");
			if ($checkPassSQL) {
				if (mysqli_num_rows($checkPassSQL) == 1) {
					$passData = mysqli_fetch_assoc($checkPassSQL);
					if (sha1($old_password) == $passData['emp_password']) {
						if ($new_password == $password_conf) {
							$editSQL = mysqli_query($db, "UPDATE `" . DB_PREFIX . "employees` SET `emp_password` = '" . sha1($new_password) . "' WHERE `emp_id` = $emp_id");
							if ($editSQL) {
								$result['code'] = 0;
								$result['result'] = 'Password has been successfully updated.';
							} else {
								$result['code'] = 1;
								$result['result'] = 'Something went wrong, please try again.';
							}
						} else {
							$result['code'] = 2;
							$result['result'] = 'Confirm password does not match.';
						}
					} else {
						$result['code'] = 3;
						$result['result'] = 'Entered wrong existing password.';
					}
				} else {
					$result['code'] = 4;
					$result['result'] = 'No such employee found.';
				}
			} else {
				$result['code'] = 5;
				$result['result'] = 'Something went wrong, please try again.';
			}
		} else {
			$result['code'] = 6;
			$result['result'] = 'All fields are mandatory.';
		}
	}

	echo json_encode($result);
}

function LoadingAllLeaves()
{
	global $db;
	$empData = GetDataByIDAndType($_SESSION['Admin_ID'], $_SESSION['Login_Type']);
	$requestData = $_REQUEST;
	$columns = array(
		0 => 'leave_id',
		1 => 'emp_code',
		2 => 'leave_subject',
		3 => 'leave_dates',
		4 => 'leave_message',
		5 => 'leave_type',
		6 => 'leave_status'
	);

	$sql = "SELECT `leave_id` ";
	$sql .= " FROM `" . DB_PREFIX . "leaves`";
	$query = mysqli_query($db, $sql);
	$totalData = mysqli_num_rows($query);
	$totalFiltered = $totalData;

	$sql = "SELECT *";
	$sql .= " FROM `" . DB_PREFIX . "leaves` WHERE 1=1";
	if (!empty($requestData['search']['value'])) {
		$sql .= " AND (`leave_id` LIKE '" . $requestData['search']['value'] . "%'";
		$sql .= " OR `emp_code` LIKE '" . $requestData['search']['value'] . "%'";
		$sql .= " OR `leave_subject` LIKE '" . $requestData['search']['value'] . "%'";
		$sql .= " OR `leave_dates` LIKE '" . $requestData['search']['value'] . "%'";
		$sql .= " OR `leave_message` LIKE '" . $requestData['search']['value'] . "%'";
		$sql .= " OR `leave_type` LIKE '" . $requestData['search']['value'] . "%'";
		$sql .= " OR `leave_status` LIKE '" . $requestData['search']['value'] . "%')";
	}
	$query = mysqli_query($db, $sql);
	$totalFiltered = mysqli_num_rows($query);
	$sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . " " . $requestData['order'][0]['dir'] . " LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "";
	$query = mysqli_query($db, $sql);

	$data = array();
	$i = 1 + $requestData['start'];
	while ($row = mysqli_fetch_assoc($query)) {
		$nestedData = array();
		$nestedData[] = $row["leave_id"];
		$nestedData[] = '<a target="_blank" href="' . REG_URL . 'reports/' . $row["emp_code"] . '/">' . $row["emp_code"] . '</a>';
		$nestedData[] = $row["leave_subject"];
		$nestedData[] = $row["leave_dates"];
		$nestedData[] = $row["leave_message"];
		$nestedData[] = $row["leave_type"];
		if ($row["leave_status"] == 'pending') {
			$nestedData[] = '<span class="label label-warning">' . ucwords($row["leave_status"]) . '</span>';
		} elseif ($row['leave_status'] == 'approve') {
			$nestedData[] = '<span class="label label-success">' . ucwords($row["leave_status"]) . 'd</span>';
		} elseif ($row['leave_status'] == 'reject') {
			$nestedData[] = '<span class="label label-danger">' . ucwords($row["leave_status"]) . 'ed</span>';
		}
		$data[] = $nestedData;
		$i++;
	}
	$json_data = array(
		"draw" => intval($requestData['draw']),
		"recordsTotal" => intval($totalData),
		"recordsFiltered" => intval($totalFiltered),
		"data" => $data
	);

	echo json_encode($json_data);
}

function LoadingMyLeaves()
{
	global $db;
	$empData = GetDataByIDAndType($_SESSION['Admin_ID'], $_SESSION['Login_Type']);
	$requestData = $_REQUEST;
	$columns = array(
		0 => 'leave_id',
		1 => 'leave_subject',
		2 => 'leave_dates',
		3 => 'leave_message',
		4 => 'leave_type',
		5 => 'leave_status'
	);

	$sql = "SELECT `leave_id` ";
	$sql .= " FROM `" . DB_PREFIX . "leaves` WHERE `emp_code` = '" . $empData['emp_code'] . "'";
	$query = mysqli_query($db, $sql);
	$totalData = mysqli_num_rows($query);
	$totalFiltered = $totalData;

	$sql = "SELECT *";
	$sql .= " FROM `" . DB_PREFIX . "leaves` WHERE `emp_code` = '" . $empData['emp_code'] . "'";
	if (!empty($requestData['search']['value'])) {
		$sql .= " AND (`leave_id` LIKE '" . $requestData['search']['value'] . "%'";
		$sql .= " OR `leave_subject` LIKE '" . $requestData['search']['value'] . "%'";
		$sql .= " OR `leave_dates` LIKE '" . $requestData['search']['value'] . "%'";
		$sql .= " OR `leave_message` LIKE '" . $requestData['search']['value'] . "%'";
		$sql .= " OR `leave_type` LIKE '" . $requestData['search']['value'] . "%'";
		$sql .= " OR `leave_status` LIKE '" . $requestData['search']['value'] . "%')";
	}
	$query = mysqli_query($db, $sql);
	$totalFiltered = mysqli_num_rows($query);
	$sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . " " . $requestData['order'][0]['dir'] . " LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "";
	$query = mysqli_query($db, $sql);

	$data = array();
	$i = 1 + $requestData['start'];
	while ($row = mysqli_fetch_assoc($query)) {
		$nestedData = array();
		$nestedData[] = $row["leave_id"];
		$nestedData[] = $row["leave_subject"];
		$nestedData[] = $row["leave_dates"];
		$nestedData[] = $row["leave_message"];
		$nestedData[] = $row["leave_type"];
		if ($row["leave_status"] == 'pending') {
			$nestedData[] = '<span class="label label-warning">' . ucwords($row["leave_status"]) . '</span>';
		} elseif ($row['leave_status'] == 'approve') {
			$nestedData[] = '<span class="label label-success">' . ucwords($row["leave_status"]) . 'd</span>';
		} elseif ($row['leave_status'] == 'reject') {
			$nestedData[] = '<span class="label label-danger">' . ucwords($row["leave_status"]) . 'ed</span>';
		}
		$data[] = $nestedData;
		$i++;
	}
	$json_data = array(
		"draw" => intval($requestData['draw']),
		"recordsTotal" => intval($totalData),
		"recordsFiltered" => intval($totalFiltered),
		"data" => $data
	);

	echo json_encode($json_data);
}

function ApplyLeaveToAdminApproval()
{
	$result = array();
	global $db;

	$adminData = GetAdminData(1);
	$empData = GetDataByIDAndType($_SESSION['Admin_ID'], $_SESSION['Login_Type']);

	$leave_subject = addslashes($_POST['leave_subject']);
	$leave_dates = addslashes($_POST['leave_dates']);
	$leave_message = addslashes($_POST['leave_message']);
	$leave_type = addslashes($_POST['leave_type']);
	if (!empty($leave_subject) && !empty($leave_dates) && !empty($leave_message) && !empty($leave_type)) {
		$AppliedDates = '';
		if (strpos($leave_dates, ',') !== false) {
			$dates = explode(',', $leave_dates);
			foreach ($dates as $date) {
				$checkLeaveSQL = mysqli_query($db, "SELECT * FROM `" . DB_PREFIX . "leaves` WHERE `leave_dates` LIKE '%$date%' AND `emp_code` = '" . $empData['emp_code'] . "'");
				if ($checkLeaveSQL) {
					if (mysqli_num_rows($checkLeaveSQL) > 0) {
						$AppliedDates .= $date . ', ';
					}
				}
			}
		}
		if (empty($AppliedDates)) {
			$leaveSQL = mysqli_query($db, "INSERT INTO `" . DB_PREFIX . "leaves` (`emp_code`, `leave_subject`, `leave_dates`, `leave_message`, `leave_type`, `apply_date`) VALUES('" . $empData['emp_code'] . "', '$leave_subject', '$leave_dates', '$leave_message', '$leave_type', '" . date('Y-m-d H:i:s') . "')");
			// if ($leaveSQL) {
			// 	$empName = $empData['first_name'] . ' ' . $empData['last_name'];
			// 	$empEmail = $empData['email'];
			// 	$adminEmail = $adminData['admin_email'];
			// 	$subject = 'Leave Application: ' . $leave_subject;
			// 	$message = '<p>Employee: ' . $empName . ' (' . $empData['emp_code'] . ')' . '</p>';
			// 	$message .= '<p>Leave Message: ' . $leave_message . '</p>';
			// 	$message .= '<p>Leave Date(s): ' . $leave_dates . '</p>';
			// 	$message .= '<p>Leave Type: ' . $leave_type . '</p>';
			// 	$message .= '<hr/>';
			// 	$message .= '<p>Please click on the buttons below or log into the admin area to get an action:</p>';
			// 	$message .= '<form method="post" action="' . BASE_URL . 'ajax/?case=ApproveLeaveApplication&id=' . mysqli_insert_id() . '" style="display:inline;">';
			// 	$message .= '<input type="hidden" name="id" value="' . mysqli_insert_id() . '" />';
			// 	$message .= '<button type="submit" style="background:green; border:1px solid green; color:white; padding:0 5px 3px; cursor:pointer; margin-right:15px;">Approve</button>';
			// 	$message .= '</form>';
			// 	$message .= '<form method="post" action="' . BASE_URL . 'ajax/?case=RejectLeaveApplication&id=' . mysqli_insert_id() . '" style="display:inline;">';
			// 	$message .= '<input type="hidden" name="id" value="' . mysqli_insert_id() . '" />';
			// 	$message .= '<button type="submit" style="background:red; border:1px solid red; color:white; padding:0 5px 3px; cursor:pointer;">Reject</button>';
			// 	$message .= '</form>';
			// 	$message .= '<p style="font-size:85%;">After clicking the button, please click on OK and then Continue to make your action complete.</p>';
			// 	$message .= '<hr/>';
			// 	$message .= '<p>Thank You<br/>' . $empName . '</p>';
			// 	$adminName = $adminData['admin_name'];
			// 	$send = Send_Mail($subject, $message, $adminName, $adminEmail, $empName, $empEmail);
			// 	if ($send == 0) {
			// 		$result['code'] = 0;
			// 		$result['result'] = 'Leave Application has been successfully send to your employer through mail.';
			// 	} else {
			// 		$result['code'] = 1;
			// 		$result['result'] = 'Notice: Leave Application not send through E-Mail, please try again.';
			// 	}


			// } else {
			// 	$result['code'] = 1;
			// 	$result['result'] = 'Something went wrong, please try again.';
			// }

			$result['code'] = 0;
			$result['result'] = 'Leave application was succesfully submitted.';
		} else {
			$alreadyDates = substr($AppliedDates, 0, -2);
			$result['code'] = 2;
			$result['result'] = 'You have already applied for leave on ' . $alreadyDates . '. Please change the leave dates.';
		}
	} else {
		$result['code'] = 3;
		$result['result'] = 'All fields are mandatory.';
	}

	echo json_encode($result);
}

function ApproveLeaveApplication()
{
	$result = array();
	global $db;

	$leaveId = $_REQUEST['id'];
	$leaveSQL = mysqli_query($db, "SELECT * FROM `" . DB_PREFIX . "leaves` WHERE `leave_id` = $leaveId AND `leave_status` = 'pending' LIMIT 0, 1");

	$update = mysqli_query($db, "UPDATE `" . DB_PREFIX . "leaves` SET `leave_status` = 'approve' WHERE `leave_id` = $leaveId");
	$result['code'] = 0;
	$result['result'] = 'Leave Application is successfully approved.';


	echo json_encode($result);
}

function RejectLeaveApplication()
{
	$result = array();
	global $db;

	$leaveId = $_REQUEST['id'];
	$leaveSQL = mysqli_query($db, "SELECT * FROM `" . DB_PREFIX . "leaves` WHERE `leave_id` = $leaveId AND `leave_status` = 'pending' LIMIT 0, 1");

	$update = mysqli_query($db, "UPDATE `" . DB_PREFIX . "leaves` SET `leave_status` = 'reject' WHERE `leave_id` = $leaveId");
	$result['code'] = 0;
	$result['result'] = 'Leave Application is rejected. ';

	echo json_encode($result);
}