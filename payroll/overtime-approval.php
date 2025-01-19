<?php
require_once(dirname(__FILE__) . '/config.php');

// Redirect if user is not logged in
if (!isset($_SESSION['Admin_ID'])) {
    header('location:' . BASE_URL);
    exit;
}

$isAdmin = $_SESSION['Login_Type'] == 'admin';
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Attendance - Payroll</title>
    <style>
        @media print {
            @page {
                size: A2 landscape;
                margin: 0;
                transform: scale(1.35);
                transform-origin: 0 0;
            }
        }
    </style>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>plugins/datatables/dataTables.bootstrap.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>dist/css/AdminLTE.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>dist/css/skins/_all-skins.min.css">
</head>

<body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">

        <?php require_once(dirname(__FILE__) . '/partials/topnav.php'); ?>
        <?php require_once(dirname(__FILE__) . '/partials/sidenav.php'); ?>

        <div class="content-wrapper">
            <section class="content-header">
                <h1>Attendance</h1>
                <ol class="breadcrumb">
                    <li><a href="<?php echo BASE_URL; ?>"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active">Attendance</li>
                </ol>
            </section>

            <section class="content">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="box">
                            <div class="box-header">
                                <h3 class="box-title">
                                    <?php echo $isAdmin ? 'Employee Attendance' : 'Your Attendance'; ?>
                                </h3>
                            </div>
                            <div class="box-body">
                                <div class="table-responsive">
                                    <table id="attendanceTable" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <?php if ($isAdmin): ?>
                                                    <th>Photo</th>
                                                    <th>Emp Code</th>
                                                    <th>Name</th>
                                                    <th>Designation</th>
                                                    <th>Department</th>
                                                    <th>Actions</th>
                                                <?php else: ?>
                                                    <th>DATE</th>
                                                    <th>PUNCH-IN</th>
                                                    <th>PUNCH-OUT</th>
                                                    <th>WORK HOURS</th>
                                                    <th>TIME IN DESC</th>
                                                    <th>TIME OUT DESC</th>
                                                <?php endif; ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Table data will be dynamically populated with AJAX -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <footer class="main-footer">
            <strong>&copy; <?php echo date("Y"); ?> Employee Management System</strong>
        </footer>
    </div>

    <script src="<?php echo BASE_URL; ?>plugins/jQuery/jquery-2.2.3.min.js"></script>
    <script src="<?php echo BASE_URL; ?>bootstrap/js/bootstrap.min.js"></script>
    <script src="<?php echo BASE_URL; ?>plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="<?php echo BASE_URL; ?>plugins/datatables/dataTables.bootstrap.min.js"></script>
    <script src="<?php echo BASE_URL; ?>dist/js/app.min.js"></script>
    <script>
        const baseurl = '<?php echo BASE_URL; ?>';
        const isAdmin = <?php echo json_encode($isAdmin); ?>;

        $(document).ready(function () {
            const fetchURL = isAdmin
                ? `${baseurl}ajax/?case=GetAllEmployees`
                : `${baseurl}ajax/?case=LoadingAttendanceByEmpCode&emp_code=<?php echo $_SESSION['Emp_Code']; ?>`;

            // Fetch data based on user type
            $.ajax({
                url: fetchURL,
                type: 'GET',
                success: function (response) {
                    const tableBody = $('#attendanceTable tbody');
                    tableBody.empty();

                    if (isAdmin) {
                        response.data.forEach(record => {
                            const row = `<tr>
                                <td>${record[6]}</td>
                                <td>${record[0]}</td>
                                <td>${record[1] + " " + record[2]}</td>
                                <td>${record[3]}</td>
                                <td>${record[4]}</td>
                                <td>
                                    <button class="btn btn-primary viewAttendance" data-emp-code="${record[0]}">
                                        View Attendance
                                    </button>
                                </td>
                            </tr>`;
                            tableBody.append(row);
                        });
                    } else {
                        response.data.forEach(attendance => {
                            const row = `<tr>
                                <td>${attendance[0]}</td>
                                <td>${attendance[3]}</td>
                                <td>${attendance[4]}</td>
                                <td>${attendance[5]}</td>
                                <td>${attendance[6]}</td>
                                <td>${attendance[7]}</td>
                            </tr>`;
                            tableBody.append(row);
                        });
                    }

                    $('#attendanceTable').DataTable();
                },
                error: function (xhr, status, error) {
                    console.error('AJAX Error: ', status, error);
                }
            });
        });
    </script>
</body>

</html>
