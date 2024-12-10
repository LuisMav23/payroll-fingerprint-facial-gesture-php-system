<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salary Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .salary-report-container {
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }

        header {
            text-align: center;
            margin-bottom: 30px;
        }

        /* Style the monthly summary table */
        .admin-monthly-summary table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .admin-monthly-summary th, .admin-monthly-summary td {
            padding: 12px;
            text-align: center;
            border: 1px solid #ddd;
        }

        .admin-monthly-summary th {
            background-color: #f2f2f2;
            color: #333;
        }

        .admin-monthly-summary tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .admin-monthly-summary tr:hover {
            background-color: #f1f1f1;
        }

        .admin-monthly-summary td {
            font-size: 16px;
        }
    </style>
</head>
<body>

    <div class="salary-report-container">
        <header>
            <h1>PAYROLL ANNUAL SUMMARY REPORT <span class="year" id="current-year"></span></h1>

            <!-- Year Filter -->
            <label for="year-filter">Select Year: </label>
            <select id="year-filter" onchange="loadSalaryReport()">
                <!-- Dynamic years will be populated here -->
            </select>
        </header>

        <!-- Monthly Summary Table -->
        <section class="admin-monthly-summary">
            <table>
                <thead>
                    <tr>
                        <th>Month</th> <!-- New column for Month -->
                        <th>Regular Hours</th>
                        <th>Overtime Hours</th>
                        <th>Total Worked Hours</th>
                        <th>Total Wage</th>
                    </tr>
                </thead>
                <tbody id="salary-report-body">
                    <!-- Monthly summary will be inserted here dynamically -->
                </tbody>
            </table>
        </section>
    </div>

</body>
</html>
