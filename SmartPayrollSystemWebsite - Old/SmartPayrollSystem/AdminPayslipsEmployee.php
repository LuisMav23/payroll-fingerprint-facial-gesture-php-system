<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Generate Payslip</title>
    <style>
        /* Styling for the page */
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
            color: #333;
            margin: 0;
            padding: 0;
        }

        h2 {
            text-align: center;
            font-size: 2rem;
            margin-bottom: 20px;
        }

        /* Date filter styling */
        .date-filter {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-bottom: 20px;
        }

            .date-filter input,
            .date-filter button {
                padding: 10px;
                font-size: 1rem;
                border-radius: 5px;
                border: 1px solid #ddd;
            }

            .date-filter button {
                background-color: #4CAF50;
                color: #fff;
                cursor: pointer;
            }

                .date-filter button:hover {
                    background-color: #45a049;
                }

        /* Table styling */
        table {
            width: 80%;
            margin: 0 auto;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }

        /* Form fields styling */
        .form-container {
            width: 80%;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

            .form-container input[type="text"],
            .form-container input[type="number"],
            .form-container input[type="date"] {
                width: 100%;
                padding: 10px;
                margin: 10px 0;
                font-size: 1rem;
                border: 1px solid #ddd;
                border-radius: 5px;
            }

            .form-container button {
                padding: 12px 20px;
                background-color: #4CAF50;
                color: #fff;
                border: none;
                border-radius: 5px;
                cursor: pointer;
            }

                .form-container button:hover {
                    background-color: #45a049;
                }
        /* Style for the radio buttons */
        .cutoff-selection {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-bottom: 20px;
        }

            .cutoff-selection label {
                font-size: 1rem;
                font-weight: bold;
            }
    </style>
</head>
<body>

    <h2>Generate Payslip for Employee</h2>
    <!-- Display Employee Name -->
    <div id="employee-name-container">
        <h3 id="employee-name">Loading employee name...</h3>

    </div>
    <h3 id="employee-rate">Loading employee rate...</h3>

    <h3 id="employee-maxicare">Loading employee maxicaretype...</h3>

    <div class="cutoff-selection">
        <label>
            <input type="radio" name="cutoff" value="first" id="first-cutoff" checked> First Cutoff
        </label>
        <label>
            <input type="radio" name="cutoff" value="second" id="second-cutoff"> Second Cutoff
        </label>
    </div>

    <!-- Date Filter Section -->
    <div class="date-filter">
        <input type="date" id="from-date" placeholder="From Date">
        <input type="date" id="to-date" placeholder="To Date">
        <button onclick="applyDateFilter()">Apply Filter</button>
    </div>

    <!-- Attendance Table Section -->
    <table id="attendance-table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Status</th>
                <th>Hours Worked</th>
                <th>Overtime Hours</th>
            </tr>
        </thead>
        <tbody>
            <!-- Attendance data will be populated here via JavaScript -->
        </tbody>
    </table>

    <!-- Payslip Generation Form Section -->
    <div class="form-container">
        <h3>Generate Payslip</h3>
        <label for="pay-date">Pay Date:</label>
        <input type="date" id="pay-date" required>

        <label for="gross-pay">Gross Pay:</label>
        <input type="number" id="gross-pay" step="0.01" placeholder="Enter Gross Pay" required>

        <!-- Deduction Fields (These will be calculated automatically) -->
        <label for="sss">SSS:</label>
        <input type="number" id="sss" step="0.01" placeholder="SSS Deduction" readonly>

        <label for="pagibig">PagIbig:</label>
        <input type="number" id="pagibig" step="0.01" placeholder="PagIbig Deduction" readonly>

        <label for="philhealth">PhilHealth:</label>
        <input type="number" id="philhealth" step="0.01" placeholder="PhilHealth Deduction" readonly>

        <label for="tax">Tax:</label>
        <input type="number" id="tax" step="0.01" placeholder="Tax Deduction" readonly>

        <label for="tax">Maxicare:</label>
        <input type="number" id="maxicare" step="0.01" placeholder="Maxicare Deduction" readonly>

        <label for="SalaryLoan">Salary Loan:</label>
        <input type="number" id="salary-loan" step="0.01" placeholder="Enter Salary Loan">

        <label for="OvertimeHours">Overtime Hours:</label>
        <input type="number" id="overtimehours" step="0.01" placeholder="Enter Overtime Hours">

        <label for="OvertimePay">Overtime Pay:</label>
        <input type="number" id="overtimepay" step="0.01" placeholder="Enter Overtime Pay">

        <label for="net-pay">Net Pay:</label>
        <input type="number" id="net-pay" step="0.01" placeholder="Net Pay" readonly>

        <button onclick="generatePayslip()">Generate Payslip</button>
    </div>

</body>
</html>