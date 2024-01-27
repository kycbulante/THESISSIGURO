<?php
include("../DBCONFIG.PHP");
include("../LoginControl.php");
include("../BASICLOGININFO.PHP");

session_start();
$adminId = $_SESSION['adminId'];
$idres = $_GET['id'];
$DELquery = "SELECT * from SHIFT WHERE shift_ID ='$idres'";
$DELselresult = mysqli_query($conn,$DELquery) or die ("Failed to search DB. ".mysql_error());
  $DELcurr = mysqli_fetch_array($DELselresult);
  $DELcount = mysqli_num_rows($DELselresult);

   if($DELcount!=0 && $DELcurr) {

        $currprefixid = $DELcurr['shift_prefix_ID'];
        $currshiftid = $DELcurr['shift_ID'];
        $currshiftsched = $DELcurr['shift_SCHEDULE'];
        $currshiftstart = $DELcurr['shift_START'];
        $currshiftend = $DELcurr['shift_END'];
       

    }
    else {
          $_SESSION['delnotif'] ="Shift information not found.";
          }/*2nd else end*/

if(isset($_POST['delete_btn'])){

 
  $selquery="SELECT shift_ID  FROM SHIFT WHERE shift_ID ='$idres'";
  $selresult = mysqli_query($conn,$selquery);
  $selcount = mysqli_num_rows($selresult);

  $activityLog = "Deleted shift ($currshiftsched)";
  $adminActivityQuery = "INSERT INTO adminactivity_log (emp_id, activity,log_timestamp) VALUES ('$adminId', '$activityLog', NOW())";
  $adminActivityResult = mysqli_query($conn, $adminActivityQuery);
  
  if($selcount!=0){
  $DELquery2 = "DELETE FROM SHIFT WHERE shift_ID = '$idres'";
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
exit(); // Ensure nothing else is sent in the response
}


?>




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



<!--Header-part-->

<?php
INCLUDE ('NAVBAR.php');
?>
<script>
    function confirmDelete() {
        swal({
                title: "Are you sure?",
                text: "Once deleted, you will not be able to recover this shift",
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
                                swal("Shift deleted successfully!", { icon: "success" })
                                    .then(() => {
                                        window.location.href = "adminMasterfileShift.php";
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
                    swal("Shift is safe!", { icon: "info" });
                }
            });
    }
</script>
<body>
<div id="content">

  <div id="content-header">
    <div id="breadcrumb"> <a href="adminDASHBOARD.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a>
    <a href ="adminMasterfileShift.php" class="tip-bottom"><i class ="icon-time"></i> Manage Shifts</a>
      <a href="#" class="tip-bottom"><i class = "icon-trash"></i>Delete Shift</a>
    </div>
  </div>

  <div class="container-fluid">
    <div class = "row-fluid">
      
  <div class="span3">
  </div>
  
    <div class="span6">
      <h3>Shift Delete</h3>
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
            <h5>Profile Delete Confirmation</h5>
          </div>
          <div class="widget-content nopadding">
            <form action="adminDELETEMasterfileShift.php?id=<?php echo $idres;?>" method="POST" class="form-horizontal">
           
              <div class="control-group">
                <label class="control-label">Shift ID: </label>
                <div class="controls">
                  <input type="text" class="span3" value = "<?php echo $currprefixid;?><?php echo $currshiftid;?>" name="DELCONid" readonly/>
                </div>
              </div>

               <div class="control-group">
                <label class="control-label">Shift Schedule: </label>
                <div class="controls">
                  <input type="text" class="span3" value = "<?php echo $currshiftsched;?>" name="DELCONname" readonly/>
                </div>
              </div>

              <div class="control-group">
                <label class="control-label">Shift Start: </label>
                <div class="controls">
                  <input type="text" class="span3" value = "<?php echo $currshiftstart;?>" name="DELCONname" readonly/>
                </div>
              </div>

              <div class="control-group">
                <label class="control-label">Shift End: </label>
                <div class="controls">
                  <input type="text" class="span3" value = "<?php echo $currshiftend;?>" name="DELCONname" readonly/>
                </div>
              </div>

              <div class="form-actions">
                <!-- <button type="submit" class="btn btn-success" name = "delete_btn" style="float:right;" onclick="confirmDelete()">Delete</button> -->
                <button type="button" class="btn btn-success" style="float:right;" onclick="confirmDelete()">Delete</button>

                
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
