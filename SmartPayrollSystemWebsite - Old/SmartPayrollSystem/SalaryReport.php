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

        /* Style the employee info */
        .employee-info {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 15px;
            border: 1px solid #ddd;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            margin-bottom: 30px;
        }

            .employee-info p {
                margin: 0;
            }

            .employee-info strong {
                color: #333;
                font-size: 16px;
            }

            .employee-info .label {
                font-weight: bold;
                color: #333;
            }

            .employee-info .value {
                font-size: 18px;
                color: #555;
            }

        /* Style the monthly summary table */
        .monthly-summary table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .monthly-summary th, .monthly-summary td {
            padding: 12px;
            text-align: center;
            border: 1px solid #ddd;
        }

        .monthly-summary th {
            background-color: #f2f2f2;
            color: #333;
        }

        .monthly-summary tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .monthly-summary tr:hover {
            background-color: #f1f1f1;
        }

        .monthly-summary td {
            font-size: 16px;
        }
    </style>
</head>
<body>

    <div class="salary-report-container">
        <header>
            <h1>PAYROLL ANNUAL SUMMARY REPORT </h1>

            <!-- Year Filter -->
            <label for="year-filter">Select Year: </label>
            <select id="year-filter" onchange="salaryreportload()">
                <!-- Dynamic years will be populated here -->
            </select>
        </header>

        <!-- Employee Info Section -->
        <section class="employee-info">
            <p><span class="label">Name of Employee:</span> <span class="value" id="employee-name"></span></p>
            <p><span class="label">Avg. Daily Hours:</span> <span class="value" id="avg-daily-hours"></span></p>
            <p><span class="label">Pay Type:</span> <span class="value" id="employee-type-rate"></span></p>
            <p><span class="label">Total Regular Hours Worked:</span> <span class="value" id="total-regular-hours"></span></p>
            <p><span class="label">Rate:</span> <span class="value" id="rate"></span></p>
            <p><span class="label">Total Overtime Hours Worked:</span> <span class="value" id="total-overtime-hours"></span></p>
            <p><span class="label">Total Wage:</span> <span class="value" id="total-wage"></span></p>
            <p><span class="label">Maxicare Type:</span> <span class="value" id="maxicare-type"></span></p>

        </section>

        <!-- Monthly Summary Table -->
        <section class="monthly-summary">
            <table>
                <thead>
                    <tr>
                        <th>Months</th>
                        <th>Regular Hours</th>
                        <th>Overtime Hours</th>
                        <th>Total Worked Hours</th>
                        <th>Total Wage</th>
                    </tr>
                </thead>
                <tbody id="monthly-summary-body">
                    <!-- Monthly summary will be inserted here dynamically -->
                </tbody>
            </table>
        </section>
    </div>

</body>
</html>
