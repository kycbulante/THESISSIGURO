<!DOCTYPE html>
<html lang="en">
<head>
<title>Manage Holidays</title>
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
$adminId = $_SESSION['adminId'];

$idres = $_GET['id'];
$DELquery = "SELECT * from HOLIDAYS WHERE holiday_ID ='$idres'";
$DELselresult = mysqli_query($conn,$DELquery) or die ("Failed to search DB. ".mysql_error());
  $DELcurr = mysqli_fetch_array($DELselresult);
  $DELcount = mysqli_num_rows($DELselresult);

   if($DELcount!=0 && $DELcurr) {

        $currprefixid = $DELcurr['holiday_prefix_ID'];
        $currholidayid = $DELcurr['holiday_ID'];
        $currholidaydate = $DELcurr['holiday_DATE'];
        $currholidayyear = $DELcurr['holiday_YEAR'];
        $currholidayname = $DELcurr['holiday_NAME'];
        $currholidaytype = $DELcurr['holiday_TYPE'];
       

    }
    else {
          $_SESSION['delnotif'] ="Holiday information not found.";
          }/*2nd else end*/

$error = false;

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



  if (empty($holidaydate)){

    $error = true;
    $holidaydateerror = "Please enter a date for the holiday.";
  }


  if(empty($holidaytype)){

    $error=true;
    $holidaytypeerror = "Please specify holiday type.";
  }
  
  $deptnamequery = "SELECT holiday_NAME FROM holidays where holiday_NAME = '$holidayname'";
  $deptnameresultqry = mysqli_query($conn,$deptnamequery);
  $deptnamecount = mysqli_num_rows($deptnameresultqry);

  if ($deptnamecount !=0){
    $error = true;
    $deptnameerror = "Department already exists.";
  }



  if (!$error){

    $newholidayqry = "UPDATE HOLIDAYS SET holiday_DATE = '$holidaydate', holiday_YEAR = '$holidayyear', holiday_NAME = '$holidayname', holiday_TYPE = '$holidaytype' WHERE holiday_ID = '$currholidayid'";
    $newholidayqryresult = mysqli_query($conn,$newholidayqry) or die ("FAILED TO ADD HOLIDAY ".mysql_error());
    $activityLog = "Edited holiday from $currholidayname to ($holidayname)";
    $adminActivityQuery = "INSERT INTO adminactivity_log (emp_id, activity,log_timestamp) VALUES ('$adminId', '$activityLog', NOW())";
    $adminActivityResult = mysqli_query($conn, $adminActivityQuery);

    
    if ($newholidayqryresult) {
      ?>
      <script>
 document.addEventListener('DOMContentLoaded', function() {
     swal({
      //  title: "Good job!",
       text: "Holiday Updated successfully",
       icon: "success",
       button: "OK",
      }).then(function() {
         window.location.href = 'adminMasterfileHoliday.php'; // Replace 'your_new_page.php' with the actual URL
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
    <div id="breadcrumb"> <a href="adminDASHBOARD.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a>
    <a href ="adminMasterfileHoliday.php" class="tip-bottom"><i class ="icon-calendar"></i> Manage Holidays</a>
      <a href="#" class="tip-bottom"><i class = "icon-trash"></i>Edit Holiday</a>
    </div>
  </div>

  <div class="container-fluid">
    <div class = "row-fluid">
      
  <div class="span3">
  </div>
  
    <div class="span6">
      <h3>Update Holiday</h3>
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-calendar"></i> </span>
            <h5>Holiday Information</h5>
          </div>
          
          <div class="widget-content nopadding">
            <form action="adminEDITMasterfileHoliday.php?id=<?php echo $idres;?>" method="POST" class="form-horizontal">
           
              <div class="control-group">
                <label class="control-label">Holiday ID: </label>
                <div class="controls">
                <input type="text" class="span3" value = "<?php echo $currprefixid;?><?php echo $currholidayid;?>" name="DELCONid" readonly/>
                </div>
              </div>

               <div class="control-group">
                <label class="control-label">Holiday Date: </label>
                <div class="controls">
                <input type="text" class="span3" id="holidaypicker" name ="hdpicker" placeholder="<?php echo $currholidaydate; ?>" value="<?php echo $currholidaydate; ?>">
                  <!-- <span class ="label label-important"><?php echo $holidaydateerror; ?></span> -->
              </div>

              <div class="control-group">
                <label class="control-label">Holiday Name: </label>
                <div class="controls">
                <input type="text" class="span10" value = "<?php echo $currholidayname;?>" name="holidayname"/>
                </div>
              </div>

              <div class="control-group">
                <label class="control-label">Holiday Type:</label>
                <div class="controls">

                <select class = "span5" name="holidaytypeoption">
                  <option><?php echo $currholidaytype;?></option>
                  <option>Regular Holiday</option>
                  <option>Special Non-working Holiday</option>
                </select>
                <!-- <span class ="label label-important"><?php echo $holidaytypeerror; ?></span> -->
                  
                </div>
              </div>

              <div class="form-actions">
                <button type="submit" class="btn btn-success" name = "submit_btn" style="float:right;">Update</button>
                
            </div>

        </div>
    </div>
  </div>
    <hr>
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
