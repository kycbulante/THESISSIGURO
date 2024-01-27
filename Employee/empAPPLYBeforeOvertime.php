
<!DOCTYPE html>
<html lang="en">
<head>
<title>Overtime Application</title>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<link rel="stylesheet" href="../css/bootstrap.min.css" />
<link rel="stylesheet" href="../css/bootstrap-responsive.min.css" />
<link rel="stylesheet" href="../css/fullcalendar.css" />
<link rel="stylesheet" href="../css/maruti-style.css" />
<link rel="stylesheet" href="../css/maruti-media.css" class="skin-color" />
<link rel="stylesheet" href="../jquery-ui-1.12.1/jquery-ui.css">
<link rel="stylesheet" href="../timepicker/jquery.timepicker.css"/>
<script src="../jquery-ui-1.12.1/jquery-3.2.1.js"></script>
<script src="../jquery-ui-1.12.1/jquery-ui.js"></script>
<script src="../timepicker/jquery.timepicker.min.js"></script>
<script src="../timepicker/jquery.timepicker.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<?php
include("../DBCONFIG.PHP");
include("../LoginControl.php");
include("../BASICLOGININFO.PHP");

session_start();

$idres = $_GET['id'];

$error = false;

if (isset($_POST['submit_btn'])){

  $otDate = $_POST['otDate'];
  $otStart = $_POST['otStart'];
  $otEnd = $_POST['otend'];
  $otInfo = $_POST['newotinfo'];


  $startTime = strtotime($otStart);
  $endTime = strtotime($otEnd);

  $otHours = ($endTime - $startTime) / 3600;
  echo $otHours;
  
  $dt = strtotime("now");
  $currdt = date("Y-m-d", $dt);

  $convdateot = strtotime($otDate);
  $dateOt = date("Y-m-d",$convdateot);
  $otdayofweek = date("l",$convdateot);
  $otStatus = "Pending";



  $checkOTdayqry = "SELECT * FROM HOLIDAYS WHERE holiday_DATE = '$dateOt'";
  $checkOTdayexecqry = mysqli_query($conn,$checkOTdayqry) or die ("FAILED TO CHECK OT DAY ".mysqli_error($conn));
  $checkOTdayarray = mysqli_fetch_array($checkOTdayexecqry);

  if ($checkOTdayarray){
    $checkOTday = $checkOTdayarray['holiday_TYPE'];

    if  ($checkOTday == "Special Holiday"){

      $otbonus = 1;
      $otInfo = "$otInfo //SPECIAL HOLIDAY OT";
  
    } else if ($checkOTday == "Regular Holiday"){
  
      $otbonus = 2;
      $otInfo = "$otInfo   //REGULAR HOLIDAY OT";
  
    }
  
  }else {
    $otbonus = 0;
  }

  
  // if ($currdt > $dateOt) {

  //   $error = true;
  //   $otDateError = "Date selected is invalid.";

  // }


  if(empty($otDate)){  

    $error = true;
    $otDateError = "Please select an overtime date.";

  }

  if(empty($otEnd)){
    $error = true;
    $otEndError = "Please provide OT time end.";

  }
  
  
  if ($otbonus==1){

    $applyOTqry = "INSERT INTO OVER_TIME (emp_id,ot_time,ot_timeout,ot_hours,ot_sh,ot_day,ot_info,ot_remarks) VALUES ('$idres','$otStart','$otEnd','$otHours','$otHours','$otDate','$otInfo','$otStatus')";
   
  
  }elseif ($otbonus==2){
    
    $applyOTqry = "INSERT INTO OVER_TIME (emp_id,ot_time,ot_timeout,ot_hours,ot_rh,ot_day,ot_info,ot_remarks) VALUES ('$idres','$otStart','$otEnd','$otHours','$otHours','$otDate','$otInfo','$otStatus')";
    

  }else {
    
    $applyOTqry = "INSERT INTO OVER_TIME (emp_id,ot_time,ot_timeout,ot_hours,ot_day,ot_info,ot_remarks) VALUES('$idres','$otStart','$otEnd','$otHours','$otDate','$otInfo','$otStatus')";
    
  }
/** TEST
  echo "<br>";
  echo $dateOt;
  echo "<br>";
  echo $otdayofweek;
  echo "<br>";
  echo $checkOTday;
  echo "<br>";
  echo $otbonus;
  echo "<br>";
  echo $otInfo;
  echo "<br>";
  echo $a;
TEST **/

  if(!$error){

    //$applyOTqry = "INSERT INTO OVER_TIME (emp_id,ot_time,ot_timeout,ot_hours,ot_day,ot_info,ot_remarks) VALUES('$idres','$otStart','$otEnd','$otHours','$otDate','$otInfo','$otStatus')";
    $applyOTqryexec = mysqli_query($conn,$applyOTqry) or die ("Failed to add OT ".mysqli_error($conn));

    if ($applyOTqryexec){

      logOvertime($conn, $idres, true);

    // $_SESSION['OTAPPROVAL'] = "OVERTIME FOR APPROVAL.";
    $notificationMessage = "New overtime application submitted by Employee ID: $idres";
    $insertNotificationQuery = "INSERT INTO notifications (emp_id, message,type,status) VALUES ('$idres', '$notificationMessage','Overtime','unread')";
    mysqli_query($conn, $insertNotificationQuery);
    // header("Location:empAPPLYOvertime.php");
    ?>
   
   <script>
      document.addEventListener('DOMContentLoaded', function() {
          swal({
            //  title: "Good job!",
            text: "Overtime Application Submitted",
            icon: "success",
            button: "OK",
            }).then(function() {
              window.location.href = 'empAPPLYOvertime.php'; // Replace 'your_new_page.php' with the actual URL
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
      

    
  }else{
    logOvertime($conn, $idres, false);
  }




?>




<script type ="text/javascript">
 $( function() {
      $('#otEnd').timepicker({ 'timeFormat': 'H:i:s' });
 } );
  $( function() {
      $( "#otdate" ).datepicker({ dateFormat: 'yy-mm-dd'});
      } );

      
</script>

<body>

<!--Header-part-->





<?php
INCLUDE ('empNAVBAR.php');
?>


<div id="content">

  <div id="content-header">
    <div id="breadcrumb"> <a href="empDASHBOARD.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a>
      <a href ="empAPPLYOvertime.php" class="tip-bottom"><i class ="icon-time"></i> Apply Overtime</a>
      <a href="#" class="tip-bottom"><i class = "icon-plus"></i>Overtime Application Form</a>
    </div>
  </div>

  <div class="container-fluid">
    <div class = "row-fluid">
      <span class="span3">
      </span>
    <div class="span6">
      <h3>Overtime Application</h3>
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
            <h5>Overtime Details</h5>
          </div>

          <div class="widget-content nopadding">
            <form action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" class="form-horizontal">

                  <div class ="control-group">

                    <label class="control-label">Date: </label>
                      <div class="controls">

                        <input type="text" id="otdate" name ="otDate" placeholder="OT Date" value="">
                        <!-- <span class ="label label-important"><?php echo $otDateError; ?></span> -->
                        
                      </div>
                  </div>

                  <div class ="control-group">

                    <label class="control-label">OT Start: </label>
                      <div class="controls">

                        <input type="text" id="otstart" name ="otStart" placeholder="OT Start" value="17:00:00" readonly>
                        <!-- <span class ="label label-important"><?php echo $otStartError; ?></span> -->
                        
                      </div>
                  </div>

                  <div class ="control-group">

                    <label class="control-label">OT End: </label>
                      <div class="controls">

                        <input type="text" class="span2 time ui-timepicker-input" placeholder="OT End" id = "otEnd" name="otend" value=""/>
                        <!-- <span class ="label label-important"><?php echo $otEndError; ?></span> -->
                        
                      </div>
                  </div>

                  <div class ="control-group">
                    <label class="control-label">OT Details:</label>
                      <div class = "controls">

                        <textarea id="otinformationn" value="" name="newotinfo"></textarea>

                      </div>
                  </div>
              <div class="form-actions">
                <button type="submit" class="btn btn-success" name = "submit_btn" style="float:right;">Submit</button>
              </div>
            </form>
        </div>
    </div>
    
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
