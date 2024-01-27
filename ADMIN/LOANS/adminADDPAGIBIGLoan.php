<!DOCTYPE html>
<html lang="en">
<head>
<title>Add PAG-IBIG Loan</title>
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
if (isset($_SESSION['anewdept'])){
  $anewdeptnotif = $_SESSION['anewdept'];
  ?>
  <script>

  alert("<?php echo $anewdeptnotif;?>");

  </script>
<?php
}

$error = false;
$adminId = $_SESSION['adminId'];
if (isset($_POST['search_btn'])){
  $pagibigidno = $_POST['pagibigidno'];
  $adminId = $_SESSION['adminId'];
  $_SESSION['PAGIBIGIDNO'] = $pagibigidno;
  $pagibigidnoquery = "SELECT emp_id,last_name,first_name,middle_name,PAGIBIG_idno FROM employees where PAGIBIG_idno = '$pagibigidno'";
  $pagibigidnoexecqry = mysqli_query($conn,$pagibigidnoquery);
  $pagibigidnocount = mysqli_num_rows($pagibigidnoexecqry);
  $pagibigidnoarray = mysqli_fetch_array($pagibigidnoexecqry);
  if ($pagibigidnoarray){
   
    $pagibigempid = $pagibigidnoarray['emp_id'];
    $lastname = $pagibigidnoarray['last_name'];
    $firstname = $pagibigidnoarray['first_name'];
    $middlename = $pagibigidnoarray['middle_name'];
    // $empname = "$lastname, $firstname $middlename"; 

  }elseif (!$pagibigidnoarray){
    $error = true;
    $sssidnumbererror = "No Employee has that SSS ID Number.";   
  }

}

if(isset($_POST['submit_btn'])){
 
  $pagibigidno = $_SESSION['PAGIBIGIDNO'];
  $pagibigempid = $_POST['pagibigempid'];
  $lastname = $_POST['last_name'];
  $firstname = $_POST['first_name'];
  $middlename = $_POST['middle_name'];
  $startdate = $_POST['startpicker'];
  $enddate = $_POST['endpicker'];
  $loanamount = $_POST['loanamount'];
  $monthlydeductionamount = $_POST['monthlydeductionamount'];
  $noofpays = $_POST['payduration'];


 

  $empidqry = "SELECT emp_id FROM employees where emp_id = '$pagibigempid'";
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

  if (!$error){

    $newdeptqry = "INSERT INTO LOANpagibig (pagibig_idno,emp_id,emplastname,empfirstname,empmiddlename,loanstart_date,loanend_date,loan_amount, loan_balance, monthly_deduct,no_of_pays, status, admin_id) VALUES ('$pagibigidno','$pagibigempid','$lastname','$firstname','$middlename','$startdate','$enddate','$loanamount', '$loanamount','$monthlydeductionamount','$noofpays', 'On-Going', $adminId)";
    $newdeptqryresult = mysqli_query($conn,$newdeptqry) or die ("FAILED TO ADD NEW PAGIBIG LOAN ".mysqli_error($conn));

    $activityLog = "Added PAGIBIG Loan ($pagibigempid)";
    $adminActivityQuery = "INSERT INTO adminactivity_log (emp_id, activity,log_timestamp) VALUES ('$adminId', '$activityLog', NOW())";
    $adminActivityResult = mysqli_query($conn, $adminActivityQuery);


    $notificationMessage = "Loan has been added for $pagibigempid";
    $insertNotificationQuery = "INSERT INTO empnotifications (admin_id,emp_id, message, type, status) VALUES ('$adminId', '$pagibigempid','$notificationMessage','Loan','unread')";
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
           window.location.href = 'adminPAGIBIGLoans.php'; // Replace 'your_new_page.php' with the actual URL
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
      <a href ="adminSSSLoans.php" class="tip-bottom"><i class ="icon-th"></i> PAG-IBIG Loans</a>
      <a href="#" class="tip-bottom"><i class = "icon-plus"></i>Add PAG-IBIG Loan</a>
    </div>
  </div>

  <div class="container-fluid">
      <div class="row-fluid">
        <div class="row-fluid">
      <span class="span3">
      </span>
    <div class="span6">
      <h3>Add PAG-IBIG Loan</h3>
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
            <h5>Loan Information</h5>
          </div>
          
          <div class="widget-content nopadding">
            <form action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" class="form-horizontal">
              <div class="control-group">
                <label class="control-label">PAG-IBIG ID Number:</label>
                <div class="controls">
                <input type="text" class="span7" placeholder="PAG-IBIG ID Number" name="pagibigidno" style="margin-left:25px" value="<?php echo $pagibigidno ?? '';?>"/>                  
                <button type="submit" class="btn btn-success printbtn" name = "search_btn">Search</button>
                  <!-- <span class ="label label-important"><?php echo $pagibigidnumbererror; ?></span> -->

                </div>
              </div>
               
            </form>

              <form action="<?php $_SERVER['PHP_SELF'];?>" method="POST" class="form-horizontal">
              <div class="control-group">
                <label class="control-label" style="margin-right:60px">Employee ID:</label>
                <div class="controls">
                <input type="text" class="span7" placeholder="Employee ID" name="pagibigempid"  value="<?php echo $pagibigempid ?? ''; ?>" readonly/>
                  <!-- <span class ="label label-important"><?php echo $empiderror; ?></span> -->
                </div>
              </div>

              <div class="control-group">
                <label class="control-label" style="margin-right:60px">Last Name:</label>
                <div class="controls">
                <input type="text" class="span7" placeholder="Name" name="last_name" value = "<?php echo $lastname ?? '';?>" readonly/>
                  <!-- <span class ="label label-important"><?php echo $nameerror; ?></span> -->
                </div>
              </div>
              <div class="control-group">
                <label class="control-label" style="margin-right:60px">First Name:</label>
                <div class="controls">
                <input type="text" class="span7" placeholder="Name" name="first_name" value = "<?php echo $firstname ?? '';?>" readonly/>
                  <!-- <span class ="label label-important"><?php echo $nameerror; ?></span> -->
                </div>
              </div>
              <div class="control-group">
                <label class="control-label" style="margin-right:60px">Middle Name:</label>
                <div class="controls">
                <input type="text" class="span7" placeholder="Name" name="middle_name" value = "<?php echo $middlename ?? '';?>" readonly/>
                  <!-- <span class ="label label-important"><?php echo $nameerror; ?></span> -->
                </div>
              </div>

              <div class="control-group">
                <label class="control-label" style="margin-right:60px">Start Date:</label>
                <div class="controls">
                  <input type="text" class="span3" id="startdatepicker" name ="startpicker" placeholder="Start Date" value="">
                  <!-- <span class ="label label-important"><?php echo $startdateerror; ?></span> -->
                </div>
              </div>

              <div class="control-group">
                <label class="control-label" style="margin-right:60px">End Date:</label>
                <div class="controls">
                  <input type="text" class="span3" id="enddatepicker" name ="endpicker" placeholder="End Date" value="">
                  <!-- <span class ="label label-important"><?php echo $enddateerror; ?></span> -->
                </div>
              </div>

              <div class="control-group">
                <label class="control-label" style="margin-right:60px">Monthly Deduction Amount:</label>
                <div class="controls">
                  <input type="text" class="span4" placeholder="Monthly Deduction Amount" name="monthlydeductionamount"/>
                  <!-- <span class ="label label-important"><?php echo $monthlydeductionamounterror; ?></span> -->
                </div>
              </div>

              <div class="control-group">
                <label class="control-label" style="margin-right:60px">Loan Amount:</label>
                <div class="controls">
                  <input type="text" class="span4" placeholder="Loan Amount" name="loanamount" readonly/>
                  <!-- <span class ="label label-important"><?php echo $loanamounterror; ?></span> -->
                </div>
              </div>



               <div class="control-group">
                <label class="control-label" style="margin-right:60px">Pay Duration:</label>
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
