<?php
require_once(dirname(__FILE__) . '/config.php');

if (!isset($_SESSION['Admin_ID']) || !isset($_SESSION['Login_Type'])) {
    header('location:' . BASE_URL);
    exit;
}

function ApplyLeaveToAdminApproval()
{
    global $db;
    $result = array();

    $adminData = GetAdminData(1);
    $empData = GetDataByIDAndType($_SESSION['Admin_ID'], $_SESSION['Login_Type']);

    $leave_subject = addslashes($_POST['leave_subject']);
    $leave_date_start = date('Y-m-d', strtotime(addslashes($_POST['leave_date_start'])));
    $leave_date_end = date('Y-m-d', strtotime(addslashes($_POST['leave_date_end'])));
    $leave_message = addslashes($_POST['leave_message']);
    $leave_type = addslashes($_POST['leave_type']);

    $leave_attachment = NULL; // Default value

    // Handle file upload
    if (isset($_FILES['leave_attachment']) && $_FILES['leave_attachment']['error'] == 0) {
        $uploadDir = 'uploads/leave_attachments/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileTmpPath = $_FILES['leave_attachment']['tmp_name'];
        $fileName = time() . '_' . basename($_FILES['leave_attachment']['name']);
        $destination = $uploadDir . $fileName;

        if (move_uploaded_file($fileTmpPath, $destination)) {
            $leave_attachment = $destination;
        }
    }

    if (!empty($leave_subject) && !empty($leave_date_start) && !empty($leave_date_end) && !empty($leave_message) && !empty($leave_type)) {
        if (strtotime($leave_date_end) < strtotime($leave_date_start)) {
            $result['code'] = 4;
            $result['result'] = 'End date cannot be before start date.';
        } else {
            $checkLeaveSQL = mysqli_query($db, "SELECT * FROM `" . DB_PREFIX . "leaves` WHERE `emp_code` = '" . $empData['emp_code'] . "' AND (
                (`leave_date_start` <= '$leave_date_end' AND `leave_date_end` >= '$leave_date_start')
            )");

            if (mysqli_num_rows($checkLeaveSQL) > 0) {
                $result['code'] = 2;
                $result['result'] = 'You have already applied for leave within this date range.';
            } else {
                $leaveSQL = mysqli_query($db, "INSERT INTO `" . DB_PREFIX . "leaves` 
                    (`emp_code`, `leave_subject`, `leave_date_start`, `leave_date_end`, `leave_message`, `leave_type`, `leave_attachment`, `apply_date`) 
                    VALUES 
                    ('" . $empData['emp_code'] . "', '$leave_subject', '$leave_date_start', '$leave_date_end', '$leave_message', '$leave_type', '$leave_attachment', '" . date('Y-m-d H:i:s') . "')");

                if ($leaveSQL) {
                    $result['code'] = 0;
                    $result['result'] = 'Leave application submitted successfully.';
                } else {
                    $result['code'] = 1;
                    $result['result'] = 'Something went wrong, please try again.';
                }
            }
        }
    } else {
        $result['code'] = 3;
        $result['result'] = 'All fields are mandatory.';
    }

    ob_clean(); // Clear previous output
	header('Content-Type: application/json');
	echo json_encode($result);
	exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    ApplyLeaveToAdminApproval();
}
?>

<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

	<title>Leaves - Payroll</title>

	<link rel="stylesheet" href="<?php echo BASE_URL; ?>bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="<?php echo BASE_URL; ?>plugins/datatables/dataTables.bootstrap.css">
	<link rel="stylesheet" href="<?php echo BASE_URL; ?>plugins/datatables/jquery.dataTables_themeroller.css">
	<link rel="stylesheet" href="<?php echo BASE_URL; ?>dist/css/AdminLTE.css">
	<link rel="stylesheet" href="<?php echo BASE_URL; ?>plugins/datepicker/datepicker3.css">
	<link rel="stylesheet" href="<?php echo BASE_URL; ?>dist/css/skins/_all-skins.min.css">

	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>

<body class="hold-transition skin-blue sidebar-mini">
	<div class="wrapper">

		<?php require_once (dirname(__FILE__) . '/partials/topnav.php'); ?>

		<?php require_once (dirname(__FILE__) . '/partials/sidenav.php'); ?>

		<div class="content-wrapper">
			<section class="content-header">
				<h1>Leaves</h1>
				<ol class="breadcrumb">
					<li><a href="<?php echo BASE_URL; ?>"><i class="fa fa-dashboard"></i> Home</a></li>
					<li class="active">Leaves</li>
				</ol>
			</section>

			<section class="content">
				<div class="row">
					<?php if ($_SESSION['Login_Type'] == 'admin') { ?>
						<div class="col-xs-12">
							<div class="box">
								<div class="box-header">
									<h3 class="box-title">All Leaves</h3>
								</div>
								<div class="box-body">
									<table id="allleaves" class="table table-bordered table-stripe">
										<thead>
											<tr>
												<th>#</th>
												<th>NAME</th>
												<th>SUBJECT</th>
												<th>DATES</th>
												<th>MESSAGE</th>
												<th>TYPE</th>
												<th>ATTACHMENT</th>
												<th>STATUS</th>
												<th>ACTIONS</th>
											</tr>
										</thead>
									</table>
								</div>
							</div>
						</div>
					<?php } else { ?>
						<div class="col-lg-4">
							<div class="box">
								<div class="box-header">
									<h3 class="box-title">Apply for Leave</h3>
								</div>
								<div class="box-body">
								<form method="post" role="form" data-toggle="validator" id="leave-form" enctype="multipart/form-data">
									<div class="form-group">
										<label for="leave_subject">Leave Subject</label>
										<input type="text" class="form-control" name="leave_subject" id="leave_subject" required />
									</div>
									<div class="form-group">
										<label for="leave_date_start">Leave Date Start (MM/DD/YYYY)</label>
										<input type="text" class="form-control datepicker" name="leave_date_start" id="leave_date_start" required />
									</div>
									<div class="form-group">
										<label for="leave_date_end">Leave Date End (MM/DD/YYYY)</label>
										<input type="text" class="form-control datepicker" name="leave_date_end" id="leave_date_end" required />
									</div>
									<div class="form-group">
										<label for="leave_message">Leave Message</label>
										<textarea class="form-control" name="leave_message" id="leave_message" rows="10" required></textarea>
									</div>
									<div class="form-group">
										<label for="leave_type">Leave Type</label>
										<select class="form-control" name="leave_type" id="leave_type" required>
											<option value="">Please make a choice</option>
											<option value="Casual Leave">Casual Leave</option>
											<option value="Earned Leave">Privileged / Earned Leave</option>
											<option value="Sick Leave">Medical / Sick Leave</option>
											<option value="Maternity Leave">Maternity Leave</option>
											<option value="Leave Without Pay">Leave Without Pay</option>
										</select>
									</div>
									<div class="form-group">
										<label for="leave_attachment">Attachment (Optional)</label>
										<input type="file" class="form-control" name="leave_attachment" id="leave_attachment" accept=".pdf,.doc,.docx,.jpg,.png">
									</div>
									<div class="form-group">
										<button type="submit" class="btn btn-primary">Apply for Leave</button>
									</div>
								</form>

								</div>
							</div>
						</div>
						<div class="col-lg-8">
							<div class="box">
								<div class="box-header">
									<h3 class="box-title">My Leaves</h3>
								</div>
								<div class="box-body">
									<table id="myleaves" class="table table-bordered table-stripe">
										<thead>
											<tr>
												<th>#</th>
												<th>SUBJECT</th>
												<th>DATES</th>
												<th>MESSAGE</th>
												<th>TYPE</th>
												<th>ATTACHMENT</th>
												<th>STATUS</th>
											</tr>
										</thead>
									</table>
								</div>
							</div>
						</div>
					<?php } ?>
				</div>
			</section>
		</div>

		<footer class="main-footer">
			<strong> &copy; <?php echo date("Y"); ?> Employee Management System </strong>
		</footer>
	</div>

	<script src="<?php echo BASE_URL; ?>plugins/jQuery/jquery-2.2.3.min.js"></script>
	<script src="<?php echo BASE_URL; ?>bootstrap/js/bootstrap.min.js"></script>
	<script src="<?php echo BASE_URL; ?>plugins/datatables/jquery.dataTables.min.js"></script>
	<script src="<?php echo BASE_URL; ?>plugins/datatables/dataTables.bootstrap.min.js"></script>
	<script src="<?php echo BASE_URL; ?>plugins/jquery-validator/validator.min.js"></script>
	<script src="<?php echo BASE_URL; ?>plugins/bootstrap-notify/bootstrap-notify.min.js"></script>
	<script src="<?php echo BASE_URL; ?>plugins/datepicker/bootstrap-datepicker.js"></script>
	<script src="<?php echo BASE_URL; ?>dist/js/app.min.js"></script>
	<script type="text/javascript">var baseurl = '<?php echo BASE_URL; ?>';</script>
	<script src="<?php echo BASE_URL; ?>dist/js/script.js?rand=<?php echo rand(); ?>"></script>

	<script>
		$(document).ready(function () {
			$("#leave-form").submit(function (e) {
				e.preventDefault(); // Prevent default form submission

				var formData = new FormData(this); // Collect form data

				$.ajax({
					url: "", // Submit to the same file
					type: "POST",
					data: formData,
					dataType: "json",
					contentType: false,
					processData: false,
					success: function (response) {
						var result = response;

						var notifyType = (result.code === 0) ? 'success' : 'danger';
						var notifyMessage = result.result;

						$.notify({
							message: notifyMessage
						}, {
							type: notifyType,
							placement: {
								from: "top",
								align: "right"
							},
							delay: 3000,
							timer: 1000
						});

						if (result.code === 0) {
							location.reload();
						}
					},
					error: function () {
						$.notify({
							message: "An error occurred. Please try again."
						}, {
							type: "danger",
							placement: {
								from: "top",
								align: "right"
							}
						});
					}
				});
			});
		});
	</script>

</body>

</html>