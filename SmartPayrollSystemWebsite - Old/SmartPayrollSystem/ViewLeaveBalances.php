<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Leave Balances</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        h2 {
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        tr:hover {
            background-color: #f1f1f1;
        }
    </style>
</head>
<body>
    <h2>Leave Balances</h2>

    <table id="leave-balances-table">
        <thead>
            <tr>
                <th>Leave Type</th>
                <th>Days Available</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="2">Loading...</td>
            </tr>
        </tbody>
    </table>

    <h2>Leave Filed</h2>
    <table id="leave-filed-table">
        <thead>
            <tr>
                <th>Leave Type</th>
                <th>Leave Start Date</th>
                <th>Leave End Date</th>
                <th>Status</th>
                <th>Reason</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="4">Loading...</td>
            </tr>
        </tbody>
    </table>

</body>
</html>
