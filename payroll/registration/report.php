<?php require(dirname(__FILE__) . '/config.php');

$empId = $_REQUEST['emp'];
$selectSQL = mysqli_query($db, "SELECT * FROM `" . DB_PREFIX . "employees` WHERE `emp_code` = '$empId' LIMIT 0, 1");
if ($selectSQL) {
	if (mysqli_num_rows($selectSQL) > 0) {
		$empDATA = mysqli_fetch_assoc($selectSQL);
	}
} ?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />

	<title>Employee Information - Payroll</title>

	<link rel="stylesheet" href="<?php echo BASE_URL; ?>bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="<?php echo BASE_URL; ?>dist/css/AdminLTE.css">
	<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">

	<style>
		body {
			font-family: 'Roboto', sans-serif;
		}

		.profile-header {
			background-image: url('../../profile.jpg');
			background-size: cover;
			color: white !important;
			background-position: center center;
			background-color: black;
			background-repeat: no-repeat;
			padding: 20px;
			border-radius: 8px;
			margin-bottom: 20px;
		}

		.profile-header p {
			color: white !important;

		}

		.profile-header img {
			background: white !important;
			width: 120px;
			height: 120px;
			border-radius: 50%;
			object-fit: cover;
			border: 3px solid #007bff;
		}

		.profile-header h2 {
			font-size: 28px;
			font-weight: 500;
		}

		.profile-header p {
			color: #6c757d;
			font-size: 16px;
		}

		.details-row {
			margin-bottom: 15px;
		}

		.details-row label {
			font-weight: 500;
		}

		.details-row p {
			font-size: 16px;
			color: #495057;
		}

		.btn-print {
			background-color: #007bff;
			color: white;
		}

		.box {
			border-radius: 8px;
			box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
		}

		.box-header {
			background-color: #007bff;
			color: white;
			padding: 15px;
			border-radius: 8px 8px 0 0;
		}

		.box-footer {
			padding: 15px;
		}

		.box-footer .btn {
			width: 100%;
		}
	</style>
</head>

<body class="hold-transition register-page">
	<div class="container">
		<div class="box box-primary">
			<div class="box-header">
				<h3 class="box-title">Employee Profile</h3>
			</div>
			<div class="box-body">
				<div class="profile-header text-center">
					<img src="<?php echo BASE_URL; ?>photos/<?php echo $empDATA['photo']; ?>" alt="Employee Photo">
					<h2><?php echo ucwords($empDATA['first_name']) . ' ' . ucwords($empDATA['last_name']); ?></h2>
					<p>Employee Code: <?php echo $empDATA['emp_code']; ?></p>
				</div>

				<div class="details-row row">
					<label class="col-sm-3">Date of Birth</label>
					<div class="col-sm-9">
						<p><?php echo $empDATA['dob']; ?></p>
					</div>
				</div>

				<div class="details-row row">
					<label class="col-sm-3">Gender</label>
					<div class="col-sm-9">
						<p><?php echo ucwords($empDATA['gender']); ?></p>
					</div>
				</div>

				<div class="details-row row">
					<label class="col-sm-3">Marital Status</label>
					<div class="col-sm-9">
						<p><?php echo ucwords($empDATA['merital_status']); ?></p>
					</div>
				</div>

				<div class="details-row row">
					<label class="col-sm-3">Nationality</label>
					<div class="col-sm-9">
						<p><?php echo ucwords($empDATA['nationality']); ?></p>
					</div>
				</div>

				<hr />

				<div class="details-row row">
					<label class="col-sm-3">Address</label>
					<div class="col-sm-9">
						<p><?php echo ucwords($empDATA['address']); ?></p>
					</div>
				</div>

				<div class="details-row row">
					<label class="col-sm-3">City</label>
					<div class="col-sm-9">
						<p><?php echo ucwords($empDATA['city']); ?></p>
					</div>
				</div>

				<div class="details-row row">
					<label class="col-sm-3">State</label>
					<div class="col-sm-9">
						<p><?php echo ucwords($empDATA['state']); ?></p>
					</div>
				</div>

				<div class="details-row row">
					<label class="col-sm-3">Country</label>
					<div class="col-sm-9">
						<p><?php echo ucwords($empDATA['country']); ?></p>
					</div>
				</div>

				<div class="details-row row">
					<label class="col-sm-3">Email</label>
					<div class="col-sm-9">
						<p><?php echo $empDATA['email']; ?></p>
					</div>
				</div>

				<div class="details-row row">
					<label class="col-sm-3">Mobile No</label>
					<div class="col-sm-9">
						<p><?php echo $empDATA['mobile']; ?></p>
					</div>
				</div>

				<div class="details-row row">
					<label class="col-sm-3">Telephone No</label>
					<div class="col-sm-9">
						<p><?php echo $empDATA['telephone'] ? $empDATA['telephone'] : 'N/A'; ?></p>
					</div>
				</div>

				<div class="details-row row">
					<label class="col-sm-3">Identification</label>
					<div class="col-sm-9">
						<p><?php echo ucwords($empDATA['identity_doc']); ?></p>
					</div>
				</div>

				<div class="details-row row">
					<label class="col-sm-3">Id No</label>
					<div class="col-sm-9">
						<p><?php echo $empDATA['identity_no']; ?></p>
					</div>
				</div>

				<hr />

				<div class="details-row row">
					<label class="col-sm-3">Employment Type</label>
					<div class="col-sm-9">
						<p><?php echo ucwords($empDATA['emp_type']); ?></p>
					</div>
				</div>

				<div class="details-row row">
					<label class="col-sm-3">Joining Date</label>
					<div class="col-sm-9">
						<p><?php echo $empDATA['joining_date']; ?></p>
					</div>
				</div>

				<div class="details-row row">
					<label class="col-sm-3">Blood Group</label>
					<div class="col-sm-9">
						<p><?php echo $empDATA['blood_group']; ?></p>
					</div>
				</div>
			</div>

			<div class="box-footer">
				<div class="col-sm-12">
					<button type="button" class="btn btn-print" onclick="window.print();">Print Profile</button>
				</div>
			</div>
		</div>
	</div>

	<script src="<?php echo BASE_URL; ?>plugins/jQuery/jquery-2.2.3.min.js"></script>
	<script src="<?php echo BASE_URL; ?>bootstrap/js/bootstrap.min.js"></script>
</body>

</html>

<?php unset($_SESSION['success']); ?>