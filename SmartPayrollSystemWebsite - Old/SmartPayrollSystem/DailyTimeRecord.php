<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Daily Time Record</title>
    <style>
        .filter-container {
            display: flex;
            gap: 10px;
            margin-bottom: 15px;
        }

        .filter-container select {
            padding: 5px;
            font-size: 16px;
        }

        .time-record-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .time-record-table th,
        .time-record-table td {
            padding: 8px;
            border: 1px solid #ddd;
            text-align: left;
        }

        .time-record-table th {
            background-color: #f4f4f4;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <h2>Daily Time Record</h2>
    <div class="filter-container">
        <label for="month">Month:</label>
        <select id="month">
            <option value="">All</option>
            <option value="1">January</option>
            <option value="2">February</option>
            <option value="3">March</option>
            <option value="4">April</option>
            <option value="5">May</option>
            <option value="6">June</option>
            <option value="7">July</option>
            <option value="8">August</option>
            <option value="9">September</option>
            <option value="10">October</option>
            <option value="11">November</option>
            <option value="12">December</option>
        </select>

        <label for="year">Year:</label>
        <select id="year">
            <!-- Populate years dynamically through JavaScript -->
        </select>

        <button onclick="fetchTimeRecords()">Filter</button>
    </div>

    <table class="time-record-table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Check-in Time</th>
                <th>Check-out Time</th>
            </tr>
        </thead>
        <tbody id="timeRecordsBody">
            <!-- Time records will be dynamically inserted here -->
        </tbody>
    </table>
</body>

</html>