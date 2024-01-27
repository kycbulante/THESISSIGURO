<?php
include("../DBCONFIG.PHP");
include("../LoginControl.php");
include("../BASICLOGININFO.PHP");
$query = "SELECT * FROM notifications";
$result = mysqli_query($conn, $query);

?>

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


</head>
<body>

<!--Header-part-->

<?php
INCLUDE ('NAVBAR.php');
?>

<div id="content">

  <div id="content-header">
    <div id="breadcrumb"> <a href="#" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a></div>
    
  </div>

  <div class="container-fluid">
  <div class ="row-fluid">
     <div class="span12">
        <div class="widget-box">
          <div class="widget-title">
            <ul class="nav nav-tabs" id="myTab">
              <li class="active"><a href="adminDashboard.php"><i class="icon-file"></i> Notifications</a></li>

            </ul>
          </div>
      <span class = "span3">
        
      </span>

      <span class="span6">
        <h4><center>Notifications</center></h4>
      </span>


        <div class="widget-box">
          <div class = "widget-title"><span class="icon"><i class ="icon-user"></i></span>
            <h5> Notifications</h5>
          </div>
          <div class="widget-content no padding">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Notification</th>
                <!-- Add more columns as needed -->
            </tr>
        </thead>
        <tbody>
            <?php
            // Loop through the notifications and display them in the table rows
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                if ($row['type'] == 'Overtime') {
                    echo "<td><a href='./OVERTIME/adminOT.php'>{$row['message']}</a></td>";
                } if ($row['type'] == 'Leave') {
                    echo "<td><a href='./LEAVES/adminLEAVES.php'>{$row['message']}</a></td>";
                } 

                echo "</td>";
                // Add more columns as needed
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</div>


    
      <span class="span2">
      </span>
    </div>
    <hr>

    

 
</body>
</html>