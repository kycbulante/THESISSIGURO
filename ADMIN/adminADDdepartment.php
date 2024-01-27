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
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker@3.1.0/daterangepicker.css" />
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker@3.1.0/daterangepicker.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

</head>



<?php
include("../DBCONFIG.PHP");
include("../LoginControl.php");
include("../BASICLOGININFO.PHP");

session_start();

$adminId = $_SESSION['adminId'];
$error = false;

if(isset($_POST['submit_btn'])){

  $deptname = $_POST['deptname'];

  if(empty($deptname)){

    $error = true;
    $deptnameerror = "Please enter a department name.";

  }

  $deptnamequery = "SELECT dept_NAME FROM DEPARTMENT where dept_NAME = '$deptname'";
  $deptnameresultqry = mysqli_query($conn,$deptnamequery);
  $deptnamecount = mysqli_num_rows($deptnameresultqry);

  if ($deptnamecount !=0){
    $error = true;
    $deptnameerror = "Department already exists.";
  }


if (!$error) {
    $newdeptqry = "INSERT INTO DEPARTMENT (dept_NAME) VALUES ('$deptname')";
    $newdeptqryresult = mysqli_query($conn, $newdeptqry) or die ("FAILED TO CREATE NEW DEPARTMENT " . mysqli_error($conn));
    $activityLog = "Added a new department ($deptname)";
    $adminActivityQuery = "INSERT INTO adminactivity_log (emp_id, activity,log_timestamp) VALUES ('$adminId', '$activityLog', NOW())";
    $adminActivityResult = mysqli_query($conn, $adminActivityQuery);

    if ($newdeptqryresult) {
        ?>
        <script>
   document.addEventListener('DOMContentLoaded', function() {
       swal({
        //  title: "Good job!",
         text: "Department inserted successfully",
         icon: "success",
         button: "OK",
        }).then(function() {
           window.location.href = 'adminMasterfileDept.php'; // Replace 'your_new_page.php' with the actual URL
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





<body>

<!--Header-part-->





<?php
INCLUDE ('NAVBAR.php');
?>


<div id="content">

  <div id="content-header">
    <div id="breadcrumb"> <a href="index.html" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a>
      <a href ="adminMasterfileDept.php" class="tip-bottom"><i class ="icon-th"></i> Manage Departments</a>
      <a href="#" class="tip-bottom"><i class = "icon-plus"></i>Add Department</a>
    </div>
  </div>

  <div class="container-fluid">
    <div class = "row-fluid">
      <span class="span3">
      </span>
    <div class="span6">
      <h3>Add Department</h3>
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
            <h5>Department Information</h5>
          </div>

          <div class="widget-content nopadding">
            <form action="adminADDdepartment.php" method="POST" class="form-horizontal">
              <div class="control-group">
                <label class="control-label">Department Name :</label>
                <div class="controls">
                  <input type="text" class="span7" placeholder="Department Name" name="deptname"/>
                  <!-- <span class ="label label-important"><?php echo $deptnameerror; ?></span> -->
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


<script src="../js/maruti.dashboard.js"></script> 

</body>
</html>
