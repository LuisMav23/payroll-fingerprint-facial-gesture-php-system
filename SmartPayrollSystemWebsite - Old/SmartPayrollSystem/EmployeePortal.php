<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Portal</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            display: flex;
            height: 100vh;
            background-color: #fff;
        }

        /* Sidebar Styling */
        .sidebar {
            width: 220px;
            background-color: #2d3e50;
            color: #fff;
            padding: 10px;
            display: flex;
            flex-direction: column;
        }

        .nav-item {
            margin-bottom: 10px;
        }

        .nav-item strong {
            cursor: pointer;
            padding: 8px 10px;
            display: block;
            color: #fff;
            background-color: #2d3e50;
            border: none;
            text-align: left;
        }

        .nav-item strong:hover {
            background-color: #1a252f;
        }

        .dropdown-content {
            display: none;
            flex-direction: column;
            padding-left: 10px;
        }

        .dropdown-content a {
            text-decoration: none;
            color: #fff;
            padding: 5px;
            background-color: #3b4d61;
            margin: 2px 0;
            border-radius: 4px;
        }

        .dropdown-content a:hover {
            background-color: #1a252f;
        }

        .nav-item.active .dropdown-content {
            display: flex;
        }

        .content {
            flex-grow: 1;
            padding: 20px;
            background-color: #f5f5f5;
        }

        /* Header Styling */
        .header {
            background-color: #fff;
            color: #000000;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logout-btn {
            background-color: #ff4d4d;
            color: #fff;
            border: none;
            padding: 8px 16px;
            cursor: pointer;
            border-radius: 4px;
        }

        .logout-btn:hover {
            background-color: #ff0000;
        }

        .dashboard-cards {
            display: flex;
            gap: 20px;
            justify-content: space-around;
            margin-top: 20px;
        }

        .card {
            flex: 1;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
            font-size: 18px;
        }

        .logo-container {
            text-align: center;
            margin-bottom: 20px;
        }

        .logo {
            max-width: 50%;
            height: auto;
            border-radius: 10px;
        }

        .triangle {
            color: #000000;
            float: right;
            font-size: 12px;
            margin-left: 5px;
            transition: transform 0.3s ease;
        }

        .nav-item.active .triangle {
            transform: rotate(180deg);
        }

        .section-divider {
            height: 1px;
            background-color: #3b4d61;
            margin: 5px 0;
            width: 100%;
        }
    </style>
</head>

<body>
    <!-- Sidebar Section -->
    <div class="sidebar">
        <div class="logo-container">
            <img src="Resources/logo.png" alt="Employee Logo" class="logo">
        </div>

        <div class="nav-item">
            <div class="section-divider"></div>
            <strong onclick="toggleDropdown(this)">
                My Account
                <span class="triangle">&#9660;</span>
            </strong>
            <div class="dropdown-content">
                <a href="EmployeeInformation.php" data-page="EmployeeInformation.php">Profile</a>
            </div>
            <div class="section-divider"></div>
        </div>

        <div class="nav-item">
            <div class="section-divider"></div>
            <strong onclick="toggleDropdown(this)">
                Leave
                <span class="triangle">&#9660;</span>
            </strong>
            <div class="dropdown-content">
                <a href="ViewLeaveBalances.php" data-page="ViewLeaveBalances.php">View Leave Balances</a>
                <a href="FileLeave.php" data-page="FileLeave.php">File Leave</a>
            </div>
            <div class="section-divider"></div>
        </div>

        <div class="nav-item">
            <div class="section-divider"></div>
            <strong onclick="toggleDropdown(this)">
                Payroll
                <span class="triangle">&#9660;</span>
            </strong>
            <div class="dropdown-content">
                <a href="PaySlips.php" data-page="PaySlips.php">PaySlips</a>
                <a href="SalaryReport.php" data-page="SalaryReport.php">Salary Report</a>
            </div>
            <div class="section-divider"></div>
        </div>

        <div class="nav-item">
            <div class="section-divider"></div>
            <strong onclick="toggleDropdown(this)">
                Time
                <span class="triangle">&#9660;</span>
            </strong>
            <div class="dropdown-content">
                <a href="DailyTimeRecord.php" data-page="DailyTimeRecord.php">Daily Time Record</a>
            </div>
            <div class="section-divider"></div>
        </div>
    </div>

    <!-- Main Content Section -->
    <div class="content">
        <div class="header">
            <h2>Employee Dashboard</h2>
            <div>
                Welcome, <span id="username">[Username]</span>
                <button class="logout-btn" onclick="logout()">Logout</button>
            </div>
        </div>

        <div id="main-content">
            <div class="dashboard-cards">
                <div class="card" id="total-leave">TOTAL LEAVE</div>
                <div class="card" id="approved-leave">APPROVED LEAVE</div>
                <div class="card" id="canceled-leave">CANCELED LEAVE</div>
            </div>
        </div>
    </div>


    <script>

        function toggleDropdown(element) {
            const navItem = element.parentElement;
            navItem.classList.toggle('active');
        }
        // Fetch and display username
        function fetchUsername() {
            fetch('getUsername.php')
                .then(response => response.text())
                .then(username => {
                    document.getElementById('username').textContent = username;
                })
                .catch(error => console.error('Error fetching username:', error));
        }


        // Combined window.onload function
        window.onload = function () {
            fetchUsername();
            const hash = window.location.hash.substring(1); // Get hash without '#'
            const defaultPage = 'EmployeeInformation.php';
            loadContent(hash || defaultPage); // Load content based on hash or default page
        };

        // Load content based on the page name
        function loadContent(page) {
            const xhttp = new XMLHttpRequest();
            xhttp.onload = function () {
                document.getElementById("main-content").innerHTML = this.responseText;

                // Check if the loaded page is ViewLeaveBalances.php
                if (page === 'ViewLeaveBalances.php') {
                    fetchLeaveBalances(); // Call fetchLeaveBalances when this page is loaded
                    fetchLeaveFiled();
                } else if (page === 'FileLeave.php') {
                    // Add event listener for the leave form submission
                    //document.getElementById('leaveForm').addEventListener('submit', submitLeaveRequest);
                    initializeFileLeavePage(); // Call FileLeave-specific setup
                } else if (page === 'EmployeeInformation.php') {
                    loadEmployeeInformation();
                } else if (page === 'DailyTimeRecord.php') {
                    loadDailyTimeRecordPage();
                } else if (page === 'PaySlips.php') {
                    populateYearFilter();
                    setTimeout(() => {
                        console.log("payslipContainer exists:", document.getElementById('payslipContainer') !== null);
                        applyPayslipFilter();
                    }, 100); // Short delay to ensure DOM is updated
                } else if (page === 'SalaryReport.php') {
                    const employeeId = sessionStorage.getItem('employee_id');
                    const year = new Date().getFullYear(); // Get current year
                    fetchEmployeeDetails(employeeId); // Fetch employee details
                    fetchPayrollData(employeeId, year); // Fetch payroll data
                }
            }
            xhttp.open("GET", page, true);
            xhttp.send();
        }

        function fetchLeaveBalances() {
            console.log("Fetching leave balances..."); // Add this line
            fetch('getLeaveBalances.php')
                .then(response => response.json())
                .then(data => {
                    // Call the function to display leave balances in the loaded page
                    displayLeaveBalances(data);
                })
                .catch(error => {
                    console.error('Error fetching leave balances:', error);
                });
        }

        // Handle link clicks for AJAX navigation
        document.querySelectorAll('.dropdown-content a').forEach(link => {
            link.addEventListener('click', function (event) {
                event.preventDefault(); // Prevent default link behavior
                const page = this.getAttribute('data-page'); // Get the page to load
                loadContent(page); // Load the content dynamically
                window.location.hash = page; // Update the URL hash
            });
        });

        // Function to display leave balances
        function displayLeaveBalances(data) {
            const tableBody = document.getElementById('leave-balances-table').getElementsByTagName('tbody')[0];
            tableBody.innerHTML = ''; // Clear the loading message

            // Check if there are any leave balances
            if (data.length === 0) {
                const row = tableBody.insertRow();
                const cell = row.insertCell(0);
                cell.colSpan = 2;
                cell.textContent = 'No leave balances found.';
            } else {
                // Populate the table with leave balances
                data.forEach(item => {
                    const row = tableBody.insertRow();
                    row.insertCell(0).textContent = item.LeaveType;
                    row.insertCell(1).textContent = item.DaysAvailable;
                });
            }
        }

        function fetchLeaveFiled() {
            console.log("Fetching leave filed..."); // Debugging line
            fetch('getLeaveFiled.php')
                .then(response => response.json())
                .then(data => {
                    displayLeaveFiled(data); // Call to display function
                })
                .catch(error => {
                    console.error('Error fetching leave filed:', error);
                });
        }

        // Function to display leave filed
        function displayLeaveFiled(data) {
            const tableBody = document.getElementById('leave-filed-table').getElementsByTagName('tbody')[0];
            tableBody.innerHTML = ''; // Clear the loading message

            // Check if there are any filed leaves
            if (data.length === 0) {
                const row = tableBody.insertRow();
                const cell = row.insertCell(0);
                cell.colSpan = 5;
                cell.textContent = 'No leave filed found.';
            } else {
                // Populate the table with filed leaves
                data.forEach(item => {
                    const row = tableBody.insertRow();
                    row.insertCell(0).textContent = item.LeaveType;
                    row.insertCell(1).textContent = item.LeaveStartDate;
                    row.insertCell(2).textContent = item.LeaveEndDate;
                    row.insertCell(3).textContent = item.indicator;
                    row.insertCell(4).textContent = item.Reason;
                });
            }
        }

        // Function to handle the leave form submission
        function submitLeaveRequest(event) {
            event.preventDefault(); // Prevent form from refreshing the page
            const form = document.querySelector('#leaveForm'); // Select the leave form
            const formData = new FormData(form); // Create FormData object from the form

            // Send data to PHP script
            fetch('leaveFile.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.text()) // Expecting plain text response
                .then(data => {
                    const responseMessage = document.getElementById('responseMessage');
                    responseMessage.textContent = data; // Display response message

                    // Check if the response includes 'success'
                    if (data.includes('success')) {
                        responseMessage.style.color = "green"; // Set success color
                        form.reset(); // Clear the form after successful submission
                    } else {
                        responseMessage.style.color = "red"; // Set error color
                    }
                })
                .catch(error => {
                    console.error('Error:', error); // Log error if any
                });
        }

        // Populate the year dropdown with recent years
        function populateYearDropdown() {
            const yearSelect = document.getElementById('year');
            const currentYear = new Date().getFullYear();
            for (let year = currentYear; year >= currentYear - 5; year--) {
                const option = document.createElement('option');
                option.value = year;
                option.textContent = year;
                yearSelect.appendChild(option);
            }
        }

        // Fetch and display the employee's time records based on selected month and year
        function fetchTimeRecords() {
            const month = document.getElementById('month').value;
            const year = document.getElementById('year').value;
            const employeeId = sessionStorage.getItem('employee_id');

            if (!employeeId) {
                console.error("Employee ID is not defined.");
                return;
            }

            // Prepare the query parameters
            let queryParams = `employeeId=${employeeId}`;
            if (month) queryParams += `&month=${month}`;
            if (year) queryParams += `&year=${year}`;

            // Fetch time records from the server
            fetch(`getTimeRecords.php?${queryParams}`)
                .then(response => response.json())
                .then(data => {
                    const timeRecordsBody = document.getElementById('timeRecordsBody');
                    timeRecordsBody.innerHTML = ''; // Clear existing records

                    if (data.error) {
                        timeRecordsBody.innerHTML = `<tr><td colspan="3">${data.error}</td></tr>`;
                    } else {
                        data.forEach(record => {
                            const row = `
                                    <tr>
                                        <td>${record.Date}</td>
                                        <td>${record.CheckInTime}</td>
                                        <td>${record.CheckOutTime || 'N/A'}</td>
                                    </tr>
                                `;
                            timeRecordsBody.innerHTML += row;
                        });
                    }
                })
                .catch(error => console.error('Error fetching time records:', error));
        }

        // Initialize the year dropdown and fetch initial records when loading the page
        function loadDailyTimeRecordPage() {
            populateYearDropdown();
            fetchTimeRecords();
        }

        // Function to load and display employee information
        // Function to load and display employee information
        function loadEmployeeInformation() {
            // Check if employee_id is defined
            const employeeId = sessionStorage.getItem('employee_id');

            if (!employeeId) {
                console.error('Employee ID is not defined in session storage.');
                return;
            }

            // Fetch employee information from the server
            fetch(`getEmployeeInformation.php`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.error) {
                        console.error('Error:', data.error);
                        return;
                    }

                    // Display employee information in the designated elements
                    document.getElementById('employeeID').textContent = data.EmployeeID;
                    document.getElementById('fullName').textContent = `${data.FirstName} ${data.MiddleName || ''} ${data.LastName}`.trim();
                    document.getElementById('age').textContent = data.Age;
                    document.getElementById('status').textContent = data.Status;
                    document.getElementById('email').textContent = data.Email;
                    document.getElementById('phone').textContent = data.Phone;
                    document.getElementById('address').textContent = data.Address;
                    document.getElementById('salary').textContent = `₱${parseFloat(data.SalaryPosition).toFixed(2)}`;
                    document.getElementById('department').textContent = data.Department;
                    document.getElementById('position').textContent = data.Position;
                    document.getElementById('maxicare').textContent = data.MaxicareType;
                })
                .catch(error => console.error('Error fetching employee information:', error));
        }

        // Function to populate the year filter and set the default month and year
        function populateYearFilter() {
            const yearSelect = document.getElementById('yearFilter');
            const monthSelect = document.getElementById('monthFilter');
            const currentYear = new Date().getFullYear();
            const currentMonth = String(new Date().getMonth() + 1).padStart(2, '0');

            for (let year = currentYear; year >= currentYear - 5; year--) {
                const option = document.createElement('option');
                option.value = year;
                option.textContent = year;
                yearSelect.appendChild(option);
            }

            monthSelect.value = currentMonth;
            yearSelect.value = currentYear;
        }

        // Fetch and display payslip data with optional filters for month and year
        function fetchPayslips(month = '', year = '') {
            const url = `getPayslips.php?month=${month}&year=${year}`;
            fetch(url)
                .then(response => response.json())
                .then(data => displayPayslips(data))
                .catch(error => console.error('Error fetching payslips:', error));
        }

        function displayPayslips(payslips) {
            const payslipContainer = document.getElementById('payslipContainer');
            payslipContainer.innerHTML = '';

            if (payslips.length === 0) {
                payslipContainer.innerHTML = '<p>No payslips found.</p>';
                return;
            }

            payslips.forEach(payslip => {
                const card = document.createElement('div');
                card.className = 'payslip-card';
                card.innerHTML = `
                        <div class="payslip-header">Pay Date: ${payslip.PayDate}</div>
                        <div class="payslip-detail"><label>Employee:</label> ${payslip.EmployeeName}</div>
                        <div class="payslip-detail"><label>Department:</label> ${payslip.Department}</div>
                        <div class="payslip-detail"><label>Position:</label> ${payslip.Position}</div>
                        <div class="payslip-detail"><label>Total Hours:</label> ${payslip.TotalHours}</div>
                        <div class="payslip-detail"><label>Gross Pay:</label> ₱${parseFloat(payslip.GrossPay).toFixed(2)}</div>
                        <div class="payslip-detail"><label>Net Pay:</label> ₱${parseFloat(payslip.NetPay).toFixed(2)}</div>
                        <div class="payslip-detail"><label>Social Security:</label> ₱${parseFloat(payslip.SocialSecuritySystem).toFixed(2)}</div>
                        <div class="payslip-detail"><label>Pag-IBIG:</label> ₱${parseFloat(payslip.PagIbig).toFixed(2)}</div>
                        <div class="payslip-detail"><label>PhilHealth:</label> ₱${parseFloat(payslip.PhilHealth).toFixed(2)}</div>
                        <div class="payslip-detail"><label>SalaryLoan:</label> ₱${parseFloat(payslip.SalaryLoan).toFixed(2)}</div>
                        <div class="payslip-detail"><label>Maxicare:</label> ₱${parseFloat(payslip.Maxicare || 0).toFixed(2)}</div>
                        <div class="payslip-detail"><label>OvertimeHours:</label> ${payslip.OvertimeHours}</div>
                        <div class="payslip-detail"><label>OvertimePay:</label> ₱${parseFloat(payslip.OvertimePay).toFixed(2)}</div>
                        <div class="payslip-detail"><label>Tax:</label> ₱${parseFloat(payslip.Tax || 0).toFixed(2)}</div>
                        <button onclick="downloadPayslip(${payslip.PayrollID})">Download</button>
                    `;
                payslipContainer.appendChild(card);
            });
        }



        // Apply the filter based on selected month and year
        function applyPayslipFilter() {
            const month = document.getElementById('monthFilter').value;
            const year = document.getElementById('yearFilter').value;
            fetchPayslips(month, year);
        }

        function downloadPayslip(payrollID) {
            window.open(`downloadPayslip.php?payrollID=${payrollID}`, '_blank');
        }


        // Logout function
        function logout() {
            window.location.href = 'logout.php';
        }

        // Listen for hash changes and load content when the hash changes
        window.onhashchange = function () {
            const hash = window.location.hash.substring(1);
            if (hash) {
                loadContent(hash);
            }
        }

        //-->>
        function fetchEmployeeDetails(employeeId) {
            fetch(`getEmployeeDetails.php?employeeId=${employeeId}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.error) {
                        console.error('Error:', data.error);
                        return;
                    }

                    // Display employee information in the designated elements
                    document.getElementById('employee-name').textContent = `${data.FirstName} ${data.MiddleName || ''} ${data.LastName}`.trim();
                    document.getElementById('rate').textContent = `₱${parseFloat(data.Salary).toFixed(2)}`;
                    document.getElementById('employee-type-rate').textContent = `${data.Indicator}`;
                    document.getElementById('maxicare-type').textContent = `${data.MaxicareType}`;
                })
                .catch(error => console.error('Error fetching employee information:', error));
        }

        function fetchPayrollData(employeeId, year) {
            fetch(`getPayrollData.php?employeeId=${employeeId}&year=${year}`)
                .then(response => response.json())
                .then(data => {
                    console.log(data);  // Log the data
                    if (data.error) {
                        console.error("Error:", data.error);
                    } else {
                        // Defensive checks for numeric values
                        const totalRegularHours = data.totalRegularHours && !isNaN(data.totalRegularHours) ? data.totalRegularHours : 0;
                        const totalOvertimeHours = data.totalOvertimeHours && !isNaN(data.totalOvertimeHours) ? data.totalOvertimeHours : 0;
                        const totalWage = data.totalWage && !isNaN(data.totalWage) ? data.totalWage : 0;

                        document.getElementById('total-regular-hours').textContent = totalRegularHours.toFixed(2);
                        document.getElementById('total-overtime-hours').textContent = totalOvertimeHours.toFixed(2);
                        document.getElementById('total-wage').textContent = `₱${totalWage.toFixed(2)}`;

                        const avgDailyHours = totalRegularHours / 365;
                        document.getElementById('avg-daily-hours').textContent = avgDailyHours.toFixed(2);

                        populateMonthlySummary(data.monthlySummary);
                    }
                })
                .catch(error => console.error('Error fetching payroll data:', error));
        }

        function populateMonthlySummary(monthlyData) {
            console.log(monthlyData); // Check if this returns the expected data
            const tableBody = document.getElementById('monthly-summary-body');
            tableBody.innerHTML = '';  // Clear previous data

            let totalRegularHours = 0;
            let totalOvertimeHours = 0;
            let totalWorkedHours = 0;
            let totalWage = 0;

            if (monthlyData && monthlyData.length > 0) {
                monthlyData.forEach(month => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                                    <td>${month.month}</td>
                                    <td>${month.regularHours}</td>
                                    <td>${month.overtimeHours}</td>
                                    <td>${month.totalWorkedHours}</td>
                                    <td>₱${month.totalWage}</td>
                                `;
                    tableBody.appendChild(row);

                    // Add to totals
                    totalRegularHours += parseFloat(month.regularHours || 0);
                    totalOvertimeHours += parseFloat(month.overtimeHours || 0);
                    totalWorkedHours += parseFloat(month.regularHours || 0) + parseFloat(month.overtimeHours || 0);
                    totalWage += parseFloat(month.totalWage || 0);
                });

                // Add total row
                const totalRow = document.createElement('tr');
                totalRow.innerHTML = `
                        <td><strong>Total</strong></td>
                        <td><strong>${totalRegularHours.toFixed(2)}</strong></td>
                        <td><strong>${totalOvertimeHours.toFixed(2)}</strong></td>
                        <td><strong>${totalWorkedHours.toFixed(2)}</strong></td>
                        <td><strong>₱${totalWage.toFixed(2)}</strong></td>
                    `;
                tableBody.appendChild(totalRow);
            } else {
                const row = document.createElement('tr');
                row.innerHTML = "<td colspan='5'>No data available for this year</td>";
                tableBody.appendChild(row);
            }
        }

        //-->>FileLeave.php
        // Monitor file input for displaying file name
        function setupFileUpload() {
            const fileInput = document.getElementById('fileAttachment');
            if (fileInput) {
                fileInput.addEventListener('change', function () {
                    const fileName = this.files[0]?.name || 'No file chosen';
                    document.getElementById('fileName').textContent = fileName;
                });
            }
        }



        //-->>FileLeave.php

        // Handle leave form submission
        function setupLeaveForm() {
            const leaveForm = document.getElementById('leaveForm');
            if (leaveForm) {
                leaveForm.addEventListener('submit', function (event) {
                    event.preventDefault();
                    const formData = new FormData(leaveForm); // Gather form data including the file

                    fetch('leaveFile.php', {
                        method: 'POST',
                        body: formData
                    })
                        .then(response => response.text())
                        .then(data => {
                            const responseMessage = document.getElementById('responseMessage');
                            responseMessage.textContent = data;

                            if (data.includes('success')) {
                                responseMessage.style.color = 'green';
                                leaveForm.reset();
                                document.getElementById('fileName').textContent = 'No file chosen';
                            } else {
                                responseMessage.style.color = 'red';
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });
                });
            }
        }

        // Initialize FileLeave functionality when FileLeave.php is loaded
        function initializeFileLeavePage() {
            setupFileUpload();
            setupLeaveForm();
        }
        //--<<
    </script>

</body>

</html>