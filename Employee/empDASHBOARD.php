<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<?php
include("../DBCONFIG.PHP");
include("../LoginControl.php");
include("../BASICLOGININFO.PHP");

session_start();


$uname = $_SESSION['uname'];
$empid = $_SESSION['empId'];


$getinfoqry = "SELECT * from employees WHERE user_name = '$uname'";
$getinfoexecqry = mysqli_query($conn,$getinfoqry) or die ("FAILED TO GET INFORMATION ".mysqli_error($conn));
$getinfoarray = mysqli_fetch_array($getinfoexecqry);
$getinforows = mysqli_num_rows($getinfoexecqry);
if ($getinfoarray && $getinforows !=0){

 $currprefixid = $getinfoarray['prefix_ID'];
 $currempid = $getinfoarray['emp_id'];
        //$currcardnumber = $getinfoarray['card_number'];
        $currfingerprintid = $getinfoarray['fingerprint_id'];
        $currusername = $getinfoarray['user_name'];
        $currlastname = $getinfoarray['last_name'];
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


if (isset($_POST['pperiod_btn'])){

  $payperiod = $_POST['payperiod'];
  $_SESSION['payperiods'] = $_POST['payperiod'];
  $searchquery = "SELECT * FROM employees, PAY_PER_PERIOD WHERE employees.emp_id = PAY_PER_PERIOD.emp_id AND PAY_PER_PERIOD.emp_id = '$empid' AND PAY_PER_PERIOD.pperiod_range = '$payperiod' ORDER BY pperiod_range";
  $search_result = filterTable($searchquery);

} else  {
 $searchquery = "SELECT * from employees, PAY_PER_PERIOD WHERE employees.emp_id = PAY_PER_PERIOD.emp_id AND PAY_PER_PERIOD.emp_id = '$empid' ORDER BY PAY_PER_PERIOD.pperiod_range ";  
 $search_result = filterTable($searchquery);
 $_SESSION['payperiods'] = 'noset';
 }


?>






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
<!-- Chartist.js -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/chartist@0.11.4/dist/chartist.min.css">
<script src="https://cdn.jsdelivr.net/npm/chartist@0.11.4/dist/chartist.min.js"></script>

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
<style>

.userinfo {
  margin-left:40px;
  
}

.uinfotab2 {
  display:block;
  margin-top:10px;

  
}

.control-group{
  float:left;
  display: block;
}



</style>
</head>

<body>

<!--Header-part-->

<?php
INCLUDE ('empNAVBAR.php');
?><div id="content">

  <div id="content-header">
    <div id="breadcrumb"> <a href="empDASHBOARD.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a>
      <a href="empDASHBOARD.php" title="Employee Info" class="tip-bottom"><i class="icon-user"></i> My Profile</a>
    </div>
  </div>

  <div class="container-fluid">
    <div class = "row-fluid">
      <span class ="span3">
      </span>
      <span class="span6">
      <h3 style="text-align: center;">Employee Information</h3>

      </span>
    </div>
    <div class ="row-fluid">
     <div class="span12">
        <div class="widget-box">
          <div class="widget-title">
            
            <ul class="nav nav-tabs" id="myTab">
              
              <li class="active"><a href="empDASHBOARD.php"><i class="icon-user"></i> Profile</a></li>
              <li><a href="empAPPLYOvertime.php"><i class="icon-time"></i> Overtime</a></li>
              <li><a href="empAPPLYLeave.php"><i class="icon-calendar"></i> Leave</a></li>
              <li><a href="empATTENDANCErecords.php"><i class="icon-th"></i> My Records</a></li>
              <li><a href="empActivitylogs.php"><i class="icon-time"></i> Activity Logs</a></li>
              <li class=""><a href="empLoans.php"><i class="icon-file"></i> Loans</a></li>
              

              
            </ul>
          </div>
          <div>
            
        
          </div>
          
          <div class = "span3">
          <div class="card">
          <div class="card__img">
    <svg xmlns="http://www.w3.org/2000/svg" width="100%">
        <rect fill="#87CEEB" width="540" height="450"></rect>
        <defs>
            <linearGradient id="a" gradientUnits="userSpaceOnUse" x1="0" x2="0" y1="0" y2="100%" gradientTransform="rotate(222,648,379)">
                <stop offset="0" stop-color="#87CEEB"></stop>
                <stop offset="1" stop-color="#00BFFF"></stop>
            </linearGradient>
            <pattern patternUnits="userSpaceOnUse" id="b" width="300" height="250" x="0" y="0" viewBox="0 0 1080 900">
                <g fill-opacity="0.5">
                    <polygon fill="#87CEEB" points="90 150 0 300 180 300"></polygon>
                    <polygon points="90 150 180 0 0 0"></polygon>
                    <polygon fill="#ADD8E6" points="270 150 360 0 180 0"></polygon>
                    <polygon fill="#B0E0E6" points="450 150 360 300 540 300"></polygon>
                    <polygon fill="#87CEEB" points="450 150 540 0 360 0"></polygon>
                    <polygon points="630 150 540 300 720 300"></polygon>
                    <polygon fill="#B0E0E6" points="630 150 720 0 540 0"></polygon>
                    <polygon fill="#87CEEB" points="810 150 720 300 900 300"></polygon>
                    <polygon fill="#FFFFFF" points="810 150 900 0 720 0"></polygon>
                    <polygon fill="#B0E0E6" points="990 150 900 300 1080 300"></polygon>
                    <polygon fill="#87CEEB" points="990 150 1080 0 900 0"></polygon>
                    <polygon fill="#B0E0E6" points="90 450 0 600 180 600"></polygon>
                    <polygon points="90 450 180 300 0 300"></polygon>
                    <polygon fill="#666" points="270 450 180 600 360 600"></polygon>
                    <polygon fill="#ADD8E6" points="270 450 360 300 180 300"></polygon>
                    <polygon fill="#B0E0E6" points="450 450 360 600 540 600"></polygon>
                    <polygon fill="#999" points="450 450 540 300 360 300"></polygon>
                    <polygon fill="#999" points="630 450 540 600 720 600"></polygon>
                    <polygon fill="#FFFFFF" points="630 450 720 300 540 300"></polygon>
                    <polygon points="810 450 720 600 900 600"></polygon>
                    <polygon fill="#B0E0E6" points="810 450 900 300 720 300"></polygon>
                    <polygon fill="#ADD8E6" points="990 450 900 600 1080 600"></polygon>
                    <polygon fill="#87CEEB" points="990 450 1080 300 900 300"></polygon>
                    <polygon fill="#4682B4" points="90 750 0 900 180 900"></polygon>
                    <polygon points="270 750 180 900 360 900"></polygon>
                    <polygon fill="#B0E0E6" points="270 750 360 600 180 600"></polygon>
                    <polygon points="450 750 540 600 360 600"></polygon>
                    <polygon points="630 750 540 900 720 900"></polygon>
                    <polygon fill="#87CEEB" points="630 750 720 600 540 600"></polygon>
                    <polygon fill="#ADD8E6" points="810 750 720 900 900 900"></polygon>
                    <polygon fill="#666" points="810 750 900 600 720 600"></polygon>
                    <polygon fill="#999" points="990 750 900 900 1080 900"></polygon>
                    <polygon fill="#999" points="180 0 90 150 270 150"></polygon>
                    <polygon fill="#B0E0E6" points="360 0 270 150 450 150"></polygon>
                    <polygon fill="#FFFFFF" points="540 0 450 150 630 150"></polygon>
                    <polygon points="900 0 810 150 990 150"></polygon>
                    <polygon fill="#4682B4" points="0 300 -90 450 90 450"></polygon>
                    <polygon fill="#FFFFFF" points="0 300 90 150 -90 150"></polygon>
                    <polygon fill="#FFFFFF" points="180 300 90 450 270 450"></polygon>
                    <polygon fill="#666" points="180 300 270 150 90 150"></polygon>
                    <polygon fill="#4682B4" points="360 300 270 450 450 450"></polygon>
                    <polygon fill="#FFFFFF" points="360 300 450 150 270 150"></polygon>
                    <polygon fill="#87CEEB" points="540 300 450 450 630 450"></polygon>
                    <polygon fill="#4682B4" points="540 300 630 150 450 150"></polygon>
                    <polygon fill="#ADD8E6" points="720 300 630 450 810 450"></polygon>
                    <polygon fill="#666" points="720 300 810 150 630 150"></polygon>
                    <polygon fill="#FFFFFF" points="900 300 810 450 990 450"></polygon>
                    <polygon fill="#999" points="900 300 990 150 810 150"></polygon>
                    <polygon points="0 600 -90 750 90 750"></polygon>
                    <polygon fill="#666" points="0 600 90 450 -90 450"></polygon>
                    <polygon fill="#ADD8E6" points="180 600 90 750 270 750"></polygon>
                    <polygon fill="#4682B4" points="180 600 270 450 90 450"></polygon>
                    <polygon fill="#4682B4" points="360 600 270 750 450 750"></polygon>
                    <polygon fill="#999" points="360 600 450 450 270 450"></polygon>
                    <polygon fill="#666" points="540 600 630 450 450 450"></polygon>
                    <polygon fill="#87CEEB" points="720 600 630 750 810 750"></polygon>
                    <polygon fill="#FFFFFF" points="900 600 810 750 990 750"></polygon>
                    <polygon fill="#87CEEB" points="900 600 990 450 810 450"></polygon>
                    <polygon fill="#B0E0E6" points="1080 300 990 450 1170 450"></polygon>
                    <polygon fill="#FFFFFF" points="1080 300 1170 150 990 150"></polygon>
                    <polygon points="1080 600 990 750 1170 750"></polygon>
                    <polygon fill="#666" points="1080 600 1170 450 990 450"></polygon>
                    <polygon fill="#DDD" points="1080 900 1170 750 990 750"></polygon>
                </g>
            </pattern>
        </defs>
        <rect x="0" y="0" fill="url(#a)" width="100%" height="100%"></rect>
        <rect x="0" y="0" fill="url(#b)" width="100%" height="100%"></rect>
    </svg>
</div>

<div class="card__avatar">
    <img height="100" width="157" src="data:image;base64,<?php echo $currimg?>" style="border-radius: 30%;">
</div>

    <div class="card__title"><?php echo $currfirstname; ?></div>
    <div class="card__subtitle"><?php echo $currdeptname; ?></div>
    <div class="card__wrapper">
       
        <span class = "uinfotab2"><a href ="empCHANGEPASSWORD.php" class = "btn btn-info"><span class="icon"><i class="icon-edit"></i> </span>Change Password</a></span>
        
    </div>
    
  
    <style>

      .widget-box {
  border-radius: 10px; /* You can adjust the value to control the amount of rounding */
  border: 1px solid #ccc; /* Optional: You can add a border for further styling */
  padding: 15px; /* Optional: Add padding to the box for better appearance */
}
/* .container-fluID{
  background: linear-gradient(to bottom, #f0f0f0, #add8e6);
} */
     .card {
  --main-color: #000;
  --submain-color: #78858F;
  --bg-color: #fff;
  font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
  position: relative;
  width: 300px;
  height: 384px;
  display: flex;
  flex-direction: column;
  align-items: center;
  border-radius: 20px;
  background: var(--bg-color);
  margin-top: 30px;
}

.card__img {
  height: 192px;
  width: 100%;
}

.card__img svg {
  height: 100%;
  border-radius: 20px 20px 0 0;
}

.card__avatar {
  position: absolute;
  width: 114px;
  height: 114px;
  background: var(--bg-color);
  border-radius: 100%;
  display: flex;
  justify-content: center;
  align-items: center;
  top: calc(50% - 57px);
}

.card__avatar svg {
  width: 100px;
  height: 100px;
}

.card__title {
  margin-top: 60px;
  font-weight: 500;
  font-size: 18px;
  color: var(--main-color);
}

.card__subtitle {
  margin-top: 10px;
  font-weight: 400;
  font-size: 15px;
  color: var(--submain-color);
}

@media (max-width: 768px) {
  .card {
    margin-top: 70px; 
    margin-left: auto;
    margin-right: auto;
  }

  .card__title {
    margin-top: 20px;
  }

  .card__subtitle {
    margin-top: 5px; 
  }
}



    </style>

   
</div>
          
          
          
      </div>
      <div class="container">
  <div class="chart-container">
    <canvas id="myPieChart" width="350" height="400"></canvas>
  </div>
  <div class="cards-container">
    <div class="sibbmpresidenteko large-card">
     
      <div class = "widget-title"><span class="icon"><i class ="icon-user"></i></span>
            <h5> Total Attendance </h5></div>
            <span class = "span6">
              <h5 style="margin-top: 20px;">You have</h5>
            </span>
            <span class = "span6">
              
            </span>

          </span>
   
      <div class="voice-chat-card-body">
      
      </div>
    </div>
    <div class="sibbmpresidenteko small-card">
    <div class = "widget-title"><span class="icon"><i class ="icon-user"></i></span>
            <h5> Total Late </h5></div>
            <span class = "span6">
              <h6 style="margin-top: 20px;">You have</h6>
            </span>
            <span class = "span6">
      <div class="voice-chat-card-body">
        <!-- Content for Card 1 goes here -->
      </div>
    </div>
    <div class="sibbmpresidenteko small-card">
      <div class = "widget-title"><span class="icon"><i class ="icon-user"></i></span>
            <h5> Total Undertime </h5></div>
            <span class = "span6">
              <h6 style="margin-top: 20px;">You have</h6>
            </span>
            <span class = "span6">
      <div class="voice-chat-card-body">
        <!-- Content for Card 2 goes here -->
      </div>
    </div>
  </div>
</div>

<style>
  .container {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin: 20px;
  }

  .chart-container {
    flex: 1;
    margin-right: 180px; 
  }

  .cards-container {
    display: flex;
    flex-wrap: wrap;
    flex-direction: row;
    justify-content: flex-start;
    align-items: flex-start;
    gap: 20px; 
  }

  .sibbmpresidenteko {
    width: 200px;
    margin-right: 20px;
    margin-bottom: 20px;
    border: 1px solid #ccc;
    border-radius: 10px;
    box-shadow: 2px 2px 10px #ccc;
    padding: 10px;
    background-color: #e8e8e8;
  }

  .small-card {
    height: 150px;
    width: 250px;
  }

  .large-card {
    width: 540px;
    height: 150px;
    margin-top: 20px;
    border: 1px solid #ccc;
    border-radius: 10px;
    box-shadow: 2px 2px 10px #ccc;
    padding: 10px;
    background-color: #e8e8e8;
  }

 
  @media (max-width: 768px) {
      .container {
        flex-direction: column;
        align-items: center;
      }

      .chart-container {
        margin-right: 40px;
        margin-bottom: 20px; 
        text-align: left;
      }

      .cards-container {
        align-items: center;
        margin-right: 40px;
      }

      .sibbmpresidenteko {
        width: 100%;
        margin-right: 0;
      }
    }
</style>




      
      


</style>
    
      

      
 

 
 
    

      <!-- <div class ="span6">
        <div class="widget-box">
          <div class = "widget-title"><span class="icon"><i class ="icon-user"></i></span>
            <h5> Profile</h5>
          </div> -->

          <div class="widget-content no-padding">
  <div class="table-responsive">
    <table class="table table-bordered data-table">
      <thead>
        <tr>
          <th>Employee ID</th>
          <th>Last Name</th>
          <th>First Name</th>
          <th>Middle Name</th>
          <th>Username</th>
          <th>Department</th>
          <th>Birthday</th>
          <th>Nationality</th>
          <th>Shift</th>
          <th>Contact Number</th>
          <th>Date Hired</th>
          <th>Date Regularized</th>
          <th>Date Resigned</th>
        </tr>
      </thead>
      <tbody>
        <tr class="gradeX">
          <td><?php echo $currempid; ?></td>
          <td><?php echo $currlastname; ?></td>
          <td><?php echo $currfirstname; ?></td>
          <td><?php echo $currmiddlename; ?></td>
          <td><?php echo $currusername; ?></td>
          <td><?php echo $currdeptname; ?></td>
          <td><?php echo $currdateofbirth; ?></td>
          <td><?php echo $currnationality; ?></td>
          <td><?php echo $currshiftsched; ?></td>
          <td><?php echo $currcontact; ?></td>
          <td><?php echo $currdatehired;?></td>
          <td><?php echo $currdateregularized; ?></td>
          <td><?php echo $currdateresigned; ?></td>
        </tr>
      </tbody>
    </table>
  </div>
</div>

            
         
            <div class="container-fluid">
              
  <div class ="row-fluid">
    <div class = "span10">
      <h3>Employee Records</h3>        
    </div>
  </div>
 
         
    <div class = "row-fluid">
        <div class = "span5">
            <div class ="control-group">
              <form action="<?php $_SERVER['PHP_SELF'];?>" method ="post">
              <?php
              $payperiodsquery = "SELECT * FROM payperiods";
              $payperiodsexecquery = mysqli_query($conn, $payperiodsquery) or die ("FAILED TO EXECUTE PAYPERIOD QUERY ".mysqli_error($conn));
              ?>
                  <label class="control-label" >Select Payroll Period: </label>
                    <div class="controls" >
                    <select name="payperiod" style="margin-bottom: 20px;">
                    <option value=""></option>
                    <?php while ($payperiodchoice = mysqli_fetch_array($payperiodsexecquery)): ?>
                        <?php
                        $selected = ($payperiodchoice['pperiod_range'] == $_SESSION['payperiods']) ? 'selected' : '';
                        ?>
                        <option value="<?php echo $payperiodchoice['pperiod_range']; ?>" <?php echo $selected; ?>>
                            <?php echo $payperiodchoice['pperiod_range']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
                <button type="submit" class="btn btn-success printbtn" name="pperiod_btn" style="margin-bottom: 20px;">Go</button>

                    </div>
                </form>
                <a href="printpayslip.php" class="btn btn-success" role="button" target="_blank">Generate Payslip</a>
                <a href="printdtr.php" class="btn btn-success" role="button" target="_blank">View DTR</a>
                <a href="printtimesheet.php" class="btn btn-success" role="button" target="_blank">View Timesheet</a>
                <a href="printleaves.php" class="btn btn-success" role="button" target="_blank">Apply for Leave</a>
                <a href ="empPAYROLLrecords.php" class = "btn btn-success"><span class="icon"><i class="icon-refresh"></i></span> Refresh</a>
                <!-- <small><?php echo $attrecordview; ?></small> -->
              </div>
                

              </div>
          </div>

       

              
    
          <div class = "span5">
                
            
          </div>


    </div> 
          </div>
        </div>
      </div>

      <div class ="span3">
      </div>  
    </div>
   
    


          

              
<!-- 
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
          </table> -->
             
        </div><!--EMPLOYEE TAB--><!--EMPLOYEE TAB--><!--EMPLOYEE TAB--><!--EMPLOYEE TAB--><!--EMPLOYEE TAB--><!--EMPLOYEE TAB--><!--EMPLOYEE TAB--><!--EMPLOYEE TAB-->
        </div>
        
      </div>
    </div>
  </div>
</div>
</div>
      

    </div>
  </div>
</div>
</div>
</div>
<div class="row-fluid">
<!-- <div id="footer" class="span12" style="position: fixed; bottom: 0; left: 0; width: 100%; text-align: center; padding: 10px;"> 2023 &copy; WEB-BASED TIMEKEEPING AND PAYROLL SYSTEM USING FINGERPRINT BIOMETRICS</div> -->

</div>
<?php
unset($_SESSION['changepassnotif']);
?>
<script src="../js/maruti.dashboard.js"></script> 
<script src="../js/excanvas.min.js"></script> 

<script src="../js/bootstrap.min.js"></script> 
<script src="../js/jquery.flot.min.js"></script> 
<script src="../js/jquery.flot.resize.min.js"></script> 
<script src="../js/jquery.peity.min.js"></script> 
<script src="../js/fullcalendar.min.js"></script> 
<script src="../js/maruti.js"></script> 
<div class="widget-title">

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Fetch data from PHP script
        fetch('fetch_data.php')
            .then(response => response.json())
            .then(data => createCustomPieChart(data, 'myPieChart', 200, 200))
            .catch(error => console.error("Error fetching data:", error));

        // Function to create a custom pie chart
        function createCustomPieChart(data, chartId, chartWidth, chartHeight) {
            var labels = data.map(item => item.label);
            var values = data.map(item => item.value);

            var ctx = document.getElementById(chartId).getContext('2d');
            var myPieChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: labels,
                    datasets: [{
                        data: values,
                        backgroundColor: getRandomColors(values.length),
                    }],
                },
                options: {
                    responsive: false, 
                    maintainAspectRatio: false, 
                    width: chartWidth,
                    height: chartHeight,
                },
            });
        }

        
        function getRandomColors(count) {
            var colors = [];
            for (var i = 0; i < count; i++) {
                var hue = (360 / count) * i;
                colors.push(`hsl(${hue}, 70%, 60%)`);
            }
            return colors;
        }
    });
</script>




</body>
</html>

