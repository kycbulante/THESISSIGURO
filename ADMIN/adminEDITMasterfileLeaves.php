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


$lvtypequery = "SELECT * from LEAVES_TYPE where lvtype_ID = '$idres'";
$lvtypeexecquery = mysqli_query($conn,$lvtypequery) or die ("FAILED TO SEARCH DB ".mysqli_error($conn));
$lvtypearray = mysqli_fetch_array($lvtypeexecquery);

if ($lvtypearray){
	
	$currprefixid = $lvtypearray['lvtype_prefix_id'];
	$currlvtypeid = $lvtypearray['lvtype_ID'];
	$currlvtypename = $lvtypearray['lvtype_name'];
	$currlvtypecount = $lvtypearray['lvtype_count'];
}

    else {
          $_SESSION['delnotif'] ="Leave information not found.";
          }/*2nd else end*/



$error = false;




if(isset($_POST['submit_btn'])){

	$lvtypeid = $_POST['lvid'];
	$lvtypename = $_POST['lvtypename'];
	// $lvtypecount = $_POST['lvcount'];
  
  $deptnamequery = "SELECT 	lvtype_name FROM 	leaves_type where lvtype_name = '$lvtypename'";
  $deptnameresultqry = mysqli_query($conn,$deptnamequery);
  $deptnamecount = mysqli_num_rows($deptnameresultqry);

  if ($deptnamecount !=0){
    $error = true;
    $deptnameerror = "Department already exists.";
  }



  if (!$error){

    $newleavesqry = "UPDATE leaves_type SET lvtype_name = '$lvtypename' where lvtype_ID = '$idres'";
    $newleavesqryresult = mysqli_query($conn,$newleavesqry) or die ("FAILED TO CREATE NEW leaves ".mysql_error());
    $activityLog = "Edited leave from $currlvtypename to $lvtypename";
    $adminActivityQuery = "INSERT INTO adminactivity_log (emp_id, activity,log_timestamp) VALUES ('$adminId', '$activityLog', NOW())";
    $adminActivityResult = mysqli_query($conn, $adminActivityQuery);

    if ($newleavesqryresult) {
      ?>
      <script>
 document.addEventListener('DOMContentLoaded', function() {
     swal({
      //  title: "Good job!",
       text: "Leave Updated successfully",
       icon: "success",
       button: "OK",
      }).then(function() {
         window.location.href = 'adminMasterfileLeaves.php'; // Replace 'your_new_page.php' with the actual URL
     });
 });
</script>
      <?php
  }
} else {
  ?>
  <script>
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
?>






</head>
<body>

<!--Header-part-->

<?php
INCLUDE ('NAVBAR.php');
?>


<div id="content">

  <div id="content-header">
    <div id="breadcrumb"> <a href="index.html" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a>
      <a href ="adminMasterfileLeaves.php" class="tip-bottom"><i class ="icon-th"></i> Manage Leaves</a>
      <a href="adminEDITMasterfileLeaves.php" class="tip-bottom"><i class = "icon-eye-open"></i>Update Leaves</a>
    </div>
  </div>

  <div class="container-fluid">
    <div class = "row-fluid">
      <span class="span3">
      </span>
    <div class="span6">
      <h3>Update Leaves</h3>
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
            <h5>Leave Information</h5>
          </div>

          <div class="widget-content nopadding">
            <form action="<?php $_SERVER['PHP_SELF'];?>" method="POST" class="form-horizontal">
              <div class="control-group">
                <label class="control-label">Leave ID :</label>
                <div class="controls">
                <input type="text" class="span7" value="<?php echo $currprefixid; echo $currlvtypeid;?>"name="lvid" readonly/>
                  <!-- <span class ="label label-important"><?php echo $lvtypeerror; ?></span> -->
                </div>
              </div>

              <div class="control-group">
                <label class="control-label">Leave Name :</label>
                <div class="controls">
                <input type="text" class="span7" value="<?php echo $currlvtypename;?>"name="lvtypename"/>
                  <!-- <span class ="label label-important"><?php echo $lvtypenameerror; ?></span> -->
                </div>
              </div>

              <!-- <div class="control-group">
                <label class="control-label">Number of Leaves:</label>
                <div class="controls">
                <input type="text" class="span2" placeholder="<?php echo $currlvtypecount;?>" name="lvcount">
                   <span class ="label label-important"><?php echo $lvtypecounterror; ?></span> -->
                <!-- </div>
              </div> - -->


              <div class="form-actions">
                <button type="submit" class="btn btn-success" name = "submit_btn" style="float:right;">Submit</button>
              </div>
            </form>
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

<script src="../js/maruti.dashboard.js"></script> 

</body>
</html>
