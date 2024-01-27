

<!DOCTYPE html>
<html lang="en">
<head>
<title>Admin Home</title>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<link rel="stylesheet" href="../css/bootstrap.min.css" />
<link rel="stylesheet" href="../css/bootstrap-responsive.min.css" />
<link rel="stylesheet" href="../css/fullcalendar.css" />
<link rel="stylesheet" href="../css/maruti-style.css" />
<link rel="stylesheet" href="../css/maruti-media.css" class="skin-color" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker@3.1.0/daterangepicker.css" />
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker@3.1.0/daterangepicker.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>


<?php
include("../DBCONFIG.PHP");
include("../LoginControl.php");
include("../BASICLOGININFO.PHP");

$adminId = $_SESSION['adminId'];

// Pagination setup
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$recordsPerPage = 10; // You can adjust this number based on your preference

$startFrom = ($page - 1) * $recordsPerPage;

// Query for fetching paginated records
$query = "SELECT * FROM adminactivity_log  LIMIT $startFrom, $recordsPerPage";
$result = mysqli_query($conn, $query);

// Query to get total number of records
$countQuery = "SELECT COUNT(*) as total FROM adminactivity_log";
$countResult = mysqli_query($conn, $countQuery);
$countRow = mysqli_fetch_assoc($countResult);
$totalRecords = $countRow['total'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Head content here -->
</head>
<body>

<!-- Header and navigation -->
<?php
include('NAVBAR.php');
?>

<div id="content">
    <!-- Content section -->
    <div id="content-header">
        <div id="breadcrumb"> <a href="#" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a></div>
    </div>

    <div class="container-fluid">
        <div class="row-fluid">
            <div class="span12">
                <!-- Widget box -->
                <div class="widget-box">
                    <!-- Widget title -->
                    <div class="widget-title">
                        <ul class="nav nav-tabs" id="myTab">
                            <li class=""><a href="adminDashboard.php"><i class="icon-file"></i> Dashboard</a></li>
                            <li><a href="adminATTENDANCEDaily.php"><i class="icon-calendar"></i> Daily Attendance</a></li>
                            <li class="active"><a href="adminACTIVITYLogs.php"><i class="icon-time"></i> Activity Logs</a></li>
                        </ul>
                    </div>

                    <span class="span3"></span>

                    <span class="span6">
                        <h4><center>Activity Logs</center></h4>
                    </span>

                    <!-- Activity Logs widget box -->
                    <div class="widget-box">
                        <div class="widget-title"><span class="icon"><i class="icon-user"></i></span>
                            <h5>Activity Logs</h5>
                        </div>
                        <div class="widget-content no-padding">
    <style>
        /* Custom styles for responsive table */
        table {
            width: 100%;
            max-width: 100%;
        }

        td.activity-column {
            max-width: 100px; /* Adjust the max-width as needed */
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        @media (min-width: 576px) {
            table {
                width: 80%;
            }
        }

        @media (min-width: 768px) {
            table {
                width: 70%;
            }
        }

        @media (min-width: 992px) {
            table {
                width: 60%;
            }
        }

        @media (min-width: 1200px) {
            table {
                width: 50%;
            }
        }
    </style>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>LOG ID</th>
                <th>Employee ID</th>
                <th>Activity</th>
                <th>Timestamp</th>
                <!-- Add more columns as needed -->
            </tr>
        </thead>
        <tbody>
            <?php
            // Loop through the paginated records and display them in the table rows
            while ($row1 = mysqli_fetch_array($result)) {
            ?>
                <tr class="gradeX">
                    <td><?php echo $row1['log_id']; ?></td>
                    <td><?php echo $row1['emp_id']; ?></td>
                    <td class="activity-column"><?php echo $row1['activity']; ?></td>
                    <td><?php echo $row1['log_timestamp']; ?></td>
                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>
</div>


                    <!-- Pagination links -->
                    <div class="pagination">
                        <?php
                        $totalPages = ceil($totalRecords / $recordsPerPage);

                        for ($i = 1; $i <= $totalPages; $i++) {
                            echo "<a href='adminACtivitylogs.php?page=$i'>$i</a> ";
                        }
                        ?>
                    </div>

                    <span class="span2"></span>
                </div>
                <hr>
            </div>
        </div>
    </div>
</div>
</body>
</html>
