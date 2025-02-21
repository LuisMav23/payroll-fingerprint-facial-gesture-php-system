<?php require_once(dirname(__FILE__) . '/config.php');
if (!isset($_SESSION['Login_Type'])) {
    header('location:' . BASE_URL);
}
?>

<!DOCTYPE html>
<html>

<head>s
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <title>My Overtime - Payroll</title>

    <link rel="stylesheet" href="<?php echo BASE_URL; ?>bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>plugins/datatables/dataTables.bootstrap.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>plugins/datepicker/datepicker3.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>dist/css/AdminLTE.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>dist/css/skins/_all-skins.min.css">
</head>

<body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">

        <?php require_once(dirname(__FILE__) . '/partials/topnav.php'); ?>
        <?php require_once(dirname(__FILE__) . '/partials/sidenav.php'); ?>

        <div class="content-wrapper">
            <section class="content-header">
                <h1>My Overtime Requests</h1>
                <ol class="breadcrumb">
                    <li><a href="<?php echo BASE_URL; ?>"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active">My Overtime</li>
                </ol>
            </section>

            <section class="content">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="box">
                            <div class="box-header">
                                <h3 class="box-title">My Overtime Requests</h3>
                            </div>
                            <div class="box-body">
                                <table id="myovertime" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>DATE</th>
                                            <th>HOURS</th>
                                            <th>STATUS</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Employee-specific overtime requests will be loaded here -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Overtime Request Form -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="box box-primary">
                            <div class="box-header">
                                <h3 class="box-title">Request Overtime</h3>
                            </div>
                            <form id="overtime-form">
                                <div class="box-body">
                                    <div class="form-group">
                                        <label for="overtime_date">Date</label>
                                        <input type="text" class="form-control datepicker" id="overtime_date" name="overtime_date" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="overtime_hours">Hours</label>
                                        <input type="number" class="form-control" id="overtime_hours" name="overtime_hours" min="1" max="12" required>
                                    </div>
                                </div>
                                <div class="box-footer">
                                    <button type="submit" class="btn btn-primary">Submit Request</button>
                                </div>
                            </form>
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
    <script src="<?php echo BASE_URL; ?>plugins/bootstrap-notify/bootstrap-notify.min.js"></script>
    <script src="<?php echo BASE_URL; ?>plugins/datepicker/bootstrap-datepicker.js"></script>
    <script src="<?php echo BASE_URL; ?>dist/js/app.min.js"></script>

    <script type="text/javascript">
        var baseurl = '<?php echo BASE_URL; ?>';

        $(document).ready(function () {
            // Fetch logged-in employee's overtime requests
            $.ajax({
                url: baseurl + 'ajax/?case=GetEmployeeOvertime',
                type: 'GET',
                data: { employee_id: '<?php echo $_SESSION["Employee_ID"]; ?>' },
                success: function (response) {
                    if (response.data) {
                        $('#myovertime tbody').empty();
                        response.data.forEach(record => {
                            const row = `
                                <tr>
                                    <td>${record[0]}</td>
                                    <td>${record[1]}</td>
                                    <td>${record[2]}</td>
                                    <td>${record[3]}</td>
                                </tr>`;
                            $('#myovertime tbody').append(row);
                        });
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Error fetching overtime data:', error);
                }
            });

            // Initialize datepicker
            $('.datepicker').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true
            });

            // Submit overtime request
            $('#overtime-form').submit(function (e) {
                e.preventDefault();
                const formData = {
                    employee_id: '<?php echo $_SESSION["Employee_ID"]; ?>',
                    date: $('#overtime_date').val(),
                    hours: $('#overtime_hours').val()
                };

                $.ajax({
                    url: baseurl + 'ajax/?case=RequestOvertime',
                    type: 'POST',
                    data: formData,
                    success: function (response) {
                        if (response.success) {
                            $.notify({ message: 'Overtime request submitted!' }, { type: 'success' });
                            location.reload();
                        } else {
                            $.notify({ message: 'Error submitting request!' }, { type: 'danger' });
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('Error submitting overtime request:', error);
                        $.notify({ message: 'An error occurred.' }, { type: 'danger' });
                    }
                });
            });
        });
    </script>
</body>

</html>
