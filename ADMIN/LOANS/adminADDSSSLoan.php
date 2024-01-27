<!DOCTYPE html>
<html lang="en">
<head>
<title>Add GSIS Loan</title>
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
$error = false;

if (isset($_POST['search_btn'])){
  $gsisidno = $_POST['gsisidno'];
  $_SESSION['GSISIDNO'] = $gsisidno;
   $gsisidnoquery = "SELECT emp_id,last_name,first_name,middle_name,GSIS_idno FROM employees where GSIS_idno = '$gsisidno'";
   echo $gsisidnoquery;
  $gsisidnoexecqry = mysqli_query($conn,$gsisidnoquery);
  $gsisidnocount = mysqli_num_rows($gsisidnoexecqry);
  $gsisidnoarray = mysqli_fetch_array($gsisidnoexecqry);
  if ($gsisidnoarray){
   
    $gsisempid = $gsisidnoarray['emp_id'];
    $lastname = $gsisidnoarray['last_name'];
    $firstname = $gsisidnoarray['first_name'];
    $middlename = $gsisidnoarray['middle_name'];
    // $empname = "$lastname, $firstname $middlename"; 

  }elseif (!$gsisidnoarray){
    $error = true;
    $gsisidnumbererror = "No Employee has that SSS ID Number.";   
  }

}

if(isset($_POST['submit_btn'])){
 
  $gsisidno = $_SESSION['GSISIDNO'];
  $gsisempid = $_POST['gsisempid'];
  // $empname = $_POST['empname'];
  $lastname = $_POST['last_name'];
  $firstname = $_POST['first_name'];
  $middlename = $_POST['middle_name'];
  $startdate = $_POST['startpicker'];
  $enddate = $_POST['endpicker'];
  $loanamount = $_POST['loanamount'];
  $monthlydeductionamount = $_POST['monthlydeductionamount'];
  $noofpays = $_POST['payduration'];


 

  $empidqry = "SELECT emp_id FROM employees where emp_id = '$gsisempid'";
  $empidexecqry = mysqli_query($conn,$empidqry) or die ("FAILED TO CHECK EMP ID ".mysqli_error($conn));
  $empidcount = mysqli_num_rows($empidexecqry);

  if($empidcount!=1){
    $error = true;
    $empiderror = "Employee ID does not exist.";
  }

if (empty($startdate)){

  $error = true;
  $startdateerror = "Please indicate loan start date.";

}

if (empty($enddate)){
  $error = true;
  $enddateerror = "Please indicate loan end date.";

}

if (empty($loanamount)){
  $error = true;
  $loanamounterror = "Please indicate the loan amount.";

}

if(empty($monthlydeductionamount)){
  $error = true;
  $monthlydeductionamounterror = "Please enter the amount to be deducted every month.";

}

if(empty($noofpays)){
  $error = true;
  $paydurationerror = "Please enter number of payment months.";
}

  if (!$error){

    $newdeptqry = "INSERT INTO LOANgsis (gsis_idno,emp_id,empfirstname,emplastname, empmiddlename, start_date,end_date,loan_amount, loan_balance, monthly_deduct,no_of_pays,status, admin_id) VALUES ('$gsisidno','$gsisempid','$lastname','$firstname','$middlename','$startdate','$enddate','$loanamount','$loanamount','$monthlydeductionamount','$noofpays','On-Going', $adminId)";
    $newdeptqryresult = mysqli_query($conn,$newdeptqry) or die ("FAILED TO CREATE NEW DEPARTMENT ".mysql_error());

    $activityLog = "Added GSIS Loan ($gsisempid)";
    $adminActivityQuery = "INSERT INTO adminactivity_log (emp_id, activity,log_timestamp) VALUES ('$adminId', '$activityLog', NOW())";
    $adminActivityResult = mysqli_query($conn, $adminActivityQuery);


    $notificationMessage = "Loan has been added for $gsisempid";
    $insertNotificationQuery = "INSERT INTO empnotifications (admin_id,emp_id message, type, status) VALUES ('$adminId', '$gsisempid','$notificationMessage','Loan','unread')";
    mysqli_query($conn, $insertNotificationQuery);

    if($newdeptqryresult){

      ?>
   
   <script>
   document.addEventListener('DOMContentLoaded', function() {
       swal({
        //  title: "Good job!",
         text: "GSIS Loan inserted successfully",
         icon: "success",
         button: "OK",
        }).then(function() {
           window.location.href = 'adminSSSLoans.php'; // Replace 'your_new_page.php' with the actual URL
       });
   });
</script>
    <?php
 
}
  } else {
    $errType = "danger";
    // $_SESSION['addprofilenotif'] = "Something went wrong. Make sure you accomplish all the required fields.";
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
  }

} 

?>





<script type ="text/javascript">
  $( function() {
      $( "#startdatepicker" ).datepicker({ 
        changeYear: true,
        yearRange: "1940:2040",
        dateFormat: 'yy-mm-dd'});
      } );

  $( function() {
      $( "#enddatepicker" ).datepicker({ 
        changeYear: true,
        yearRange: "1940:2040",
        dateFormat: 'yy-mm-dd'});
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
      <a href ="adminSSSLoans.php" class="tip-bottom"><i class ="icon-th"></i> GSIS Loans</a>
      <a href="#" class="tip-bottom"><i class = "icon-plus"></i>Add GSIS Loan</a>
    </div>
  </div>

  <div class="container-fluid">
    <div class = "row-fluid">
      <span class="span3">
      </span>
    <div class="span6">
      <h3>Add GSIS Loan</h3>
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
            <h5>Loan Information</h5>
          </div>

          <div class="widget-content nopadding">
            <form action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" class="form-horizontal">
              <div class="control-group">
                <label class="control-label">GSIS ID Number:</label>
                <div class="controls">
                <!-- <input type="text" class="span7" placeholder="SSS ID Number" name="sssidno" value="<?php echo $sssidno;?>"/> -->
                <input type="text" class="span7" placeholder="GSIS ID Number" name="gsisidno" value="<?php echo $gsisidno ?? ''; ?>"/>

                  <button type="submit" class="btn btn-success printbtn" name = "search_btn">Search</button>
                  <!-- <span class ="label label-important"><?php echo $sssidnumbererror; ?></span> -->

                </div>
              </div>
               
            </form>

              <form action="<?php $_SERVER['PHP_SELF'];?>" method="POST" class="form-horizontal">
              <div class="control-group">
                <label class="control-label">Employee ID:</label>
                <div class="controls">
                <input type="text" class="span7" placeholder="Employee ID" name="gsisempid" value="<?php echo $gsisempid ?? ''; ?>" readonly/>
                  <!-- <span class ="label label-important"><?php echo $empiderror; ?></span> -->
                </div>
              </div>

              <div class="control-group">
                <label class="control-label">Last Name:</label>
                <div class="controls">
                <input type="text" class="span7" placeholder="Name" name="last_name" value = "<?php echo $lastname ?? '';?>" readonly/>
                  <!-- <span class ="label label-important"><?php echo $nameerror; ?></span> -->
                </div>
              </div>
              <div class="control-group">
                <label class="control-label">First Name:</label>
                <div class="controls">
                <input type="text" class="span7" placeholder="Name" name="first_name" value = "<?php echo $firstname ?? '';?>" readonly/>
                  <!-- <span class ="label label-important"><?php echo $nameerror; ?></span> -->
                </div>
              </div>
              <div class="control-group">
                <label class="control-label">Middle Name:</label>
                <div class="controls">
                <input type="text" class="span7" placeholder="Name" name="middle_name" value = "<?php echo $middlename ?? '';?>" readonly/>
                  <!-- <span class ="label label-important"><?php echo $nameerror; ?></span> -->
                </div>
              </div>

              <div class="control-group">
                <label class="control-label">Start Date:</label>
                <div class="controls">
                <input type="text" class="span3" id="startdatepicker" name ="startpicker" placeholder="Start Date" value="">
                  <!-- <span class ="label label-important"><?php echo $startdateerror; ?></span> -->
                </div>
              </div>

              <div class="control-group">
                <label class="control-label">End Date:</label>
                <div class="controls">
                  <input type="text" class="span3" id="enddatepicker" name ="endpicker" placeholder="End Date" value="">
                  <!-- <span class ="label label-important"><?php echo $enddateerror; ?></span> -->
                </div>
              </div>


              <div class="control-group">
                <label class="control-label">Monthly Deduction Amount:</label>
                <div class="controls">
                  <input type="text" class="span4" placeholder="Monthly Deduction Amount" name="monthlydeductionamount"/>
                  <!-- <span class ="label label-important"><?php echo $monthlydeductionamounterror; ?></span> -->
                </div>
              </div>
              <div class="control-group">
                <label class="control-label">Loan Amount:</label>
                <div class="controls">
                  <input type="text" class="span4" placeholder="Loan Amount" name="loanamount" readonly/>
                  <!-- <span class ="label label-important"><?php echo $loanamounterror; ?></span> -->
                </div>
              </div>

              <div class="control-group">
                <label class="control-label">Pay Duration:</label>
                <div class="controls">
                  <input type="text" class="span1" placeholder="" name="payduration" readonly/>
                  <span> months</span>
                  <!-- <span class ="label label-important"><?php echo $paydurationerror; ?></span> -->
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
unset($_SESSION['anewdept']);
?>

<script src="../js/maruti.dashboard.js"></script> 
<script>
  $(document).ready(function() {
    // Assuming date format is yyyy mm dd
    $("#enddatepicker, #startdatepicker").on("change", function() {
      var startDate = $("#startdatepicker").val();
      var endDate = $("#enddatepicker").val();

      if (startDate && endDate) {
        var start = new Date(startDate);
        var end = new Date(endDate);

        var monthDiff = (end.getFullYear() - start.getFullYear()) * 12 + end.getMonth() - start.getMonth();

        $("input[name='payduration']").val(monthDiff);
      }
    });
  });
</script>
<script>
  $(document).ready(function() {
    // Assuming date format is yyyy mm dd
    $("#enddatepicker, #startdatepicker, input[name='monthlydeductionamount'], input[name='payduration']").on("change", function() {
      var startDate = $("#startdatepicker").val();
      var endDate = $("#enddatepicker").val();
      var monthlyDeduction = parseFloat($("input[name='monthlydeductionamount']").val());
      var payDuration = parseInt($("input[name='payduration']").val());

      if (startDate && endDate && !isNaN(monthlyDeduction) && !isNaN(payDuration)) {
        var start = new Date(startDate);
        var end = new Date(endDate);

        var monthDiff = (end.getFullYear() - start.getFullYear()) * 12 + end.getMonth() - start.getMonth();

        var loanAmount = monthlyDeduction * monthDiff;

        $("input[name='loanamount']").val(loanAmount.toFixed(2));
      }
    });
  });
</script>



</body>
</html>
