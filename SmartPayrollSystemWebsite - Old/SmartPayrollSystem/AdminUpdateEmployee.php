<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Employee Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background-color: #f5f5f5;
            color: #333;
        }

        .header {
            background-color: #fff;
            color: #000;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #ddd;
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

        .profile-container {
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            max-width: 100%; /* Take full width */
            width: calc(100% - 40px); /* Add padding around */
            box-sizing: border-box; /* Include padding and borders in width */
        }

        .header h1 {
            font-size: 1.5em;
            margin: 0;
        }

        .header .actions {
            display: flex;
            gap: 10px;
        }

        .actions button {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 0.9em;
        }

            .actions button:hover {
                background-color: #0056b3;
            }

        .image-section {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 20px;
            flex-wrap: wrap; /* Ensure proper wrapping on smaller screens */
        }

        .profile-image {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            background-color: #ddd;
            object-fit: cover;
        }

        .profile-info {
            flex: 1;
        }

            .profile-info h2 {
                margin: 0;
                font-size: 1.5em;
            }

            .profile-info p {
                margin: 5px 0;
            }

        .tabs {
            display: flex;
            justify-content: space-around;
            border-bottom: 2px solid #ddd;
            margin-bottom: 20px;
            flex-wrap: wrap; /* Ensure wrapping */
        }

            .tabs a {
                padding: 10px 20px;
                text-decoration: none;
                color: #007bff;
                font-weight: bold;
                text-align: center; /* Center text for better alignment */
            }

                .tabs a.active {
                    border-bottom: 2px solid #007bff;
                }

        .section {
            margin-bottom: 20px;
        }

            .section h3 {
                font-size: 1.2em;
                border-bottom: 1px solid #ddd;
                padding-bottom: 5px;
            }

        .form-group {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .form-item {
            flex: 1 1 calc(50% - 20px);
        }

            .form-item label {
                font-weight: bold;
                display: block;
                margin-bottom: 5px;
            }

            .form-item input,
            .form-item select {
                width: 100%;
                padding: 10px;
                border: 1px solid #ccc;
                border-radius: 4px;
                background-color: #f9f9f9;
                font-size: 0.9em;
            }

        @media (max-width: 768px) {
            .profile-container {
                padding: 10px;
            }

            .form-item {
                flex: 1 1 100%; /* Full width on smaller screens */
            }

            .tabs a {
                padding: 10px;
            }

            .header h1 {
                font-size: 1.2em;
            }

            .actions button {
                font-size: 0.8em;
                padding: 8px 12px;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Employee List</h1>
        <div>
            Welcome, <span id="username">[Username]</span>
            <button class="logout-btn" onclick="logout()">Logout</button>
        </div>
    </div>

    <div class="profile-container">
        <div class="header">
            <div class="actions">
                <button onclick="updateEmployee(event)">Save</button>
                <button onclick="cancelUpdate()">Cancel</button>
            </div>
        </div>

        <div class="image-section">
            <img id="employee-face-image" class="profile-image" src="" alt="Employee Image">
            <div class="profile-info">
                <h2 id="employee-name"></h2>
                <p>Active | Hired <span id="employment-date-up"></span> | <span id="employee-position-up"></span></p>
                <p>Username: <span id="username"></span> | Password: <span id="password"></span></p>
            </div>
        </div>

        <div class="tabs">
            <a href="#profile" class="active">Profile</a>
            <a href="#documents">Documents</a>
        </div>

        <div id="profile-section" class="section">
            <h3>Personal Information</h3>
            <div class="form-group">
                <div class="form-item">
                    <label for="first-name">First Name</label>
                    <input type="text" id="first-name" required oninput="validateName(this)">
                </div>
                <div class="form-item">
                    <label for="middle-name">Middle Name</label>
                    <input type="text" id="middle-name" oninput="validateName(this)">
                </div>
                <div class="form-item">
                    <label for="last-name">Last Name</label>
                    <input type="text" id="last-name" required oninput="validateName(this)">
                </div>
                <div class="form-item">
                    <label for="age">Age</label>
                    <input type="text" id="employee-age" required oninput="validateAge(this)">
                </div>
                <div class="form-item">
                    <label for="status">Status</label>
                    <!-- <input type="text" id="employee-status" readonly> -->
                    <select id="employee-status" name="status" required>
                        <option value="Single">Single</option>
                        <option value="Married">Married</option>
                        <option value="Divorced">Divorced</option>
                        <option value="Widowed">Widowed</option>
                        <option value="Separated">Separated</option>
                    </select>
                </div>
                <div class="form-item">
                    <label for="birth-date">Birth Date</label>
                    <input type="text" id="employee-birthdate" readonly>
                </div>
            </div>
        </div>

        <div id="contact-section" class="section">
            <h3>Contact Information</h3>
            <div class="form-group">
                <div class="form-item">
                    <label for="barangay">Barangay</label>
                    <input type="text" id="employee-barangay" required>
                </div>
                <div class="form-item">
                    <label for="street">Street Name, Building, House No.</label>
                    <input type="text" id="employee-streetnumber" required>
                </div>
                <div class="form-item">
                    <label for="city">City</label>
                    <input type="text" id="employee-city" required>
                </div>
                <div class="form-item">
                    <label for="postal-zip">Postal/Zip Code</label>
                    <input type="text" id="employee-zipcode" required>
                </div>
                <div class="form-item">
                    <label for="email">Email</label>
                    <input type="text" id="employee-email" required>
                </div>
                <div class="form-item">
                    <label for="contact">Contact No.</label>
                    <input type="text" id="employee-phone" oninput="validatePhonev1(this)" placeholder="+63">
                </div>
            </div>
        </div>

        <div id="work-section" class="section">
            <h3>Work Information</h3>
            <div class="form-group">
                <div class="form-item">
                    <label for="rate">Rate</label>
                    <input type="text" id="employee-salary" readonly>
                </div>
                <div class="form-item">
                    <label for="position">Position</label>
                    <!-- <input type="text" id="employee-position" readonly>  -->
                    <select id="position" name="position" required>
                        <option value="">Select Position</option>
                    </select>
                </div>
                <div class="form-item">
                    <label for="employment-date">Date of Employment</label>
                    <input type="text" id="employment-date" readonly>
                </div>
                <div class="form-item">
                    <label for="maxicare-type">Maxicare Type</label>
                    <!-- <input type="text" id="maxicare-type" readonly> -->
                    <select id="maxicare-type" name="maxicare-type" required>
                        <option value="not_applicable">not_applicable</option>
                        <option value="silver">silver</option>
                        <option value="platinum">platinum</option>
                        <option value="gold">gold</option>
                        <option value="platinum_plus">platinum_plus</option>
                    </select>
                </div>
                <div class="form-item">
                    <label for="loan">Company Loan</label>
                    <!-- <input type="text" id="employment-loan" readonly> -->
                    <select id="employment-loan" name="employment-loan" required>
                        <option value="No">No</option>
                        <option value="Yes">Yes</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
