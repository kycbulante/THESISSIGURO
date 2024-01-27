<?php
include("../DBCONFIG.PHP");
include("../LoginControl.php");
include("../BASICLOGININFO.PHP");

session_start();
if(isset($_SESSION['masterfilenotif'])){

$mfnotif = $_SESSION['masterfilenotif'];
?>  
<script>
alert("<?php echo $mfnotif;?>");
</script>
<?php

}
$master = $_SESSION['master'];
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
    <div id="breadcrumb"> <a href="index.html" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a>
      <a href ="adminMasterfileDept.php" class="tip-bottom"><i class ="icon-th"></i> Manage Departments</a></div>
  </div>

  <div class="container-fluid">
    <div class ="row-fluid">
      <div class = "span10">
        <h3 style="margin-top:30px">Departments</h3>        
      </div>
    </div>
   
<div class ="row-fluid">
     <div class="span12">
        <div class="widget-box">
          <div class="widget-title">
            <ul class="nav nav-tabs" id="myTab">
              <li><a href="adminMasterfile.php"><i class="icon-user"></i> Employees</a></li>
              <li class="active"><a href="adminMasterfileDept.php"><i class="icon-th"></i> Manage Departments</a></li>
              <li><a href="adminMasterfileShift.php"><i class="icon-time"></i> Manage Shifts</a></li>
              <li><a href="adminMasterfileHoliday.php"><i class="icon-calendar"></i> Manage Holidays</a></li>
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
             <a href ="adminMasterfileDept.php" class = "btn btn-success" style = "float:right; margin-left: 4px;"><span class="icon"><i class="icon-refresh"></i></span> Refresh</a>
               <a href ="adminADDdepartment.php" class = "btn btn-info" style = "float:right;"><span class="icon"><i class="icon-plus"></i></span> Add Department</a>
               <br>
               <br>
               <table class="table table-bordered data-table">
               <thead>
                <tr>
                  <th>Department ID</th>
                  <th>Department Name</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody> 
              <?php
              $results_perpageDEPT = 20;

              if (isset($_GET['page']))
                {
                  $pageDEPT = $_GET['page'];
                } 
              else 
                {
                  $pageDEPT=1;
                }

                $start_fromDEPT = ($pageDEPT-1) * $results_perpageDEPT;
                $searchqueryDEPT ="SELECT * FROM DEPARTMENT ORDER BY dept_ID ASC LIMIT $start_fromDEPT,".$results_perpageDEPT;
                $searchresultDEPT= filterTableDEPT($searchqueryDEPT);

               function filterTableDEPT($searchqueryDEPT)
               {

                    $connDEPT = mysqli_connect("localhost:3307","root","","masterdb");
                    $filter_ResultDEPT = mysqli_query($connDEPT,$searchqueryDEPT) or die ("failed to query masterfile ".mysql_error());
                    return $filter_ResultDEPT;
               }

                $countdataqryDEPT = "SELECT COUNT(dept_ID) AS total FROM DEPARTMENT";
                $countdataqryresultDEPT = mysqli_query($conn,$countdataqryDEPT) or die ("FAILED TO EXECUTE COUNT QUERY ". mysql_error());
                $rowDEPT = $countdataqryresultDEPT->fetch_assoc();
                $totalpagesDEPT=ceil($rowDEPT['total'] / $results_perpageDEPT);
                while($row1DEPT = mysqli_fetch_array($searchresultDEPT)):;
               ?>
                  <tr class="gradeX">
                  <td><?php echo $row1DEPT['dept_prefix_ID'],$row1DEPT['dept_ID'];?></td>
                  <td><?php echo $row1DEPT['dept_NAME'];?></td>
                 
                  <td><center><a href = "adminEDITMasterfileDept.php?id=<?php echo $row1DEPT['dept_NAME']?>" class = "btn btn-info btn-mini"><span class="icon"><i class="icon-eye-open"></i></span> View</a>
                    <a href = "adminDELETEMasterfileDept.php?id=<?php echo $row1DEPT['dept_ID'];?>" class = "btn btn-danger btn-mini"><span class="icon"><i class="icon-trash"></i></span> Delete</a></center></td>
                </tr>
              <?php endwhile;?>
              
              <!-- <tr class="gradeX">
                  <td>DEPT-1001</td>
                  <td>Accounting</td>
                 
                  <td><center><a href = "adminEDITMasterfileDept.php" class = "btn btn-info btn-mini"><span class="icon"><i class="icon-eye-open"></i></span> View</a>
                    <a href = "adminDELETEMasterfileDept.php?id=<?php echo $row1DEPT['dept_ID'];?>" class = "btn btn-danger btn-mini"><span class="icon"><i class="icon-trash"></i></span> Delete</a></center></td>
                </tr> -->
              </tbody>
            </table>
               <div class = "pagination alternate" style="float:right;">
               <ul>
               <?php

                for ($iDEPT=1; $iDEPT<=$totalpagesDEPT; $iDEPT++){
                    echo "<li><a href='adminMasterfileDept.php?page=".$iDEPT."'";
                    if ($iDEPT==$pageDEPT) echo " class='curPage'";
                          echo ">".$iDEPT."</a></li> ";
                    };
                ?>
              
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
unset($_SESSION['masterfilenotif']);
?>



<div class="row-fluid">
<div id="footer" class="span12"> 2023 &copy; WEB-BASED TIMEKEEPING AND PAYROLL SYSTEM USING FINGERPRINT BIOMETRICS</div></div>

<script src="../js/maruti.dashboard.js"></script> 
<script src="../js/excanvas.min.js"></script> 
<script src="../js/jquery.min.js"></script> 
<script src="../js/jquery.ui.custom.js"></script> 
<script src="../js/bootstrap.min.js"></script> 
<script src="../js/jquery.flot.min.js"></script> 
<script src="../js/jquery.flot.resize.min.js"></script> 
<script src="../js/jquery.peity.min.js"></script> 
<script src="../js/fullcalendar.min.js"></script> 
<script src="../js/maruti.js"></script> 
</body>
</html>

