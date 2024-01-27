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

if(isset($_POST['delete_btn'])){

 
  $selquery="SELECT holiday_ID FROM HOLIDAYS  WHERE holiday_ID ='$idres'";
  $selresult = mysqli_query($conn,$selquery);
  $selcount = mysqli_num_rows($selresult);
  $activityLog = "Deleted holiday named ($currholidayname)";
  $adminActivityQuery = "INSERT INTO adminactivity_log (emp_id, activity,log_timestamp) VALUES ('$adminId', '$activityLog', NOW())";
  $adminActivityResult = mysqli_query($conn, $adminActivityQuery);
  
  if($selcount!=0){
  $DELquery2 = "DELETE FROM HOLIDAYS WHERE holiday_ID = '$idres'";
  $delval = mysqli_query($conn,$DELquery2);


  /**$auditinfo = $idres+" Profile deleted";

           $auditquery = "INSERT INTO audittrail (emp_id, audit_info) VALUES ('$empid','$auditinfo')";
            $auditresult = mysqli_query($conn,$auditquery) or die(mysql_error());  
*/
if ($delval) {
  echo "success";
} else {
  echo "Error deleting profile.";
}
} else {
echo "Employee Profile does not exist.";
}
exit(); 
}


?>




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
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker@3.1.0/daterangepicker.css" />
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker@3.1.0/daterangepicker.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</head>


<script>
    function confirmDelete() {
        swal({
                title: "Are you sure?",
                text: "Once deleted, you will not be able to recover this holiday!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    var xhttp = new XMLHttpRequest();
                    xhttp.onreadystatechange = function () {
                        if (this.readyState == 4 && this.status == 200) {
                            var response = this.responseText.trim();
                            if (response === "success") {
                                swal("Holiday deleted successfully!", { icon: "success" })
                                    .then(() => {
                                        window.location.href = "adminMasterfileHoliday.php";
                                    });
                            } else {
                                swal("Error deleting profile: " + response, { icon: "error" });
                            }
                        }
                    };

                    xhttp.open("POST", "", true);
                    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                    xhttp.send("id=<?php echo $idres; ?>&delete_btn=1");
                } else {
                    swal("Holiday is safe!", { icon: "info" });
                }
            });
    }
</script>

<body>

<!--Header-part-->



<?php
INCLUDE ('NAVBAR.php');
?>


<div id="content">

  <div id="content-header">
    <div id="breadcrumb"> <a href="adminDASHBOARD.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a>
    <a href ="adminMasterfileHoliday.php" class="tip-bottom"><i class ="icon-calendar"></i> Manage Holidays</a>
      <a href="#" class="tip-bottom"><i class = "icon-trash"></i>Delete Holiday</a>
    </div>
  </div>

  <div class="container-fluid">
    <div class = "row-fluid">
      
  <div class="span3">
  </div>
  
    <div class="span6">
      <h3>Holiday Delete</h3>
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
            <h5>Holiday Delete Confirmation</h5>
          </div>
          <div class="widget-content nopadding">
          <form action="<?php echo $_SERVER['PHP_SELF']; ?>?id=<?php echo $idres; ?>" method="POST" class="form-horizontal" onsubmit="confirmDelete(); return false;">
           
              <div class="control-group">
                <label class="control-label">Holiday ID: </label>
                <div class="controls">
                  <input type="text" class="span3" value = "<?php echo $currprefixid;?><?php echo $currholidayid;?>" name="DELCONid" readonly/>
                </div>
              </div>

               <div class="control-group">
                <label class="control-label">Holiday Date: </label>
                <div class="controls">
                  <input type="text" class="span3" value = "<?php echo $currholidaydate;?>" name="DELCONname" readonly/>
                </div>
              </div>

              <div class="control-group">
                <label class="control-label">Holiday Name: </label>
                <div class="controls">
                  <input type="text" class="span3" value = "<?php echo $currholidayname;?>" name="DELCONname" readonly/>
                </div>
              </div>

              <div class="control-group">
                <label class="control-label">Holiday Year: </label>
                <div class="controls">
                  <input type="text" class="span2" value = "<?php echo $currholidayyear;?>" name="DELCONname" readonly/>
                </div>
              </div>

              <div class="control-group">
                <label class="control-label">Holiday Type: </label>
                <div class="controls">
                  <input type="text" class="span3" value = "<?php echo $currholidaytype;?>" name="DELCONname" readonly/>
                </div>
              </div>

              <div class="form-actions">
                <button type="submit" class="btn btn-success" style="float:right;" onclick="confirmDelete()">Delete</button>
                
                
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
  <div id="footer" class="span12"> 2023 &copy;  WEB-BASED TIMEKEEPING AND PAYROLL SYSTEM USING FINGERPRINT BIOMETRICS</div>
</div>
<script src="../js/maruti.dashboard.js"></script> 

</body>
</html>
