<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payslips</title>
    <style>
        .payslip-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            padding: 20px;
        }

        .payslip-card {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            transition: box-shadow 0.3s ease;
        }

            .payslip-card:hover {
                box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
            }

        .payslip-header {
            font-size: 1.2em;
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
        }

        .payslip-detail {
            display: flex;
            justify-content: space-between;
            margin: 8px 0;
            font-size: 0.95em;
            color: #555;
        }

            .payslip-detail label {
                font-weight: bold;
                color: #222;
            }

        .filter-container {
            display: flex;
            gap: 10px;
            padding: 10px 20px;
        }
    </style>
</head>
<body>
    <h2>My Payslips</h2>
    <div class="filter-container">
        <label for="monthFilter">Month:</label>
        <select id="monthFilter">
            <option value="">All</option>
            <option value="01">January</option>
            <option value="02">February</option>
            <option value="03">March</option>
            <option value="04">April</option>
            <option value="05">May</option>
            <option value="06">June</option>
            <option value="07">July</option>
            <option value="08">August</option>
            <option value="09">September</option>
            <option value="10">October</option>
            <option value="11">November</option>
            <option value="12">December</option>
        </select>
        <label for="yearFilter">Year:</label>
        <select id="yearFilter">
            <option value="">All</option>
            <!-- JavaScript will populate recent years here -->
        </select>
        <button onclick="applyPayslipFilter()">Filter</button>
    </div>
    <div id="payslipContainer" class="payslip-container">
        <!-- Payslip cards will be dynamically inserted here -->
    </div>
</body>
</html>
