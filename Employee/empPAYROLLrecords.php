<?php
include("../DBCONFIG.PHP");
include("../LoginControl.php");
include("../BASICLOGININFO.PHP");

session_start();

if(isset($_SESSION['masterfilenotif'])){

$mfnotif = $_SESSION['masterfilenotif'];
?>  
<script>
alert("<?php echo $mfnotif;?>");
</script>
<?php
}



$currentempid = $_SESSION['empID'];

$userIdpage  = $_SESSION['empID'];

$pageViewed = basename($_SERVER['PHP_SELF']);
$pageInfo = pathinfo($pageViewed);

// Get the filename without extension
$pageViewed1 = $pageInfo['filename'];



// Log the page view
logPageView($conn, $userIdpage, $pageViewed1);

// //total number of rows
$pagecountqry = "SELECT COUNT(emp_id) from PAY_PER_PERIOD WHERE emp_id = '$currentempid'";
$pagecountres = mysqli_query($conn,$pagecountqry) or die ("Failed to count pages ".mysqli_error($conn));
$pagecounttotal = mysqli_fetch_row($pagecountres);
$rows = $pagecounttotal[0];


//number of results per page
$page_rows = 20;
//page number of last page
$lastpage = ceil($rows/$page_rows);
//This makes sure $lastpage cant be less than 1
if ($lastpage < 1){
  $lastpage=1;
}

$pagenum = 1;
//get pagenum from URL
if (isset($_GET['pn'])){
  $pagenum = preg_replace('#[^0-9]#', '', $_GET['pn']);
}
// makes sure page number isnt below 1 or more than $lastpage
if ($pagenum < 1){
  $pagenum = 1;
}else if ($pagenum > $lastpage){
  $pagenum = $lastpage;
}
//This set range of rows to query for $pagenum
$limit = "LIMIT "  .($pagenum-1)* $page_rows . ',' .$page_rows;

//What page and number of pages
$pageline1 = "Page <b>$pagenum</b> of <b>$lastpage</b>";
//pagectrls
$paginationCtrls = '';
//If more than 1 page
if ($lastpage !=1){
  /*Check if on page 1. If yes, previous link not needed. If not, we generate links to the first page and to the previos page. */
  if ($pagenum>1){
      $previous = $pagenum-1;
      $paginationCtrls .= '<li><a href="'.$_SERVER['PHP_SELF'].'?id='.$idres.'&pn='.$previous.'">Prev</a></li>';
      //number links left
      for ($i = $pagenum-4; $i < $pagenum; $i++){

        if($i > 0){
          $paginationCtrls .= '<li><a href="'.$_SERVER['PHP_SELF'].'?id='.$idres.'&pn='.$i.'">'.$i.'</a></li>';
        }

      }
  }

  //target page
    $paginationCtrls .='<li class = "active"><a href="'.$_SERVER['PHP_SELF'].'"</a>'.$pagenum.'</li>';
  //render clickable number links appear on right target page
    for ($i = $pagenum+1; $i <= $lastpage; $i++){
      $paginationCtrls .='<li><a href="'.$_SERVER['PHP_SELF'].'?id='.$idres.'&pn='.$i.'">'.$i.'</a></li>';
      if ($i >= $pagenum+4){
        break;
      }
    }

    if ($pagenum != $lastpage) {
        $next = $pagenum + 1;
        $paginationCtrls .= '<li><a href = "'.$_SERVER['PHP_SELF'].'?id='.$idres.'&pn='.$next.'">Next</a></li> ';
    }
}


if (isset($_POST['pperiod_btn'])){

   $payperiod = $_POST['payperiod'];
   
   $searchquery = "SELECT * FROM employees, PAY_PER_PERIOD WHERE employees.emp_id = PAY_PER_PERIOD.emp_id AND PAY_PER_PERIOD.emp_id = '$currentempid' AND PAY_PER_PERIOD.pperiod_range = '$payperiod' ORDER BY pperiod_range DESC $limit";
   $search_result = filterTable($searchquery);

} else  {
  $searchquery = "SELECT * from employees, PAY_PER_PERIOD WHERE employees.emp_id = PAY_PER_PERIOD.emp_id AND PAY_PER_PERIOD.emp_id = '$currentempid' ORDER BY PAY_PER_PERIOD.pperiod_range DESC $limit";  
  $search_result = filterTable($searchquery);
  }


?>


<!DOCTYPE html>
<html lang="en">
<head>
<title>Employee Records</title>
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
<script type ="text/javascript">
  $( function() {
      $( "#datepickerfrom" ).datepicker({ dateFormat: 'yy-mm-dd'});
      } );
  $( function() {
      $( "#datepickerto" ).datepicker({ dateFormat: 'yy-mm-dd'});
      } );
  
</script>

<body>

<!--Header-part-->

<?php
INCLUDE ('empNAVBAR.php');
?>


<div id="content">

  <div id="content-header">
    <div id="breadcrumb"> <a href="index.html" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a>
      <a href ="adminMasterfile.php" class="tip-bottom"><i class ="icon-calendar"></i> Attendance Records</a></div>
  </div>

  <div class="container-fluid">
    <div class ="row-fluid">
      <div class = "span10">
        <h3>Employee Records</h3>        
      </div>
    </div>
    
    <style>
      .widget-box {
  border-radius: 10px; /* You can adjust the value to control the amount of rounding */
  border: 1px solid #ccc; /* Optional: You can add a border for further styling */
  padding: 15px; /* Optional: Add padding to the box for better appearance */
}
@media (max-width: 768px) {
  .widget-box {
    margin: auto;
    margin-top: 70px; /* This will center the widget-box */
  }

  .widget-title li {
    /* Adjust the styles for list items inside widget-title at smaller screens */
    display: block;
    margin-bottom: 10px;
  }

  .active {
    /* Adjust the styles for the active class when the screen width is 768px or less */
    
  }
}  
    </style>
    <div class ="row-fluid">
     <div class="span12">
        <div class="widget-box">
          <div class="widget-title">
            <ul class="nav nav-tabs" id="myTab">
              <li><a href="empDASHBOARD.php"><i class="icon-user"></i> Profile</a></li>
              <li><a href="empAPPLYOvertime.php"><i class="icon-time"></i> Overtime</a></li>
              <li><a href="empAPPLYLeave.php"><i class="icon-calendar"></i> Leave</a></li>
              <li class="active"><a href="empATTENDANCErecords.php"><i class="icon-th"></i> My Records</a></li>
              <li><a href="empActivitylogs.php"><i class="icon-time"></i> Activity Logs</a></li>
              <li class=""><a href="empActivitylogs.php"><i class="icon-file"></i> Loans</a></li>
              
            </ul>
          </div>
    
        <div class="widget-box">
          <div class="widget-title">
            <ul class="nav nav-tabs" id="myTab">
              <li><a href="empATTENDANCErecords.php"><i class="icon-calendar"></i> Attendance Records</a></li>
              <li class="active"><a href="<?php $_SERVER['PHP_SELF'];?>"><i class="icon-th"></i> Payroll Records</a></li>
            </ul>
          </div>
          <div class="widget-content tab-content">
          <div id="tab1" class="tab-pane fade in active"><!--EMPLOYEE TAB--><!--EMPLOYEE TAB--><!--EMPLOYEE TAB--><!--EMPLOYEE TAB--><!--EMPLOYEE TAB--><!--EMPLOYEE TAB--><!--EMPLOYEE TAB--><!--EMPLOYEE TAB-->
               
      <div class = "row-fluid">
          <div class = "span5">
              <div class ="control-group">
                <form action="<?php $_SERVER['PHP_SELF'];?>" method ="post">
                <?php
                $payperiodsquery = "SELECT * FROM payperiods";
                $payperiodsexecquery = mysqli_query($conn, $payperiodsquery) or die ("FAILED TO EXECUTE PAYPERIOD QUERY ".mysqli_error($conn));
                ?>
                    <label class="control-label">Select Payroll Period: </label>
                      <div class="controls">
                        <select name ="payperiod">
                      
                          <option></option>
                          <?php  while($payperiodchoice = mysqli_fetch_array($payperiodsexecquery)):;?>
                          <option><?php echo $payperiodchoice['pperiod_range'];?></option>
                          <?php endwhile;?>
                          
                        </select>
                        <button type="submit" class="btn btn-success printbtn" name = "pperiod_btn">Go</button>
                      </div>
                  </form>
                </div>
            </div>

            <div class = "span2">


                  
            </div>

                
      
            <div class = "span5">
                  <a href ="empPAYROLLrecords.php" class = "btn btn-success" style = "float:right; margin-left: 4px;"><span class="icon"><i class="icon-refresh"></i></span> Refresh</a>
                  <!-- <small><?php echo $attrecordview; ?></small> -->
                </div>
              
            </div>


      </div>
            

                

               <table class="table table-bordered data-table">
               <thead>
                <tr>
                  
                  <th>Last Name</th>
                  <th>First Name</th>
                  <th>Middle Name</th>
                  <th>Pay Period</th>
                  <th>Basic Pay</th>
                  <th>OT Pay</th>
                  <th>Reg. Holiday</th>
                  <th>Special Non-Working Holiday</th>
                  <th>Gross Salary</th>
                  <th>Philhealth</th>
                  <th>GSIS</th>
                  <th>PAG-IBIG/HDMF</th>
                  <th>GSIS Loan</th>
                  <th>PAG-IBIG Loan</th>
                  <th>Total Deductions</th>
                  <th>Net Pay</th>
                                  
                  
                </tr>
              </thead>
              <tbody> 

               <?php

              

               function filterTable($searchquery)
               {

                    $conn1 = mysqli_connect("localhost:3307","root","","masterdb");
                    $filter_Result = mysqli_query($conn1,$searchquery) or die ("failed to query masterfile ".mysqli_error($conn1));
                    return $filter_Result;
               }
               while($row1 = mysqli_fetch_array($search_result)):;
               $basepay = $row1['reg_pay'];
               $otpay = $row1['ot_pay'];
               $shdaypay = $row1['shday_pay'];
               $hdaypay  =$row1['hday_pay'];

               $grosspay = ($basepay + $otpay + $shdaypay + $hdaypay);
               $gpay = number_format((float)$grosspay,2,'.','');
               $philhealth = $row1['philhealth_deduct'];
               $sss = $row1['sss_deduct'];
               $pagibig = $row1['pagibig_deduct'];
               $sssloan = $row1['sssloan_deduct'];
               $pagibigloan = $row1['pagibigloan_deduct'];
               $withholdingtax = $row1['tax_deduct'];
               $totaldeduct = $row1['total_deduct'];

               $netpay = ($grosspay - $totaldeduct);
               $npay = number_format((float)$netpay,2,'.',''); 

               
              
                      
               ?>
                  <tr class="gradeX">
                  <td><?php echo $row1['last_name'];?></td>
                  <td><?php echo $row1['first_name'];?></td>
                  <td><?php echo $row1['middle_name']; ?></td>
                  <td><?php echo $row1['pperiod_range'];?></td>
                  <td><?php echo $basepay;?></td>
                  <td><?php echo $otpay;?></td>
                  <td><?php echo $hdaypay;?></td>
                  <td><?php echo $shdaypay;?></td>
                  <td><?php echo $gpay;?></td>
                  <td><?php echo $philhealth; ?></td>
                  <td><?php echo $sss; ?></td>
                  <td><?php echo $pagibig; ?></td>
                  <td><?php echo $sssloan; ?></td>
                  <td><?php echo $pagibigloan; ?></td>
                  <td><?php echo $totaldeduct; ?></td>
                  <td><center><b>&#8369; <?php echo $npay;?></td>

                  
                </tr>
                <?php endwhile;?>
              </tbody>
            </table>
               
          </div><!--EMPLOYEE TAB--><!--EMPLOYEE TAB--><!--EMPLOYEE TAB--><!--EMPLOYEE TAB--><!--EMPLOYEE TAB--><!--EMPLOYEE TAB--><!--EMPLOYEE TAB--><!--EMPLOYEE TAB-->
          </div>
          
        </div>
      </div>
    </div>
  </div>
</div>
<?php
unset($_SESSION['masterfilenotif']);
?>



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

