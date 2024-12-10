<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Portal</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            display: flex;
            height: 100vh;
            background-color: #fff;
        }

        .sidebar {
            height: 5000vh;
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

        /* Style for the inverted triangle */
        .triangle {
            color: #000000;
            float: right;
            font-size: 12px;
            margin-left: 5px;
            transition: transform 0.3s ease;
        }

        /* Rotate triangle on dropdown active */
        .nav-item.active .triangle {
            transform: rotate(180deg);
            /* Flip the triangle */
        }

        /* Style for section dividers */
        .section-divider {
            height: 1px;
            background-color: #3b4d61;
            margin: 5px 0;
            width: 100%;
        }

        .modal {
            display: none;
            /* Hidden by default */
            position: fixed;
            z-index: 1000;
            /* On top */
            left: 0;
            top: 0;
            width: 100%;
            /* Full width */
            height: 100%;
            /* Full height */
            overflow: auto;
            /* Enable scroll if needed */
            background-color: rgba(0, 0, 0, 0.5);
            /* Black w/ opacity */
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            /* 15% from the top and centered */
            padding: 20px;
            border: 1px solid #888;
            width: 30%;
            /* Could be more or less, depending on screen size */
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            text-align: center;
        }

        .modal-actions {
            display: flex;
            justify-content: space-around;
            margin-top: 20px;
        }

        .modal-actions button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        #confirmButton {
            background-color: #28a745;
            color: white;
        }

        #cancelButton {
            background-color: #dc3545;
            color: white;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tensorflow-models/facemesh"></script>
</head>

<body>
    <!-- For Modal -->
    <div id="warningModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <p id="modalMessage"></p>
        </div>
    </div>
    <!-- For Modal -->
    <!-- Custom Modal Delete Employee -->
    <div id="customModal" class="modal" style="display: none;">
        <div class="modal-content">
            <p id="deleteModalMessage"></p>
            <div class="modal-actions">
                <button id="confirmButton">Yes</button>
                <button id="cancelButton">No</button>
            </div>
        </div>
    </div>

    <div id="updateModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <p id="modalUpdateMessage"></p>
        </div>
    </div>

    <div class="sidebar">

        <!-- Logo Section -->
        <div class="logo-container">
            <img src="Resources/logo.png" alt="Admin Logo" class="logo">
        </div>

        <div class="nav-item">
            <div class="section-divider"></div> <!-- Top divider -->
            <strong onclick="toggleDropdown(this)">
                Dashboard
                <span class="triangle">&#9660;</span> <!-- Inverted triangle -->
            </strong>
            <div class="dropdown-content">
                <a href="AdminDashboard.php" data-page="AdminDashboard.php">Dashboard</a>
            </div>
            <div class="section-divider"></div> <!-- Bottom divider -->
        </div>

        <div class="nav-item">
            <div class="section-divider"></div> <!-- Top divider -->
            <strong onclick="toggleDropdown(this)">
                Employee
                <span class="triangle">&#9660;</span> <!-- Inverted triangle -->
            </strong>
            <div class="dropdown-content">
                <a href="AdminEmployeeList.php" data-page="AdminEmployeeList.php">Employee List</a>
            </div>
            <div class="section-divider"></div> <!-- Bottom divider -->
        </div>

        <div class="nav-item">
            <div class="section-divider"></div> <!-- Top divider -->
            <strong onclick="toggleDropdown(this)">
                Payroll
                <span class="triangle">&#9660;</span> <!-- Inverted triangle -->
            </strong>
            <div class="dropdown-content">
                <a href="AdminPayslips.php" data-page="AdminPayslips.php">PaySlips</a>
                <a href="AdminSalaryReport.php" data-page="AdminSalaryReport.php">Salary Report</a>
                <a href="AdminPdfDistribution.php" data-page="AdminPdfDistribution.php">PDF Distribution</a>
            </div>
            <div class="section-divider"></div> <!-- Bottom divider -->
        </div>

        <div class="nav-item">
            <div class="section-divider"></div> <!-- Top divider -->
            <strong onclick="toggleDropdown(this)">
                Time
                <span class="triangle">&#9660;</span> <!-- Inverted triangle -->
            </strong>
            <div class="dropdown-content">
                <a href="AdminDailyTimeRecord.php" data-page="AdminDailyTimeRecord.php">Daily Time Record</a>
                <a href="AdminViewLeaveBalances.php" data-page="AdminViewLeaveBalances.php">View Leave Balances</a>
            </div>
            <div class="section-divider"></div> <!-- Bottom divider -->
        </div>
    </div>

    <div class="content">

        <!--
        <div class="header">
            <h1>Admin Portal</h1>
            <div>
                Welcome, <span id="username">[Username]</span>
                <button class="logout-btn" onclick="logout()">Logout</button>
            </div>
        </div>
        -->

        <div id="main-content">
            <div class="dashboard-cards">
                <div class="card" id="total-employees">Total Employees</div>
                <div class="card" id="on-time-today">On Time Today</div>
                <div class="card" id="late-today">Late Today</div>
            </div>
        </div>
    </div>

    <script>

        function toggleDropdown(element) {
            const navItem = element.parentElement;
            navItem.classList.toggle('active');
        }

        document.querySelectorAll('.dropdown-content a').forEach(link => {
            link.addEventListener('click', function (event) {
                event.preventDefault();
                const page = this.getAttribute('data-page');
                loadPage(page);
            });
        });

        // Function to load the username
        window.onload = function () {
            const currentPage = sessionStorage.getItem('currentPage') || 'AdminDashboard.php';
            loadPage(currentPage);

            // Fetch the logged-in user's username from session
            fetch('getUsername.php')
                .then(response => response.text())
                .then(username => {
                    document.getElementById('username').textContent = username;
                })
                .catch(error => console.error('Error fetching username:', error));
        };

        // Logout function to destroy session and redirect to login page
        function logout() {
            window.location.href = 'logout.php';
        }

        function fetchUserName() {
            // Fetch the logged-in user's username from session
            fetch('getUsername.php')
                .then(response => response.text())
                .then(username => {
                    document.getElementById('username').textContent = username;
                })
                .catch(error => console.error('Error fetching username:', error));
        }

        //--->>AdminViewLeaveBalances.php
        function fetchLeaveBalances() {
            fetch('getEmployeeLeaveBalances.php')
                .then(response => response.json())
                .then(data => {
                    console.log(data); // Debug the data here
                    const leaveBalancesBody = document.getElementById('leaveBalancesBody');
                    leaveBalancesBody.innerHTML = ''; // Clear previous data

                    if (data.error) {
                        leaveBalancesBody.innerHTML = `<tr><td colspan="4" class="error">${data.error}</td></tr>`;
                    } else {
                        data.forEach(balance => {
                            const row = `<tr>
                                    <td>${balance.Name}</td>
                                    <td>${balance.Position}</td>
                                    <td>
                                        <button class="view-profile-btn" onclick="redirectToEmployeeLeaveBalances(${balance.EmployeeID})">View Balances</button>
                                    </td>
                                </tr>`;
                            leaveBalancesBody.innerHTML += row;
                        });
                    }
                })
                .catch(error => {
                    console.error('Error fetching leave balances:', error);
                });
        }
        //--->>AdminViewLeaveBalances.php

        //--->>AdminViewLeaveBalancesContent.php
        function redirectToEmployeeLeaveBalances(employeeId) {
            if (!employeeId) {
                console.error('Employee ID is missing');
                return;
            }

            // Store the Employee ID in sessionStorage
            sessionStorage.setItem('employeeId', employeeId);

            // Load the AdminViewLeaveBalancesContent.php
            loadPage('AdminViewLeaveBalancesContent.php', () => {
                fetchEmployeeLeaveBalances(employeeId);
            });
        }

        // Fetch the leave balances for a specific employee
        function fetchEmployeeLeaveBalances(employeeId = null) {
            // Use employeeId from sessionStorage if not passed as an argument
            employeeId = employeeId || sessionStorage.getItem('employeeId');

            if (!employeeId) {
                console.error('Employee ID is missing');
                return;
            }

            fetch(`getEmployeeLeaveBalancesContent.php?employeeId=${employeeId}`)
                .then((response) => response.json())
                .then((data) => {
                    const leaveBalancesBody = document.getElementById('leaveBalancesBody');
                    leaveBalancesBody.innerHTML = ''; // Clear previous data

                    if (data.error) {
                        leaveBalancesBody.innerHTML = `<tr><td colspan="4" class="error">${data.error}</td></tr>`;
                    } else {
                        data.forEach((balance) => {
                            const row = `<tr>
                                    <td>${balance.Name}</td>
                                    <td>${balance.Email}</td>
                                    <td>${balance.LeaveType}</td>
                                    <td>${balance.DaysAvailable}</td>
                                </tr>`;
                            leaveBalancesBody.innerHTML += row;
                        });
                    }
                })
                .catch((error) => console.error('Error fetching leave balances:', error));
        }
        //--->>AdminViewLeaveBalancesContent.php

        // Function to load the default page or a given page
        function loadPage(page, callback) {
            fetch(page)
                .then(response => response.text())
                .then(html => {
                    document.getElementById('main-content').innerHTML = html;

                    // Save the current page in sessionStorage
                    sessionStorage.setItem('currentPage', page);

                    // Execute the callback function if provided (for custom logic after page load)
                    if (callback && typeof callback === 'function') {
                        callback();
                    }

                    // Check if a special page is loaded to execute additional logic
                    if (page === 'AdminEmployeeList.php') {
                        fetchUserName();
                        fetchEmployees();
                        fetchEmployeesSearch();
                    } else if (page === 'AdminDashboard.php') {
                        fetchUserName();
                        fetchTotalEmployees(); // Load total employees when dashboard loads
                        fetchTodayAttendanceStatus(); // Fetch On Time and Late attendance status for today
                    } else if (page === 'AdminViewLeaveBalances.php') {
                        fetchLeaveBalances(); // Call the function when the leave balances page is loaded
                        fetchLeaveRequests();
                        fetchUserName();
                    } else if (page === 'AdminDailyTImeRecord.php') {
                        fetchUserName();
                        fetchAttendanceData(); // Call the function when the daily time record page is loaded
                    } else if (page === 'AdminUpdateEmployee.php') {
                        const employeeId = sessionStorage.getItem('employeeId');
                        if (employeeId) {
                            loadEmployeeData(employeeId); // Load employee data for update
                        }
                        validateName();
                        validatePhone();
                        validateAge();
                        validateSalaryInput();
                        fetchPositions();
                        fetchUserName();
                    } else if (page === 'AdminPayslips.php') {
                        fetchUserName();
                        fetchEmployeesForPayslips();
                        fetchEmployeesSearchForPayslips();
                    } else if (page === 'AdminPayslipsEmployee.php') {
                        initializePage();
                        applyDateFilter();
                        generatePayslip();
                        fetchEmployeeName();
                        fetchMaxicareDeduction();
                        /*fetchMaxicareDeduction(sessionStorage.getItem('employeeId'))  // Ensure Maxicare deduction is fetched
                            .then(maxicare => {
                                // Optionally, update the UI to show the Maxicare deduction here if needed
                                console.log('Maxicare Deduction:', maxicare); // For debugging, log the value
                            })
                            .catch(error => {
                                console.error('Error fetching Maxicare deduction:', error);
                            });*/
                    } else if (page === 'AdminSalaryReport.php') {
                        populateYearFilter();  // Populate the year dropdown with the last 5 years
                        loadSalaryReport();    // Load the salary report for the current year
                    } else if (page === 'AdminPdfDistribution.php') {
                        fetchEmployeesForPayslipsDistribution();
                        fetchEmployeesSearchForPayslipsDistribution();
                    } else if (page === 'AdminPdfDistributionContent.php') {
                        populateYearFilterDistribution();
                        applyPayslipFilter();
                        //setTimeout(() => {
                        //console.log("payslipContainer exists:", document.getElementById('payslipContainer') !== null);
                        //applyPayslipFilter();
                        // }, 100); // Short delay to ensure DOM is updated
                    } else if (page === 'AdminViewLeaveBalancesContent.php') {
                        fetchEmployeeLeaveBalances();
                        fetchUserName();
                    }
                })
                .catch(error => console.error('Error loading page:', error));
        }
        //-->> AdminEmployeeList.php
        // Fetch employees
        // Function to fetch employees dynamically from the server
        function fetchEmployees() {
            fetch('getEmployees.php') // Adjust the endpoint based on your backend
                .then(response => response.json())
                .then(data => {
                    const employeeList = document.getElementById('employee-list');
                    employeeList.innerHTML = ''; // Clear the list before appending

                    data.forEach(employee => {
                        // Construct the full name dynamically
                        const middleName = employee.MiddleName ? employee.MiddleName : '';
                        const fullName = `${employee.FirstName} ${middleName} ${employee.LastName}`.replace(/\s+/g, ' ').trim();

                        // Append the employee row to the table
                        const row = `
                                                        <tr>
                                                            <td>${fullName}</td>
                                                            <td>${employee.Position}</td>
                                                            <td>
                                                                <button class="view-profile-btn" onclick="redirectToProfile(${employee.EmployeeID})">View Profile</button>
                                                            </td>
                                                        </tr>
                                                    `;
                        employeeList.innerHTML += row;
                    });
                })
                .catch(error => console.error('Error fetching employees:', error));
        }

        //-->>AdminUpdateEmployee.php

        function redirectToUpdateForm() {
            const employeeId = sessionStorage.getItem('employeeId'); // Retrieve stored employeeId
            if (!employeeId) {
                alert("No employee selected. Please select an employee.");
                return;
            }
            loadPage('AdminUpdateEmployee.php', () => {
                loadEmployeeData(employeeId); // Load employee data into the update form
                fetchPositions();
            });
        }

        //=============================
        function showUpdateForm(employeeId) {
            sessionStorage.setItem('employeeId', employeeId); // Store employeeId for later use
            //loadPage('AdminUpdateEmployee.php');
            redirectToUpdateForm(); // Redirect to update form
        }


        function loadEmployeeData(employeeId) {
            fetch(`getEmployeeProfile.php?id=${employeeId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        console.error('Error fetching employee data:', data.error);
                        //alert('Error loading employee data.');
                        showModal('Error loading employee data.');
                        return;
                    }

                    // Populate form fields
                    document.getElementById('first-name').value = data.FirstName || '';
                    document.getElementById('middle-name').value = data.MiddleName || '';
                    document.getElementById('last-name').value = data.LastName || '';
                    document.getElementById('employee-email').value = data.Email || '';
                    document.getElementById('employee-birthdate').value = data.BirthDate || '';
                    document.getElementById('employee-age').value = data.Age || '';
                    document.getElementById('employee-status').value = data.Status || '';
                    document.getElementById('employee-phone').value = data.Phone || '';
                    document.getElementById('employee-barangay').value = data.Barangay || '';
                    document.getElementById('employee-streetnumber').value = data.StreetNumber || '';
                    document.getElementById('employee-city').value = data.City || '';
                    document.getElementById('employee-zipcode').value = data.ZipCode || '';
                    document.getElementById('employee-salary').value = data.Salary || '';
                    document.getElementById('position').value = data.Position || '';
                    document.getElementById('employment-date').value = data.DateAdded || '';
                    document.getElementById('employment-loan').value = data.SalaryLoan_ind || '';
                    document.getElementById('maxicare-type').value = data.MaxicareType || '';
                })
                .catch(error => {
                    console.error('Error loading employee data:', error);
                });
        }

        function updateEmployee(event) {
            event.preventDefault(); // Prevent default form submission
            const employeeId = sessionStorage.getItem('employeeId'); // Retrieve the EmployeeID from sessionStorage

            if (!employeeId) {
                //alert("No employee ID found. Unable to update.");
                showModal('No employee ID found. Unable to update.');
                return;
            }

            // Gather form data
            const formData = new FormData();
            formData.append('employeeId', employeeId);
            formData.append('firstName', document.getElementById('first-name').value);
            formData.append('middleName', document.getElementById('middle-name').value);
            formData.append('lastName', document.getElementById('last-name').value);
            formData.append('email', document.getElementById('employee-email').value);
            formData.append('birthDate', document.getElementById('employee-birthdate').value);
            formData.append('age', document.getElementById('employee-age').value);
            formData.append('status', document.getElementById('employee-status').value);
            formData.append('phone', document.getElementById('employee-phone').value);
            formData.append('barangay', document.getElementById('employee-barangay').value);
            formData.append('streetNumber', document.getElementById('employee-streetnumber').value);
            formData.append('city', document.getElementById('employee-city').value);
            formData.append('zipCode', document.getElementById('employee-zipcode').value);
            formData.append('position', document.getElementById('position').value);
            formData.append('maxicareType', document.getElementById('maxicare-type').value);
            formData.append('salaryLoan', document.getElementById('employment-loan').value);

            fetch('updateEmployee.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.text())
                .then(data => {
                    //alert(data); // Display server response
                    showModal(data);
                    if (data.includes('success')) {
                        loadPage('AdminEmployeeList.php'); // Redirect to employee list on success
                    }
                })
                .catch(error => console.error('Error updating employee:', error));
        }

        function cancelUpdate() {
            loadPage('AdminEmployeeList.php'); // Redirect to the employee list
        }
        //-->>AdminUpdateEmployee.php

        /*
        function confirmDelete(employeeId) {
            const confirmed = confirm('Are you sure you want to delete this employee?');
            if (confirmed) {
                deleteEmployee(employeeId);
            }
        }
        */
        function confirmDelete(employeeId) {
            const modal = document.getElementById("customModal");
            const modalMessage = document.getElementById("deleteModalMessage");
            const confirmButton = document.getElementById("confirmButton");
            const cancelButton = document.getElementById("cancelButton");

            // Set the confirmation message
            modalMessage.textContent = "Are you sure you want to delete this employee?";
            modal.style.display = "block"; // Show the modal

            // Handle confirm action
            confirmButton.onclick = () => {
                modal.style.display = "none"; // Hide the modal
                deleteEmployee(employeeId);
                loadPage('AdminEmployeeList.php'); // Redirect to employee list on success
            };

            // Handle cancel action
            cancelButton.onclick = () => {
                modal.style.display = "none"; // Hide the modal
            };
        }


        function deleteEmployee(employeeId) {
            fetch(`deleteEmployee.php?id=${employeeId}`, { method: "DELETE" })
                .then((response) => response.text())
                .then((data) => {
                    // Assuming the response contains the success message
                    showModalMessage(data, true); // Pass the message and specify it is a delete operation
                    fetchEmployees(); // Refresh the employee list after deletion
                })
                .catch((error) => {
                    console.error("Error deleting employee:", error);
                    showModalMessage("An error occurred while deleting the employee.", true);
                });
        }

        // Function to show a custom pop-up for messages
        function showModalMessage(message, isDelete = false) {
            const modal = document.getElementById("customModal");
            const modalMessage = isDelete
                ? document.getElementById("deleteModalMessage")
                : document.getElementById("modalMessage");
            const confirmButton = document.getElementById("confirmButton");
            const cancelButton = document.getElementById("cancelButton");

            // Set the message
            modalMessage.textContent = message;

            // Hide Yes/No buttons for success message
            if (isDelete && message !== "Are you sure you want to delete this employee?") {
                confirmButton.style.display = "none";
                cancelButton.textContent = "OK";
            }

            modal.style.display = "block"; // Show the modal

            // Close the modal when OK or No is clicked
            cancelButton.onclick = () => {
                modal.style.display = "none";
                confirmButton.style.display = "inline-block"; // Restore Yes button for future use
                cancelButton.textContent = "No"; // Restore No button text
            };
        }
        //--<<




        // Redirect to profile using AJAX
        function redirectToProfile(employeeId) {
            sessionStorage.setItem('employeeId', employeeId); // Store Employee ID in sessionStorage
            // Load the profile page template
            loadPage('AdminEmployeeProfile.php', function () {
                // Fetch and display employee profile details after loading the template
                loadEmployeeProfile(employeeId);
                fetchUserName();
            });
        }

        // Fetch and display employee profile
        function loadEmployeeProfile(employeeId) {
            fetch(`getEmployeeProfile.php?id=${employeeId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        console.error('Error fetching employee profile:', data.error);
                        return;
                    }

                    // Combine full name
                    const fullName = `${data.FirstName} ${data.MiddleName || ''} ${data.LastName}`.trim();

                    // Update DOM elements with fetched data
                    document.getElementById('employee-name').textContent = fullName;
                    document.getElementById('first-name').value = data.FirstName || 'N/A';
                    document.getElementById('middle-name').value = data.MiddleName || 'N/A';
                    document.getElementById('last-name').value = data.LastName || 'N/A';
                    document.getElementById('employee-email').value = data.Email || 'N/A';
                    document.getElementById('employee-birthdate').value = data.BirthDate || 'N/A';
                    document.getElementById('employee-age').value = data.Age || 'N/A';
                    document.getElementById('employee-status').value = data.Status || 'N/A';
                    document.getElementById('employee-phone').value = data.Phone || 'N/A';
                    document.getElementById('employee-barangay').value = data.Barangay || 'N/A';
                    document.getElementById('employee-streetnumber').value = data.StreetNumber || 'N/A';
                    document.getElementById('employee-city').value = data.City || 'N/A';
                    document.getElementById('employee-zipcode').value = data.ZipCode || 'N/A';
                    document.getElementById('maxicare-type').value = data.MaxicareType || 'N/A';
                    document.getElementById('employee-salary').value = data.Salary ? `₱${parseFloat(data.Salary).toFixed(2)}` : 'N/A';
                    document.getElementById('employee-position').value = data.Position || 'N/A';
                    document.getElementById('employment-date').value = data.DateAdded || 'N/A';
                    document.getElementById('employment-loan').value = data.SalaryLoan_ind || 'N/A';

                    document.getElementById('employment-date-up').textContent = data.DateAdded || 'N/A';
                    document.getElementById('employee-position-up').textContent = data.Position || 'N/A';


                    // Display face image if available
                    const faceImage = document.getElementById('employee-face-image');
                    if (data.FaceData) {
                        faceImage.src = `data:image/jpeg;base64,${data.FaceData}`;
                        faceImage.style.display = 'block';
                    } else {
                        faceImage.style.display = 'none';
                    }
                })
                .catch(error => console.error('Error loading employee profile:', error));
        }


        // Fetch departments for the New Hire form
        /*
        function fetchDepartments(callback) {
            fetch('getDepartments.php')
                .then(response => response.json())
                .then(data => {
                    const departmentSelect = document.getElementById('department');
                    departmentSelect.innerHTML = ''; // Clear the dropdown before appending

                    data.forEach(department => {
                        const option = document.createElement('option');
                        option.value = department.DepartmentID;
                        option.textContent = department.DepartmentName;
                        departmentSelect.appendChild(option);
                    });

                    if (callback) callback();
                })
                .catch(error => console.error('Error fetching departments:', error));
        }
        */

        // Fetch positions for the New Hire form
        function fetchPositions(callback) {
            fetch('getPositions.php')
                .then(response => response.json())
                .then(data => {
                    const positionSelect = document.getElementById('position');
                    positionSelect.innerHTML = ''; // Clear the dropdown before appending

                    data.forEach(position => {
                        const option = document.createElement('option');
                        option.value = position.PositionID;
                        option.textContent = position.PositionName;
                        positionSelect.appendChild(option);
                    });

                    if (callback) callback();
                })
                .catch(error => console.error('Error fetching positions:', error));
        }



        // Function to fetch total employees for the Admin Dashboard
        function fetchTotalEmployees() {
            fetch('getEmployees.php')
                .then(response => response.json())
                .then(data => {
                    const totalEmployees = document.getElementById('total-employees');
                    if (totalEmployees) {
                        totalEmployees.textContent = data.length;
                    }
                })
                .catch(error => {
                    console.error('Error fetching employees:', error);
                });
        }

        function fetchLeaveRequests() {
            fetch('getLeaveRequests.php')
                .then(response => response.json())
                .then(data => {
                    const leaveRequestsBody = document.getElementById('leaveRequestsBody');
                    leaveRequestsBody.innerHTML = ''; // Clear previous data

                    if (data.error) {
                        leaveRequestsBody.innerHTML = `<tr><td colspan="6" class="error">${data.error}</td></tr>`;
                    } else {
                        data.forEach(request => {
                            const row = `
                                                                    <tr>
                                                                        <td>${request.Name}</td>
                                                                        <td>${request.LeaveType}</td>
                                                                        <td>${request.LeaveStartDate}</td>
                                                                        <td>${request.LeaveEndDate}</td>
                                                                        <td>${request.Reason}</td>
                                                                        <td>${request.indicator}</td>
                                                                        <td>${request.Attachment}</td>
                                                                        <td>
                                                                            <button class="action-btn approve-btn" onclick="updateLeaveStatus(${request.LeaveFileID}, 'APPROVED')">Approve</button>
                                                                            <button class="action-btn reject-btn" onclick="updateLeaveStatus(${request.LeaveFileID}, 'REJECTED')">Reject</button>
                                                                        </td>
                                                                    </tr>`;
                            leaveRequestsBody.innerHTML += row;
                        });
                    }
                })
                .catch(error => console.error('Error fetching leave requests:', error));
        }

        function updateLeaveStatus(leaveFileID, status) {
            fetch('updateLeaveStatus.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    leaveFileID: leaveFileID,
                    status: status
                })
            })
                .then(response => response.text())
                .then(data => {
                    alert(data);
                    fetchLeaveRequests(); // Reload the leave requests after updating
                })
                .catch(error => console.error('Error updating leave status:', error));
        }

        // Function to apply the selected filters
        function applyFilters() {
            const date = document.getElementById('dateFilter').value;
            const status = document.getElementById('statusFilter').value;
            console.log("Applying filters:", { date, status }); // Debugging line
            fetchAttendanceData(date, status);
        }

        // Function to fetch and display attendance records
        function fetchAttendanceData(date = '', status = '') {
            console.log("Fetching data with filters:", { date, status }); // Debugging line
            fetch(`fetchAttendance.php?date=${date}&status=${status}`)
                .then(response => response.json())
                .then(data => {
                    console.log("Filtered data received:", data); // Debugging line
                    populateAttendanceTable(data);
                })
                .catch(error => console.error('Error fetching attendance data:', error));
        }

        // Function to populate the attendance table
        function populateAttendanceTable(records) {
            const tableBody = document.getElementById('attendanceTableBody');
            tableBody.innerHTML = ''; // Clear the table before populating

            if (records.length > 0) {
                records.forEach(record => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                                                            <td>${record.Name}</td>
                                                            <td>${record.PositionName}</td>
                                                            <td>${record.Date}</td>
                                                            <td>${record.CheckInTime}</td>
                                                            <td>${record.CheckOutTime || 'N/A'}</td>
                                                            <td>${record.Status}</td>
                                                        `;
                    tableBody.appendChild(row);
                });
            } else {
                tableBody.innerHTML = '<tr class="no-data"><td colspan="5">No records found</td></tr>';
            }
        }

        // Initial fetch to load data without filters when the page loads
        document.addEventListener('DOMContentLoaded', function () {
            fetchAttendanceData();
        });

        // Set up event listeners for navigation links
        document.querySelectorAll('.nav-item a').forEach(link => {
            link.addEventListener('click', function (event) {
                event.preventDefault();
                const page = this.getAttribute('data-page');
                loadPage(page);
            });
        });

        // Initial fetch of department and positions dropdowns when the page loads
        document.addEventListener('DOMContentLoaded', function () {
            if (sessionStorage.getItem('currentPage') === 'AdminUpdateEmployee.php') {
                fetchDepartments();
                fetchPositions();
            }
        });

        // Listen for New Hire button clicks from loaded pages
        document.addEventListener('click', function (event) {
            if (event.target && event.target.id === 'new-hire-btn') {
                event.preventDefault();
                loadPage('AdminNewHireRegister.php'); // Load the New Hire Register page
            }
        });

        //-->>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
        //for modal
        function showModal(message) {
            const modal = document.getElementById('warningModal');
            const messageElement = document.getElementById('modalMessage');
            messageElement.textContent = message;
            modal.style.display = 'block';
        }

        function closeModal() {
            const modal = document.getElementById('warningModal');
            modal.style.display = 'none';
        }

        // Close the modal when clicking outside of it
        window.onclick = function (event) {
            const modal = document.getElementById('warningModal');
            if (event.target === modal) {
                closeModal();
            }
        };
        //--<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<


        //AdminNewHireRegister.php Validations
        // Validate name to contain only letters and spaces
        function validateName(input) {
            const regex = /^[A-Za-z\s]*$/;
            if (!regex.test(input.value)) {
                input.value = input.value.replace(/[^A-Za-z\s]/g, ''); // Remove invalid characters
                showModal('Names can only contain letters and spaces.');
            }
        }



        // Validate age to contain only numbers
        function validateAge(input) {
            const regex = /^[0-9]*$/;
            if (!regex.test(input.value)) {
                input.value = input.value.replace(/[^0-9]/g, ''); // Remove invalid characters
                showModal('Age can only contain numbers.');
            }
        }

        // Validate salary to allow numbers with up to two decimal places
        function validateSalaryInput(event) {
            const input = event.target;
            const regex = /^\d{0,10}(\.\d{0,2})?$/;
            if (!regex.test(input.value)) {
                input.value = input.value.slice(0, -1); // Remove last character if invalid
                showModal('Salary must be a number with up to two decimal places.');
            }
        }

        // Function to search and display employees based on the search term
        function searchEmployees(term) {
            fetch(`searchEmployees.php?term=${encodeURIComponent(term)}`) // Adjust the endpoint as needed
                .then(response => response.json())
                .then(data => {
                    const employeeList = document.getElementById('employee-list');
                    employeeList.innerHTML = ''; // Clear the table body before appending

                    if (data.length === 0) {
                        // Display a "No employees found" message in the table
                        employeeList.innerHTML = `
                                                <tr>
                                                    <td colspan="3" style="text-align: center;">No employees found.</td>
                                                </tr>
                                            `;
                    } else {
                        data.forEach(employee => {
                            // Construct full name with middle name (fallback to empty string if null)
                            const middleName = employee.MiddleName ? employee.MiddleName : '';
                            const fullName = `${employee.FirstName} ${middleName} ${employee.LastName}`.replace(/\s+/g, ' ').trim();

                            // Append the employee row to the table
                            const row = `
                                                    <tr>
                                                        <td>${fullName}</td>
                                                        <td>${employee.Position}</td>
                                                        <td>
                                                            <button class="view-profile-btn" onclick="redirectToProfile(${employee.EmployeeID})">View Profile</button>
                                                        </td>
                                                    </tr>
                                                `;
                            employeeList.innerHTML += row;
                        });
                    }
                })
                .catch(error => console.error('Error searching employees:', error));
        }

        // Event listener for the search bar input in AdminEmployeeList.php
        document.addEventListener('input', function (event) {
            if (event.target && event.target.id === 'search-bar') {
                const searchTerm = event.target.value;
                console.log("Search term:", searchTerm); // Debugging line
                searchEmployees(searchTerm); // Call search function with the input value
            }
        });

        // Fetch employees by default on page load
        function fetchEmployeesSearch() {
            searchEmployees(''); // Empty term fetches all employees
        }

        // Function to fetch today's attendance status and update the dashboard
        function fetchTodayAttendanceStatus() {
            fetch('fetchAttendanceStatus.php')
                .then(response => response.json())
                .then(data => {
                    // Update the On Time and Late counts in AdminDashboard.php
                    document.getElementById('on-time-today').textContent = data.onTime;
                    document.getElementById('late-today').textContent = data.late;
                })
                .catch(error => console.error('Error fetching today\'s attendance status:', error));
        }

        //-->>AdminPayslips.php
        // Fetch employees for PaySlips page and populate the table
        function fetchEmployeesForPayslips() {
            fetch('getEmployees.php')  // Replace with the correct endpoint to fetch employees
                .then(response => response.json())
                .then(data => {
                    const employeeList = document.getElementById('employee-list-pay');
                    employeeList.innerHTML = ''; // Clear previous content

                    data.forEach(employee => {
                        const middleName = employee.MiddleName ? employee.MiddleName : '';
                        const fullName = `${employee.FirstName} ${middleName} ${employee.LastName}`.replace(/\s+/g, ' ').trim();

                        // Append employee row to the table
                        employeeList.innerHTML += `
                    <tr>
                        <td>${fullName}</td>
                        <td>
                            <button class="view-profile-btn" onclick="loadPayslipPage(${employee.EmployeeID})">Generate Payslip</button>
                        </td>
                    </tr>
                `;
                    });
                })
                .catch(error => console.error('Error fetching employees:', error));
        }

        // Fetch employees when the page loads
        function fetchEmployeesSearchForPayslips() {
            searchEmployeesForPayslips(''); // Empty term fetches all employees
        }

        // Search employees based on input from the search bar
        function searchEmployeesForPayslips(term) {
            fetch(`searchEmployees.php?term=${encodeURIComponent(term)}`) // Adjust with the correct PHP endpoint
                .then(response => response.json())
                .then(data => {
                    const employeeList = document.getElementById('employee-list-pay');
                    employeeList.innerHTML = ''; // Clear previous content

                    if (data.length === 0) {
                        employeeList.innerHTML = `
                    <tr>
                        <td colspan="2" style="text-align: center;">No employees found.</td>
                    </tr>
                `;
                    } else {
                        data.forEach(employee => {
                            const middleName = employee.MiddleName ? employee.MiddleName : '';
                            const fullName = `${employee.FirstName} ${middleName} ${employee.LastName}`.replace(/\s+/g, ' ').trim();

                            employeeList.innerHTML += `
                        <tr>
                            <td>${fullName}</td>
                            <td>
                                <button class="view-profile-btn" onclick="loadPayslipPage(${employee.EmployeeID})">Generate Payslip</button>
                            </td>
                        </tr>
                    `;
                        });
                    }
                })
                .catch(error => console.error('Error searching employees:', error));
        }

        // Function to load the payslip page
        function loadPayslipPage(employeeId) {
            sessionStorage.setItem('employeeId', employeeId);
            console.log('Employee ID stored:', employeeId); // Debugging line
            loadPage('AdminPayslipsEmployee.php'); // Load the payslip page

        }


        // Function to load employees by default on page load
        document.addEventListener('DOMContentLoaded', function () {
            if (sessionStorage.getItem('currentPage') === 'AdminPayslips.php') {
                fetchEmployeesForPayslips();

            }
        });

        // Event listener for the search bar input

        document.addEventListener('input', function (event) {
            if (event.target && event.target.id === 'search-bar-pay') {
                const searchTerm = event.target.value;
                console.log("Search term:", searchTerm); // Debugging line
                searchEmployeesForPayslips(searchTerm); // Search for employees based on the input
            }
        });

        //-->> for AdminPayslips

        // Function to calculate overtime pay based on checkout time
        function calculateOvertimePay(checkOutTime) {
            let overtimePay = 0;
            const fivePM = new Date();
            fivePM.setHours(17, 0, 0); // 5:00 PM time threshold

            if (checkOutTime > fivePM) {
                let excessTime = (checkOutTime - fivePM) / 60000; // Calculate excess time in minutes
                overtimePay = Math.floor(excessTime / 60); // For every 60 minutes, 1 hour of overtime
            }

            return overtimePay;
        }


        // Fetch attendance based on date filter
        // Fetch and display employees' attendance for payslips
        function applyDateFilter() {
            const fromDate = document.getElementById('from-date').value;
            const toDate = document.getElementById('to-date').value;
            const employeeId = sessionStorage.getItem('employeeId');

            fetch(`getAttendanceForPayslip.php?employeeId=${employeeId}&fromDate=${fromDate}&toDate=${toDate}`)
                .then(response => response.json())
                .then(data => {
                    const tableBody = document.querySelector('#attendance-table tbody');
                    tableBody.innerHTML = ''; // Clear any previous data

                    if (data.length === 0) {
                        tableBody.innerHTML = '<tr><td colspan="4">No attendance records found for the selected dates.</td></tr>';
                    } else {
                        data.forEach(record => {
                            // No need to call calculateOvertimePay here since it's already done in PHP
                            tableBody.innerHTML += `
                                                                    <tr>
                                                                        <td>${record.Date}</td>
                                                                        <td>${record.Status}</td>
                                                                        <td>${record.HoursWorked}</td>
                                                                        <td>${record.OvertimePay}</td> <!-- Use the OvertimePay from the backend -->
                                                                    </tr>
                                                                `;
                        });
                    }
                })
                .catch(error => console.error('Error fetching attendance data:', error));
        }


        // Function to generate the payslip

        // Function to fetch the Maxicare deduction based on MaxicareType and Age
        function fetchMaxicareDeduction() {
            const employeeId = sessionStorage.getItem('employeeId'); // Get employeeId from sessionStorage
            console.log('Employee ID:', employeeId); // Debugging line to check the value

            if (employeeId) {
                return new Promise((resolve, reject) => {
                    // Fetch employee details (MaxicareType, Age)
                    fetch(`getEmployeeDetails.php?employeeId=${employeeId}`)
                        .then(response => response.json())
                        .then(data => {
                            const maxicareType = data.MaxicareType;
                            const age = data.Age;

                            if (maxicareType && age) {
                                // Fetch Maxicare deduction from getMaxicareDeduction.php
                                fetch(`getMaxicareDeduction.php?employeeId=${employeeId}&maxicareType=${maxicareType}&age=${age}`)
                                    .then(response => response.json())
                                    .then(maxicareData => {
                                        if (maxicareData && maxicareData.maxicare_annual_amt) {
                                            const maxicareAmount = parseFloat(maxicareData.maxicare_annual_amt);
                                            console.log("Maxicare Deduction: ", maxicareAmount);
                                            // Update the Maxicare field with the monthly deduction
                                            document.getElementById('maxicare').value = maxicareAmount.toFixed(2);  // Update Maxicare field value
                                            resolve(maxicareAmount); // Resolve the promise with the deduction value
                                        } else {
                                            reject('Maxicare data not found');
                                        }
                                    })
                                    .catch(error => {
                                        reject('Error fetching Maxicare deduction: ' + error);
                                    });
                            } else {
                                reject('MaxicareType or Age is missing');
                            }
                        })
                        .catch(error => {
                            reject('Error fetching employee details: ' + error);
                        });
                });
            } else {
                console.error('No employeeId found in sessionStorage');
                return Promise.reject('No employeeId found in sessionStorage');
            }
        }



        // Function to generate the payslip
        function generatePayslip() {
            const payDate = document.getElementById('pay-date').value;
            const grossPay = parseFloat(document.getElementById('gross-pay').value);
            const salaryLoan = parseFloat(document.getElementById('salary-loan').value) || 0; // Get the salary loan value
            const overtimepay = parseFloat(document.getElementById('overtimepay').value) || 0; // Get overtime pay
            const overtimeHours = parseInt(document.getElementById('overtimehours').value); // Ensure overtime hours are captured
            const maxicare = parseFloat(document.getElementById('maxicare').value) || 0; // Get overtime pay

            if (!payDate || isNaN(grossPay)) {
                alert("Please fill all fields with valid values.");
                return;
            }

            let sss = 0, pagibig = 0, philhealth = 0, tax = 0;

            // Calculate total hours from the attendance data
            let totalHours = 0;
            const rows = document.querySelectorAll('#attendance-table tbody tr');
            rows.forEach(row => {
                const hoursWorked = parseFloat(row.cells[2].innerText);
                totalHours += hoursWorked;
            });

            // Check which cutoff is selected
            if (document.getElementById('first-cutoff').checked) {
                // First cutoff: Compute PagIbig and Salary Loan
                pagibig = calculatePagIbig(grossPay);
                // Omit SSS, PhilHealth, and Tax
            } else if (document.getElementById('second-cutoff').checked) {
                // Second cutoff: Compute SSS, PhilHealth, and Tax
                sss = calculateSSS(grossPay);
                philhealth = calculatePhilHealth(grossPay);
                tax = calculateTax(grossPay);
                // Omit PagIbig
            }

            // Calculate net pay considering deductions
            //const netPay = grossPay - (sss + pagibig + philhealth + tax + salaryLoan) + overtimePay;
            const netPay = grossPay + overtimepay - (sss + pagibig + tax + philhealth + salaryLoan + maxicare);

            // Prepare data to be sent to the PHP backend
            const payrollData = {
                employeeId: sessionStorage.getItem('employeeId'),
                payDate: payDate,
                totalHours: totalHours,
                grossPay: grossPay,
                netPay: netPay,
                sss: sss,
                pagibig: pagibig,
                philhealth: philhealth,
                tax: tax,
                salaryLoan: salaryLoan,
                overtimepay: overtimepay,
                overtimehours: overtimeHours, // Include overtime hours in the payload
                // Include salary loan in the data sent
                maxicare: maxicare
            };

            // Send data to the backend for insertion into the Payroll table
            fetch('generatePayslip.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(payrollData)
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert("Payslip generated successfully!");
                        // Clear fields after successful generation
                        document.getElementById('pay-date').value = '';
                        document.getElementById('gross-pay').value = '';
                        document.getElementById('overtimepay').value = '';
                        document.getElementById('overtimehours').value = '';
                        document.getElementById('salary-loan').value = ''; // Clear the salary loan field
                    } else {
                        alert("Failed to generate payslip.");
                    }
                })
                .catch(error => console.error('Error generating payslip:', error));
        }


        // Calculation functions for deductions
        function calculateSSS(grossPay) {
            // Add your SSS calculation logic here based on gross pay
            // Example: Assuming SSS contribution is 13% for the salary range
            return grossPay * 0.13; // Adjust based on your SSS contribution table
        }

        function calculatePagIbig(grossPay) {
            // Add Pag-Ibig calculation (2% max of 100)
            return grossPay <= 5000 ? grossPay * 0.02 : 100; // Pag-Ibig max contribution
        }

        function calculatePhilHealth(grossPay) {
            // Add PhilHealth calculation (4.5%)
            return grossPay * 0.045;
        }

        function calculateTax(grossPay) {
            if (grossPay <= 10417) {
                return 0.00; // 0.00 tax for compensation <= P10,417
            } else if (grossPay <= 16666) {
                return (grossPay - 10417) * 0.15; // 15% for amounts over P10,417 up to P16,666
            } else if (grossPay <= 33332) {
                return 1250 + (grossPay - 16666) * 0.20; // P1,250 + 20% for amounts over P16,667 up to P33,332
            } else if (grossPay <= 83332) {
                return 5416.67 + (grossPay - 33332) * 0.25; // P5,416.67 + 25% for amounts over P33,333 up to P83,332
            } else if (grossPay <= 333332) {
                return 20416.67 + (grossPay - 83332) * 0.30; // P20,416.67 + 30% for amounts over P83,333 up to P333,332
            } else {
                return 100416.67 + (grossPay - 333332) * 0.35; // P100,416.67 + 35% for amounts over P333,333
            }
        }

        //-->>TEMP===========================================
        function initializePage() {
            setupEventListeners();  // Initialize event listeners for input fields
        }

        function setupEventListeners() {
            const grossPayField = document.getElementById('gross-pay');
            const overtimePayField = document.getElementById('overtimepay');
            const overtimeHoursField = document.getElementById('overtimehours');
            const salaryLoanField = document.getElementById('salary-loan');
            const maxicare = document.getElementById('maxicare');

            if (grossPayField && overtimePayField && overtimeHoursField && salaryLoanField) {
                grossPayField.addEventListener('input', calculateDeductions);
                overtimePayField.addEventListener('input', calculateDeductions);
                overtimeHoursField.addEventListener('input', calculateDeductions);
                salaryLoanField.addEventListener('input', calculateDeductions); // Added listener for salary-loan
            }
        }

        // Function to calculate deductions dynamically
        function calculateDeductions() {
            const grossPay = parseFloat(document.getElementById('gross-pay').value);
            const overtimepay = parseFloat(document.getElementById('overtimepay').value) || 0;
            const salaryLoan = parseFloat(document.getElementById('salary-loan').value) || 0;
            const maxicare = parseFloat(document.getElementById('maxicare').value) || 0;

            console.log("grossPay: " + grossPay, "overtimePay: " + overtimepay, "salaryLoan: " + salaryLoan); // Debugging line

            if (!isNaN(grossPay)) {
                let sss = 0, pagibig = 0, philhealth = 0, tax = 0;

                // Check which cutoff is selected
                if (document.getElementById('first-cutoff').checked) {
                    // First Cutoff: Exclude SSS, PhilHealth, and Tax
                    pagibig = calculatePagIbig(grossPay);  // Only Pag-IBIG is computed for first cutoff
                } else if (document.getElementById('second-cutoff').checked) {
                    // Second Cutoff: Exclude Pag-IBIG
                    sss = calculateSSS(grossPay);
                    philhealth = calculatePhilHealth(grossPay);
                    tax = calculateTax(grossPay);
                }

                // Calculate the net pay considering the selected cutoff deductions
                const netPay = grossPay + overtimepay - (sss + pagibig + tax + philhealth + salaryLoan + maxicare);

                // Update the UI with the computed deductions
                document.getElementById('sss').value = sss.toFixed(2);
                document.getElementById('pagibig').value = pagibig.toFixed(2);
                document.getElementById('philhealth').value = philhealth.toFixed(2);
                document.getElementById('tax').value = tax.toFixed(2);
                document.getElementById('net-pay').value = netPay.toFixed(2);
            }
        }

        // Calculation functions for deductions
        function calculateSSS(grossPay) {
            return grossPay * 0.13;
        }

        function calculatePagIbig(grossPay) {
            return grossPay <= 5000 ? grossPay * 0.02 : 100;
        }

        function calculatePhilHealth(grossPay) {
            return grossPay * 0.045;
        }

        function calculateTax(grossPay) {
            if (grossPay <= 10417) {
                return 0.00; // 0.00 tax for compensation <= P10,417
            } else if (grossPay <= 16666) {
                return (grossPay - 10417) * 0.15; // 15% for amounts over P10,417 up to P16,666
            } else if (grossPay <= 33332) {
                return 1250 + (grossPay - 16666) * 0.20; // P1,250 + 20% for amounts over P16,667 up to P33,332
            } else if (grossPay <= 83332) {
                return 5416.67 + (grossPay - 33332) * 0.25; // P5,416.67 + 25% for amounts over P33,333 up to P83,332
            } else if (grossPay <= 333332) {
                return 20416.67 + (grossPay - 83332) * 0.30; // P20,416.67 + 30% for amounts over P83,333 up to P333,332
            } else {
                return 100416.67 + (grossPay - 333332) * 0.35; // P100,416.67 + 35% for amounts over P333,333
            }
        }




        // Initialization when AdminPayslips is loaded via AJAX
        document.addEventListener('DOMContentLoaded', function () {
            if (sessionStorage.getItem('currentPage') === 'AdminPayslips.php') {
                setupEventListeners();
            }
        });
        //--<<TEMP===========================================


        // Fetch employee name based on employeeId
        function fetchEmployeeName() {
            const employeeId = sessionStorage.getItem('employeeId'); // Get employeeId from sessionStorage

            if (employeeId) {
                fetch(`getEmployeeName.php?employeeId=${employeeId}`)
                    .then(response => response.json())
                    .then(data => {
                        // Ensure that the full name is correctly formatted
                        const fullName = `${data.FirstName} ${data.MiddleName || ''} ${data.LastName}`;
                        document.getElementById('employee-name').textContent = `Employee: ${fullName}`;
                        document.getElementById('employee-rate').textContent = `Employee Rate: ${data.rate}`;
                        document.getElementById('employee-maxicare').textContent = `Employee MaxicareType: ${data.MaxicareType}`;
                    })
                    .catch(error => {
                        console.error('Error fetching employee name:', error);
                        document.getElementById('employee-name').textContent = 'Employee: Unknown';
                        document.getElementById('employee-rate').textContent = `Employee Rate: Unknown`;
                        document.getElementById('employee-maxicare').textContent = `Employee MaxicareType: Unknown`;
                    });
            } else {
                console.error('No employeeId found in sessionStorage');
                document.getElementById('employee-name').textContent = 'Employee: Unknown';
                document.getElementById('employee-rate').textContent = `Employee Rate: Unknown`;
                document.getElementById('employee-maxicare').textContent = `Employee MaxicareType: Unknown`;
            }
        }
        //--<<

        //--<<
        //AdminSalaryReport.php
        // Function to populate the year filter dropdown
        function populateYearFilter() {
            const yearSelect = document.getElementById('year-filter');
            const currentYear = new Date().getFullYear();
            for (let year = currentYear; year >= currentYear - 5; year--) {
                const option = document.createElement('option');
                option.value = year;
                option.textContent = year;
                yearSelect.appendChild(option);
            }

            // Set the current year as the default selected option
            yearSelect.value = currentYear;
        }

        // Function to load the Salary Report data
        function loadSalaryReport() {
            // Get the selected year from the filter
            const selectedYear = document.getElementById('year-filter').value;

            // Fetch payroll data based on the selected year
            fetch(`getPayrollDataAdmin.php?year=${selectedYear}`)
                .then(response => response.json())
                .then(data => {
                    console.log(data); // Debugging the data

                    if (data.error) {
                        console.error("Error fetching payroll data:", data.error);
                        const tableBody = document.getElementById('salary-report-body');
                        tableBody.innerHTML = '<tr><td colspan="5">No payroll data found for the selected year.</td></tr>';
                        return;
                    }

                    // Populate the table with the fetched data
                    const tableBody = document.getElementById('salary-report-body');
                    tableBody.innerHTML = '';  // Clear the table body before adding new data

                    let totalRegularHours = 0;
                    let totalOvertimeHours = 0;
                    let totalWorkedHours = 0;
                    let totalWage = 0;

                    // Check if data is populated
                    if (data.monthlySummary && data.monthlySummary.length > 0) {
                        data.monthlySummary.forEach(monthData => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                                                    <td>${monthData.month}</td>
                                                                    <td>${monthData.regularHours}</td>
                                                                    <td>${monthData.overtimeHours}</td>
                                                                    <td>${monthData.totalWorkedHours}</td>
                                                                    <td>₱${parseFloat(monthData.totalWage).toFixed(2)}</td>
                                                                `;
                            tableBody.appendChild(row);

                            // Add to totals
                            totalRegularHours += parseFloat(monthData.regularHours || 0);
                            totalOvertimeHours += parseFloat(monthData.overtimeHours || 0);
                            totalWorkedHours += parseFloat(monthData.totalWorkedHours || 0);
                            totalWage += parseFloat(monthData.totalWage || 0);
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
                        tableBody.innerHTML = `<tr><td colspan="5">No data available for this year.</td></tr>`;
                    }
                })
                .catch(error => {
                    console.error('Error loading salary report data:', error);
                });
        }
        //-->>

        //-->>AdminPdfDistribution.php
        // Function to fetch employees for PaySlips page
        function fetchEmployeesForPayslipsDistribution() {
            fetch('getEmployees.php')  // Replace with the correct endpoint to fetch employees
                .then(response => response.json())
                .then(data => {
                    const employeeList = document.getElementById('employee-list-pay');
                    employeeList.innerHTML = ''; // Clear previous content

                    data.forEach(employee => {
                        const middleName = employee.MiddleName ? employee.MiddleName : '';
                        const fullName = `${employee.FirstName} ${middleName} ${employee.LastName}`.replace(/\s+/g, ' ').trim();

                        employeeList.innerHTML += `
                                                                <div class="employee-card">
                                                                    <p>${fullName}</p>
                                                                    <button class="gen-pay-button" onclick="loadPayslipsDistributionPage(${employee.EmployeeID})">Distribute Payslip</button>
                                                                </div>
                                                            `;
                    });
                })
                .catch(error => console.error('Error fetching employees:', error));
        }

        function fetchEmployeesSearchForPayslipsDistribution() {
            searchEmployeesForPayslipsDistribution(''); // Empty term fetches all employees
        }

        // Function to search employees based on input from the search bar
        function searchEmployeesForPayslipsDistribution(term) {
            fetch(`searchEmployees.php?term=${encodeURIComponent(term)}`) // Adjust with the correct PHP endpoint
                .then(response => response.json())
                .then(data => {
                    const employeeList = document.getElementById('employee-list-pay');
                    employeeList.innerHTML = ''; // Clear previous content

                    if (data.length === 0) {
                        employeeList.innerHTML = '<p>No employees found.</p>';
                    } else {
                        data.forEach(employee => {
                            const middleName = employee.MiddleName ? employee.MiddleName : '';
                            const fullName = `${employee.FirstName} ${middleName} ${employee.LastName}`.replace(/\s+/g, ' ').trim();

                            employeeList.innerHTML += `
                                                                    <div class="employee-card">
                                                                        <p>${fullName}</p>
                                                                        <button class="gen-pay-button" onclick="loadPayslipsDistributionPage(${employee.EmployeeID})">Generate Payslip</button>
                                                                    </div>
                                                                `;
                        });
                    }
                })
                .catch(error => console.error('Error searching employees:', error));
        }


        // Function to load the payslip page
        function loadPayslipsDistributionPage(employeeId) {
            sessionStorage.setItem('employeeId', employeeId);
            console.log('Employee ID stored:', employeeId); // Debugging line
            loadPage('AdminPdfDistributionContent.php'); // Load the payslip page



        }
        document.addEventListener('input', function (event) {
            if (event.target && event.target.id === 'search-bar') {
                const searchTerm = event.target.value;
                console.log("Search term:", searchTerm); // Debugging line
                searchEmployeesForPayslipsDistribution(searchTerm); // Call search function with the input value
            }
        });

        // Add event listeners for cutoff radio buttons
        document.querySelectorAll('input[name="cutoff"]').forEach((input) => {
            input.addEventListener('change', calculateNetPay);
        });
        //--<<


        //-->>
        //-->>AdminPdfDistributionContent.php
        // Function to populate the year filter and set the default month and year
        function populateYearFilterDistribution() {
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

        // Apply the filter based on selected month and year
        function applyPayslipFilter() {
            const month = document.getElementById('monthFilter').value;
            const year = document.getElementById('yearFilter').value;
            fetchPayslips(month, year);
        }

        // Fetch and display payslip data with optional filters for month and year
        function fetchPayslips(month = '', year = '') {
            const employeeId = sessionStorage.getItem('employeeId');  // Retrieve employeeId from sessionStorage

            if (employeeId) {
                const url = `getPayslipsPdf.php?employeeId=${employeeId}&month=${month}&year=${year}`;
                fetch(url)
                    .then(response => response.json())
                    .then(data => displayPayslips(data))
                    .catch(error => console.error('Error fetching payslips:', error));
            } else {
                console.error('No employeeId found in sessionStorage');
            }
        }

        // Display the fetched payslips
        function displayPayslips(payslips) {
            const payslipContainer = document.getElementById('payslipContainer');
            payslipContainer.innerHTML = ''; // Clear existing content

            if (payslips.length === 0) {
                payslipContainer.innerHTML = '<p>No payslips found.</p>';
            } else {
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
                                                            <button onclick="releasepayslip(${payslip.PayrollID})">Release Payslip</button>
                                                            <button onclick="deletepayslip(${payslip.PayrollID})">Delete Payslip</button>
                                                        `;
                    payslipContainer.appendChild(card);
                });
            }
        }

        function releasepayslip(payrollID) {
            fetch('AdminreleasePayslip.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ payrollID: payrollID })
            })
                .then(response => response.json())  // Ensure the response is parsed as JSON
                .then(data => {
                    if (data.success) {
                        alert(data.message);  // Display success message
                        // Optionally, refresh the payslip data to reflect changes
                        applyPayslipFilter(); // Assuming this is your function to reload the payslip data
                    } else {
                        alert(data.message);  // Display error message
                    }
                })
                .catch(error => console.error('Error releasing payslip:', error));
        }

        function deletepayslip(payrollID) {
            if (confirm("Are you sure you want to delete this payslip?")) {
                fetch('AdmindeletePayslip.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ payrollID: payrollID })
                })
                    .then(response => response.json())  // Ensure the response is parsed as JSON
                    .then(data => {
                        if (data.success) {
                            alert(data.message);  // Display success message
                            // Optionally, refresh the payslip data to reflect changes
                            applyPayslipFilter(); // Assuming this is your function to reload the payslip data
                        } else {
                            alert(data.message);  // Display error message
                        }
                    })
                    .catch(error => console.error('Error deleting payslip:', error));
            }
        }
        //--<<

        //--<<
    </script>

</body>

</html>