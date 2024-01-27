<?php
include("../DBCONFIG.PHP");
include("../LoginControl.php");
include("../BASICLOGININFO.PHP");

session_start();

$otID = $_GET['id'];
$otquery = "SELECT * FROM OVER_TIME WHERE ot_ID= '$otID'";
$otqueryexec = mysqli_query($conn,$otquery) or die ("FAILED TO GET OT INFO ".mysqli_error($conn));
$otinfo = mysqli_fetch_array($otqueryexec);

if ($otinfo){

  $otemp = $otinfo['emp_id'];
  $otin = $otinfo['ot_time'];
  $otout = $otinfo['ot_timeout'];
  $othours = $otinfo['ot_hours'];
  $otday = $otinfo['ot_day'];
  $otinformation = $otinfo['ot_info'];
  $infoquery = "SELECT last_name,first_name,middle_name,shift_SCHEDULE,prefix_ID FROM employees WHERE emp_id = '$otemp'";
  $infoqqueryexec = mysqli_query($conn,$infoquery);
  $infofetch = mysqli_fetch_array($infoqqueryexec);

  if($infofetch){

    $lastname =$infofetch['last_name'];
    $firstname = $infofetch['first_name'];
    $middlename =  $infofetch['middle_name'];
    $shiftsched = $infofetch['shift_SCHEDULE'];
    $idprefix = $infofetch['prefix_ID'];

    $empidinfo = "$idprefix$otemp";
    $fullname = "$lastname, $firstname $middlename";
  }
}

// ... (your existing code)

if (isset($_POST['submit_btn'])) {
  $action_info_update = $_POST['newotinfo'];
  $ot_remark = "For Approval";

  // Fetch existing overtime information for comparison
  $old_ot_info_query = "SELECT ot_info FROM OVER_TIME WHERE ot_ID = '$otID'";
  $old_ot_info_result = mysqli_query($conn, $old_ot_info_query) or die("FAILED TO FETCH OLD OT INFO " . mysqli_error($conn));
  $old_ot_info_array = mysqli_fetch_array($old_ot_info_result);
  $old_ot_info = $old_ot_info_array['ot_info'];

  // Log the overtime review attempt
  // logOvertimeReview($conn, $otemp, $otID, $old_ot_info, 'Review', false);

  // Check if the information has changed
  $changes_detected = $action_info_update != $old_ot_info;

  $update_ot = "UPDATE OVER_TIME SET ot_info = '$action_info_update', ot_remarks = '$ot_remark' WHERE ot_ID = '$otID'";
  $update_ot_exec = mysqli_query($conn, $update_ot) or die ("FAILED TO APPROVE/REJECT " . mysqli_error($conn));

  if ($update_ot_exec) {
      // Log the review/update based on changes
      $action = $changes_detected ? 'Update' : 'Review';
      logOvertimeReview($conn, $otemp, $action);

      $_SESSION['OTAPPROVAL'] = "OVERTIME FOR APPROVAL.";
      header("Location:empAPPLYOvertime.php");
  }else {$action = $changes_detected ? 'Update' : 'Review';
  logOvertimeReview($conn, $otemp, $action);}

  ?>
  <script>
      alert("<?php echo $action_update_alert; ?>");
  </script>
  <?php
}
?>
// ... (the rest of your code)





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
<script type ="text/javascript">
  $( function() {
      $( "#holidaypicker" ).datepicker({ dateFormat: 'yy-mm-dd'});
      } );
  </script>
</head>

<style>
.userinfo {
  margin-left:40px;
  
}

textarea {
  width: 670px;
  height:100px;

}
.btn{
  float:right;
  margin-right:40px;
}


</style>
<body>

<!--Header-part-->

<?php
INCLUDE ('empNAVBAR.php');
?>


<div id="content">

  <div id="content-header">
    <div id="breadcrumb"> <a href="../adminDASHBOARD.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a>
      <a href ="empAPPLYOvertime.php" class="tip-bottom"><i class ="icon-time"></i> Apply Overtime</a>
      <a href="empOTApply.php" class="tip-bottom"><i class = "icon-edit"></i>Review Overtime</a>
    </div>
  </div>

  <div class="container-fluid">
    <div class = "row-fluid">
      <span class="span3">
      </span>
    <div class="span6">
      <h3>Review Overtime</h3>
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
            <h5>Overtime Information</h5>
          </div>

          <div class="widget-content nopadding">
            <form action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" class="form-horizontal">
                  <br>
                  <p>
                  <span class="userinfo">Employee ID:<b> <?php echo $empidinfo;?></b></span><br>
                  <span class="userinfo">Name:<b> <?php echo $fullname;?></b></span><br>
                  <span class="userinfo">Shift:<b> <?php echo $shiftsched;?></b></span><br>
                  <span class="userinfo">DATE OF OVERTIME:<b> <?php echo $otday;?></b></span>
                  <span class="userinfo">OVERTIME IN:<b> <?php echo $otin;?></b></span>&nbsp<span class="userinfo">OVERTIME OUT:<b> <?php echo $otout;?></b></span><br>
                  <span class="userinfo">TOTAL OVERTIME HOUR/S:<b> <?php echo $othours;?></b></span><br>
                  <div class = "userinfo">
                    <label for="otinformation">OVERTIME INFORMATION:</label>
                    <textarea id="otinformationn" value="<?php echo $otinformation;?>" name="newotinfo"><?php echo $otinformation;?></textarea>
                  </div>
                  <br>
             
                  </span><span><button type="submit" class="btn btn-success" name = "submit_btn">Submit</button></span>
                  </p> 
                  <br>
                  <br>
            </form>        
          </div>
        </div>

             
          
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

</body>
</html>
