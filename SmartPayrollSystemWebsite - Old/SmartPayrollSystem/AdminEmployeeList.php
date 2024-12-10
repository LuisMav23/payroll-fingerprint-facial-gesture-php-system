<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee List</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }

        .employee-portal {
            padding: 20px;
        }

        .header {
            background-color: #fff;
            color: #000000;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .filter-bar {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

            .filter-bar input[type="search"] {
                padding: 8px;
                width: 250px;
                border: 1px solid #ccc;
                border-radius: 4px;
                margin-right: 10px;
            }

            .filter-bar label {
                margin-right: 15px;
                display: flex;
                align-items: center;
            }

                .filter-bar label input {
                    margin-right: 5px;
                }

        #new-hire-btn {
            margin-left: auto;
            padding: 8px 12px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

            #new-hire-btn:hover {
                background-color: #0056b3;
            }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
        }

            table th, table td {
                padding: 12px 15px;
                text-align: left;
                border: 1px solid #ddd;
            }

            table th {
                background-color: #f4f4f4;
            }

            table tr:hover {
                background-color: #f1f1f1;
            }

        .view-profile-btn {
            padding: 6px 12px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
        }

            .view-profile-btn:hover {
                background-color: #0056b3;
            }

        .header {
            background-color: #fff;
            color: #000000;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
    </style>
</head>
<body>

    <div class="employee-portal">
        <div class="header">
            <h1>Employee List</h1>
            <div>
                Welcome, <span id="username">[Username]</span>
                <button class="logout-btn" onclick="logout()">Logout</button>
            </div>
        </div>
        </div>
    <div>

        <div class="filter-bar">
            <input type="search" id="search-bar" placeholder="Search Employee">
            <label><input type="checkbox" id="new-hire"> New Hire</label>
            <label><input type="checkbox" id="active"> Active</label>
            <button id="new-hire-btn" onclick="redirectToNewHire()">+ New Hire</button>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Position</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="employee-list">
                <!-- Employee rows will be rendered here -->
            </tbody>
        </table>
    </div>
</body>
</html>