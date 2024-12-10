<?php require_once(dirname(__FILE__) . '/config.php');
if (!isset($_SESSION['Admin_ID']) || $_SESSION['Login_Type'] != 'admin') {
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
								<div class="box-tools">
									<input type="file" id="fileInput" accept=".txt" style="display: none;" />
									<input type="file" id="fileInput" accept=".txt" style="display: none;" />

									<?php
									$output = shell_exec('ip.bat');
									$ipAddress = trim($output);
									$ipAddressWithPort = rtrim($ipAddress, '/') . ":5000";


									if (filter_var($ipAddressWithPort, FILTER_VALIDATE_URL)) {
										echo '<a class="btn btn-primary" target="_blank" href="' . $ipAddressWithPort . '">
            <i class="fa fa-qrcode"></i> Facial ID & Gesture Attendance
          </a>';
									} else {
										echo "Unable to fetch valid IP address.";
									}
									?>


									<script>
										document.getElementById('importButton').addEventListener('click', function () {
											document.getElementById('fileInput').click();
										});

										document.getElementById('fileInput').addEventListener('change', function (event) {
											const file = event.target.files[0];
											if (file) {
												const reader = new FileReader();
												reader.onload = function (e) {
													const content = e.target.result;
													console.log(content);
													$.ajax({
														url: '../import.php', // Replace with your server endpoint
														type: 'POST',
														data: { fileData: content },
														success: function (response) {
															console.log('File content sent successfully:', response);
															// Reload the page on success
															window.location.reload();
														},
														error: function (xhr, status, error) {
															console.error('Error sending file content:', error);
															// Alert the user on error
															alert('An error occurred while uploading the file. Please try again.');
														}
													});
												};
												reader.readAsText(file);
											}
										});
									</script>

									<button class="btn btn-success" id="printButton"><i class="fa fa-print"></i>
										Print Attendance</button>
								</div>
							</div>
							<div class="box-body">
								<div class="table-responsiove">
									<table id="attendance" class="table table-bordered table-striped">
										<thead>
											<tr>
												<th>DATE</th>
												<th>EMP CODE</th>
												<th>NAME</th>
												<th>PUNCH-IN</th>
												<th>PUNCH-OUT</th>
												<th>WORK HOURS</th>
												<!-- <th>PUNCH-IN MESSAGE</th> -->
												<!-- <th>PUNCH-OUT MESSAGE</th> -->
											</tr>
										</thead>
									</table>
								</div>
							</div>
						</div>
					</div>
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
	<script src="<?php echo BASE_URL; ?>plugins/bootstrap-notify/bootstrap-notify.min.js"></script>
	<script src="<?php echo BASE_URL; ?>dist/js/app.min.js"></script>
	<script type="text/javascript">var baseurl = '<?php echo BASE_URL; ?>';</script>
	<script src="<?php echo BASE_URL; ?>dist/js/script.js?rand=<?php echo rand(); ?>"></script>

	<script>
		document.getElementById('printButton').addEventListener('click', function () {
			window.print();
		});
	</script>

	<div id="printOptionsPopup" class="custom-popup" style="display: none;">
		<div class="custom-popup-content">
			<span class="custom-popup-close" onclick="closePopup()">&times;</span>
			<h4 class="custom-popup-title">Print Attendance</h4>
			<form id="printOptionsForm">
				<div class="form-group">
					<label for="empCodeSelect">Employee Code</label>
					<select id="empCodeSelect" class="form-control select2" multiple="multiple">
						<option value="all">All</option>
						<!-- Add employee codes dynamically -->
					</select>
				</div>
				<div class="form-group">
					<label for="fromDate">From Date</label>
					<input type="text" id="fromDate" class="form-control datepicker" placeholder="MM-YYYY">
				</div>
				<div class="form-group">
					<label for="toDate">To Date</label>
					<input type="text" id="toDate" class="form-control datepicker" placeholder="MM-YYYY">
				</div>
				<button type="button" class="btn btn-primary" id="submitPrintOptions">Print</button>
			</form>
		</div>
	</div>


	<script>

		$(document).ready(function () {
			// Initialize DataTable
			var table = $('#attendance').DataTable();
			$('.select2').select2();

			function populateEmployeeCodes() {
				var empCodes = new Set();
				table.rows().every(function () {
					var data = this.data();
					var empCode = data[1].trim();
					if (empCode) {
						empCodes.add(empCode);
					}
				});

				empCodes.forEach(function (code) {
					$('#empCodeSelect').append(new Option(code, code));
				});

				$('#empCodeSelect').trigger('change');
			}

			// Call the function to populate employee codes
			table.on('init', function () {
				populateEmployeeCodes();
			});

			// Print button click handler
			$('#printButton').click(function () {
				$('#printOptionsPopup').show();
			});

			// Close the popup
			window.closePopup = function () {
				$('#printOptionsPopup').hide();
			};

			// Submit Print Options
			$('#submitPrintOptions').click(function () {
				var selectedEmpCodes = $('#empCodeSelect').val();
				var fromDateStr = $('#fromDate').val();
				var toDateStr = $('#toDate').val();

				var fromDateParts = fromDateStr.split('/');
				var toDateParts = toDateStr.split('/');

				var fromDate = new Date(fromDateParts[2], fromDateParts[0] - 1, 1);
				var toDate = new Date(toDateParts[2], toDateParts[0], 0);

				var filteredData = [];
				table.rows().every(function () {
					var data = this.data();
					var dateStr = data[0];
					var empCode = data[1];

					var dateParts = dateStr.split('-');
					var tableDate = new Date(dateParts[2], dateParts[1] - 1, dateParts[0]);

					var isDateInRange = tableDate >= fromDate && tableDate <= toDate;
					var isEmpCodeIncluded = selectedEmpCodes === "all" || selectedEmpCodes.includes(empCode);

					if (isDateInRange && isEmpCodeIncluded) {
						filteredData.push(data);
					}
				});

				if (filteredData.length === 0) {
					alert("No records found.");
				} else {
					var printWindow = window.open('', '_blank');
					var printContent = '<html><head><title>Print Attendance</title>';
					printContent += '<style>';
					printContent += '@media print {';
					printContent += 'body { font-family: Arial, sans-serif; }';
					printContent += '@page { size: landscape; margin: 20mm; }';
					printContent += 'table { width: 100%; border-collapse: collapse; }';
					printContent += 'th, td { border: 1px solid #ddd; padding: 8px; }';
					printContent += 'th { background-color: #f4f4f4; text-align: center; }';
					printContent += 'td { text-align: center; }';
					printContent += 'h1 { text-align: center; margin-bottom: 20px; }';
					printContent += '}';
					printContent += '</style>';
					printContent += '</head><body>';
					printContent += '<h1>Attendance Report</h1>';
					printContent += '<table>';
					printContent += '<thead><tr><th>DATE</th><th>EMP CODE</th><th>NAME</th><th>PUNCH-IN</th><th>PUNCH-IN MESSAGE</th><th>PUNCH-OUT</th><th>PUNCH-OUT MESSAGE</th><th>WORK HOURS</th></tr></thead><tbody>';

					filteredData.forEach(function (row) {
						printContent += '<tr>';
						row.forEach(function (cell) {
							printContent += '<td>' + cell + '</td>';
						});
						printContent += '</tr>';
					});

					printContent += '</tbody></table></body></html>';

					printWindow.document.write(printContent);
					printWindow.document.close();
					printWindow.print();

					closePopup(); // Close the popup after printing
				}
			});
		});

	</script>
</body>

</html>