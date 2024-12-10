<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPEAR - Smart Payroll System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        /* Header */
        .header {
            background-color: #000;
            padding: 20px;
            color: white;
            text-align: left;
        }

        .header h1 {
            margin: 0;
            font-size: 30px;
            padding-left: 20px;
        }

        /* Navbar */
        .navbar {
            background-color: #2c3e50;
            overflow: hidden;
            padding: 10px;
        }

        .navbar img {
            width: 50px;
            /* Adjust logo size */
            height: 50px;
            margin-right: 20px;
            /* Space between logo and links */
            margin-left: 20px;
        }


        .navbar a {
            float: right;
            padding: 12px;
            text-decoration: none;
            color: #fff;
            font-weight: bold;
            border-radius: 5px;
        }

        .navbar a:hover {
            background-color: #ddd;
        }

        .navbar a.active {
            background-color: #aaa;
            color: #fff;
        }

        /* Main Content */
        .main-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            height: 80vh;
            gap: 40px;
            background-color: #ffffff;
        }

        .main-content img {
            width: 45%;
            max-width: 450px;
            /* Adjust image size */
        }

        .welcome-text {
            max-width: 50%;
            text-align: left;
        }

        .welcome-text h2 {
            font-size: 40px;
            color: #333;
            margin-bottom: 20px;
        }

        .welcome-text p {
            font-size: 18px;
            color: #333;
            line-height: 1.6;
            margin-bottom: 20px;
        }

        .cta {
            text-align: left;
        }

        .cta button {
            padding: 14px 40px;
            font-size: 18px;
            background-color: #5c6bc0;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }

        .cta button:hover {
            background-color: #3f4f8b;
        }

        /* Adjust illustration styling */
        .illustration {
            width: 50%;
            height: 50%;
            margin-left: auto;
        }

        /* Media Query for smaller screens */
        @media only screen and (max-width: 768px) {
            .navbar {
                flex-direction: column;
                align-items: center;
            }

            .main-content {
                flex-direction: column;
                text-align: center;
                gap: 20px;
            }

            .main-content img {
                width: 70%;
                /* Adjust image size for small screens */
                max-width: 300px;
            }

            .welcome-text {
                max-width: 90%;
            }

            .cta button {
                padding: 12px 30px;
                font-size: 16px;
            }
        }

        /* Media Query for very small screens */
        @media only screen and (max-width: 480px) {
            .header h1 {
                font-size: 24px;
            }

            .welcome-text h2 {
                font-size: 30px;
            }

            .welcome-text p {
                font-size: 16px;
            }
        }
    </style>
</head>

<body>

    <!-- Navigation Bar -->
    <div class="navbar">
        <img src="Resources/logo.png" alt="Logo"> <!-- Add your logo here -->
        <a href="AdminLoginPage.php">Admin Login</a>
        <a href="EmployeeLoginPage.php">Employee Login</a>
        <a href="index.php" class="active">Home</a>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Welcome Text -->
        <div class="welcome-text">
            <h2>SPEAR</h2>
            <p>Smart Payroll with Enhanced AI Recognition</p>
            <p>Empower your HR with an automated payroll management system.</p>
            <p>Our Smart Payroll System adapts to your business needs and grows with you.</p>
        </div>

        <!-- Illustration -->
        <div class="illustration">
            <img src="Resources/img1.png" alt="Smart Payroll Illustration">
        </div>
    </div>

</body>

</html>