<!DOCTYPE html>
<html lang="en">
<head>
<title>Leave Application</title>
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
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<?php
include("../DBCONFIG.PHP");
include("../LoginControl.php");
include("../BASICLOGININFO.PHP");

session_start();

$idres = $_GET['id'];

$lvcountqry = "SELECT * FROM LEAVES WHERE emp_id = '$idres'";
$lvcountexecqry = mysqli_query($conn,$lvcountqry) or die ("FAILED TO COUNT LEAVE AVAILABILITY ".mysqli_error($conn));
$lvcount = mysqli_fetch_array($lvcountexecqry);


if ($lvcount){

  $leavecount = $lvcount['leave_count'];
  $vacleavecount = $lvcount['vacleave_count'];
}




$error = false;

if (isset($_POST['submit_btn'])){

  if ($leavecount>0){

  $lvtypesel = $_POST['lvtype'];
  $lvstartdate = $_POST['lvstart'];
  $lvenddate = $_POST['lvend'];
  $lvinfo = $_POST['newotinfo'];
  $lvstatus = "Pending";

  if(empty($lvtypesel)){  

    $error = true;
    $leavetypeerror = "Please select a leave type.";

  }

  if(empty($lvstartdate)){
    $error = true;
    $leavestartError = "Please provide a date start for your leave.";

  }

  if(!$error){

    $insertlv = "INSERT INTO LEAVES_APPLICATION (emp_id,leave_type,leave_datestart,leave_dateend,leave_info,leave_status) VALUES ('$idres','$lvtypesel','$lvstartdate','$lvenddate','$lvinfo','$lvstatus')";
    $insertlvexec = mysqli_query($conn,$insertlv) or die ("FAILED TO APPLY LEAVE ".mysqli_error($conn));
    $notificationMessage = "New leave application submitted by Employee ID: $idres";
    $insertNotificationQuery = "INSERT INTO notifications (emp_id, message) VALUES ('$idres', '$notificationMessage')";
    mysqli_query($conn, $insertNotificationQuery);

    logLeave($conn, $idres, true);

    if($insertlvexec){
      if(empty($lvenddate)){
        $lvdays = '1';
      } else {

        $lvdayscount = "SELECT *, DATEDIFF('$lvenddate','$lvstartdate') as LEAVEDAYS FROM LEAVES_APPLICATION where emp_id = '$idres' AND leave_datestart = '$lvstartdate'";
        $lvdayscountexec = mysqli_query($conn,$lvdayscount);
        $lvdaysarray = mysqli_fetch_array($lvdayscountexec);
        if ($lvdaysarray){

          $lvdays = $lvdaysarray['LEAVEDAYS'];


        }

      }

      $newLeaveCount = $leavecount - $lvdays;
      $updateQuery = "UPDATE leaves SET leave_count = '$newLeaveCount'  WHERE emp_id = $idres";
      mysqli_query($conn, $updateQuery);


      $updateleavedays = "UPDATE LEAVES_APPLICATION SET leave_days = '$lvdays' WHERE emp_id = '$idres' AND leave_datestart = '$lvstartdate'";
      $updateleavedaysexec = mysqli_query($conn,$updateleavedays) or die ("FAILED TO UPDATE ".mysqli_error($conn));
     
      // $_SESSION['LEAVEAPPROVAL'] = "Leave application has been sent.";
      // header("Location:empAPPLYLeave.php");
      ?>
      <script>
      document.addEventListener('DOMContentLoaded', function() {
          swal({
            //  title: "Good job!",
            text: "Leave Application Submitted",
            icon: "success",
            button: "OK",
            }).then(function() {
              window.location.href = 'empAPPLYLeave.php'; // Replace 'your_new_page.php' with the actual URL
          });
      });
    </script>
        <?php
      } 
  }else{
    logLeave($conn, $idres, false);
  }





} else {
  // $_SESSION['LEAVEAPPROVAL'] = "hatdog";
  // header("Location:empAPPLYLeave.php");
    $errType = "danger";
    // $_SESSION['addprofilenotif'] = "Something went wrong. Make sure you accomplish all the required fields.";
    ?><script>
    document.addEventListener('DOMContentLoaded', function() {
        swal({
          // title: "Data ",
          text: "Not enough leave credits..",
          icon: "error",
          button: "Try Again",
          }).then(function() {
              window.location.href = 'empLEAVEApplication.php'; // Replace 'your_new_page.php' with the actual URL
          });
        });
    </script>
    <?php
  
}
}


?>






<script type ="text/javascript">
  $( function() {
      $( "#leavestart" ).datepicker({ dateFormat: 'yy-mm-dd'});
      } );

  $( function() {
      $( "#leaveend" ).datepicker({ dateFormat: 'yy-mm-dd'});
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
      <a href ="empAPPLYLeave.php" class="tip-bottom"><i class ="icon-calendar"></i> Apply Leave</a>
      <a href="#" class="tip-bottom"><i class = "icon-plus"></i>Leave Application Form</a>
    </div>
  </div>

  <div class="container-fluid">
    <div class = "row-fluid">
      <span class="span3">
      </span>
    <div class="span6">
      <h3>Leave Application</h3>
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
            <h5>Leave Details</h5>
          </div>

          <div class="widget-content nopadding">
            <form action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" class="form-horizontal">


              <div class ="control-group">
                    <label class="control-label">Leave Credits</label>
                      <div class="controls">
                        <span class ="badge badge-success"><?php echo $leavecount;?></span>

                      </div>
              </div>


              <!-- <div class ="control-group">
                    <label class="control-label">Available Vacation Leave: </label>
                      <div class="controls">
                        <span class ="badge badge-success">15</span>

                      </div>
              </div> -->

<?php
      $leavetypesquery = "SELECT * FROM LEAVES_TYPE";
      $leavetypesexecqry = mysqli_query($conn, $leavetypesquery) or die ("FAILED TO EXECUTE leaves type QUERY ".mysql_error());
      ?>
              <div class ="control-group">
                    <label class="control-label">Leave Type: </label>
                      <div class="controls">
                        <select name="lvtype">
                          <option></option>
                          <?php  while($leavechoice = mysqli_fetch_array($leavetypesexecqry)):;?>
                          <option><?php echo $leavechoice['lvtype_name'];?></option>
                          <?php endwhile; ?>
                        </select>
                        <!-- <span class = "label label-important"><?php echo $leavetypeerror; ?></span> -->
                      </div>
                  </div>

                  <div class ="control-group">
                    <label class="control-label">Date Start: </label>
                      <div class="controls">
                        <input type="text" id="leavestart" name ="lvstart" placeholder="Start Date" value="">
                        <!-- <span class ="label label-important"><?php echo $leavestartError; ?></span> -->

                      </div>

                  </div>

                  <div class ="control-group">

                    <label class="control-label">Date End: </label>
                      <div class="controls">

                        <input type="text" id="leaveend" name ="lvend" placeholder="End Date" value="">
                        <!-- <span class ="label label-important"><?php echo $leaveendError; ?></span> -->
                        <small>*Provide an end date if leave is more than one day</small>
                      </div>
                  </div>

                  <div class ="control-group">
                    <label class="control-label">Leave Details:</label>
                      <div class = "controls">

                        <textarea id="otinformationn" value="<?php echo $otinformation;?>" name="newotinfo"></textarea>

                      </div>
                  </div>
              <div class="form-actions">
                <button type="submit" class="btn btn-success"  name = "submit_btn" style="float:right;">Submit</button>
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
