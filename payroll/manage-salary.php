<?php require_once(dirname(__FILE__) . '/config.php');
if (!isset($_SESSION['Admin_ID']) || $_SESSION['Login_Type'] != 'admin') {
	header('location:' . BASE_URL);
}
if (!isset($_GET['emp_code']) || empty($_GET['emp_code']) || !isset($_GET['month']) || empty($_GET['month']) || !isset($_GET['year']) || empty($_GET['year'])) {
	header('location:' . BASE_URL);
}

$currentDay = date('j');
$cutoffFlag = 0;
if ($currentDay >= 1 && $currentDay <= 15) {
    $cutoffFlag = 0;
} elseif ($currentDay >= 16 && $currentDay <= 30) {
    $cutoffFlag = 1;
}

$currentCutoff = $cutoffFlag ? '30th': '15th';

$empData = GetEmployeeDataByEmpCode($_GET['emp_code']);
$month = $_GET['month'] . ', ' . $_GET['year'];
$empLeave = GetEmployeeLWPDataByEmpCodeAndMonth($_GET['emp_code'], $month);
$flag = 0;
$totalEarnings = 0;
$totalDeductions = 0;
$checkSalarySQL = mysqli_query($db, "SELECT * FROM `" . DB_PREFIX . "salaries` WHERE `emp_code` = '" . $empData['emp_code'] . "' AND `pay_month` = '$month' AND `cutoff` = '$cutoffFlag'");
if ($checkSalarySQL) {
	$checkSalaryROW = mysqli_num_rows($checkSalarySQL);

	if ($checkSalaryROW > 0) {
		$flag = 1;
		$empSalary = GetEmployeeSalaryByEmpCodeAndMonth($_GET['emp_code'], $month, $cutoffFlag);
	} else {
		$empHeads = GetEmployeePayheadsByEmpCode($_GET['emp_code']);
	}
} ?>

<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

	<title>Salary for <?php echo $month; ?> - Payroll</title>

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
				<h1>Salary for <?php echo $month; ?> - <?php echo $currentCutoff; ?> Day Cutoff </h1>
				<ol class="breadcrumb">
					<li><a href="<?php echo BASE_URL; ?>"><i class="fa fa-dashboard"></i> Home</a></li>
					<li class="active">Salary for <?php echo $month; ?></li>
				</ol>
			</section>

			<section class="content">
				<div class="row">
					<div class="col-xs-12">
						<div class="box">
							<div class="box-body">
							
								<?php if ($flag == 0) { ?>
									<form method="POST" role="form" id="payslip-form">
										<input type="hidden" name="emp_code" value="<?php echo $empData['emp_code']; ?>" />
										<input type="hidden" name="pay_month" value="<?php echo $month; ?>" />
										<input type="hidden" name="cutoff" value="<?php echo $cutoffFlag; ?>" />
										<div class="table-responsive">
										<table class="table table-bordered">
											<tr>
													<td width="20%">Employee Code</td>
													<td width="30%"><?php echo strtoupper($empData['emp_code']); ?></td>
													<!-- <td width="20%">Bank Name</td>
													<td width="30%"><?php echo ucwords($empData['bank_name']); ?></td> -->
												</tr>
												<tr>
													<td>Employee Name</td>
													<td><?php echo ucwords($empData['first_name'] . ' ' . $empData['last_name']); ?>
													</td>
													<!-- <td>Bank Account</td>
													<td><?php echo $empData['account_no']; ?></td> -->
												</tr>
												<tr>
													<td>Position</td>
													<td><?php echo ucwords($empData['designation']); ?></td>
													<!-- <td>IFSC Code 
													<td><?php echo strtoupper($empData['ifsc_code']); ?></td> --> -->
												</tr>
												<tr>
													<td>Gender</td>
													<td><?php echo ucwords($empData['gender']); ?></td>
													<!-- <td>PAN
													<td><?php echo strtoupper($empData['pan_no']); ?></td> --> -->
												</tr>
												<tr>
													<td>Location</td>
													<td><?php echo ucwords($empData['city']); ?></td>
													<!-- <td>PF Account
													<td><?php echo strtoupper($empData['pf_account']); ?></td> --> -->
												</tr>
												<tr>
													<td>Department</td>
													<td><?php echo ucwords($empData['department']); ?></td>
													<td>Payable/Working Days
													<td><?php echo ($empLeave['workingDays'] - $empLeave['withoutPay']); ?>/<?php echo $empLeave['workingDays']; ?>
														Days</td>
												</tr>
												<tr>
													<td>Date of Joining</td>
													<td><?php echo date('d-m-Y', strtotime($empData['joining_date'])); ?>
													</td>
													<td>Taken/Remaining Leaves</td>
													<td><?php echo $empLeave['payLeaves']; ?>/<?php echo ($empLeave['totalLeaves'] - $empLeave['payLeaves']); ?>
														Days</td>
												</tr>
											</table>
											<table class="table table-bordered">
												<thead>
													<tr>
														<th width="35%">Earnings</th>
														<th width="15%" class="text-right">Amount (Php)</th>
														<th width="35%">Deductions</th>
														<th width="15%" class="text-right">Amount (Php)</th>
													</tr>
												</thead>
												<tbody>
													<?php if (!empty($empHeads)) { ?>
														<tr>
															<td colspan="2" style="padding:0">
																<table class="table table-bordered table-striped"
																	style="margin:0">
																	<?php
																		$valid_designations = [
																			'General Manager', 'Operation Manager', 'Accounting Staff', 
																			'Finance Supervisor', 'HR Manager', 'Admin Officer', 'Liaison Officer'
																		];
																		$salary_mapping = [
																			'General Manager' => 30000,
																			'Operation Manager' => 30000,
																			'Accounting Staff' => 26000,
																			'Finance Supervisor' => 26000,
																			'HR Manager' => 25000,
																			'Admin Officer' => 25000,
																			'Liaison Officer' => 25000
																		];

																		$position = $empData['designation'];
																		$fixed_salary = 0;
																		$totalEarnings = 0;

																		if (!in_array($position, $valid_designations)) {
																			$dailySalary = GetEmployeeAttendanceBasedSalaryByEmpcodeAndMonth($empData['emp_code'], $month, $cutoffFlag);
																			$totalEarnings += $dailySalary;
																		?>
																			<tr>
																				<td width="70%">Daily Salary</td>
																				<td width="30%" class="text-right">
																					<input type="hidden" name="earnings_heads[]" value="Daily Salary" />
																					<input type="text" name="earnings_amounts[]" value="<?php echo number_format($dailySalary, 2, '.', ''); ?>" class="form-control text-right" />
																				</td>
																			</tr>
																		<?php 
																		} else {
																			$fixed_salary = $salary_mapping[$position] / 2 ?? 0;
																			$totalEarnings += $fixed_salary;
																		?>
																			<tr>
																				<td width="70%">Fixed Salary</td>
																				<td width="30%" class="text-right">
																					<input type="hidden" name="earnings_heads[]" value="Fixed Salary" />
																					<input type="text" name="earnings_amounts[]" value="<?php echo number_format($fixed_salary, 2, '.', ''); ?>" class="form-control text-right" />
																				</td>
																			</tr>
																		<?php } ?>
																	<?php
																		
																	?>
																	<?php foreach ($empHeads as $head) { ?>
																		<?php if ($head['payhead_type'] == 'earnings') { ?>
																			<?php $totalEarnings += $head['default_salary']; ?>
																			<tr>
																				<td width="70%">
																					<?php echo $head['payhead_name']; ?>
																				</td>
																				<td width="30%" class="text-right">
																					<input type="hidden" name="earnings_heads[]"
																						value="<?php echo $head['payhead_name']; ?>" />
																					<input type="text" name="earnings_amounts[]"
																						value="<?php echo number_format($head['default_salary'], 2, '.', ''); ?>"
																						class="form-control text-right" />
																				</td>
																			</tr>
																		<?php } ?>
																	<?php } ?>
																	<?php if (CheckOvertimeDataByEmpcode($empData['emp_code'], $month, $cutoffFlag) && !in_array($position, $valid_designations)) { ?>
																	<?php
																		$overtimeEarnings = GetOvertimeHoursByEmpCodeAndMonth($empData['emp_code'], $month, $cutoffFlag);
																		$totalEarnings += $overtimeEarnings; 
																	?>
																	<tr>
																		<td width="70%">
																			Overtime Pay
																		</td>
																		<td width="30%" class="text-right">
																			<input type="hidden" name="earnings_heads[]"
																				value="Overtime Pay" />
																			<input type="text" name="earnings_amounts[]"
																				value="<?php echo number_format($overtimeEarnings, 2, '.', ''); ?>"
																				class="form-control text-right" />
																		</td>
																	</tr>
																	<?php } ?>
																</table>
															</td>
															<td colspan="2" style="padding:0">
																<table class="table table-bordered table-striped"
																	style="margin:0">
																	<?php
																		$lateDeduction = GetEmployeeLateDeductionByEmpcodeAndMonth($empData['emp_code'], $month, $cutoffFlag);
																		$totalDeductions += $lateDeduction; 
																	?>
																	<tr>
																		<td width="70%">
																			Late Deduction
																		</td>
																		<td width="30%" class="text-right">
																			<input type="hidden" name="deductions_heads[]"
																				value="Late Deduction" />
																			<input type="text" name="deductions_amounts[]"
																				value="<?php echo number_format($lateDeduction, 2, '.', ''); ?>"
																				class="form-control text-right" />
																		</td>
																	</tr>
																	<?php
																		if ($cutoffFlag == 1){
																		$withholdingTax = calculateWithholdingTax($totalEarnings - $totalDeductions + GetPreviousCutoffTotalEarnigns($empData['emp_code'], $month));
																		$totalDeductions += $withholdingTax; 
																	?>
																	<tr>
																		<td width="70%">
																			Withholding Tax
																		</td>
																		<td width="30%" class="text-right">
																			<input type="hidden" name="deductions_heads[]"
																				value="Withholding Tax" />
																			<input type="text" name="deductions_amounts[]"
																				value="<?php echo number_format($withholdingTax, 2, '.', ''); ?>"
																				class="form-control text-right" />
																		</td>
																	</tr>
																	<?php } ?>
																	<?php foreach ($empHeads as $head) { ?>
																		<?php if ($head['payhead_type'] == 'deductions') { ?>
																			<?php if(!$cutoffFlag  && $head['payhead_name'] == 'SSS Contribution' || $head['payhead_name'] == 'Salary Loan' ){ ?>
																			<?php $totalDeductions += $head['default_salary']; ?>
																			<tr>
																				<td width="70%">
																					<?php echo $head['payhead_name']; ?>
																				</td>
																				<td width="30%" class="text-right">
																					<input type="hidden" name="deductions_heads[]"
																						value="<?php echo $head['payhead_name']; ?>" />
																					<input type="text" name="deductions_amounts[]"
																						value="<?php echo number_format($head['default_salary'], 2, '.', ''); ?>"
																						class="form-control text-right" />
																				</td>
																			</tr>
																			<?php } else if($cutoffFlag  && ($head['payhead_name'] == 'Pag-Ibig Loan' || $head['payhead_name'] == 'SSS Loan' || $head['payhead_name'] == 'Philhealth Contribution' || $head['payhead_name'] == 'Withholding Tax' || $head['payhead_name'] == 'SSS Premium' || $head['payhead_name'] == 'Maxicare' || $head['payhead_name'] == 'Salary Loan' )){ ?>
																			<?php $totalDeductions += $head['default_salary']; ?>
																			<tr>
																				<td width="70%">
																					<?php echo $head['payhead_name']; ?>
																				</td>
																				<td width="30%" class="text-right">
																					<input type="hidden" name="deductions_heads[]"
																						value="<?php echo $head['payhead_name']; ?>" />
																					<input type="text" name="deductions_amounts[]"
																						value="<?php echo number_format($head['default_salary'], 2, '.', ''); ?>"
																						class="form-control text-right" />
																				</td>
																			</tr>
																			<?php } ?>
																		<?php } ?>
																	<?php } ?>
																	
																</table>
															</td>
														</tr>
													<?php } else { ?>
														<tr>
															<td colspan="4">No payheads are assigned for this employee</td>
														</tr>
													<?php } ?>
												</tbody>
												<tfoot>
													<tr>
														<td><strong>Total Earnings</strong></td>
														<td class="text-right">
															<strong id="totalEarnings">
																<?php echo number_format($totalEarnings, 2, '.', ''); ?>
															</strong>
														</td>
														<td><strong>Total Deductions</strong></td>
														<td class="text-right">
															<strong id="totalDeductions">
																<?php echo number_format($totalDeductions, 2, '.', ''); ?>
															</strong>
														</td>
													</tr>
												</tfoot>
											</table>
										</div>
										<div class="row">
											<div class="col-sm-6">
												<h3 class="text-success" style="margin-top:0">
													Net Salary Payable:
													<span
														id="netSalary"><?php echo number_format(($totalEarnings - $totalDeductions), 2, '.', ''); ?></span>
												</h3>
											</div>
											<div class="col-sm-6 text-right">
												<?php if (!empty($empHeads)) { ?>
													<button onclick="reloadPage()" type="submit" class="btn btn-info">
														<i class="fa fa-plus"></i> Generate PaySlip
													</button>

													<script>
														function reloadPage() {
															setTimeout(function () {
																location.reload();
															}, 500); // 1000 milliseconds = 1 second
														}
													</script>

												<?php } ?>
											</div>
										</div>
									</form>
								<?php } else { ?>
									<div class="table-responsive">
										<table class="table table-bordered">
											<thead>
												<tr>
													<th width="35%">Earnings</th>
													<th width="15%" class="text-right">Amount (Php)</th>
													<th width="35%">Deductions</th>
													<th width="15%" class="text-right">Amount (Php)</th>
												</tr>
											</thead>
											<tbody>
												<?php if (!empty($empSalary)) { ?>
													<tr>
														<td colspan="2" style="padding:0">
															<table class="table table-bordered table-striped" style="margin:0">
																<?php foreach ($empSalary as $salary) { ?>
																	<?php if ($salary['pay_type'] == 'earnings') { ?>
																		<?php $totalEarnings += $salary['pay_amount']; ?>
																		<tr>
																			<td width="70%">
																				<?php echo $salary['payhead_name']; ?>
																			</td>
																			<td width="30%" class="text-right">
																				<?php echo number_format($salary['pay_amount'], 2, '.', ','); ?>
																			</td>
																		</tr>
																	<?php } ?>
																<?php } ?>
															</table>
														</td>
														<td colspan="2" style="padding:0">
															<table class="table table-bordered table-striped" style="margin:0">
																<?php foreach ($empSalary as $salary) { ?>
																	<?php if ($salary['pay_type'] == 'deductions') { ?>
																		<?php $totalDeductions += $salary['pay_amount']; ?>
																		<tr>
																			<td width="70%">
																				<?php echo $salary['payhead_name']; ?>
																			</td>
																			<td width="30%" class="text-right">
																				<?php echo number_format($salary['pay_amount'], 2, '.', ','); ?>
																			</td>
																		</tr>
																	<?php } ?>
																<?php } ?>
															</table>
														</td>
													</tr>
												<?php } else { ?>
													<tr>
														<td colspan="4">No payheads are assigned for this employee</td>
													</tr>
												<?php } ?>
											</tbody>
											<tfoot>
												<tr>
													<td><strong>Total Earnings</strong></td>
													<td class="text-right">
														<strong id="totalEarnings">
															<?php echo number_format($totalEarnings, 2, '.', ','); ?>
														</strong>
													</td>
													<td><strong>Total Deductions</strong></td>
													<td class="text-right">
														<strong id="totalDeductions">
															<?php echo number_format($totalDeductions, 2, '.', ','); ?>
														</strong>
													</td>
												</tr>
											</tfoot>
										</table>
									</div>
									<div class="row">
										<div class="col-sm-6">
											<h3 class="text-success" style="margin-top:0">
												Net Salary Payable:
												₱ <?php echo number_format(($totalEarnings - $totalDeductions), 2, '.', ','); ?>
												<small>(In words:
													<?php echo ucfirst(ConvertNumberToWords(($totalEarnings - $totalDeductions))); ?>)</small>
											</h3>
										</div>
										<div class="col-sm-6 text-right">
											<button type="button" class="btn btn-success"
												onclick="openInNewTab('<?php echo BASE_URL; ?>payslips/<?php echo $empData['emp_code']; ?>/<?php echo str_replace(', ', '-', $month); ?>/<?php echo str_replace(', ', '-', $month); ?><?php echo ($cutoffFlag == 0 ? '-15th' : '-30th')?>.pdf');">
												<i class="fa fa-download"></i> Show PaySlip (PDF Version)
											</button>
											<!-- <button type="button" class="btn btn-info"
												onclick="sendPaySlipByMail('<?php echo $empData['emp_code']; ?>', '<?php echo $month; ?>');">
												<i class="fa fa-envelope"></i> Send to Employee
											</button> -->
										</div>
									</div>
								<?php } ?>
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
	<script src="<?php echo BASE_URL; ?>plugins/jquery-validator/validator.min.js"></script>
	<script src="<?php echo BASE_URL; ?>plugins/bootstrap-notify/bootstrap-notify.min.js"></script>
	<script src="<?php echo BASE_URL; ?>dist/js/app.min.js"></script>
	<script type="text/javascript">var baseurl = '<?php echo BASE_URL; ?>';</script>
	<script src="<?php echo BASE_URL; ?>dist/js/script.js?rand=<?php echo rand(); ?>"></script>
	<?php if (isset($_SESSION['PaySlipMsg'])) { ?>
		<script type="text/javascript">
			$.notify({
				icon: 'glyphicon glyphicon-ok-circle',
				message: '<?php echo $_SESSION['PaySlipMsg']; ?>',
			}, {
				allow_dismiss: false,
				type: "success",
				placement: {
					from: "top",
					align: "right"
				},
				z_index: 9999,
			});
		</script>
	<?php } ?>
</body>

</html>
<?php unset($_SESSION['PaySlipMsg']); ?>