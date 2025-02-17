<?php require_once(dirname(__FILE__) . '/config.php');
if (!isset($_SESSION['Admin_ID'])) {
	header('location:' . BASE_URL);
} ?>

<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

	<title>Attendance - Payroll</title>
	<style>
		@media print {
			@page {
				size: A2 landscape;
				margin: 0;
				/* Remove margins */
				transform: scale(1.35);
				/* Scale the content */
				transform-origin: 0 0;
				/* Adjust the origin for scaling */
			}

		}
	</style>


	<link rel="stylesheet" href="<?php echo BASE_URL; ?>bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="<?php echo BASE_URL; ?>plugins/datatables/dataTables.bootstrap.css">
	<link rel="stylesheet" href="<?php echo BASE_URL; ?>plugins/datatables/jquery.dataTables_themeroller.css">
	<link rel="stylesheet" href="<?php echo BASE_URL; ?>dist/css/AdminLTE.css">
	<link rel="stylesheet" href="<?php echo BASE_URL; ?>dist/css/skins/_all-skins.min.css">

	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>

<body class="hold-transition skin-blue sidebar-mini">
	<div class="wrapper">

		<?php require_once(dirname(__FILE__) . '/partials/topnav.php'); ?>

		<?php require_once(dirname(__FILE__) . '/partials/sidenav.php'); ?>

		<div class="content-wrapper">
			<section class="content-header">
				<h1>
					Attendance
				</h1>
				<ol class="breadcrumb">
					<li><a href="<?php echo BASE_URL; ?>"><i class="fa fa-dashboard"></i> Home</a></li>
					<li class="active">Attendance</li>
				</ol>
			</section>
			<section class="content">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="box">
                            <div class="box-header">
                                <h3 class="box-title">Employee Attendance</h3>
                            </div>
							<?php if ($_SESSION['Login_Type'] == 'admin'): ?>
                            <div class="box-body">
                                <div class="table-responsive">
                                    <table id="employeeList" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
												<th>Photo</th>
												<th>Emp Code</th>
                                                <th>Name</th>
												<th>Designation</th>
                                                <th>Department</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
							<?php else: ?>
							<div class="box-body">
								<div class="table-responsive">
									<table id="attendanceTable" class="table table-bordered table-striped">
										<thead>
											<tr>
												<th>DATE</th>
												<th>PUNCH-IN</th>
												<th>PUNCH-OUT</th>
												<th>WORK HOURS</th>
												<th>TIME IN DESC</th>
												<th>TIME OUT DESC</th>
											</tr>
										</thead>
										<tbody>
											<!-- Attendance details will be inserted here dynamically -->
										</tbody>
									</table>
								</div>
							</div>
							<?php endif; ?>
                        </div>
                    </div>
                </div>
            </section>

			<div id="attendanceModal" class="modal fade" tabindex="-1" role="dialog">
				<div class="modal-dialog modal-lg" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
							<h4 class="modal-title">Attendance Record</h4>
						</div>
						<div class="modal-body">
							<table id="attendanceDetails" class="table table-bordered table-striped">
								<thead>
									<tr>
										<th>DATE</th>
										<th>PUNCH-IN</th>
										<th>PUNCH-OUT</th>
										<th>WORK HOURS</th>
										<th>TIME IN DESC</th>
										<th>TIME OUT DESC</th>
									</tr>
								</thead>
								<tbody>
									<!-- Attendance details will be inserted here dynamically -->
								</tbody>
							</table>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						</div>
					</div>
				</div>
		</div>

		
	</div>
	<footer class="main-footer">
		<strong> &copy; <?php echo date("Y"); ?> Employee Management System </strong>
		</footer>
	</footer>
	<script src="<?php echo BASE_URL; ?>plugins/jQuery/jquery-2.2.3.min.js"></script>
	<script src="<?php echo BASE_URL; ?>bootstrap/js/bootstrap.min.js"></script>
	<script src="<?php echo BASE_URL; ?>plugins/datatables/jquery.dataTables.min.js"></script>
	<script src="<?php echo BASE_URL; ?>plugins/datatables/dataTables.bootstrap.min.js"></script>
	<script src="<?php echo BASE_URL; ?>plugins/bootstrap-notify/bootstrap-notify.min.js"></script>
	<script src="<?php echo BASE_URL; ?>dist/js/app.min.js"></script>
	<script type="text/javascript">var baseurl = '<?php echo BASE_URL; ?>';</script>



	<script>
		var baseurl = '<?php echo BASE_URL; ?>';
		var loginType = '<?php echo $_SESSION["Login_Type"] ?>';
		var emp_id = '<?php echo $_SESSION['Admin_ID']; ?>';
		$(document).ready(function () {
			console.log(loginType);
			console.log(emp_id);
			if (loginType === 'admin') {
			// Fetch all employees
			$.ajax({
				url: baseurl + 'ajax/?case=GetAllEmployees',
				type: 'GET',
				success: function (response) {
					console.log(response);  // Debugging line
					$('#employeeList tbody').empty();
					response.data.forEach(record => {
						const row = `<tr>
							<td>${record[6]}</td>
							<td>${record[0]}</td>
							<td>${record[1] + " " + record[2]}</td>
							<td>${record[3]}</td>
							<td>${record[4]}</td>

							<td>
								<button class="btn btn-primary viewAttendance" data-emp-code="${record[0]}">
									View Attendance
								</button>
							</td>
						</tr>`;
						$('#employeeList tbody').append(row);
					});
					$('#employeeList').DataTable();
				},
				error: function (xhr, status, error) {
					console.error('AJAX Error: ', status, error);  // Debugging line
				}
			});
		} else {
			// Fetch attendance records for the logged-in employee
			$.ajax({
				url: baseurl + 'ajax/?case=LoadingAttendanceByEmpId&emp_id=' + emp_id,
				type: 'GET',
				data: { emp_id: emp_id },
				success: function (response) {
					console.log(response);  // Debugging line
					$('#attendanceTable tbody').empty();
					if (response.data.length === 0) {
						$('#attendanceTable tbody').append('<tr><td colspan="6">No attendance records found.</td></tr>');
					} else {
						response.data.forEach(attendance => {
							const row = `<tr>
								<td>${attendance[0]}</td>
								<td>${attendance[3]}</td>
								<td>${attendance[4]}</td>
								<td>${attendance[5]}</td>
								<td>${attendance[6]}</td>
								<td>${attendance[7]}</td>
							</tr>`;
							$('#attendanceTable tbody').append(row);
						});
					}
					$('#attendanceTable').DataTable();
				},
				error: function (xhr, status, error) {
					console.error('AJAX Error: ', status, error);  // Debugging line
				}
			});
		}
    // Handle the "View Attendance" button click
    $(document).on('click', '.viewAttendance', function () {
        const empCode = $(this).data('emp-code');
        console.log('here')
        // AJAX call to fetch attendance records
        $.ajax({
            url: baseurl + 'ajax/?case=LoadingAttendanceByEmpCode&emp_code=' + empCode, // Your backend call to get attendance details
            type: 'GET',
            data: { emp_code: empCode },
            success: function (response) {
				console.log(empCode)
				console.log(response);  // Debugging line
                // Empty the previous attendance details
                $('#attendanceDetails tbody').empty();
				
                if (response.data.length === 0) {
                    $('#attendanceDetails tbody').append('<tr><td colspan="4">No attendance records found.</td></tr>');
                } else {
                    // Loop through attendance records and display them
                    response.data.forEach(attendance => {
                        const row = `<tr>
                            <td>${attendance[0]}</td>
                            <td>${attendance[3]}</td>
                            <td>${attendance[4]}</td>
                            <td>${attendance[5]}</td>
                            <td>${attendance[6]}</td>
                            <td>${attendance[7]}</td>
                        </tr>`;
                        $('#attendanceDetails tbody').append(row);
                    });
                }
                
                // Open the modal
                $('#attendanceModal').modal('show');
            },
            error: function (xhr, status, error) {
                console.error('AJAX Error: ', status, error);  // Debugging line
            }
        });
    });
});
	</script>
</body>

</html>