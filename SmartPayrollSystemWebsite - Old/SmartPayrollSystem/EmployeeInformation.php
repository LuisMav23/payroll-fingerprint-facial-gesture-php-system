<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Employee Information</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 600px;
            margin: 30px auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

            .container h2 {
                color: #333;
                text-align: center;
                margin-bottom: 1em;
            }

        .info-list {
            list-style: none;
            padding: 0;
            margin: 0;
            font-size: 1em;
            color: #333;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #ddd;
        }

            .info-item:last-child {
                border-bottom: none;
            }

        .label {
            font-weight: bold;
            color: #555;
        }

        .value {
            color: #333;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Employee Information</h2>
        <ul class="info-list">
            <li class="info-item">
                <span class="label">Employee ID:</span>
                <span class="value" id="employeeID">Loading...</span>
            </li>
            <li class="info-item">
                <span class="label">Full Name:</span>
                <span class="value" id="fullName">Loading...</span>
            </li>
            <li class="info-item">
                <span class="label">Age:</span>
                <span class="value" id="age">Loading...</span>
            </li>
            <li class="info-item">
                <span class="label">Status:</span>
                <span class="value" id="status">Loading...</span>
            </li>
            <li class="info-item">
                <span class="label">Email:</span>
                <span class="value" id="email">Loading...</span>
            </li>
            <li class="info-item">
                <span class="label">Phone:</span>
                <span class="value" id="phone">Loading...</span>
            </li>
            <li class="info-item">
                <span class="label">Address:</span>
                <span class="value" id="address">Loading...</span>
            </li>
            <li class="info-item">
                <span class="label">MaxicareType:</span>
                <span class="value" id="maxicare">Loading...</span>
            </li>
            <li class="info-item">
                <span class="label">Salary:</span>
                <span class="value" id="salary">Loading...</span>
            </li>
            <li class="info-item">
                <span class="label">Department:</span>
                <span class="value" id="department">Loading...</span>
            </li>
            <li class="info-item">
                <span class="label">Position:</span>
                <span class="value" id="position">Loading...</span>
            </li>
        </ul>
    </div>
</body>
</html>
