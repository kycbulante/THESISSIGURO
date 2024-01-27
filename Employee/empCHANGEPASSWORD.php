<!DOCTYPE html>
<html lang="en">
<head>
<title>Employee Home</title>
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
// include("../logfunctions.PHP");

session_start();



$uname = $_SESSION['uname'];


$getinfoqry = "SELECT * from employees WHERE user_name = '$uname'";
$getinfoexecqry = mysqli_query($conn,$getinfoqry) or die ("FAILED TO GET INFORMATION ".mysqli_error($conn));
$getinfoarray = mysqli_fetch_array($getinfoexecqry);
$getinforows = mysqli_num_rows($getinfoexecqry);
if ($getinfoarray && $getinforows !=0){

 $currprefixid = $getinfoarray['prefix_ID'];
 $currempid = $getinfoarray['emp_id'];
        $currfingerprintid = $getinfoarray['fingerprint_id'];
        $currusername = $getinfoarray['user_name'];
        $currlastname = $getinfoarray['last_name'];
        $currpassword = $getinfoarray['pass_word'];
        $currfirstname = $getinfoarray['first_name'];
        $currmiddlename = $getinfoarray['middle_name'];
        $currdateofbirth = $getinfoarray['date_of_birth'];
        $curraddress = $getinfoarray['emp_address'];
        $currnationality = $getinfoarray['emp_nationality'];
        $currdeptname = $getinfoarray['dept_NAME'];
        $currshiftsched = $getinfoarray['shift_SCHEDULE'];
        $currcontact = $getinfoarray['contact_number'];
        $currdatehired = $getinfoarray['date_hired'];
        $currdateregularized = $getinfoarray['date_regularized'];
        $currdateresigned = $getinfoarray['date_resigned'];
        $currimg = $getinfoarray['img_tmp'];
$_SESSION['empID'] = $currempid;

}



$error = false;

if(isset($_POST['submit_btn'])){

  $currpass = $_POST['currpass'];
  $newpass = $_POST['newpass'];
  $newpass2 = $_POST['newpass2'];
  if($currpass != $currpassword){
    $error = true;
    $passwordError = "Password does not match the current password.";
  }

  if ($newpass!=$newpass2){
    $error = true;
    $newpasswordError = "Passwords do not match.";

  }


  if (!$error) {
    $changepassquery = "UPDATE employees SET pass_word = '$newpass2' WHERE emp_id = '$currempid'";
    $changepassqueryexec = mysqli_query($conn, $changepassquery) or die("FAILED TO CHANGE PASS " . mysqli_error($conn));

    // Log the password change attempt or success
    logPasswordChange($conn, $currempid, $changepassqueryexec);

    // $_SESSION['changepassnotif'] = "You have changed your password.";
    ?><script>
    document.addEventListener('DOMContentLoaded', function() {
        swal({
         //  title: "Good job!",
          text: "Password Changed successfully",
          icon: "success",
          button: "OK",
         }).then(function() {
            window.location.href = 'empDashboard.php'; // Replace 'your_new_page.php' with the actual URL
        });
    });
 </script>
 <?php
} else {
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
  logPasswordChange($conn, $currempid, false);
}
}


?>







</head>

<body>

<!--Header-part-->

<?php
INCLUDE ('empNAVBAR.php');
?><div id="content">

  <div id="content-header">
    <div id="breadcrumb"> <a href="empDASHBOARD.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a>
      <a href="empDASHBOARD.php" title="Employee Info" class="tip-bottom"><i class="icon-user"></i> Change Password</a>
    </div>
  </div>

  <div class="container-fluid">
    <div class = "row-fluid">
      <span class ="span3">
      </span>
      <span class="span6">
        <h3>Change Password</h3>
      </span>
    </div>
    <div class="row-fluid">

      <div class = "span3">
      </div>

      <div class ="span6">
        <div class="widget-box">
          <div class = "widget-title"><span class="icon"><i class ="icon-user"></i></span>
            <h5> Change Password</h5>
          </div>

          <div class = "widget-content no padding">
            <form method = "post" action="<?php $_SERVER['PHP_SELF']; ?>">
              
              <div class="control-group">
                  <label class="control-label">Enter current password:</label>
                      <div class="controls">
                        <input type="password" class="span11" placeholder="" name="currpass"/>
                        <!-- <span class ="label label-important"><?php echo $passwordError; ?></span> -->
                      </div>
              </div>

               <div class="control-group">
                  <label class="control-label">Enter new password:</label>
                      <div class="controls">
                        <input type="password" class="span11" placeholder="" name="newpass"/>
                        <!-- <span class ="label label-important"><?php echo $newpasswordError; ?></span> -->
                      </div>
              </div>

               <div class="control-group">
                  <label class="control-label">Enter new password again:</label>
                      <div class="controls">
                        <input type="password" class="span11" placeholder="" name="newpass2"/>
                        <!-- <span class ="label label-important"><?php echo $newpasswordError; ?></span> -->
                      </div>
              </div>

              <div class="form-actions">
                    <button type="submit" class="btn btn-success" name = "submit_btn" style="float:right;">Update</button>
                  </div>


            </form>
          </div>
        </div>
      </div>

      <div class ="span3">
      </div>  
    </div>
   
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