<!DOCTYPE html>
<html lang="en">
<head>
<title>Admin Home</title>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<link rel="stylesheet" href="../css/bootstrap.min.css" />
<link rel="stylesheet" href="../css/bootstrap-responsive.min.css" />
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
<!-- <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script> -->
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker@3.1.0/daterangepicker.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

<?php

include("../DBCONFIG.PHP");
include("../LoginControl.php");
include("../BASICLOGININFO.PHP");

session_start();

$error = false;
$adminId = $_SESSION['adminId'];
if(isset($_POST['submit_btn'])){

  $shiftsched = $_POST['shiftsched'];
  $shiftstartmorning = $_POST['shiftstart'];
  // $shiftendmorning = $_POST['shiftendmorning'];
  // $shiftstartafternoon = $_POST['shiftstartafternoon'];
  $shiftend = $_POST['shiftend'];

  if(empty($shiftsched)){

    $error = true;
    $shiftnameerror = "Please enter shift schedule.";

  }

  if(empty($shiftstartmorning)){

    $error = true;
    $shiftstarterror = "Please enter shift start.";

  }

  if(empty($shiftend)){

    $error = true;
    $shiftenderror = "Please enter shift end.";

  }

  // if(empty($shiftstartafternoon)){

  //   $error = true;
  //   $shiftstarterror = "Please enter shift start.";

  // }

  // if(empty($shiftendmorning)){

  //   $error = true;
  //   $shiftenderror = "Please enter shift end.";

  // }

  $shiftnamequery = "SELECT shift_SCHEDULE FROM SHIFT where shift_SCHEDULE = '$shiftsched'";
  $shiftnameresultqry = mysqli_query($conn,$shiftnamequery);
  $shiftnamecount = mysqli_num_rows($shiftnameresultqry);

  if ($shiftnamecount !=0){
    $error = true;
    $shiftschederror = "Department already exists.";
  }
  if (!$error){

    $newshiftqry = "INSERT INTO SHIFT (shift_SCHEDULE, shift_START, shift_END) VALUES ('$shiftsched','$shiftstartmorning','$shiftend')";
    $newshiftqryresult = mysqli_query($conn,$newshiftqry) or die ("FAILED TO CREATE NEW SHIFT ".mysql_error());
    $activityLog = "Added a new shift ($shiftsched)";
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


<?php
INCLUDE ('NAVBAR.php');
?>


<div id="content">

  <div id="content-header">
    <div id="breadcrumb"> <a href="index.html" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a>
      <a href ="adminMasterfileShift.php" class="tip-bottom"><i class ="icon-th"></i> Manage Shifts</a>
      <a href="#" class="tip-bottom"><i class = "icon-plus"></i>Add Shift</a>
    </div>
  </div>

  <div class="container-fluid">
    <div class = "row-fluid">
      <span class="span3">
      </span>
    <div class="span6">
      <h3>Add Shift</h3>
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
            <h5>Shift Information</h5>
          </div>

          <div class="widget-content nopadding">
            <form action="adminADDshift.php" method="POST" class="form-horizontal">
              <div class="control-group">
                <label class="control-label">Shift Schedule :</label>
                <div class="controls">
                  <input type="text" class="span7" placeholder="Shift Schedule" name="shiftsched"/>
                  <!-- <span class ="label label-important"><?php echo $shiftschederror; ?></span> -->
                </div>
              </div>

              <div class="control-group">
                <label class="control-label">Shift Start:</label>
                <div class="controls">
                  <input type="text" class="span2" placeholder="Shift Start" id="shiftStartTime" name="shiftstart" value=""/>
                  <!-- <input type="text" class="span2" placeholder="Morning End" id="shiftEndTimeMorning" name="shiftendmorning" value=""/> -->
                  <!-- <span class ="label label-important"><?php echo $shiftstarterror; ?></span> -->
                </div>
              </div>

               <div class="control-group">
                <label class="control-label">Shift End:</label>
                <div class="controls">
                  <!-- <input type="text" class="span2 time ui-timepicker-input" placeholder="Afternoon Start" id = "shiftStartTimeAfternoon" name="shiftstartafternoon" value=""/> -->
                  <input type="text" class="span2 time ui-timepicker-input" placeholder="Shift End" id = "shiftEndTime" name="shiftend" value=""/>
                  <!-- <span class ="label label-important"><?php echo $shiftenderror; ?></span> -->
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
  <div id="footer" class="span12"> 2023 &copy; WEB-BASED TIMEKEEPING AND PAYROLL SYSTEM USING FINGERPRINT BIOMETRICS</div>
</div>
<?php
unset($_SESSION['anewshift']);
?>

<script src="../js/maruti.dashboard.js"></script> 

</body>
</html>
