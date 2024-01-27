<?php
include("../DBCONFIG.PHP");
include("../LoginControl.php");
include("../BASICLOGININFO.PHP");

session_start();

// if(isset($_SESSION['masterfilenotif'])){

// $mfnotif = $_SESSION['masterfilenotif'];
// ?>  
<script>
// alert("<?php echo $mfnotif;?>");
// </script>
<?php
// }
$master = $_SESSION['master'];
// -->
?> 


<!DOCTYPE html>
<html lang="en">
<head>
<title>Manage Holidays</title>
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
    <div id="breadcrumb"> <a href="index.html" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a>
      <a href ="adminMasterfileHoliday.php" class="tip-bottom"><i class ="icon-calendar"></i> Manage Holidays</a></div>
  </div>

  <div class="container-fluid">
    <div class ="row-fluid">
      <div class = "span10">
        <h3 style="margin-top:30px">Holidays</h3>        
      </div>
    </div>
   
<div class ="row-fluid">
     <div class="span12">
        <div class="widget-box">
          <div class="widget-title">
            <ul class="nav nav-tabs" id="myTab">
              <li><a href="adminMasterfile.php"><i class="icon-user"></i> Employees</a></li>
              <li><a href="adminMasterfileDept.php"><i class="icon-th"></i> Manage Departments</a></li>
              <li><a href="adminMasterfileShift.php"><i class="icon-time"></i> Manage Shifts</a></li>
              <li class="active"><a href="adminMasterfileHoliday.php"><i class="icon-calendar"></i> Manage Holidays</a></li>
              <li><a href="adminMasterfileLeaves.php"><i class="icon-calendar"></i> Manage Leaves</a></li>
              <li><a href="adminPAYROLLPERIODS.php"><i class="icon-user"></i> Manage Payroll Periods</a></li>
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
          <div class="widget-content tab-content">
          <div id="tab1" class="tab-pane fade in active">
            <a href ="adminMasterfileHoliday.php" class = "btn btn-success" style = "float:right; margin-left: 4px;"><span class="icon"><i class="icon-refresh"></i></span> Refresh</a>
               <a href ="adminADDholiday.php" class = "btn btn-info" style = "float:right;"><span class="icon"><i class="icon-plus"></i></span> Add Holiday</a>
               <br>
               <br>
               <table class="table table-bordered data-table">
               <thead>
                <tr>
                  <th>Holiday ID</th>
                  <th>Holiday Date</th>
                  <th>Holiday Name</th>
                  <th>Holiday Type</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody> 
                <?php
                $searchquery ="SELECT * FROM HOLIDAYS";
                $searchresult= filterTable($searchquery);

               function filterTable($searchquery)
               {

                    $conn1 = mysqli_connect("localhost:3307","root","","masterdb");
                    $filter_Result = mysqli_query($conn1,$searchquery) or die ("failed to query Holidays".mysql_error());
                    return $filter_Result;
               }while($row1 = mysqli_fetch_array($searchresult)):;
               ?>
                  <tr class="gradeX">
                  <td><?php echo $row1['holiday_prefix_ID'],$row1['holiday_ID'];?></td>
                  <td><?php echo $row1['holiday_DATE'];?></td>
                  <td><?php echo $row1['holiday_NAME'];?></td>
                  <td><?php echo $row1['holiday_TYPE'];?></td>
                 
                  <td><center><a href = "adminEDITMasterfileHoliday.php?id=<?php echo $row1['holiday_ID']?>"  class = "btn btn-info btn-mini"><span class="icon"><i class="icon-edit"></i></span> Edit</a>
                    <a href = "adminDELETEMasterfileHoliday.php?id=<?php echo $row1['holiday_ID'];?>" class = "btn btn-danger btn-mini"><span class="icon"><i class="icon-trash"></i></span> Delete</a></center></td>
                </tr>
              <?php endwhile;?>
              </tbody>
            </table>
               <div class = "pagination alternate" style="float:right;">
               <ul>
               <!-- <?php

                    for ($i=1; $i<=$totalpages; $i++){
                         echo "<li><a href='adminMasterfileHoliday.php?page=".$i."'";
                         if ($i==$page) echo " class='curPage'";
                              echo ">".$i."</a></li> ";
                         };
               ?> -->
               </ul>
               </div>

                  </div>
                </div>
          
        </div>
      </div>
    </div>
  </div>
</div>
     

<?php
// unset($_SESSION['masterfilenotif']);
?>

<div class="row-fluid">
<div id="footer" class="span12"> 2023 &copy; WEB-BASED TIMEKEEPING AND PAYROLL SYSTEM USING FINGERPRINT BIOMETRICS</div>
</div>

<script src="../js/maruti.dashboard.js"></script> 

</html>

