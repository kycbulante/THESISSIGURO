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
<link rel="stylesheet" href="../jquery-ui-1.12.1/jquery-ui.css"/>
<link rel="stylesheet" href="../timepicker/jquery.timepicker.css"/>
<script src="../jquery-ui-1.12.1/jquery-3.2.1.js"></script>
<script src="../jquery-ui-1.12.1/jquery-ui.js"></script>
<script src="../timepicker/jquery.timepicker.min.js"></script>
<script src="../timepicker/jquery.timepicker.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
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

session_start();

$adminId = $_SESSION['adminId'];
$_SESSION['editdeptid'] = $_GET['id'];
$idres = $_GET['id'];
$editdeptid = $_SESSION['editdeptid'];
$DELquery = "SELECT * from SHIFT WHERE shift_SCHEDULE ='$idres'";
$DELselresult = mysqli_query($conn,$DELquery) or die ("Failed to search DB. ".mysql_error());
  $DELcurr = mysqli_fetch_array($DELselresult);
  $DELcount = mysqli_num_rows($DELselresult);

   if($DELcount!=0 && $DELcurr) {

        $currprefixid = $DELcurr['shift_prefix_ID'];
        $currshiftid = $DELcurr['shift_ID'];
        $currshiftsched = $DELcurr['shift_SCHEDULE'];
        $currshiftstartmorning = $DELcurr['shift_START'];
        $currshiftendmorning = $DELcurr['shift_ENDMorning'];
        $currshiftstartafternoon = $DELcurr['shift_STARTAfternoon'];
        $currshiftend = $DELcurr['shift_END'];
       

    }
    else {
          $_SESSION['delnotif'] ="Shift information not found.";
          }/*2nd else end*/



$error = false;

if(isset($_POST['submit_btn'])){

  $shiftsched = $_POST['shiftsched'];
  $shiftstartmorning = $_POST['shiftstart'];
  // $shiftendmorning = $_POST['shiftendmorning'];
  // $shiftstartafternoon = $_POST['shiftstartafternoon'];
  $shiftend = $_POST['shiftend'];
  echo "Shift Schedule: " . $_POST['shiftsched'] . "<br>";
  echo "Shift Start: " . $_POST['shiftstart'] . "<br>";
  echo "Shift End: " . $_POST['shiftend'] . "<br>";

  

  $shiftnamequery = "SELECT shift_SCHEDULE FROM SHIFT where shift_SCHEDULE = '$shiftsched'";
  $shiftnameresultqry = mysqli_query($conn,$shiftnamequery);
  $shiftnamecount = mysqli_num_rows($shiftnameresultqry);

 

  if (!$error){

    $newshiftqry = "UPDATE SHIFT SET shift_SCHEDULE = '$shiftsched', shift_START = '$shiftstartmorning', shift_END = '$shiftend' where shift_ID = '$currshiftid'";
echo "SQL Query: " . $newshiftqry . "<br>";
    $newshiftqryresult = mysqli_query($conn,$newshiftqry) or die ("FAILED TO CREATE NEW SHIFT ".mysql_error());
    $activityLog = "Edited shift from $currshiftsched to $shiftsched";
    $adminActivityQuery = "INSERT INTO adminactivity_log (emp_id, activity,log_timestamp) VALUES ('$adminId', '$activityLog', NOW())";
    $adminActivityResult = mysqli_query($conn, $adminActivityQuery);
    if($newshiftqryresult){?>
   
   <script>
      document.addEventListener('DOMContentLoaded', function() {
          swal({
            //  title: "Good job!",
            text: "Shift inserted successfully",
            icon: "success",
            button: "OK",
            }).then(function() {
              window.location.href = 'adminMasterfileShift.php'; // Replace 'your_new_page.php' with the actual URL
          });
      });
    </script>
        <?php
      } else {
        $errType = "danger";
        // $_SESSION['addprofilenotif'] = "Something went wrong. Make sure you accomplish all the required fields.";
        ?><script>
        document.addEventListener('DOMContentLoaded', function() {
            swal({
              // title: "Data ",
              text: "Something went wrong.",
              icon: "error",
              button: "Try Again",
            });
        }); </script>
        <?php
  }
}
}

?>





<script type ="text/javascript">
$( function() {
      $('#shiftStartTime').timepicker({ 'timeFormat': 'H:i:s' });
 } );

$( function() {

      $('#shiftEndTimeMorning').timepicker({ 'timeFormat': 'H:i:s' });
    
   } );
$( function() {
      $('#shiftStartTimeAfternoon').timepicker({ 'timeFormat': 'H:i:s' });
 } );
$( function() {

      $('#shiftEndTime').timepicker({ 'timeFormat': 'H:i:s' });
    
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
    <div id="breadcrumb"> <a href="index.html" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a>
      <a href ="adminMasterfileShift.php" class="tip-bottom"><i class ="icon-th"></i> Manage Shifts</a>
      <a href="#" class="tip-bottom"><i class = "icon-eye-open"></i>View Shift</a>
    </div>
  </div>

  <div class="container-fluid">
    <div class = "row-fluid">
      <span class="span3">
      </span>
    <div class="span6">
      <h3>Update Shift</h3>
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
            <h5>Shift Information</h5>
          </div>

          <div class="widget-content nopadding">
            <form action="adminEDITMasterfileShift.php?id=<?php echo $idres;?>" method="POST" class="form-horizontal">
              <div class="control-group">
                <label class="control-label">Shift ID :</label>
                <div class="controls">
                <input type="text" class="span7" value="<?php echo $currprefixid; echo $currshiftid;?>"name="shiftsched" readonly/>
                  <!-- <span class ="label label-important"><?php echo $shiftschederror; ?></span> -->
                </div>
              </div>

              <div class="control-group">
                <label class="control-label">Shift Schedule :</label>
                <div class="controls">
                <input type="text" class="span7" value="<?php echo $currshiftsched;?>"name="shiftsched"/>
                  <!-- <span class ="label label-important"><?php echo $shiftschederror; ?></span> -->
                </div>
              </div>

              <div class="control-group">
                <label class="control-label">Shift Start:</label>
                <div class="controls">
                <input type="text" class="span2" placeholder="<?php echo $currshiftstartmorning;?>" id="shiftStartTime" name="shiftstart" value="<?php echo $currshiftstartmorning;?>"/>
                  <!-- <input type="text" class="span2" placeholder="<?php echo $currshiftendmorning;?>" id="shiftEndTimeMorning" name="shiftendmorning" value="<?php echo $currshiftendmorning;?>"/> -->
                  <!-- <span class ="label label-important"><?php echo $shiftstarterror; ?></span> -->
                </div>
              </div>

               <div class="control-group">
                <label class="control-label">Shift End:</label>
                <div class="controls">
                <!-- <input type="text" class="span2 time ui-timepicker-input" placeholder="<?php echo $currshiftstartafternoon; ?>" id = "shiftStartTimeAfternoon" name="shiftstartafternoon" value="<?php echo $currshiftstartafternoon; ?>"/> -->
                  <input type="text" class="span2 time ui-timepicker-input" placeholder="<?php echo $currshiftend; ?>" id = "shiftEndTime" name="shiftend" value="<?php echo $currshiftend; ?>"/>
                  <!-- <span class ="label label-important"><?php echo $shiftenderror; ?></span> -->
                </div>
              </div>

              <div class="form-actions">
                <button type="submit" class="btn btn-success" name = "submit_btn" style="float:right;">Submit</button>
              </div>
            </form>
        </div>
      </div>
    </div>
  </div>
    <div class="row-fluid">
      <div class="span12">
        <div class="widget-box">
          <div class="widget-title">
            <ul class="nav nav-tabs" id="myTab">
              <li class="active"><a href="#tab1"><i class="icon-th"></i><?php echo $currshiftsched;?></a></li>
            </ul>
          </div>
          <div class="widget-content tab-content">
          <div id="tab1" class="tab-pane fade in active">
             <h5> Employees with <?php echo $currshiftsched;?> Shift</h5>
               <br>
               <br>
               <table class="table table-bordered data-table">
               <thead>
                <tr>
                  <th>Employee ID</th>
                  <th>Name</th>
                  <th>Shift</th>
                  <th>Department</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody> 
              <?php

               $results_perpageDEPT = 20;

               if (isset($_GET['page'])){

                    $pageDEPT = $_GET['page'];
               } else {

                    $pageDEPT=1;
               }

              //  $start_fromDEPT = ($pageDEPT-1) * $results_perpageDEPT;
              //  $searchqueryDEPT ="SELECT * FROM Masterfile WHERE shift_SCHEDULE = '$editdeptid' ORDER BY emp_id ASC LIMIT $start_fromDEPT,".$results_perpageDEPT;
               
              //  $searchresultDEPT= filterTableDEPT($searchqueryDEPT);

              //  function filterTableDEPT($searchqueryDEPT)
              //  {

              //       $connDEPT = mysqli_connect("localhost:3307","root","","TAGBAC");
              //       $filter_ResultDEPT = mysqli_query($connDEPT,$searchqueryDEPT) or die ("failed to query masterfile ".mysql_error());
              //       return $filter_ResultDEPT;
              //  }

              //  $countdataqryDEPT = "SELECT COUNT(shift_SCHEDULE = '$editdeptid') AS total FROM Masterfile";
              //  $countdataqryresultDEPT = mysqli_query($conn,$countdataqryDEPT) or die ("FAILED TO EXECUTE COUNT QUERY ". mysql_error());
              //  $rowDEPT = $countdataqryresultDEPT->fetch_assoc();
              //  $totalpagesDEPT=ceil($rowDEPT['total'] / $results_perpageDEPT);
              //  while($row1DEPT = mysqli_fetch_array($searchresultDEPT)):;

              $query = "SELECT * FROM employees WHERE shift_SCHEDULE = '$currshiftsched'";
               $result = mysqli_query($conn, $query);
               if ($result) {
                while ($row = mysqli_fetch_assoc($result)) {
                    // Output each employee row as a table row
                    echo "<tr class='gradeX'>";
                    echo "<td>" . $row['emp_id'] . "</td>";
                    echo "<td>" . $row['last_name'] . " " . $row['first_name'] . " " . $row['middle_name'] . "</td>";
                    echo "<td>" . $row['shift_SCHEDULE'] . "</td>";
                    echo "<td>" . $row['dept_NAME'] . "</td>";
                    echo "<td><center><a href='adminEDITMasterfile.php?id=" . $row['emp_id'] . "' class='btn btn-success btn-mini'><span class='icon'><i class='icon-edit'></i></span> Change Shift</a></center></td>";
                    echo "</tr>";
                }
            } else {
                echo "Error fetching employee records: " . mysqli_error($conn);
            }
            
            // Close the result set
            mysqli_free_result($result);
            ?>
              
              </tbody>
            </table>
               <div class = "pagination alternate" style="float:right;">
               <ul>
               <!-- <?php

                    for ($iDEPT=1; $iDEPT<=$totalpagesDEPT; $iDEPT++){
                         echo "<li><a href='adminEDITMasterfileShift.php?page=".$iDEPT."'";
                         if ($iDEPT==$pageDEPT) echo " class='curPage'";
                              echo ">".$iDEPT."</a></li> ";
                         };
               ?> -->
               </ul>
               </div>

                  </div>
            </div>
        </div>
      </div>
      


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
<?php
unset($_SESSION['anewdept']);
?>

<script src="../js/maruti.dashboard.js"></script> 

</body>
</html>
