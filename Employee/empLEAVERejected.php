<?php
include("../DBCONFIG.PHP");
include("../LoginControl.php");
include("../BASICLOGININFO.PHP");

session_start();

if(isset($_SESSION['OTAPPROVAL'])){

$mfnotif = $_SESSION['OTAPPROVAL'];
?>  
<script>
alert("<?php echo $mfnotif;?>");
</script>
<?php
}
$currentempid = $_SESSION['empID'];
$results_perpage = 20;

               if (isset($_GET['page'])){

                    $page = $_GET['page'];
               } else {

                    $page=1;
               }





if (isset($_POST['searchbydate_btn'])){

  $start_from = ($page-1) * $results_perpage;
  $datesearch = $_POST['dphired'];
  $searchquery = "SELECT LEAVES_APPLICATION.*, employees.* from employees, LEAVES_APPLICATION  WHERE LEAVES_APPLICATION.leave_datestart = '$datesearch' AND LEAVES_APPLICATION.leave_status = 'Rejected' AND employees.emp_id = LEAVES_APPLICATION.emp_id and employees.emp_id = '$currentempid' ORDER BY LEAVES_APPLICATION.leave_datestart DESC LIMIT $start_from,".$results_perpage;  
  $search_result = filterTable($searchquery);

}else{

  $start_from = ($page-1) * $results_perpage;
  // $datesearch = $_POST['dphired'];
  $searchquery = "SELECT LEAVES_APPLICATION.*, employees.* from employees,  LEAVES_APPLICATION  WHERE LEAVES_APPLICATION.leave_status = 'Rejected' AND employees.emp_id = LEAVES_APPLICATION.emp_id and employees.emp_id = '$currentempid' ORDER BY LEAVES_APPLICATION.leave_datestart DESC LIMIT $start_from,".$results_perpage;  
  $search_result = filterTable($searchquery);


}

$countdataqry = "SELECT COUNT(la_id) AS total FROM LEAVES_APPLICATION WHERE leave_status ='Rejected' AND emp_id = '$currentempid'";
$countdataqryresult = mysqli_query($conn,$countdataqry) or die ("FAILED TO EXECUTE COUNT QUERY ". mysql_error());      
$row = $countdataqryresult->fetch_assoc();
$totalpages=ceil($row['total'] / $results_perpage);


?>


<!DOCTYPE html>
<html lang="en">
<head>
<title>Apply Leave</title>
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
<script type ="text/javascript">
  $( function() {
      $( "#datepicker" ).datepicker({ dateFormat: 'yy-mm-dd'});
      } );
  
</script>
</head>

<body>

<!--Header-part-->

<?php
INCLUDE ('empNAVBAR.php');
?>


<div id="content">

  <div id="content-header">
    <div id="breadcrumb"> <a href="empDASHBOARD.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a>
      <a href ="empAPPLYLeave.php" class="tip-bottom"><i class ="icon-time"></i> Apply Leaves</a></div>
  </div>

  <div class="container-fluid">
    <div class ="row-fluid">
      <div class = "span10">
        <h3>Leave Application</h3>        
      </div>
    </div>
   
    <div class ="row-fluid">
     <div class="span12">
        <div class="widget-box">
          <div class="widget-title">
            <ul class="nav nav-tabs" id="myTab">
              <li><a href="empDASHBOARD.php"><i class="icon-user"></i> Profile</a></li>
              <li><a href="empAPPLYOvertime.php"><i class="icon-time"></i> Overtime</a></li>
              <li class="active"><a href="empAPPLYLeave.php"><i class="icon-calendar"></i> Leave</a></li>
              <li><a href="empATTENDANCErecords.php"><i class="icon-th"></i> My Records</a></li>
              
            </ul>
          </div>
     <div class="span12">
        <div class="widget-box">
          <div class="widget-title">
            <ul class="nav nav-tabs" id="myTab">
              <li><a href="empAPPLYLeave.php"><i class="icon-calendar"></i> For Approval</a></li>
              <li><a href="empLEAVEApproved.php"><i class="icon-calendar"></i> Approved</a></li>
              <li   class="active"><a href="empLEAVERejected.php"><i class="icon-calendar"></i> Rejected</a></li>
            </ul>
          </div>
          <div class="widget-content tab-content">
          <div id="tab1" class="tab-pane fade in active"><!--EMPLOYEE TAB--><!--EMPLOYEE TAB--><!--EMPLOYEE TAB--><!--EMPLOYEE TAB--><!--EMPLOYEE TAB--><!--EMPLOYEE TAB--><!--EMPLOYEE TAB--><!--EMPLOYEE TAB-->
               
              <div class = "row-fluid">
                <div class = "span2">
                    <form action="empAPPLYOvertime.php" method = "post">
                       <div class ="control-group">
                    <label class="control-label">Search by date: </label>
                      <div class="controls">
                        <div id = "search">
                        <input class ="span8" type="text" id="datepicker" name ="dphired" placeholder="Date" value=""><button type="submit" class = "btn btn-primary" name ="searchbydate_btn"><i class ="icon-search icon-white"></i></button>
                        </div>
                        <!-- <span class ="label label-important"><?php echo $rfidError; ?></span> -->
                      </div>
                  </div>
                    
                </div>

                <div class="span3">
                </div>

                <div class = "span2">


                 

                </div>
                  </form>
                <div class = "span5">
                  <a href ="empAPPLYLeave.php" class = "btn btn-success" style = "float:right; margin-left: 4px;"><span class="icon"><i class="icon-refresh"></i></span> Refresh</a>
                  <a href ="empLEAVEApplication.php?id=<?php echo $currentempid;?>" class = "btn btn-info" style = "float:right;"><span class="icon"><i class="icon-plus"></i></span> Apply Leave</a>
                  <!-- <small><?php echo $attrecordview; ?></small> -->
                </div>
              
            </div>

               <table class="table table-bordered data-table">
               <thead>
                <tr>
                  <th>Employee ID</th>
                  <th>Last Name</th>
                  <th>First Name</th>
                  <th>Middle Name</th>
                  <th>Shift</th>
                  <th>Leave Start</th>
                  <th>Leave End</th>
                  <th>Leave Days</th>
                  <th>Status</th> 
                                 
                  
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
                  
                  <td><?php echo $row1['prefix_ID'];?><?php echo $row1['emp_id'];?></td>
                  <td><?php echo $row1['last_name'];?></td>
                  <td><?php echo $row1['first_name'];?></td>
                  <td><?php echo $row1['middle_name']; ?></td>
                  <td><?php echo $row1['shift_SCHEDULE'];?></td>
                  <td><?php echo $row1['leave_datestart'];?></td>
                  <td><?php echo $row1['leave_dateend'];?></td>
                  <td><?php echo $row1['leave_days'];?></td>
                  <td><?php echo $row1['leave_status'];?></td>
                  
                  
                </tr>
              <?php endwhile;?>
              </tbody>
            </table>
               <div class = "pagination alternate" style="float:right;">
               <ul>
               <!-- <?php

                    for ($i=1; $i<=$totalpages; $i++){
                         echo "<li><a href='adminMasterfile.php?page=".$i."'";
                         if ($i==$page) echo " class='curPage'";
                              echo ">".$i."</a></li> ";
                         };
               ?> -->
               </ul>
               </div>
          </div><!--EMPLOYEE TAB--><!--EMPLOYEE TAB--><!--EMPLOYEE TAB--><!--EMPLOYEE TAB--><!--EMPLOYEE TAB--><!--EMPLOYEE TAB--><!--EMPLOYEE TAB--><!--EMPLOYEE TAB-->
          </div>
          
        </div>
      </div>
    </div>
  </div>
</div>
<?php
unset($_SESSION['OTAPPROVAL']);
?>



<div class="row-fluid">
  <!-- <div id="footer" class="span12" style="position: fixed; bottom: 0; left: 0; width: 100%; text-align: center; padding: 10px;"> 2023 &copy; WEB-BASED TIMEKEEPING AND PAYROLL SYSTEM USING FINGERPRINT BIOMETRICS</div> -->
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

