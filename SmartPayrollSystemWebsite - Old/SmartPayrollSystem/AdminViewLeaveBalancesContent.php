<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Leave Balances</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }

        .container {
            padding: 20px;
            max-width: 900px;
            margin: 0 auto;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .back-button {
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            padding: 8px 12px;
            cursor: pointer;
        }

            .back-button:hover {
                background-color: #0056b3;
            }

        h1 {
            text-align: center;
            font-size: 24px;
            margin-bottom: 20px;
        }

        .company-info {
            text-align: center;
            margin-bottom: 20px;
        }

        .details {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

            .details p {
                margin: 0;
                font-weight: bold;
            }

        table {
            width: 100%;
            border-collapse: collapse;
        }

            table th,
            table td {
                padding: 12px 15px;
                text-align: left;
                border: 1px solid #ddd;
            }

            table th {
                background-color: #f4f4f4;
            }

            table tr:hover {
                background-color: #f1f1f1;
            }

        .profile-section {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            margin-top: 20px;
        }

            .profile-section span {
                margin-left: 10px;
                font-weight: bold;
            }

        .profile-icon {
            width: 20px;
            height: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>View Leave Balances</h1>
        <div>
            Welcome, <span id="username">[Username]</span>
            <button class="logout-btn" onclick="logout()">Logout</button>
        </div>
    </div>
    <div class="container">


        <div class="company-info">
            <h2>SMITH BROTHER CORPORATION</h2>
        </div>

        <div class="details">
            <p>Employee Name:  <span id="employee-name"></span> </p>
            <p>Date of Employment: <span id="employment-date-up"></span> </p>
            <p>Position: <span id="employment-position"></span> </p>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Employee Name</th>
                    <th>Email</th>
                    <th>Leave Type</th>
                    <th>Days Available</th>
                </tr>
            </thead>
            <tbody id="leaveBalancesBody">
                <!-- Data rows will be rendered here dynamically -->
            </tbody>
        </table>
    </div>
</body>
</html>
