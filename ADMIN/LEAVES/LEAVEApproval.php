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
<!-- <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script> -->
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker@3.1.0/daterangepicker.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<?php
include("../../DBCONFIG.PHP");
include("../../LoginControl.php");
include("BASICLOGININFO.PHP");

session_start();
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
$otquery = "SELECT * FROM LEAVES_APPLICATION WHERE la_id= '$otID'";
$otqueryexec = mysqli_query($conn,$otquery) or die ("FAILED TO GET OT INFO ".mysqli_error($conn));
$otinfo = mysqli_fetch_array($otqueryexec);

if ($otinfo){

  $otemp = $otinfo['emp_id'];
  $leavestart = $otinfo['leave_datestart'];
  $leaveend = $otinfo['leave_dateend'];
  $leavetype = $otinfo['leave_type'];
  $leavedays = intval($otinfo['leave_days']);
  $leaveinformation = $otinfo['leave_info'];
  $leavestatus = $otinfo['leave_status'];
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

if(isset($_POST['submit_btn'])){

  $actionupdate = $_POST['otaction'];
  $actioninfoupdate = $_POST['newotinfo'];
  
  if ($actionupdate == "Approve"){

    if ($leavedays == 1){
      $lvdeduct = '1';
      $updatelvcount = "UPDATE LEAVES SET leave_count =(leave_count - '$lvdeduct') WHERE emp_id = '$otemp' AND leaves_year = YEAR(CURDATE())";
      $updatelvcountexecqry = mysqli_query($conn,$updatelvcount) or die ("FAILED TO DEDUCT LEAVES ".mysqli_query($conn));

      $updatetimekeep = "INSERT INTO TIME_KEEPING (emp_id,in_morning,out_morning,in_afternoon,out_afternoon,lv_hours,hours_work,timekeep_day,timekeep_remarks) VALUES ('$otemp','00:00:00','00:00:00','00:00:00','00:00:00','8','8','$leavestart','$leavetype')";               
      $updatetimekeepexec = mysqli_query($conn,$updatetimekeep) or die ("FAILED TO APPROVE ".mysqli_error($conn));


    }else if ($leavedays>1){
     $lvdeduct = $leavedays;
      $updatelvcount = "UPDATE LEAVES SET leave_count =(leave_count - '$lvdeduct') WHERE emp_id = '$otemp' AND leaves_year = YEAR(CURDATE())";
      $updatelvcountexecqry = mysqli_query($conn,$updatelvcount) or die ("FAILED TO DEDUCT LEAVES ".mysqli_query($conn));
      $leavedate = date('Y-m-d',strtotime("$leavestart"));

      for ($x = 0; $x<=$leavedays; $x++){
        $cntleave = "SELECT * FROM TIME_KEEPING WHERE emp_id = '$otemp' AND timekeep_day = '$leavedate'";
        $cntleaveexec = mysqli_query($conn,$cntleave) or die ("FAILED TO CHECK LEAVE DAY ".mysqli_query($conn));
        $cntleaverow = mysqli_num_rows($cntleaveexec);

        if ($cntleaverow !=1){
            $updatetimekeep = "INSERT INTO TIME_KEEPING (emp_id,in_morning,out_morning,in_afternoon,out_afternoon,lv_hours,hours_work,timekeep_day,timekeep_remarks) VALUES ('$otemp','00:00:00','00:00:00','00:00:00','00:00:00','8','8','$leavedate','$leavetype')";               
            $updatetimekeepexec = mysqli_query($conn,$updatetimekeep) or die ("FAILED TO APPROVE ".mysqli_error($conn));


            $leavedate = date('Y-m-d',strtotime("$leavedate+1 day"));
        }
        
      }
    
    }

     $otremark = "Approved";
    // $_SESSION['OTAPPROVAL'] = "LEAVE APPROVED";
    $showSweetAlert = true;
    
  } else if ($actionupdate == "Reject"){
    $otremark = "Rejected";
    // $_SESSION['OTAPPROVAL'] = "LEAVE REJECTED.";
    $showSweetAlert = true;
   
  } else if ($actionupdate ==""){
    $otremark = "Pending";
    // $_SESSION['OTAPPROVAL'] = "LEAVE PENDING.";
    $showSweetAlert = true;
  }

  $updateot = "UPDATE LEAVES_APPLICATION SET leave_info = '$actioninfoupdate', leave_status = '$otremark', leave_approver = '$name' WHERE la_id = '$otID'";
  $updateotexec = mysqli_query($conn,$updateot) or die ("FAILED TO APPROVE/REJECT ".mysqli_error($conn));

  $activityLog = "Changed leave status ($otremark)";
  $adminActivityQuery = "INSERT INTO adminactivity_log (emp_id, activity,log_timestamp) VALUES ('$adminId', '$activityLog', NOW())";
  $adminActivityResult = mysqli_query($conn, $adminActivityQuery);


  $notificationMessage = "Leave application updated by Admin ID: $adminId";
  $insertNotificationQuery = "INSERT INTO empnotifications (admin_id,emp_id, message, type, status) VALUES ('$adminId', '$otemp','$notificationMessage','Leave','unread')";
  mysqli_query($conn, $insertNotificationQuery);

  // if($updateotexec){

  //   header("Location:adminLEAVES.php");
  
  // }
  if ($showSweetAlert) {

    if ($otremark == 'Approved' || $otremark == 'Allowed'){
      $icon = 'success';
    }else if ($otremark == 'Rejected'){
      $icon = 'error';
    }
    else if ($otremark == "Pending"){
      $icon = 'info';
    }

?>
   
   <script>
   document.addEventListener('DOMContentLoaded', function() {
       swal({
        //  title: "Good job!",
         text: "Leave <?php echo $otremark; ?>",
         icon: "<?php echo $icon; ?>",
         button: "OK",
        }).then(function() {
           window.location.href = 'adminLEAVES.php'; // Replace 'your_new_page.php' with the actual URL
       });
   });
</script>
    <?php
} else {
  header("Location:adminLEAVES.php");
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
      <a href ="adminOT.php" class="tip-bottom"><i class ="icon-calendar"></i> Leaves</a>
      <a href="OTApproval.php" class="tip-bottom"><i class = "icon-edit"></i>Leave Approval</a>
    </div>
  </div>

  <div class="container-fluid">
    <div class = "row-fluid">
      <span class="span3">
      </span>
    <div class="span6">
      <h3>Leave Approval</h3>
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
            <h5>Leave Information</h5>
          </div>

          <div class="widget-content nopadding">
            <form action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" class="form-horizontal">
                  <br>
                  <p>
                  <span class="userinfo">Employee ID:<b> <?php echo $empidinfo;?></b></span><br>
                  <span class="userinfo">Name:<b> <?php echo $fullname;?></b></span><br>
                  <span class="userinfo">Shift:<b> <?php echo $shiftsched;?></b></span><br>
                  <span class="userinfo">LEAVE TYPE:<b> <?php echo $leavetype;?></b></span><br>
                  <span class="userinfo">START DATE OF LEAVE:<b> <?php echo $leavestart;?></b></span>
                  <span class="userinfo">LEAVE UNTIL:<b> <?php echo $leaveend;?></b></span>
                  <span class="userinfo">LEAVE DAYS:<b> <?php echo $leavedays;?></b></span>
                  
                  
                  <div class = "userinfo">
                    <label for="otinformation">LEAVE DETAILS:</label>
                    <textarea id="otinformationn" value="<?php echo $leaveinformation;?>" name="newotinfo"><?php echo $leaveinformation;?></textarea>
                  </div>
                  <span class="userinfo">
                    <label class = "userinfo" for ="selectaction">Action:</label>

                    <select class="userinfo" id = "selectaction" name="otaction">
                      <option></option>
                      <option>Approve</option>
                      <option>Reject</option> 
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
  <div id="footer" class="span12"> 2018 &copy; Tagbac Multi-purpose Cooperative.</div>
</div>


<script src="../../js/maruti.dashboard.js"></script> 

</body>
</html>
