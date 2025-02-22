<?php require_once(dirname(__FILE__) . '/config.php');
if (!isset($_SESSION['Admin_ID']) || !isset($_SESSION['Login_Type'])) {
	header('location:' . BASE_URL);
} ?>

<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

	<title>Overtime - Payroll</title>

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

		<?php require_once(dirname(__FILE__) . '/partials/topnav.php'); ?>
		<?php require_once(dirname(__FILE__) . '/partials/sidenav.php'); ?>
		<?php if ($_SESSION['Login_Type'] == 'admin'){ ?>
		<div class="content-wrapper">
			<section class="content-header">
				<h1>Overtime</h1>
				<ol class="breadcrumb">
					<li><a href="<?php echo BASE_URL; ?>"><i class="fa fa-dashboard"></i> Home</a></li>
					<li class="active">Overtime</li>
				</ol>
			</section>

			<section class="content">
				<div class="row">
					<div class="col-xs-12">
						<div class="box">
							<div class="box-header">
								<h3 class="box-title">All Overtime Requests</h3>
							</div>
							<div class="box-body">
								<table id="allovertime" class="table table-bordered table-striped">
									<thead>
										<tr>
											<th>EMP CODE</th>
											<th>NAME</th>
											<th>DATE</th>
											<th>HOURS</th>
											<th>STATUS</th>
											<th>ACTIONS</th>
										</tr>
									</thead>
									<tbody>
										<!-- Overtime data will be dynamically added here -->
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</section>
		</div>
		<?php } else { ?>
			<div class="content-wrapper">
			<section class="content-header">
				<h1>My Overtime</h1>
				<ol class="breadcrumb">
					<li><a href="<?php echo BASE_URL; ?>"><i class="fa fa-dashboard"></i> Home</a></li>
					<li class="active">Overtime</li>
				</ol>
			</section>

			<section class="content">
				<div class="row">
					<div class="col-xs-12">
						<div class="box">
							<div class="box-header">
								<h3 class="box-title">All Overtime Requests</h3>
								<button class="btn btn-primary pull-right" id="applyOvertime" onclick="applyForOvertime('<?php echo $_SESSION['Employee_Code']; ?>')">
									Apply for Overtime
								</button>
							</div> 
							<div class="box-body">
								<table id="myovertimes" class="table table-bordered table-striped">
									<thead>
										<tr>
											<th>#</th>
											<th>DATE</th>
											<th>HOURS</th>
											<th>STATUS</th>
											<th>CREATED AT</th>
										</tr>
									</thead>
									<tbody>
										<!-- Overtime data will be dynamically added here -->
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</section>
		</div>

		<?php } ?>

		<footer class="main-footer">
			<strong>&copy; <?php echo date("Y"); ?> Employee Management System</strong>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<script type="text/javascript">
		var baseurl = '<?php echo BASE_URL; ?>';

		function applyForOvertime(empCode) {
        fetch("http://localhost:5000/apply-overtime", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify({ emp_code: empCode }),
        })
          .then((response) => response.json())
          .then((data) => {
            if (data.message) {
              Swal.fire({
                title: data.message,
                icon: "success"
              });
              setTimeout(() => {
                location.reload();
              }, 3000);
            } else if (data.error) {
              Swal.fire({
                title: data.error,
                icon: "error"
              });
              
            }
          })
          .catch((error) => {
            alert("Error applying for overtime: " + error);
          });
      }

		$(document).ready(function () {
			// Fetch all overtime requests
			if ('<?php echo $_SESSION['Login_Type']; ?>' == 'admin') {
				$.ajax({
					url: baseurl + 'ajax/?case=GetAllOvertimes',
					type: 'GET',
					success: function (response) {
						if (response.data) {
							$('#allovertime tbody').empty();
							response.data.forEach(record => {
								const row = `
									<tr>
										<td>${record[0]}</td>
										<td>${record[1]}</td>
										<td>${record[2]}</td>
										<td>${record[3]}</td>
										<td>${record[4]}</td>
										<td>
											<button class="btn btn-primary approveovertime" data-overtimeid="${record[5]}">
												Approve
											</button>
											<button class="btn btn-danger rejectovertime" data-overtimeid="${record[5]}">
												Reject
											</button>
										</td>
									</tr>`;
								$('#allovertime tbody').append(row);
							});
						}
					},
					error: function (xhr, status, error) {
						console.error('Error fetching overtime data:', error);
					}
				});

				$(document).on('click', '.approveovertime', function () {
				const overtimeid = $(this).data('overtimeid');
				$.ajax({
					url: baseurl + 'ajax/?case=ApproveOvertime',
					type: 'POST',
					data: { overtime_id: overtimeid },
					success: function (response) {
						console.log(response);
						location.reload();
					},
					error: function (xhr, status, error) {
						console.error('Error approving overtime:', error);
					}
				});
			});
 
			// Reject overtime
			$(document).on('click', '.rejectovertime', function () {
				const overtimeid = $(this).data('overtimeid');
				$.ajax({
					url: baseurl + 'ajax/?case=RejectOvertime',
					type: 'POST',
					data: { overtime_id: overtimeid },
					success: function (response) {
						console.log(response);
						location.reload();
					},
					error: function (xhr, status, error) {
						console.error('Error rejecting overtime:', error);
					}
				});
			});
			} else {
				console.log('<?php echo $_SESSION['Employee_Code']; ?>')
				$.ajax({
					url: baseurl + 'ajax/?case=GetMyOvertimes',
					type: 'GET',
					data: { emp_code: '<?php echo $_SESSION['Employee_Code']; ?>' },
					success: function (response) {
						if (response.data) {
							$('#myovertimes tbody').empty();
							response.data.forEach(record => {
								const row = `
									<tr>
										<td>${record[0]}</td>
										<td>${record[1]}</td>
										<td>${record[2]}</td>
										<td>${record[3]}</td>
										<td>${record[4]}</td>
									</tr>`;
								$('#myovertimes tbody').append(row);
							});
						}
					},
					error: function (xhr, status, error) {
						console.error('Error fetching overtime data:', error);
					}
				});
			}
			// $.ajax({
			// 	url: baseurl + 'ajax/?case=GetAllOvertimes',
			// 	type: 'GET',
			// 	success: function (response) {
			// 		if (response.data) {
			// 			$('#allovertime tbody').empty();
			// 			response.data.forEach(record => {
			// 				const row = `
			// 					<tr>
			// 						<td>${record[0]}</td>
			// 						<td>${record[1]}</td>
			// 						<td>${record[2]}</td>
			// 						<td>${record[3]}</td>
			// 						<td>${record[4]}</td>
			// 						<td>
			// 							<button class="btn btn-primary approveovertime" data-overtimeid="${record[0]}">
			// 								Approve
			// 							</button>
			// 							<button class="btn btn-danger rejectovertime" data-overtimeid="${record[0]}">
			// 								Reject
			// 							</button>
			// 						</td>
			// 					</tr>`;
			// 				$('#allovertime tbody').append(row);
			// 			});
			// 		}
			// 	},
			// 	error: function (xhr, status, error) {
			// 		console.error('Error fetching overtime data:', error);
			// 	}
			// });

			// Approve overtime
			
		});
	</script>
</body>

</html>
