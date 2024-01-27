<?php
include("../DBCONFIG.PHP");
include("../LoginControl.php");
include("../BASICLOGININFO.PHP");

session_start();

$master = $_SESSION['master'];

if (isset($_POST['searchbydate_btn'])){
$datesearch = $_POST['payPeriod'];
if(empty($datesearch)){
  $searchquery = "SELECT * FROM payperiods";
$search_result = filterTable($searchquery);


}else{
$searchquery = "SELECT * FROM payperiods WHERE pperiod_range = '$datesearch'";
$search_result = filterTable($searchquery);
}
}else {

$searchquery = "SELECT * FROM payperiods";
$search_result = filterTable($searchquery);

}

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
<link rel="stylesheet" href="../jquery-ui-1.12.1/jquery-ui.css">
<script src="../jquery-ui-1.12.1/jquery-3.2.1.js"></script>
<script src="../jquery-ui-1.12.1/jquery-ui.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker@3.1.0/daterangepicker.css" />
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker@3.1.0/daterangepicker.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

<script type ="text/javascript">

  $( function() {
      $( "#datepicker" ).datepicker({ dateFormat: 'yy-mm-dd'});
      } );
  
</script>
</head>
<body>

<!--Header-part-->

<?php
INCLUDE ('NAVBAR.php');
?>


<div id="content">

<div id="content-header">
  <div id="breadcrumb"> <a href="index.html" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a></div>
</div>
<span class="span6">
      <h3 style="margin-top: 30px">Manage Payroll Periods</h3>
    </span>

  <div class="container-fluid">
  <div class ="row-fluid">
  <div class ="row-fluid">
     <div class="span12">
        <div class="widget-box">
          
          <div class="widget-title">
            <ul class="nav nav-tabs" id="myTab">
              <li><a href="adminMasterfile.php"><i class="icon-user"></i> Employees</a></li>
              <li><a href="adminMasterfileDept.php"><i class="icon-th"></i> Manage Departments</a></li>
              <li><a href="adminMasterfileShift.php"><i class="icon-time"></i> Manage Shifts</a></li>
              <li><a href="adminMasterfileHoliday.php"><i class="icon-calendar"></i> Manage Holidays</a></li>
              <li><a href="adminMasterfileLeaves.php"><i class="icon-calendar"></i> Manage Leaves</a></li>
              <li class="active"><a href="adminPAYROLLPERIODS.php"><i class="icon-user"></i> Manage Payroll Periods</a></li>
              <?php
            if ($master) {
                echo '
                    <li><a href="adminPositions.php"><i class="icon-th"></i> Manage Positions</a></li>
                    <li><a href="adminSalaryGrades.php"><i class="icon-th"></i> Manage Salary Grades</a></li>
                ';
            }
            ?>
            </ul>
          </div>
          </div>
      </div>

          <div class="widget-content tab-content">
            
          <div id="tab1" class="tab-pane fade in active">

          
          <div class = "span3">
                <br><br>
                <a href ="<?php $_SERVER['PHP_SELF']; ?>" class = "btn btn-success" style = "float:right"><span class="icon"><i class="icon-refresh"></i></span> Refresh</a>
                <a href ="adminNEWPAYROLLPERIOD.php" class = "btn btn-info"><span class="icon"><i class="icon-plus"></i></span> Add New Payroll Period</a>
              </div>
            
          <div class="row-fluid">
    <div class="span6">
      
        <div class="control-group">
            <label class="control-label" style="margin-left: 90px">Search by start date: </label>
            <div class="controls" style="margin-left:90px; margin-top:20px">
                <div id="search">
                    <form action="<?php $_SERVER['PHP_SELF']; ?>" method="post">
                        <select name="payPeriod">
                            <option value="" >Select Payroll Period</option>
                            <?php
                            $query = "SELECT payperiod_ID, pperiod_range FROM payperiods";
                            $result = mysqli_query($conn, $query);

                            if (!$result) {
                                die("Query failed: " . mysqli_error($conn));
                            }

                            $selectedPayPeriod = isset($_POST['payPeriod']) ? $_POST['payPeriod'] : '';

                            if (mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $pperiodID = $row['payperiod_ID'];
                                    $pperiodRange = $row['pperiod_range'];

                                    // Output each option with 'selected' attribute if it matches the submitted value
                                    echo '<option value="' . $pperiodRange . '" ' . ($selectedPayPeriod == $pperiodID ? 'selected' : '') . '>' . $pperiodRange . '</option>';
                                }
                            } else {
                                echo "No pay periods found.";
                            }
                            ?>
                        </select>
                        <button type="submit" class="btn btn-primary" name="searchbydate_btn" style="height: 32px; margin-left: 5px; margin-top: 4px"><i class="icon-search icon-white">
                        </i></button>
                    </form>
                    
                </div>
                
            </div>
            
        </div>
        
    </div>
    
</div>

            </div>
            <br>
            <div class = "row-fluid">
           <!--TABLE START-->
            <table class="table table-bordered data-table">
               <thead>
                <tr>
                  <th>Payroll Period ID</th>
                  <th>Payroll Period</th>
                  <th>Period Start</th>
                  <th>Period End</th>
                  <th>Period Days</th>
                  <th>Payroll Year</th>
                  
                </tr>
              </thead>
              <tbody> 

               <?php

              

               function filterTable($searchquery)
               {

                    $conn1 = mysqli_connect("localhost:3307","root","","masterdb");
                    $filter_Result = mysqli_query($conn1,$searchquery) or die ("failed to query masterfile ".mysqli_error($conn1));
                    return $filter_Result;
               }

               
               while($row1 = mysqli_fetch_array($search_result)):;
               ?> 
                  <tr class="gradeX">
                  <td><?php echo $row1['payperiod_ID'];?></td>
                  <td><?php echo $row1['pperiod_range'];?></td>
                  <td><?php echo $row1['pperiod_start'];?></td>
                  <td><?php echo $row1['pperiod_end'];?></td>
                  <td><?php echo $row1['payperiod_days'];?></td>
                  <td><?php echo $row1['pperiod_year'];?></td>
                  <!--<td><center><a href = "adminPRINTtimesheet.php?id=<?php echo $row1['emp_id']; ?>" class = "btn btn-info btn-mini" target = "_blank"><span class="icon"><i class="icon-print"></i></span> Print</a>
                    -->
                   
                </tr>
              <?php endwhile;?>
              </tbody>
            </table>
            </div>
          <!-- INSIDE WIDGET INSIDE WIDGET -->

          </div>
        </div>
      </span>  
      <span class = "span3">
      </span>
    </div>
    <hr>
    <div class="row-fluid">
      


    </div>
    <hr>
    <div class="row-fluid">
      
      

    </div>
  </div>
</div>
</div>
</div>
<div class="row-fluid">
<div id="footer" class="span12"> 2023 &copy; WEB-BASED TIMEKEEPING AND PAYROLL SYSTEM USING FINGERPRINT BIOMETRICS</div>
</div>
<script src="../js/maruti.dashboard.js"></script> 
<script src="../js/excanvas.min.js"></script> 

<script src="../js/bootstrap.min.js"></script> 
<script src="../js/jquery.flot.min.js"></script> 
<script src="../js/jquery.flot.resize.min.js"></script> 
<script src="../js/jquery.peity.min.js"></script> 
<script src="../js/fullcalendar.min.js"></script> 
<script src="../js/maruti.js"></script> 

</body>
</html>