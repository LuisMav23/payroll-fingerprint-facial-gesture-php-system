<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            display: flex;
            height: 100vh;
            background-color: #fff;
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        .dashboard-container {
            display: flex;
            justify-content: space-around;
            gap: 20px;
            margin-top: 20px;
        }

        .card {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 250px;
        }

            .card h3 {
                font-size: 18px;
                color: #333;
                margin: 10px 0;
                font-weight: bold;
            }

            .card p {
                font-size: 36px;
                font-weight: bold;
                margin: 5px 0;
                color: #000;
            }

            .card img {
                width: 50px;
                height: 50px;
                margin-top: 10px;
            }
    </style>
</head>
<body>
    <div class="header">
        <h1>Admin Portal</h1>
        <div>
            Welcome, <span id="username">[Username]</span>
            <button class="logout-btn" onclick="logout()">Logout</button>
        </div>
    </div>
    <div class="dashboard-container">
        <!-- Total Employees Card -->
        <div class="card">
            <h3>Total Employees</h3>
            <p id="total-employees">2</p>
            <img src="Resources/group.png" alt="Total Employees Icon">
        </div>

        <!-- On Time Today Card -->
        <div class="card">
            <h3>On Time Today</h3>
            <p id="on-time-today">0</p>
            <img src="Resources/on-time-icon.png" alt="On Time Icon">
        </div>

        <!-- Late Today Card -->
        <div class="card">
            <h3>Late Today</h3>
            <p id="late-today">0</p>
            <img src="Resources/delay-icon.png" alt="Late Today Icon">
        </div>
    </div>
</body>
</html>
