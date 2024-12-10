<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Daily Time Record</title>
    <style>
        .filter-container {
            margin-top: 50px;
            margin-bottom: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            padding: 10px;
            border: 1px solid #ddd;
        }

        th {
            background-color: #3f51b5;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .no-data {
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Daily Time Record</h1>
        <div>
            Welcome, <span id="username">[Username]</span>
            <button class="logout-btn" onclick="logout()">Logout</button>
        </div>
    </div>

    <div class="filter-container">
        <input type="search" id="search-bar" placeholder="Search Employee">
        <label for="dateFilter">Filter by Date:</label>
        <input type="date" id="dateFilter">

        <label for="statusFilter">Filter by Status:</label>
        <select id="statusFilter">
            <option value="">All</option>
            <option value="On Time">On Time</option>
            <option value="Late">Late</option>
        </select>

        <!-- New Filter Button -->
        <button onclick="applyFilters()">Filter</button>
    </div>

    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Position</th>
                <th>Date</th>
                <th>Time In</th>
                <th>Time Out</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody id="attendanceTableBody">
            <tr class="no-data">
                <td colspan="5">No records found</td>
            </tr>
        </tbody>
    </table>
</body>

</html>