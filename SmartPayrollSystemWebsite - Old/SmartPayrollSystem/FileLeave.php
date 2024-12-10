<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Leave</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        h2 {
            color: #333;
            margin-bottom: 20px;
        }

        .container {
            max-width: 600px;
            margin: 30px auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        select, input[type="date"], textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        textarea {
            resize: none;
        }

        .file-upload {
            margin-bottom: 20px;
        }

            .file-upload input[type="file"] {
                display: none;
            }

            .file-upload label {
                display: inline-block;
                padding: 10px 15px;
                background-color: #007bff;
                color: white;
                border-radius: 4px;
                cursor: pointer;
            }

                .file-upload label:hover {
                    background-color: #0056b3;
                }

        .file-name {
            margin-top: 10px;
            font-size: 14px;
            color: #555;
        }

        button {
            background-color: #28a745;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

            button:hover {
                background-color: #218838;
            }

        #responseMessage {
            margin-top: 15px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>File Leave</h2>
        <p>Submit your leave request below:</p>

        <form id="leaveForm" method="POST" enctype="multipart/form-data">
            <label for="leaveType">Leave Type:</label>
            <select id="leaveType" name="leaveType" required>
                <option value="Sick Leave">Sick Leave</option>
                <option value="Emergency Leave">Emergency Leave</option>
                <option value="Vacation Leave">Vacation Leave</option>
                <option value="Maternity Leave">Maternity Leave</option>
                <option value="Paternity Leave">Paternity Leave</option>
            </select>

            <label for="leaveStartDate">Leave Start Date:</label>
            <input type="date" id="leaveStartDate" name="leaveStartDate" required>

            <label for="leaveEndDate">Leave End Date:</label>
            <input type="date" id="leaveEndDate" name="leaveEndDate" required>

            <label for="reason">Reason:</label>
            <textarea id="reason" name="reason" rows="4" placeholder="Enter the reason for your leave..." required></textarea>

            <div class="file-upload">
                <label for="fileAttachment">Attach File</label>
                <input type="file" id="fileAttachment" name="fileAttachment" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                <div class="file-name" id="fileName">No file chosen</div>
            </div>

            <button type="submit">Submit Leave Request</button>
        </form>

        <div id="responseMessage"></div>
    </div>
</body>
</html>
