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

$results_perpage = 20;

               if (isset($_GET['page'])){

                    $page = $_GET['page'];
               } else {

                    $page=1;
               }

$currentempid = $_SESSION['empID'];

$userIdpage  = $_SESSION['empID'];

$pageViewed = basename($_SERVER['PHP_SELF']);
$pageInfo = pathinfo($pageViewed);

// Get the filename without extension
$pageViewed1 = $pageInfo['filename'];



// Log the page view
logPageView($conn, $userIdpage, $pageViewed1);

if (isset($_POST['searchbydate_btn'])){
    $start_from = ($page-1) * $results_perpage;
   $datefrom = $_POST['dpfrom'];
   $dateto = $_POST['dpto'];
   $searchquery = "SELECT * FROM DTR,employees WHERE DTR.emp_id = '$currentempid' AND DTR.emp_id = employees.emp_id AND DATE(DTR_day) BETWEEN '$datefrom' and '$dateto' ORDER BY DTR_day DESC LIMIT $start_from,".$results_perpage;
   $search_result = filterTable($searchquery);

} else  {
  $start_from = ($page-1) * $results_perpage;
  $searchquery ="SELECT * FROM DTR,employees WHERE DTR.emp_id = '$currentempid' AND DTR.emp_id = employees.emp_id ORDER BY DTR_day DESC LIMIT $start_from,".$results_perpage; 
  $search_result = filterTable($searchquery);
  }

$countdataqry = "SELECT COUNT(emp_id) AS total FROM DTR where emp_id = '$currentempid'";
$countdataqryresult = mysqli_query($conn,$countdataqry) or die ("FAILED TO EXECUTE COUNT QUERY ". mysql_error());      
$row = $countdataqryresult->fetch_assoc();
$totalpages=ceil($row['total'] / $results_perpage);
// echo "Generated Query: $searchquery";

?>


<!DOCTYPE html>
<html lang="en">
<head>
<title>Employee Records</title>
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
      $( "#datepickerfrom" ).datepicker({ dateFormat: 'yy-mm-dd'});
      } );
  $( function() {
      $( "#datepickerto" ).datepicker({ dateFormat: 'yy-mm-dd'});
      } );
  
</script>

<body>

<!--Header-part-->

<?php
INCLUDE ('empNAVBAR.php');
?>

<style>
  
</style>

<div id="content">

  <div id="content-header">
    <div id="breadcrumb"> <a href="index.html" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a>
      <a href ="adminMasterfile.php" class="tip-bottom"><i class ="icon-calendar"></i> Attendance Records</a></div>
  </div>
  

  <div class="container-fluid">
    <div class ="row-fluid">
      <div class = "span10">
        <h3>Employee Records</h3>        
      </div>
    </div>
   
    <div class ="row-fluid">
     <div class="span12">
        <div class="widget-box">
          <div class="widget-title">
            <ul class="nav nav-tabs" id="myTab">
              <li><a href="empDASHBOARD.php"><i class="icon-user"></i> Profile</a></li>
              <li><a href="empAPPLYOvertime.php"><i class="icon-time"></i> Overtime</a></li>
              <li><a href="empAPPLYLeave.php"><i class="icon-calendar"></i> Leave</a></li>
              <li class="active"><a href="empATTENDANCErecords.php"><i class="icon-th"></i> My Records</a></li>
              <li><a href="empActivitylogs.php"><i class="icon-time"></i> Activity Logs</a></li>
              <li class=""><a href="empActivitylogs.php"><i class="icon-file"></i> Loans</a></li>


              
            </ul>
          </div>
     
          <style>
            .widget-box {
  border-radius: 10px; /* You can adjust the value to control the amount of rounding */
  border: 1px solid #ccc; /* Optional: You can add a border for further styling */
  padding: 15px; /* Optional: Add padding to the box for better appearance */
}
@media (max-width: 768px) {
  .widget-box {
    margin: auto;
    margin-top: 70px; /* This will center the widget-box */
  }

  .widget-title li {
    /* Adjust the styles for list items inside widget-title at smaller screens */
    display: block;
    margin-bottom: 10px;
  }

  .active {
    /* Adjust the styles for the active class when the screen width is 768px or less */
    
  }
}  
          </style>
        <div class="widget-box">
          <div class="widget-title">
            <ul class="nav nav-tabs" id="myTab">
              <li class="active"><a href="<?php $_SERVER['PHP_SELF'];?>"><i class="icon-calendar"></i> Attendance Records</a></li>
              <li><a href="empPAYROLLrecords.php"><i class="icon-th"></i> Payroll Records</a></li>
            </ul>
          </div>
          <div class="widget-content tab-content">
          <div id="tab1" class="tab-pane fade in active"><!--EMPLOYEE TAB--><!--EMPLOYEE TAB--><!--EMPLOYEE TAB--><!--EMPLOYEE TAB--><!--EMPLOYEE TAB--><!--EMPLOYEE TAB--><!--EMPLOYEE TAB--><!--EMPLOYEE TAB-->
               
              <div class = "row-fluid">
                <div class = "span5">
                  <div class ="control-group">
                    <label class="control-label">Search by date: </label>
                      <div class="controls">
                        <form method="post" action="<?php $_SERVER['PHP_SELF']; ?>">
                        <div id="search">
                        <input class ="span8" type="text" id="datepickerfrom" name ="dpfrom" placeholder="From" value="">
                        </div>
                        <div id = "search">
                        <input class ="span8" type="text" id="datepickerto" name ="dpto" placeholder="To" value=""><button type="submit" class = "btn btn-primary" name ="searchbydate_btn"><i class ="icon-search icon-white"></i></button>
                        </div>
                       
                      </div>        
            </div>
                </div>
            <style>
              
            </style>

                <div class = "span2">


                  
                  </div>

                
                  </form>
                <div class = "span5">
                  <a href ="empATTENDANCErecords.php" class = "btn btn-success" style = "float:right; margin-left: 4px;"><span class="icon"><i class="icon-refresh"></i></span> Refresh</a>
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
                  <th>Morning In</th>
                  <th>Morning Out</th>
                  <th>Afternoon In</th>
                  <th>Afternoon Out</th>
                  <th>Day of Record</th>
                  <th>Remarks</th>                
                  
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
                  <td><?php echo $row1['in_morning'];?></td>
                  <td><?php echo $row1['out_morning'];?></td>
                  <td><?php echo $row1['in_afternoon'];?></td>
                  <td><?php echo $row1['out_afternoon'];?></td>
                  <td><?php echo $row1['DTR_day'];?></td>
                  <td><?php echo $row1['DTR_remarks'];?></td>
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
unset($_SESSION['masterfilenotif']);
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

