<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register New Employee</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
        }

        .container {
            max-width: 800px;
            margin: auto;
            background: #fff;
            padding: 30px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        .message {
            text-align: center;
            color: green;
            margin-top: 20px;
        }

        .note {
            font-size: 0.9em;
            color: #555;
            margin-top: 15px;
            text-align: center;
        }

        .form-control:focus {
            box-shadow: none !important;
        }
    </style>
</head>

<body>

    <div class="container">
        <h1>Register New Employee</h1>

        <form method="POST" enctype="multipart/form-data" action="registerEmployee.php">
            <div class="form-group">
                <label for="first-name">First Name:</label>
                <input type="text" class="form-control" id="first-name" name="firstName">
            </div>

            <div class="form-group">
                <label for="middle-name">Middle Name:</label>
                <input type="text" class="form-control" id="middle-name" name="middleName">
            </div>

            <div class="form-group">
                <label for="last-name">Last Name:</label>
                <input type="text" class="form-control" id="last-name" name="lastName">
            </div>

            <div class="form-group">
                <label for="birthdate">Birthdate:</label>
                <input type="date" class="form-control" id="birthdate" name="birthdate">
            </div>

            <div class="form-group">
                <label for="age">Age:</label>
                <input type="number" class="form-control" id="employee-age" name="age">
            </div>

            <div class="form-group">
                <label for="status">Status:</label>
                <select class="form-control" id="status" name="status">
                    <option value="Single">Single</option>
                    <option value="Married">Married</option>
                    <option value="Divorced">Divorced</option>
                    <option value="Widowed">Widowed</option>
                    <option value="Separated">Separated</option>
                </select>
            </div>

            <div class="form-group">
                <label for="sex">Sex:</label>
                <select class="form-control" name="sex">
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email">
            </div>

            <div class="form-group">
                <label for="phone">Phone:</label>
                <input required type="tel" class="form-control" name="phone" placeholder="Enter your contact number"
                    required value="63" type="tel" pattern="\639\d{9}"
                    oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 12); if (!this.value.startsWith('63')) this.value = '63' + this.value.slice(2);">

            </div>

            <div class="form-group">
                <label for="barangay">Barangay:</label>
                <input type="text" class="form-control" id="barangay" name="barangay">
            </div>

            <div class="form-group">
                <label for="streetnumber">Street Number/Building/House No:</label>
                <input type="text" class="form-control" id="streetnumber" name="streetnumber">
            </div>

            <div class="form-group">
                <label for="city">City:</label>
                <input type="text" class="form-control" id="city" name="city">
            </div>

            <div class="form-group">
                <label for="postalzipcode">Postal/Zip Code:</label>
                <input type="text" class="form-control" id="postalzipcode" name="postalzipcode">
            </div>
            <div class="form-group">
                <label for="position">Position:</label>
                <select class="form-control" name="position">
                    <option value="">Select Position</option>
                    <?php
                    include 'db.php';

                    $sql = "SELECT PositionID, PositionName FROM Position";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo '<option value="' . $row['PositionID'] . '">' . $row['PositionName'] . '</option>';
                        }
                    } else {
                        echo '<option value="">No positions available</option>';
                    }

                    $conn->close();
                    ?>
                </select>
            </div>


            <div class="form-group">
                <label for="maxicaretype">Maxicare:</label>
                <select class="form-control" id="maxicaretype" name="maxicaretype">
                    <option value="not_applicable">Not Applicable</option>
                    <option value="silver">Silver</option>
                    <option value="platinum">Platinum</option>
                    <option value="gold">Gold</option>
                    <option value="platinum_plus">Platinum Plus</option>
                </select>
            </div>

            <div class="form-group">
                <label for="salaryloanoption">Avail Salary Loan?</label>
                <select class="form-control" id="salaryloanoption" name="salaryloanoption">
                    <option value="No">No</option>
                    <option value="Yes">Yes</option>
                </select>
            </div>

            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" class="form-control" id="username" name="username">
            </div>

            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" class="form-control" id="password" name="password">
            </div>

            <div class="form-group">
                <label for="role">Role:</label>
                <select class="form-control" id="role" name="role">
                    <option value="Admin">Admin</option>
                    <option value="Employee">Employee</option>
                </select>
            </div>

            <div class="form-group">
                <label for="faceData">Upload Face Data (15 images):</label>
                <input type="file" class="form-control" id="faceData" name="faceData[]" accept="image/*" multiple>
            </div>

            <button type="submit" class="btn btn-success btn-block">Register</button>
        </form>


        <div class="note">Default leave balance will be set to 15 days.</div>
        <div class="message" id="message"></div>
    </div>

    <!-- Bootstrap JS (optional) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>