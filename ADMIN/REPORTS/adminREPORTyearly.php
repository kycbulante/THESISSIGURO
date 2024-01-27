<?php
include("../../DBCONFIG.PHP");
include("../../LoginControl.php");
include("BASICLOGININFO.PHP");

session_start();
if(isset($_SESSION['masterfilenotif'])){

$mfnotif = $_SESSION['masterfilenotif'];
?>  
<script>
alert("<?php echo $mfnotif;?>");
</script>
<?php
}

if (isset($_POST['print_btn'])){
  $_SESSION['reportyear'] = $_POST['reportyear'];
  $payperiod = $_POST['payperiod'];
  
  header("Location:adminPRINTyearlyreport.php");

} 



?>



<!DOCTYPE html>
<html lang="en">
<head>
<title>Yearly Reports</title>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<link rel="stylesheet" href="../../css/bootstrap.min.css" />
<link rel="stylesheet" href="../../css/bootstrap-responsive.min.css" />
<link rel="stylesheet" href="../../css/fullcalendar.css" />
<link rel="stylesheet" href="../../css/maruti-style.css" />
<link rel="stylesheet" href="../../css/maruti-media.css" class="skin-color" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker@3.1.0/daterangepicker.css" />
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker@3.1.0/daterangepicker.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</head>

<body>

<!--Header-part-->

<?php
INCLUDE ('NAVBAR.php');
?>


<div id="content">

  <div id="content-header">
    <div id="breadcrumb"> <a href="adminDASHBOARD.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a>
      <a href ="<?php $_SERVER['PHP_SELF']; ?>" class="tip-bottom"><i class ="icon-th"></i> Yearly Reports</a></div>
  </div>

  <span class="span6">
        <h3>Yearly Reports</h3>
      </span>

  <div class="container-fluid">
    <div class = "row-fluid">
    <div class ="row-fluid">
     <div class="span12">
        <div class="widget-box">
          <div class="widget-title">
            <ul class="nav nav-tabs" id="myTab">
              <li class=""><a href="../adminTIMESHEET.php"><i class="icon-time"></i> Timesheets</a></li>
              <li class=""><a href="../adminDTR.php"><i class="icon-time"></i> Daily Time Record</a></li>
              <li class=""><a href="../adminPAYROLLRegister.php"><i class="icon-time"></i> Payroll Register</a></li>
              <li class=""><a href="../adminPAYROLLPrintPayslip.php"><i class="icon-th"></i> Print Payslips</a></li>
              <li class=""><a href="adminGOVTReports.php"><i class="icon-th"></i> Government Contribution Reports</a></li>
              <!-- <li class=""><a href="adminREPORT13thmonth.php"><i class="icon-th"></i> 13th Month Reports</a></li> -->
              <li class="active"><a href="adminREPORTyearly.php"><i class="icon-th"></i> Yearly Reports</a></li>
              
            </ul>
          </div>
    <div class ="row-fluid">
      <div class="span4">
      </div>
      <div class = "span5">
        <!-- <h3>Yearly Reports</h3>         -->
      </div>
    </div>

    <div class = "row-fluid"><!--ROW-->

      <div class="span4">
      </div>
      <div class="span5">
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
            <h5>Print Yearly Report</h5>
          </div>

          <div class="widget-content nopadding">

            <form action="<?php $_SERVER['PHP_SELF'] ?>" method="POST" class="form-horizontal" target="_blank">
            
      <?php
      $payperiodsquery = "SELECT * FROM PAYROLLYEARS";
      $payperiodsexecquery = mysqli_query($conn, $payperiodsquery) or die ("FAILED TO EXECUTE PAYPERIOD QUERY ".mysqli_error($conn));
      ?>
            <div class = "control-group">
                
                 <label class="control-label">Select Report Year: </label>
                      <div class="controls">
                        <select name ="reportyear">
                      
                          <option></option>
                          <?php  while($payperiodchoice = mysqli_fetch_array($payperiodsexecquery)):;?>
                          <option><?php echo $payperiodchoice['pay_year'];?></option>
                          <?php endwhile;?>
                        </select>
                        <button type="submit" class="btn btn-success printbtn" name = "print_btn">Submit</button>
                      </div>
                    
            </div>
            </form>
          </div>
        </div>
      </div>
    </div><!-- ROW -->
    

</div><!--CONTAINER-->
     
<?php
unset($_SESSION['masterfilenotif']);
?>

</div>

<div class="row-fluid">
  <!-- <div id="footer" class="span12"> 2023 &copy; WEB-BASED TIMEKEEPING AND PAYROLL SYSTEM USING FINGERPRINT BIOMETRICS</div> -->
</div>

<script src="../js/maruti.dashboard.js"></script> 
<script src="../js/excanvas.min.js"></script> 
<script src="../js/jquery.min.js"></script> 
<script src="../js/jquery.ui.custom.js"></script> 
<script src="../js/bootstrap.min.js"></script> 
<script src="../js/jquery.flot.min.js"></script> 
<script src="../js/jquery.flot.resize.min.js"></script> 
<script src="../js/jquery.peity.min.js"></script> 
<script src="../js/fullcalendar.min.js"></script> 
<script src="../js/maruti.js"></script> 
</body>
</html>

