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
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker@3.1.0/daterangepicker.css" />
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
<!-- <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script> -->
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker@3.1.0/daterangepicker.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<?php
include("../DBCONFIG.PHP");
include("../LoginControl.php");
include("../BASICLOGININFO.PHP");

session_start();


$error = false;
$adminId = $_SESSION['adminId'];
if(isset($_POST['submit_btn'])){

  $holidayname = trim($_POST['holidayname']);
  $holidayname = strip_tags($holidayname);
  $holidayname = htmlspecialchars($holidayname, ENT_QUOTES);
  $holidaydate = $_POST['hdpicker'];
  $holidaytype = $_POST['holidaytypeoption'];

  $date = strtotime($holidaydate);
  $holidayyear = date("Y",$date);

  if(empty($holidayname)){

    $error = true;
    $holidaynameerror = "Please enter a holiday name.";

  }

  $deptnamequery = "SELECT holiday_NAME FROM holidays where holiday_NAME = '$holidayname'";
  $deptnameresultqry = mysqli_query($conn,$deptnamequery);
  $deptnamecount = mysqli_num_rows($deptnameresultqry);

  if ($deptnamecount !=0){
    $error = true;
    $deptnameerror = "Department already exists.";
  }

  // $holidayaddquery = "SELECT holiday_NAME,holiday_YEAR FROM HOLIDAYS where holiday_NAME = '$deptname' and holiday_YEAR = '$holidayyear'";
  // $holidayaddresultqry = mysqli_query($conn,$holidayaddquery) or die ("FAILED TO VALIDATE HOLIDAY ".mysql_error());
  // $holidayaddcount = mysqli_num_rows($holidayaddresultqry);

  // if ($holidayaddcount !=0){
  //   $error = true;
  //   $holidaynameerror = "Holiday is already set.";
  // }

  // if (empty($holidaydate)){

  //   $error = true;
  //   $holidaydateerror = "Please enter a date for the holiday.";
  // }


  // if(empty($holidaytype)){

  //   $error=true;
  //   $holidaytypeerror = "Please specify holiday type.";
  // }

  if (!$error){
    // echo $holidaytype;


    $newholidayqry = "INSERT INTO HOLIDAYS (holiday_DATE, holiday_YEAR, holiday_NAME, holiday_TYPE) VALUES ('$holidaydate', '$holidayyear', '$holidayname', '$holidaytype')";
    $newholidayqryresult = mysqli_query($conn,$newholidayqry) or die ("FAILED TO ADD HOLIDAY ".mysqli_error($conn));
    $activityLog = "Added a new holiday ($holidayname)";
    $adminActivityQuery = "INSERT INTO adminactivity_log (emp_id, activity,log_timestamp) VALUES ('$adminId', '$activityLog', NOW())";
    $adminActivityResult = mysqli_query($conn, $adminActivityQuery);

    

    if ($newholidayqryresult) {
      ?>
      <script>
 document.addEventListener('DOMContentLoaded', function() {
     swal({
      //  title: "Good job!",
       text: "Holiday inserted successfully",
       icon: "success",
       button: "OK",
      }).then(function() {
         window.location.href = 'adminMasterfileHoliday.php'; // Replace 'your_new_page.php' with the actual URL
     });
 });
</script>
      <?php
  }else {
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
 else {
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




<!-- 
    // if($newholidayqryresult){

      // $_SESSION['masterfilenotif'] = "New Holiday Added!";
      
     
    // }
  // } else {

    // $_SESSION['anewholiday'] = "Something went wrong. Please try again.";
  // }

      // if ($holidaytype == 'Legal Holiday'){

      // $addholidaysel = "SELECT emp_id FROM Masterfile";
      // $addholidayexecquery = mysqli_query($conn, $addholidaysel) or die ("FAILED TO ADD HOLIDAY SELECT ".mysqli_error($conn));

      // while ($addholidayrow = mysqli_fetch_array($addholidayexecquery)):;
      //   $empaddid = $addholidayrow['emp_id'];
      //   $checkholiday = "SELECT emp_id,timekeep_day FROM TIME_KEEPING WHERE emp_id = '$empaddid' AND timekeep_day = '$holidaydate'";
      //   $checkholidayexecquery = mysqli_query($conn,$checkholiday) or die ("FAILED TO CHECK HOLIDAY ".mysqli_error($conn));
      //   $checkholidaycount = mysqli_num_rows($checkholidayexecquery);

      //   if ($checkholidaycount!=1){
      //     $holidayinsert = "INSERT INTO TIME_KEEPING(emp_id,rh_hours, hours_work,timekeep_day,timekeep_remarks) VALUES('$empaddid','8','0','$holidaydate','$holidaytype')";
      //     $holidayinsertexecqry = mysqli_query($conn,$holidayinsert) or die ("FAILED TO INSERT LEG. HOLIDAY ".mysqli_error($conn));
      //    /** $holidaydtrinsert = "INSERT INTO DTR (emp_id,hours_worked,DTR_day,DTR_remarks) VALUES('$empaddid','8','$holidaydate','$holidaytype')";
      //     $holidaydtrinsertexecqry = mysqli_query($conn,$holidaydtrinsert) or die ("FAILED TO INSERT DTR REG. HOLIDAY ".mysqli_error($conn));**/
      //   }
      //  endwhile;
       
    // }
    // unset($holidayname);
    //   unset($holidaydate);
    //   unset($holidayyear);
    //   unset($holidaytype); 
    //    header("Location:adminMasterfileHoliday.php"); -->







<script type ="text/javascript">
  $( function() {
      $( "#holidaypicker" ).datepicker({ dateFormat: 'yy-mm-dd'});
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
      <a href ="adminMasterfileHoliday.php" class="tip-bottom"><i class ="icon-th"></i> Manage Holidays</a>
      <a href="#" class="tip-bottom"><i class = "icon-plus"></i>Add Holiday</a>
    </div>
  </div>

  <div class="container-fluid">
    <div class = "row-fluid">
      <span class="span3">
      </span>
    <div class="span6">
      <h3>Add Holiday</h3>
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
            <h5>Holiday Information</h5>
          </div>

          <div class="widget-content nopadding">
            <form action="adminADDholiday.php" method="POST" class="form-horizontal">
          
              <div class ="control-group">
                <label class="control-label">Holiday Date: </label>
                <div class="controls">
        
                  <input type="text" class="span3" id="holidaypicker" name ="hdpicker" placeholder="Holiday Date" value="">
                  <!-- <span class ="label label-important"><?php echo $holidaydateerror; ?></span> -->

                             
                </div>
              </div>

              <div class="control-group">
                <label class="control-label">Holiday Name:</label>
                <div class="controls">
                  <input type="text" class="span11" placeholder="Holiday Name" name="holidayname"/>
                  <!-- <span class ="label label-important"><?php echo $holidaynameerror; ?></span> -->
                </div>
              </div>

               <div class="control-group">
                <label class="control-label">Holiday Type:</label>
                <div class="controls">

                <select class = "span4" name="holidaytypeoption">
                  <option></option>
                  <option>Regular Holiday</option>
                  <option>Special Holiday</option>
                </select>
                <!-- <span class ="label label-important"><?php echo $holidaytypeerror; ?></span> -->
                  
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
unset($_SESSION['anewholiday']);
?>

<script src="../js/maruti.dashboard.js"></script> 

</body>
</html>
