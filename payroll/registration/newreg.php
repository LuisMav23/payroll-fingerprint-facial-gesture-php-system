<?php require(dirname(__FILE__) . '/config.php');

$errors = array();
$expensions = array("jpeg", "jpg", "png");
$target_dir = dirname(__FILE__) . "/photos/";

if (isset($_POST['submit'])) {

    $selectSQL = mysqli_query($db, "SELECT `emp_code` FROM `" . DB_PREFIX . "employees` ORDER BY `emp_id` DESC LIMIT 1");
    $selectDeletedSQL = mysqli_query($db, "SELECT `emp_code` FROM `" . DB_PREFIX . "deleted_employees` ORDER BY `emp_id` DESC LIMIT 1");
    if ($selectSQL) {
        if (mysqli_num_rows($selectSQL) > 0) {
            $row = mysqli_fetch_assoc($selectSQL);
            $rowDeleted = mysqli_fetch_assoc($selectDeletedSQL);
            $lastEmpCode = $row['emp_code'];
            $lastDeletedEmpCode = $rowDeleted['emp_code'];
            
            // Extract the numeric part of the emp_code
            $lastEmpNumber = (int)substr($lastEmpCode, 2); // Get the number after 'WY'
            $lastDeletedEmpNumber = (int)substr($lastDeletedEmpCode, 2); // Get the number after 'WY'
            $lastNumber = $lastEmpNumber > $lastDeletedEmpNumber ? $lastEmpNumber : $lastDeletedEmpNumber;
            
            // Generate the new emp_code
            $curEmpID = 'WY' . sprintf("%02d", $lastNumber + 1);
        } else {
            $curEmpID = 'WY01';
        }
    } else {
        $errors['database'] = '<span class="text-danger">Something went wrong, please contact to support team!</span>';
    }

    if (empty($_POST['designation'])) {
        $errors['designation'] = '<span class="text-danger">Please enter your designation!</span>';
    }

    if (empty($_POST['department'])) {
        $errors['department'] = '<span class="text-danger">Please enter your designation!</span>';
    }

    if (empty($_POST['first_name'])) {
        $errors['first_name'] = '<span class="text-danger">Please enter your first name!</span>';
    }
    if (empty($_POST['last_name'])) {
        $errors['last_name'] = '<span class="text-danger">Please enter your last name!</span>';
    }
    if (empty($_POST['dob'])) {
        $errors['dob'] = '<span class="text-danger">Please enter your date of birth!</span>';
    }
    if (empty($_POST['gender'])) {
        $errors['gender'] = '<span class="text-danger">Please select your gender!</span>';
    }
    if (empty($_POST['merital_status'])) {
        $errors['merital_status'] = '<span class="text-danger">Please choose your merital status!</span>';
    }
    if (empty($_POST['nationality'])) {
        $errors['nationality'] = '<span class="text-danger">Please enter your nationality!</span>';
    }
    if (empty($_POST['address'])) {
        $errors['address'] = '<span class="text-danger">Please enter your address!</span>';
    }
    if (empty($_POST['city'])) {
        $errors['city'] = '<span class="text-danger">Please enter your city!</span>';
    }
    if (empty($_POST['state'])) {
        $errors['state'] = '<span class="text-danger">Please enter your state!</span>';
    }
    if (empty($_POST['country'])) {
        $errors['country'] = '<span class="text-danger">Please enter your country!</span>';
    }
    if (empty($_POST['email'])) {
        $errors['email'] = '<span class="text-danger">Please enter your email id!</span>';
    }
    if (empty($_POST['mobile'])) {
        $errors['mobile'] = '<span class="text-danger">Please enter your mobile number!</span>';
    }
    if (empty($_POST['identification'])) {
        $errors['identification'] = '<span class="text-danger">Please choose your identification document!</span>';
    }
    if (empty($_POST['id_no'])) {
        $errors['id_no'] = '<span class="text-danger">Please enter your identification number!</span>';
    }
    if (empty($_POST['employment_type'])) {
        $errors['employment_type'] = '<span class="text-danger">Please choose your employment type!</span>';
    }
    if (empty($_POST['joining_date'])) {
        $errors['joining_date'] = '<span class="text-danger">Please enter your joining date!</span>';
    }
    if (empty($_POST['bloodgrp'])) {
        $errors['bloodgrp'] = '<span class="text-danger">Please enter your blood group!</span>';
    }
    if (empty($_POST['emp_password'])) {
        $errors['emp_password'] = '<span class="text-danger">Please set employee password!</span>';
    } else {
        $emp_password = addslashes($_POST['emp_password']);
    }

    if (empty($_FILES['photo']['name'])) {
        $errors['photo'] = '<span class="text-danger">Please upload your recent photograph!</span>';
    } else {
        $file_tmp = $_FILES['photo']['tmp_name'];
        $file_type = $_FILES['photo']['type'];
        $file_ext = strtolower(end(explode('.', $_FILES['photo']['name'])));

        $photocopy = $curEmpID . '.' . $file_ext;
        if (in_array($file_ext, $expensions) === false) {
            $errors['photo'] = '<span class="text-danger">Extension not allowed, please choose a JPEG or PNG file!</span>';
        }
    }

    if (empty($errors) == true) {
        if (move_uploaded_file($file_tmp, $target_dir . $photocopy)) {

            extract($_POST);
            $insertSQL = mysqli_query($db, "INSERT INTO `" . DB_PREFIX . "employees`(`emp_code`, `first_name`, `last_name`, `dob`, `gender`, `merital_status`, `nationality`, `address`, `city`, `state`, `country`, `email`, `mobile`, `telephone`, `identity_doc`, `identity_no`, `emp_type`, `joining_date`, `blood_group`, `emp_password`, `department`, `designation`, `photo`, `created`) 
            VALUES ('$curEmpID', '$first_name', '$last_name', '$dob', '$gender', '$merital_status', '$nationality', '$address', '$city', '$state', '$country', '$email', '$mobile', '$telephone', '$identification', '$id_no', '$employment_type', '$joining_date', '$bloodgrp', '" . sha1($emp_password) . "', '$department', '$designation', '$photocopy', NOW())");

            $_SESSION['success'] = '<p class="text-center"><span class="text-success">Employee registration successfully!</span></p>';
            echo "<script>alert('Registration Success');window.location.href='newreg.php'</script>";
            exit();
        } else {
            $errors['photo'] = '<span class="text-danger">Photo is not uploaded, please try again!</span>';
        }
    }
} 
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <title>Employee Registration - Payroll</title>

    <link rel="stylesheet" href="<?php echo BASE_URL; ?>bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>dist/css/AdminLTE.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>plugins/datepicker/datepicker3.css">

    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <style>
        .register-page {
        background-color: #111733;
      }

        .register-title {
            font-family: "Orbitron", sans-serif;
            color: rgb(255, 233, 111);
            
        }

        .payroll {
            color:rgb(247, 165, 34);
            font-weight:bold;
        }

        .management {
            color:rgb(255, 233, 111);
            font-weight:100;
        }
    </style>
</head>

<body class="hold-transition register-page">
    <div class="container">
        <div class="register-box">
            <div class="register-logo register-title">
                <a href="<?php echo BASE_URL; ?>" class="register-title">
                <!-- Added Code - Andrie -->
                    <img src="../dist/img/alar-logo.png" alt="Alar Logo">
                    <b class="payroll">Employee Registration </b> <span class="management"> Form</span>
                </a>
                <br>
            </div>
            <div class="form-group">
        </div>
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Fill the below form</h3>
                <div class="box-tools pull-right">
                    <span class="text-red">All fields are mandatory</span>
                </div>
            </div>
            <form class="form-horizontal" method="post" enctype="multipart/form-data" novalidate="">
                <div class="box-body">
                    <div class="form-group">
                        <label for="first_name" class="col-sm-2 control-label">Full Name</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" id="first_name" name="first_name"
                                placeholder="First Name" value="<?php echo $_POST['first_name']; ?>" required />
                            <?php echo $errors['first_name']; ?>
                        </div>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" id="last_name" name="last_name"
                                placeholder="Last Name" value="<?php echo $_POST['last_name']; ?>" required />
                            <?php echo $errors['last_name']; ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="dob" class="col-sm-2 control-label">Date of Birth</label>
                        <div class="col-sm-5">
                            <div class="input-group">
                                <input type="text" class="form-control" id="dob" name="dob" placeholder="MM/DD/YYYY"
                                    value="<?php echo $_POST['dob']; ?>" required />
                                <span class="input-group-addon">
                                    <i class="glyphicon glyphicon-calendar"></i>
                                </span>
                            </div>
                            <?php echo $errors['dob']; ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-2 control-label">Gender</label>
                        <div class="col-xs-10">
                            <div class="btn-group" data-toggle="buttons">
                                <label
                                    class="btn btn-default <?php echo $_POST['gender'] == 'male' ? 'active' : ''; ?>">
                                    <input type="radio" name="gender" value="male" <?php echo $_POST['gender'] == 'male' ? 'checked' : ''; ?> required /> Male
                                </label>
                                <label
                                    class="btn btn-default <?php echo $_POST['gender'] == 'female' ? 'active' : ''; ?>">
                                    <input type="radio" name="gender" value="female" <?php echo $_POST['gender'] == 'female' ? 'checked' : ''; ?> required /> Female
                                </label>
                            </div><br />
                            <?php echo $errors['gender']; ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="marital_status" class="col-sm-2 control-label">Marital status</label>
                        <div class="col-sm-5">
                            <select class="form-control" id="merital_status" name="merital_status" required>
                                <option value="">Please make a choice</option>
                                <option <?php echo $_POST['merital_status'] == 'Single' ? 'selected' : ''; ?>
                                    value="Single">Single</option>
                                <option <?php echo $_POST['merital_status'] == 'Cohabitation' ? 'selected' : ''; ?>
                                    value="Cohabitation">Cohabitation</option>
                                <option <?php echo $_POST['merital_status'] == 'Married' ? 'selected' : ''; ?>
                                    value="Married">Married</option>
                                <option <?php echo $_POST['merital_status'] == 'Registered partnership' ? 'selected' : ''; ?> value="Registered partnership">Registered partnership</option>
                                <option <?php echo $_POST['merital_status'] == 'Have been married before' ? 'selected' : ''; ?> value="Have been married before">Have been married before</option>
                                <option <?php echo $_POST['merital_status'] == 'Widow' ? 'selected' : ''; ?>
                                    value="Widow">Widow</option>
                            </select>
                            <?php echo $errors['merital_status']; ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="nationality" class="col-sm-2 control-label">Nationality</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" id="nationality" name="nationality"
                                placeholder="Nationality" value="<?php echo $_POST['nationality']; ?>" required />
                            <?php echo $errors['nationality']; ?>
                        </div>
                    </div>
                    <hr />
                    <div class="form-group">
                        <label for="address" class="col-sm-2 control-label">Address</label>
                        <div class="col-sm-10">
                            <textarea class="form-control" id="address" name="address" placeholder="Address"
                                required><?php echo $_POST['address']; ?></textarea>
                            <?php echo $errors['address']; ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="city" class="col-sm-2 control-label">City</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="city" name="city" placeholder="City"
                                value="<?php echo $_POST['city']; ?>" required />
                            <?php echo $errors['city']; ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="state" class="col-sm-2 control-label">State</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="state" name="state" placeholder="State"
                                value="<?php echo $_POST['state']; ?>" required />
                            <?php echo $errors['state']; ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="country" class="col-sm-2 control-label">Country</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="country" name="country" placeholder="Country"
                                value="<?php echo $_POST['country']; ?>" required />
                            <?php echo $errors['country']; ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="email" class="col-sm-2 control-label">Email Id</label>
                        <div class="col-sm-10">
                            <input type="email" class="form-control" id="email" name="email" placeholder="Email Id"
                                value="<?php echo $_POST['email']; ?>" required />
                            <?php echo $errors['email']; ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="mobile" class="col-sm-2 control-label">Contact No</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" id="mobile" name="mobile" placeholder="Mobile No"
                                value="<?php echo $_POST['mobile']; ?>" required />
                            <?php echo $errors['mobile']; ?>
                        </div>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" id="telephone" name="telephone"
                                value="<?php echo $_POST['telephone']; ?>" placeholder="Telephone No" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="identification" class="col-sm-2 control-label">Identification</label>
                        <div class="col-sm-8">
                            <select class="form-control" id="identification" name="identification" required>
                                <option value="">Please make a choice</option>
                                <option <?php echo $_POST['identification'] == 'Voter ID' ? 'selected' : ''; ?>
                                    value="Voter ID">Voter ID</option>
                                <option <?php echo $_POST['identification'] == 'National ID Card' ? 'selected' : ''; ?>
                                    value="National ID Card">National ID Card</option>
                                <option <?php echo $_POST['identification'] == 'Driving License' ? 'selected' : ''; ?>
                                    value="Driving License">Driving License</option>
                                <option <?php echo $_POST['identification'] == 'Passport' ? 'selected' : ''; ?>
                                    value="Passport">Passport</option>
                            </select>
                            <?php echo $errors['identification']; ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="id_no" class="col-sm-2 control-label">ID Number</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="id_no" name="id_no"
                                placeholder="Identification No" value="<?php echo $_POST['id_no']; ?>" required />
                            <?php echo $errors['id_no']; ?>
                        </div>
                    </div>
                    <hr />
                    <div class="form-group">
									<!-- <div class="col-sm-4">
										<label for="department">Department</label>
										<input type="text" class="form-control" name="department" id="department"
											 /> -->
											 <!-- Added Code - Andrie -->
                        <!-- <div class="form-group">
                        <label for="department" class="col-sm-2 control-label">Department</label>
											<div class="col-sm-10">
												<select class="form-control" name="department" id="department" required>
													<option value="">Please make a choice</option>
													<?php
													$department = [
														"Operation Department", "Admin Department", "Billing Department", "Warehouse Department", 
                                                        "Accounting Department"
													];

													$selectedDepartment= $_POST['department'] ?? '';

													foreach ($department as $department) {
														$selected = ($selectedDepartment == $department) ? 'selected' : '';
														echo "<option value=\"$department\" $selected>$department</option>";
													}
													?>
												</select>
												<?php echo $errors['department'] ?? ''; ?>
											</div>
                                         </div> 
                        <div class="form-group">
                        <label for="designation" class="col-sm-2 control-label">Position</label>
											<div class="col-sm-10">
												<select class="form-control" name="designation" id="designation" required>
													<option value="">Please make a choice</option>
													<?php
													$designations = [
														"Collectors", "Drivers", "Pesticide Handler", "Operation Technician", 
                                                        "Liaison Officer", "Billing Officer", "Supervisor/Team", "General Manager",
														"Operation Manager", "HR Manager", "Finance Supervisor",
														"Admin Officer", "Accounting Staff"
													];

													$selectedDesignation = $_POST['designation'] ?? '';

													foreach ($designations as $designation) {
														$selected = ($selectedDesignation == $designation) ? 'selected' : '';
														echo "<option value=\"$designation\" $selected>$designation</option>";
													}
													?>
												</select>
												<?php echo $errors['designation'] ?? ''; ?>
											</div>
                        </div> -->
                    <div class="form-group">
                        <label for="department" class="col-sm-2 control-label">Department</label>
                            <div class="col-sm-8">
                                <select class="form-control" name="department" id="department" required>
                                    <option value="">Please make a choice</option>
                                    <?php
                                    $departments = [
                                        "Operation Department", "Admin Department", "Billing Department", "Warehouse Department",
                                        "Accounting Department"
                                    ];

                                    $selectedDepartment = $_POST['department'] ?? '';

                                    foreach ($departments as $department) {
                                        $selected = ($selectedDepartment == $department) ? 'selected' : '';
                                        echo "<option value=\"$department\" $selected>$department</option>";
                                    }
                                    ?>
                                </select>
                                <?php echo $errors['department'] ?? ''; ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="designation" class="col-sm-2 control-label">Position</label>
                            <div class="col-sm-8">
                                <select class="form-control" name="designation" id="designation" required>
                                    <option value="">Please make a choice</option>
                                </select>
                                <?php echo $errors['designation'] ?? ''; ?>
                            </div>
                        </div>

                        <script>
                            const departmentDesignations = {
                                "Operation Department": ["Collectors", "Drivers", "Pesticide Handler", "Operation Technician"],
                                "Admin Department": ["Liaison Officer", "Admin Officer", "HR Manager"],
                                "Billing Department": ["Billing Officer"],
                                "Warehouse Department": ["Supervisor/Team"],
                                "Accounting Department": ["General Manager", "Operation Manager", "Finance Supervisor", "Accounting Staff"]
                            };

                            document.getElementById("department").addEventListener("change", function () {
                                const department = this.value;
                                const designationSelect = document.getElementById("designation");

                                designationSelect.innerHTML = '<option value="">Please make a choice</option>'; // Reset options

                                if (departmentDesignations[department]) {
                                    departmentDesignations[department].forEach(function (designation) {
                                        let option = document.createElement("option");
                                        option.value = designation;
                                        option.textContent = designation;
                                        designationSelect.appendChild(option);
                                    });
                                }
                            });
                        </script>
                    <div class="form-group">
                        <label for="employment_type" class="col-sm-2 control-label">Employee Type</label>
                        <div class="col-sm-8">
                            <select class="form-control" id="employment_type" name="employment_type">
                                <option value="">Please make a choice</option>
                                <option <?php echo $_POST['employment_type'] == 'Part-time employee' ? 'selected' : ''; ?>
                                    value="Part-time employee">Part-time employee</option>
                                <option <?php echo $_POST['employment_type'] == 'Intern' ? 'selected' : ''; ?>
                                    value="Intern">Intern</option>
                                <option <?php echo $_POST['employment_type'] == 'Holiday worker' ? 'selected' : ''; ?>
                                    value="Holiday worker">Holiday worker</option>
                                <option <?php echo $_POST['employment_type'] == 'Permanent position' ? 'selected' : ''; ?>
                                    value="Permanent position">Permanent position</option>
                            </select>
                            <?php echo $errors['employment_type']; ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="joining_date" class="col-sm-2 control-label">Joining Date</label>
                        <div class="col-sm-5">
                            <div class="input-group">
                                <input type="text" class="form-control" id="joining_date" name="joining_date"
                                    placeholder="MM/DD/YYYY" value="<?php echo $_POST['joining_date']; ?>" required />
                                <span class="input-group-addon">
                                    <i class="glyphicon glyphicon-calendar"></i>
                                </span>
                            </div>
                            <?php echo $errors['joining_date']; ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="bloodgrp" class="col-sm-2 control-label">Blood Group</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="bloodgrp" name="bloodgrp"
                                placeholder="Blood Group" value="<?php echo $_POST['bloodgrp']; ?>" required />
                            <?php echo $errors['bloodgrp']; ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="photo" class="col-sm-2 control-label">Photograph</label>
                        <div class="col-sm-8">
                            <input type="file" class="form-control" id="photo" name="photo" accept="image/*"
                                placeholder="Photograph" required style="height:auto" />
                            <?php echo $errors['photo']; ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password" class="col-sm-2 control-label">Password</label>
                        <div class="col-sm-4">
                            <input type="password" class="form-control" id="emp_password" name="emp_password"
                                placeholder="Password" value="<?php echo $_POST['emp_password']; ?>" required />
                            <?php echo $errors['emp_password']; ?>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-primary" name="submit">Submit</button>
                </div>
                <br><br>
            </form>
        </div>
    </div>

    <script src="<?php echo BASE_URL; ?>plugins/jQuery/jquery-2.2.3.min.js"></script>
    <script src="<?php echo BASE_URL; ?>bootstrap/js/bootstrap.min.js"></script>
    <script src="<?php echo BASE_URL; ?>plugins/datepicker/bootstrap-datepicker.js"></script>
    <script type="text/javascript">
        $('#dob, #joining_date').datepicker();
    </script>
</body>

</html>