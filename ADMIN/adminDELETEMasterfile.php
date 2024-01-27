
<?php
include("../DBCONFIG.PHP");
include("../LoginControl.php");
include("../BASICLOGININFO.PHP");
session_start();
$adminId = $_SESSION['adminId'];
$idres = $_GET['id'];
$DELquery = "SELECT * FROM employees WHERE emp_id ='$idres'";
$DELselresult = mysqli_query($conn, $DELquery) or die("Failed to search DB. " . mysql_error());
$DELcurr = mysqli_fetch_array($DELselresult);
$DELcount = mysqli_num_rows($DELselresult);

if ($DELcount != 0 && $DELcurr) {
    $currprefixid = $DELcurr['prefix_ID'];
    $currempid = $DELcurr['emp_id'];
    $currlastname = $DELcurr['last_name'];
    $currfirstname = $DELcurr['first_name'];
    $currmiddlename = $DELcurr['middle_name'];
    $currfingerprintid = $DELcurr['fingerprint_id'];
} else {
    $updateselecterror = "Employee information not found.";
}

if (isset($_POST['delete_btn'])) {
  $selquery = "SELECT emp_id FROM employees WHERE emp_id ='$idres'";
  $selresult = mysqli_query($conn, $selquery);
  $selcount = mysqli_num_rows($selresult);

  if ($selcount != 0) {
      $DELquery2 = "DELETE FROM employees WHERE emp_id = '$idres'";
      $delval = mysqli_query($conn, $DELquery2);

      $DELquery3 = "DELETE FROM PAYROLLINFO WHERE emp_id = '$idres'";
      $delval2 = mysqli_query($conn, $DELquery3);

      $activityLog = "Deleted employee profile (ID: $idres)";
      $adminActivityQuery = "INSERT INTO adminactivity_log (emp_id, activity,log_timestamp) VALUES ('$adminId', '$activityLog', NOW())";
      $adminActivityResult = mysqli_query($conn, $adminActivityQuery);

      if ($delval && $delval2) {
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
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker@3.1.0/daterangepicker.css" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker@3.1.0/daterangepicker.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

</head>
<body>

<?php
INCLUDE ('NAVBAR.php');
?>
<script>
    function confirmDelete() {
        swal({
                title: "Are you sure?",
                text: "Once deleted, you will not be able to recover this profile!",
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
                                swal("Profile deleted successfully!", { icon: "success" })
                                    .then(() => {
                                        window.location.href = "adminMasterfile.php";
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
                    swal("Profile is safe!", { icon: "info" });
                }
            });
    }
</script>



<div id="content">

  <div id="content-header">
    <div id="breadcrumb"> <a href="adminDASHBOARD.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a>
    <a href ="adminMasterfile.php" class="tip-bottom"><i class ="icon-file"></i> Employee Masterlist</a>
      <a href="#" class="tip-bottom"><i class = "icon-trash"></i>Delete Profile</a>
    </div>
  </div>

  <div class="container-fluid">
    <div class = "row-fluid">
      
  <div class="span3">
  </div>
  
    <div class="span6">
      <h3>Profile Delete</h3>
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
            <h5>Profile Delete Confirmation</h5>
          </div>
          <div class="widget-content nopadding">
          <form action="" method="POST" class="form-horizontal">
           
              <div class="control-group">
                <label class="control-label">Employee ID: </label>
                <div class="controls">
                  <input type="text" class="span3" value = "<?php echo $currprefixid;?><?php echo $currempid;?>" name="DELCONid" readonly/>
                </div>
              </div>

               <div class="control-group">
                <label class="control-label">Name: </label>
                <div class="controls">
                  <input type="text" class="span11" value = "<?php echo $currlastname;?>, <?php echo $currfirstname;?> <?php echo $currmiddlename;?>" name="DELCONname" readonly/>
                </div>
              </div>

              <!-- <div class="control-group">
                <label class="control-label">RFID: </label>
                <div class="controls">
                  <input type="text" class="span4" value = "<?php echo $currcardnumber;?>" name="DELCONcard" readonly/>
                </div>
              </div> -->

              <div class="form-actions">
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
