<!DOCTYPE html>
<html lang="en">
<head>
<title>Admin Home</title>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<link rel="stylesheet" href="../../css/bootstrap.min.css" />
<link rel="stylesheet" href="../../css/bootstrap-responsive.min.css" />
<link rel="stylesheet" href="../../css/fullcalendar.css" />
<link rel="stylesheet" href="../../css/maruti-style.css" />
<link rel="stylesheet" href="../../css/maruti-media.css" class="skin-color" />
 <link rel="stylesheet" href="../../jquery-ui-1.12.1/jquery-ui.css">
<script src="../../jquery-ui-1.12.1/jquery-3.2.1.js"></script>
<script src="../../jquery-ui-1.12.1/jquery-ui.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker@3.1.0/daterangepicker.css" />
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker@3.1.0/daterangepicker.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<?php
include("../../DBCONFIG.PHP");
include("../../LoginControl.php");
include("BASICLOGININFO.PHP");

session_start();
date_default_timezone_set('Asia/Hong_Kong'); 
$adminId = $_SESSION['adminId'];
$adminquery = "SELECT * FROM employees WHERE emp_id= '$adminId'";
$adminqueryexec = mysqli_query($conn,$adminquery) or die ("FAILED TO GET admin INFO ".mysqli_error($conn));
$admininfo = mysqli_fetch_array($adminqueryexec);
if ($admininfo){
  $fname = $admininfo['first_name'];
  $mname = $admininfo['middle_name'];
  $lname = $admininfo['last_name'];

  $name = $fname .' '. $lname;

}


$otID = $_GET['id'];

$currtime = strtotime("now");
$nowdate = date('Y-m-d',$currtime);
$nowtime = date('H:i:s',$currtime);
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
  $otrh = $otinfo['ot_rh'];
  $otsh = $otinfo['ot_sh'];
  $infoquery = "SELECT last_name,first_name,middle_name,shift_SCHEDULE,prefix_ID FROM employees WHERE emp_id = '$otemp'";
  $infoqqueryexec = mysqli_query($conn,$infoquery);
  $infofetch = mysqli_fetch_array($infoqqueryexec);

  $ottime = strtotime($otday);
  $otdate = date('Y-m-d',$ottime);


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

if(isset($_POST['submit_btn'])){

  $actionupdate = $_POST['otaction'];
  $actioninfoupdate = $_POST['newotinfo'];
  echo $actionupdate;
  echo $actioninfoupdate;
  if ($actionupdate == "Approve"){

    $otremark = "Approved";
    $showSweetAlert = true;
    $updatetimekeep = "UPDATE TIME_KEEPING SET overtime_hours = '$othours', ot_rh ='$otrh', ot_sh='$otsh' WHERE emp_id = '$otemp' AND timekeep_day = '$otday'";
    $updatetimekeepexec = mysqli_query($conn,$updatetimekeep) or die ("FAILED TO APPROVE ".mysqli_error($conn));
   
  } else if ($actionupdate == "Reject"){
    $otremark = "Rejected";
    // $_SESSION['OTAPPROVAL'] = "OVERTIME REJECTED.";
    $showSweetAlert = true;
   
  } else if ($actionupdate ==""){
    $otremark = "Pending";
    // $_SESSION['OTAPPROVAL'] = "OVERTIME PENDING.";
    $showSweetAlert = true;
  
  }else if ($actionupdate =="Allow"){
    $otremark = "Allowed";
    // $_SESSION['OTAPPROVAL'] ="OVERTIME ALLOWED.";
    $showSweetAlert = true;
  }

  $updateot = "UPDATE OVER_TIME SET ot_info = '$actioninfoupdate', ot_remarks = '$otremark', ot_approver = '$name' WHERE ot_ID = '$otID'";
  $updateotexec = mysqli_query($conn,$updateot) or die ("FAILED TO APPROVE/REJECT ".mysqli_error($conn));

  $activityLog = "Changed overtime status ($otremark)";
  $adminActivityQuery = "INSERT INTO adminactivity_log (emp_id, activity,log_timestamp) VALUES ('$adminId', '$activityLog', NOW())";
  $adminActivityResult = mysqli_query($conn, $adminActivityQuery);

  $notificationMessage = "Overtime application updated by Admin ID: $adminId";
  $insertNotificationQuery = "INSERT INTO empnotifications (admin_id, emp_id, message, type, status) VALUES ('$adminId','$otemp', '$notificationMessage','Overtime','unread')";
  mysqli_query($conn, $insertNotificationQuery);

  if ($showSweetAlert) {

      if ($otremark == 'Approved' || $otremark == 'Allowed'){
        $icon = 'success';
      }else if ($otremark == 'Rejected'){
        $icon = 'error';
      }
      else if ($otremark == 'Pending'){
        $icon = 'info';
      }

      echo '<script>
      swal({
          text: "Overtime ' . $otremark . '",
          icon: "' . $icon . '", 
          button: "OK",
      }).then(function() {
          window.location.href = "adminOT.php";
      });
  </script>';
} else {
    header("Location:adminOT.php");
}


?>
  <script>
  alert("<?php echo $actionupdatealert;?>");
  </script>
  <?php
  
}

?>





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
INCLUDE ('NAVBAR.php');
?>


<div id="content">

  <div id="content-header">
    <div id="breadcrumb"> <a href="../adminDASHBOARD.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a>
      <a href ="adminOT.php" class="tip-bottom"><i class ="icon-time"></i> Overtimes</a>
      <a href="OTApproval.php" class="tip-bottom"><i class = "icon-edit"></i>Review Overtime</a>
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
                  <span class="userinfo">
                    <label class = "userinfo" for ="selectaction">Action:</label>

                    <select class="userinfo" id = "selectaction" name="otaction">

                      <option></option>
              <?php if ($nowdate<$otday){ ?>
                      
                      <option>Allow</option>
                      <option>Reject</option> 

              <?php }elseif ($nowdate==$otday && $nowtime<$otin){ ?>

                      <option>Allow</option>
                      <option>Reject</option>

              <?php }elseif ($nowdate==$otday){ ?>

                      <option>Allow</option>
                      <option>Reject</option>

               <?php }elseif ($nowdate>$otday){ ?>

                      <option>Approve</option>
                      <option>Reject</option>

              <?php } ?>


                    </select>
             
                  </span><span><button type="submit" class="btn btn-success" name = "submit_btn">Submit</button></span>
                  </p> 
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
  <div id="footer" class="span12"> 2023 &copy; WEB-BASED TIMEKEEPING AND PAYROLL SYSTEM USING FINGERPRINT BIOMETRICS</div>
</div>


<script src="../../js/maruti.dashboard.js"></script> 

</body>
</html>
