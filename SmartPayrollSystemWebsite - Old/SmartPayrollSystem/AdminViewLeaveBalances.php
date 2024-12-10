<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Leave Balances</title>
    <style>
        

        h2 {
            color: #333;
        }

        table {
            
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .error {
            color: red;
        }

        .action-btn {
            padding: 5px 10px;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 4px;
        }

        .approve-btn {
            background-color: #28a745;
        }

        .reject-btn {
            background-color: #dc3545;
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

    <div id="leaveBalancesContainer">
        <table>
            <thead>
                <tr>
                    <th>Employee Name</th>
                    <th>Position</th>
                    <th>View Leave Balances</th>
                </tr>
            </thead>
            <tbody id="leaveBalancesBody">
                <!-- Data will be inserted here via JavaScript -->
            </tbody>
        </table>
    </div>

    <h2>Pending Leave Requests</h2>

    <div id="leaveRequestsContainer">
        <table>
            <thead>
                <tr>
                    <th>Employee Name</th>
                    <th>Leave Type</th>
                    <th>Leave Start</th>
                    <th>Leave End</th>
                    <th>Reason</th>
                    <th>Status</th>
                    <th>Files (if any)</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="leaveRequestsBody">
                <!-- Leave request data will be inserted here via JavaScript -->
            </tbody>
        </table>
    </div>

</body>
</html>
