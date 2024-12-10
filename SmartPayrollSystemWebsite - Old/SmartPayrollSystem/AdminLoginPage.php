<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Payroll System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        /* Header */
        .header {
            background-color: #2c3e50;
            color: white;
            padding: 10px;
            text-align: left;
        }

        .header h1 {
            margin: 0;
            font-size: 30px;
            padding-left: 20px;
        }

        /* Navbar */
        .navbar {
            background-color: #2c3e50;
            overflow: hidden;
            padding: 10px;
        }

        .navbar img {
            width: 50px;
            height: 50px;
            margin-right: 20px;
            margin-left: 20px;
        }

        .navbar a {
            float: right;
            padding: 12px;
            text-decoration: none;
            color: #fff;
            font-weight: bold;
            border-radius: 5px;
        }

        .navbar a:hover {
            background-color: #ddd;
        }

        .navbar a.active {
            background-color: #aaa;
            color: #fff;
        }

        /* Layout adjustments for Admin Login Page */
        .login-page {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 40px;
            background-color: #fff;
        }

        .login-container {
            background-color: #fff;
            width: 450px;
            padding: 30px;
            text-align: center;
            border-radius: 10px;
            box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            gap: 20px;
            justify-content: center;
            /* Centers the content vertically */
            align-items: center;
            /* Centers the content horizontally */
        }

        .login-container img {
            width: 80px;
            height: 80px;
            margin-bottom: 20px;
            /* Adds spacing below the image */
        }

        .login-container h2 {
            margin: 10px 0;
            font-size: 24px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="password"] {
            width: 90%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            background-color: #5c6bc0;
            border: none;
            color: #fff;
            padding: 12px 20px;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }

        button:hover {
            background-color: #3f4f8b;
        }

        .show-password {
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9em;
            color: #555;
        }

        .show-password input[type="checkbox"] {
            margin-left: 5px;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
        }

        .modal-content {
            background-color: #fff;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 300px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
        }

        .modal-content h3 {
            margin: 0 0 15px;
            color: #FF0000;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover {
            color: #000;
        }

        /* Adjust layout for smaller screens */
        @media (max-width: 768px) {
            .login-page {
                flex-direction: column;
                gap: 20px;
            }

            .login-container {
                width: 90%;
                padding: 20px;
            }

            .login-page img {
                width: 80%;
                max-width: 300px;
                margin-bottom: 20px;
            }
        }
    </style>
</head>

<body>

    <!-- Navigation Bar -->
    <div class="navbar">
        <img src="Resources/logo.png" alt="Logo">
        <a href="AdminLoginPage.php" class="active">Admin Login</a>
        <a href="EmployeeLoginPage.php">Employee Login</a>
        <a href="index.php">Home</a>
    </div>

    <!-- Admin Login Page -->
    <div class="login-page">
        <!-- Image Section -->
        <div class="image-section">
            <img src="Resources/img1.png" alt="Admin Login Illustration">
        </div>

        <!-- Login Form -->
        <div class="login-container">
            <img src="Resources/admin.png" alt="Admin Icon">
            <h2>ADMIN LOGIN</h2>
            <input type="text" id="username" placeholder="Username">
            <input type="password" id="password" placeholder="Password">

            <div class="show-password">
                <label for="show-password">Show Password</label>
                <input type="checkbox" id="show-password" onclick="togglePasswordVisibility()">
            </div>

            <button onclick="login()">Login</button>
        </div>
    </div>

    <!-- Modal -->
    <div id="errorModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h3>Warning!!!</h3>
            <p id="modalMessage"></p>
        </div>
    </div>

    <!-- Success Modal -->
    <div id="successModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeSuccessModal()">&times;</span>
            <h3>Success</h3>
            <p id="successMessage">Login Successful! Redirecting...</p>
        </div>
    </div>

    <script>
        function login() {
            var username = document.getElementById('username').value;
            var password = document.getElementById('password').value;

            // Send credentials to PHP script using fetch
            fetch('adminLogin.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `username=${encodeURIComponent(username)}&password=${encodeURIComponent(password)}`
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Redirect to AdminPortal.php on successful login
                        //window.location.href = 'AdminPortal.php';
                        // Show success modal
                        showSuccessModal();
                    } else {
                        showModal('Invalid credentials or not an Admin.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }

        function togglePasswordVisibility() {
            const passwordField = document.getElementById('password');
            passwordField.type = passwordField.type === 'password' ? 'text' : 'password';
        }

        function showModal(message) {
            const modal = document.getElementById('errorModal');
            const modalMessage = document.getElementById('modalMessage');
            modalMessage.innerText = message;
            modal.style.display = 'block';
        }

        function closeModal() {
            const modal = document.getElementById('errorModal');
            modal.style.display = 'none';
        }

        function showSuccessModal() {
            const successModal = document.getElementById('successModal');
            successModal.style.display = 'block';

            // Redirect to EmployeePortal.php after 2 seconds
            setTimeout(() => {
                window.location.href = 'AdminPortal.php';
            }, 2000);
        }

        function closeSuccessModal() {
            const successModal = document.getElementById('successModal');
            successModal.style.display = 'none';
        }

        // Event listener for Enter key
        document.addEventListener('keydown', function (event) {
            if (event.key === 'Enter') {
                login();
            }
        });

        // Close modal when clicking outside of it
        window.onclick = function (event) {
            const errorModal = document.getElementById('errorModal');
            const successModal = document.getElementById('successModal');
            if (event.target == errorModal) {
                errorModal.style.display = 'none';
            } else if (event.target == successModal) {
                successModal.style.display = 'none';
            }
        };
    </script>
</body>

</html>