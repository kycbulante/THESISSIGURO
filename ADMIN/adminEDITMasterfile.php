<!DOCTYPE html>
<html lang="en">
<head>
<title>Admin</title>
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
$master = $_SESSION['master'];
$idres = $_GET['id'];
$DELquery = "SELECT * from employees WHERE emp_id ='$idres'";
$DELselresult = mysqli_query($conn,$DELquery) or die ("Failed to search DB. ".mysql_error());
  $DELcurr = mysqli_fetch_array($DELselresult);
  $DELcount = mysqli_num_rows($DELselresult);


   if($DELcount!=0 && $DELcurr) {

        $currprefixid = $DELcurr['prefix_ID'];
        $currempid = $DELcurr['emp_id'];
        $currfingerprintnumber = $DELcurr['fingerprint_id'];
        $currusername = $DELcurr['user_name'];
        $currlastname = $DELcurr['last_name'];
        $currfirstname = $DELcurr['first_name'];
        $currmiddlename = $DELcurr['middle_name'];
        $curremptype = $DELcurr['employment_TYPE'];
        $currposition = $DELcurr['position'];
        $currdateofbirth = $DELcurr['date_of_birth'];
        $currgender = $DELcurr['emp_gender'];
        $curracctype = $DELcurr['acct_type'];
        $curraddress = $DELcurr['emp_address'];
        $currnationality = $DELcurr['emp_nationality'];
        $currdeptname = $DELcurr['dept_NAME'];
        $currshiftsched = $DELcurr['shift_SCHEDULE'];
        $currcontact = $DELcurr['contact_number'];
        $currdatehired = $DELcurr['date_hired'];
        $currdateregularized = $DELcurr['date_regularized'];
        $currdateresigned = $DELcurr['date_resigned'];
        $currimg = $DELcurr['img_tmp'];
        $currgsis = $DELcurr['GSIS_idno'];
        $currphilhealth = $DELcurr['PHILHEALTH_idno'];
        $currpagibig = $DELcurr['PAGIBIG_idno'];
        $currtin = $DELcurr['TIN_number'];
        $currmaritalstatus = $DELcurr['rel_status'];
        $currspouse = $DELcurr['rel_partner'];
        $currempstatus = $DELcurr['emp_status'];
        $currchildnum = $DELcurr['num_children'];
        $currchild1 = $DELcurr['child_1'];
        $currchild2 = $DELcurr['child_2'];
        $currchild3 = $DELcurr['child_3'];
        $currchild4 = $DELcurr['child_4'];



        $sql = "SELECT s.monthlysalary
        FROM salarygrade s
        JOIN position p ON p.salarygrade = s.salarygrade
        WHERE p.position_name = '$currposition'";
        $sqlresult = mysqli_query($conn,$sql) or die ("Failed to search DB. ");
        $sqlcurr = mysqli_fetch_array($sqlresult);
        $currsalary = isset($sqlcurr['monthlysalary'] )? $sqlcurr['monthlysalary']: '';
        $payroll = "SELECT daily_rate FROM payrollinfo WHERE emp_id ='$idres'";
        $payrollresult = mysqli_query($conn,$payroll) or die ("Failed to search DB. ");
        $payrollcurr = mysqli_fetch_array($payrollresult);
        $currdaily = $payrollcurr ['daily_rate'];
    }
    else {
          $updateselecterror ="Employee information not found.";
          }/*2nd else end*/

$error = false;

if (isset($_POST['submit_btn']) ){


  $dateofbirth = $_POST['dob'];
  $datehired = $_POST['dphired'];
  $datereg = $_POST['dreg'];
  $dateres = $_POST['dres'];
  $nationality = $_POST['nationality'];

  // $fingerprintnumber = $_POST['fingerprint'];

  $address = trim($_POST['address']);
  $address = strip_tags($address);
  $address = htmlspecialchars($address,ENT_QUOTES);

  $lastname = trim($_POST['lastname']);
  $lastname = strip_tags($lastname);
  $lastname = htmlspecialchars($lastname);

  $firstname = trim($_POST['firstname']);
  $firstname = strip_tags($firstname);
  $firstname = htmlspecialchars($firstname);

  $middlename = trim($_POST['middlename']);
  $middlename = strip_tags($middlename);
  $middlename = htmlspecialchars($middlename);

  /*$email = trim($_POST['email']);
  $email = strip_tags($email);
  $email = htmlspecialchars($email);*/

  $username = trim($_POST['username']);
  $username = strip_tags($username);
  $username = htmlspecialchars($username);


  $gsisidno = ($_POST['gsisidno']);
  $philhealthnumber = ($_POST['philhealthnumber']);
  $tin = ($_POST['tin']);
  $pagibig = ($_POST['pagibignumber']);

  $contact = ($_POST['cellphonenumber']);

  $employoptionvar = ($_POST['employoption']);

  $genderoptionvar = ($_POST['genderoption']);

  $acctoptionvar = ($_POST['acctoption']);
  
  $deptoptionvar = ($_POST['deptoption']);

  $position = $_POST['position'] ?? '';

  $salaryGrade = $_POST['salaryGrade'] ?? '';

  $dailyRate = ($_POST['dailyRate']);

   $empstatus = ($_POST['empstatusoption']);

  $maritalstatus = ($_POST['maritaloption']);

  $spousename = ($_POST['spousename']);
  
  $shiftoptionvar = ($_POST['shifttime']);

  $numberofchild = ($_POST['numberofchild']);


    $monthlyrate = ($dailyRate * 22);
    $hrate = ($dailyRate / 8);

    $mrate = number_format((float)$monthlyrate,2,'.','');


  // $child1 = ($_POST['child1name']);

  // $child2 = ($_POST['child2name']);

  // $child3 = ($_POST['child3name']);

  // $child4 = ($_POST['child4name']);




  // if ($numberofchild == 1 && empty($child1)){
  //   $error = true;
  //   $numberofchilderror = "Number of children do not match with number of children names.";

  // } else if ($numberofchild == 2 && empty($child2)) {
  //   $error = true;
  //   $numberofchilderror = "Number of children do not match with number of children names.";
  
  // } else if ($numberofchild == 3 && empty($child3)){
  //   $error = true;
  //   $numberofchilderror = "Number of children do not match with number of children names.";

  // } else if ($numberofchild == 4 && empty($child4)){
  //   $error = true;
  //   $numberofchilderror = "Number of children do not match with number of children names.";

  // }

  if(empty($maritalstatus)){
    $error = true;
    $maritalerror = "Please select marital status.";
  }

  if($maritalstatus == "Married" && empty($spousename)){
    $error = true;
    $spouseerror = "Please enter name of spouse.";
  }


  if(empty($empstatus)){
    $error = true;
    $empstatusError = "Please select employee status";
  }

  if (empty($gsisidno)){
    $error = true;
    $gsisidnoerror = "Please provide GSIS ID Number.";
  }

  if(empty($pagibig)){
    $error = true;
    $pagibigerror = "Please provide PAG-IBIG Number.";

  }

  if(empty($philhealthnumber)){
    $error = true;
    $philhealtherror = "Please provide Philhealth Number.";
  }

  if(empty($tin)){
    $error = true;
    $tinerror = "Please provide TIN.";
  }


  
  if(empty($datehired)){
    $error = true;
    $datehirederror = "Please provide the hire date of this employee.";
  }

  if(empty($dateofbirth)){
    $error = true;
    $birthdateerror = "Please provide a birthdate.";
  }

  if(empty($address)){
    $error = true;
    $addresserror = "Please provide your address.";
  }else if(strlen($address)< 5){
    $error = true;
    $addresserror = "Please provide your complete address.";
  }

  if(empty($genderoptionvar)){
    $error = true;
    $gendererror = "Please indicate your gender.";
  }

  if(empty($contact)){
    $error = true;
    $cellphoneerror = "Please provide your cellphone number.";
  }else if(strlen($contact < 7)){
    $error = true;
    $cellphoneerror = "Please provide a valid number.";
  }
  

  if ($acctoptionvar == "Administrator"){

    $accounttype = "Administrator";
    $idprefix = "ADMIN-";

  } elseif ($acctoptionvar=="Employee") {

    $accounttype = "Employee";
    $idprefix = "EMP-";
  } elseif ($acctoptionvar=="Master"){
	
	$accounttype = "Master";
  $idprefix = "MSTR-";
  }
  else {
    $error = true;
    $acctError = "Account type not set.";
  }

  if (empty($username)){
    $error = true;
    $usernameError = "Please provide a username.";
  }

  if (strlen($username) < 3){
    $error = true;
    $usernameError = "Username must have at least 3 characters.";
  }

  if (empty($lastname) || empty($firstname) ){
      $error = true;
      $nameError = "Please enter your full name.";
  } else if (strlen($lastname) < 2){
    $error = true;
    $nameError ="Last name must have at least 2 characters.";
  } 
  
  if ($acctoptionvar == "Administrator"){

    $accounttype = "Administrator";
    $idprefix = "ADMIN-";

  } elseif ($acctoptionvar=="Employee") {

    $accounttype = "Employee";
    $idprefix = "EMP-";
  } elseif ($acctoptionvar=="Master"){
	
	$accounttype = "Master";
  $idprefix = "MSTR-";
  }
  else {
    $error = true;
    $acctError = "Account type not set.";
  }

  if($maritalstatus == "Married" && empty($spousename)){
    $error = true;
    $spouseerror = "Please enter name of spouse.";
  } else if ($maritalstatus == "Single"){
    $spousename = " ";
  }
  

    $basicpay = $_POST['dailyRate'];

    $monthlyrate = ($basicpay * 22);
    $hrate = ($basicpay / 8);

    $mrate = number_format((float)$monthlyrate,2,'.','');


    //GSIS
    if($employoptionvar == "Contractual"){
      $gsisEE = 0;
      $gsisER = 0;
      $gsisTOTAL = 0;
    } else if ($employoptionvar == "Permanent"){
    $gsisEE = $monthlyrate * .09; 
    $gsisER = $monthlyrate * .12; 
    $gsisTOTAL = $gsisEE + $gsisER;
    }

  //philhealth
    if($employoptionvar == "Contractual"){
      $philhealthdeductEE = 0;
      $philhealthdeductER = 0;
      $philhealthdeductTOTAL = 0;
    } else if ($employoptionvar == "Permanent"){
      $philhealthdeductEE = (($mrate * 0.045) / 2);
      $philhealthdeductER = (($mrate * 0.045) / 2);
      $philhealthdeductTOTAL = ($mrate * 0.045);
    }
    
    
    //pagibig
    if($employoptionvar == "Contractual"){
      $pagibigdeductEE = 0;
      $pagibigdeductER = 0;
      $pagibigdeductTOTAL = 0;

    } else if ($employoptionvar == "Permanent"){

    switch ($mrate){

      case ($mrate<=1500):
    
        $pagibigdeductEE = ($mrate*0.01);
        $pagibigdeductER = ($mrate*0.02);
        $pagibigdeductTOTAL = $pagibigdeductEE + $pagibigdeductER;
    
      break;
    
      case ($mrate<=5000):
    
        $pagibigdeductEE = ($mrate*0.02);
        $pagibigdeductER = ($mrate*0.02);
        $pagibigdeductTOTAL = $pagibigdeductEE + $pagibigdeductER;
    
      break;
    
      case ($mrate>5000): 
    
        $pagibigdeductEE = 100.00;
        $pagibigdeductER = 100.00;
        $pagibigdeductTOTAL = $pagibigdeductEE + $pagibigdeductER;
    
      break;
    }
  }

  $datehired1 = DateTime::createFromFormat('Y-m-d', $datehired);
  $currentDate = new DateTime();
  $currentYear = (int)$currentDate->format('Y');
  $sixMonthsLater = clone $datehired1;
  $sixMonthsLater->modify('+1 month');
  
  // leaves
  if ($employoptionvar == "Contractual") {
      $leaves = 0;
  } else if ($employoptionvar == "Permanent") {
      if ($currentDate < $sixMonthsLater) {
          $leaves = 0;
      } else {
          $monthsWorked = (($currentDate->format('Y') - $datehired1->format('Y')) * 12) + $currentDate->format('n') - $datehired1->format('n');
          $leaves = max(0, floor($monthsWorked / 1)) * 1.25;
      }
  }



  

  if(!$error){
    $sqlquery = "UPDATE employees SET position = '$position', num_children ='$numberofchild',  rel_status = '$maritalstatus', rel_partner = '$spousename', GSIS_idno = '$gsisidno',PAGIBIG_idno = '$pagibig', PHILHEALTH_idno = '$philhealthnumber', TIN_number = '$tin', emp_status = '$empstatus', user_name = '$username', last_name = '$lastname', first_name = '$firstname', middle_name = '$middlename', contact_number = '$contact', acct_type = '$acctoptionvar', dept_NAME = '$deptoptionvar', shift_SCHEDULE = '$shiftoptionvar', date_hired = '$datehired', date_of_birth = '$dateofbirth',emp_address = '$address', emp_nationality = '$nationality', emp_gender = '$genderoptionvar', date_hired = '$datehired', date_regularized = '$datereg', date_resigned = '$dateres', employment_TYPE = '$employoptionvar' WHERE emp_id = '$currempid'";
    $result = mysqli_query($conn,$sqlquery) or die ("FAILED TO INSERT ".mysqli_error($conn));

  
	   
    $payrollinfoqry = "UPDATE PAYROLLINFO SET base_pay = '$mrate', daily_rate='$dailyRate', hourly_rate='$hrate', gsisEE='$gsisEE',gsisER='$gsisER', gsisTOTAL ='$gsisTOTAL', ph_EE ='$philhealthdeductEE',ph_ER ='$philhealthdeductER',ph_TOTAL ='$philhealthdeductTOTAL', pagibig_EE='$pagibigdeductEE',pagibig_ER='$pagibigdeductER',pagibig_TOTAL='$pagibigdeductTOTAL' WHERE emp_id = '$currempid'";
    $payrollinfoexecqry = mysqli_query($conn,$payrollinfoqry) or die ("FAILED TO ADD NEW PAY INFO ".mysqli_error($conn));

    $leaveinfoqry = "UPDATE leaves SET leave_count = $leaves, leaves_year='$currentYear' WHERE emp_id = '$currempid'";
    $leaveexecqry = mysqli_query($conn,$leaveinfoqry) or die ("FAILED TO ADD NEW PAY INFO ".mysqli_error($conn));

    $activityLog = "Edited employee profile (ID: $idres)";
    $adminActivityQuery = "INSERT INTO adminactivity_log (emp_id, activity,log_timestamp) VALUES ('$adminId', '$activityLog', NOW())";
    $adminActivityResult = mysqli_query($conn, $adminActivityQuery);

      if($result) {
          for ($i = 1; $i <= $numberofchild; $i++) {
              $childname = $_POST["child" . $i . "name"];
              $childsql = "UPDATE employees SET child_" . $i . " = '$childname' WHERE emp_id = $currempid";
              
              if ($conn->query($childsql) !== TRUE) {
                  echo "Error: " . $childsql . "<br>" . $conn->error;
              }
          }

          

          ?>

          
   
   <script>
            document.addEventListener('DOMContentLoaded', function() {
                swal({
                    text: "Date inserted successfully",
                    icon: "success",
                    button: "OK",
                }).then(function() {
                    // Redirect to another location
                    window.location.href = "adminMasterfile.php";
                });
            });
        </script>

           <?php
      
     // $_SESSION['masterfilenotif'] = "Employee Profile Updated!";
      // unset($lastname);
      // unset($firstname);
      // unset($middlename);
      
      // unset($contact);

      // header("Location: adminMasterfile.php");
	}

   /* $sqlquery2 = "SELECT emp_id FROM accounts where user_name = '$username'";
    $result2=mysqli_query($conn,$sqlquery2) or die (mysql_error());  
    $row2 = mysqli_fetch_array($result2);

    if ($row2){

      $pempid = $row2['emp_id'];

      $sqlquery3="INSERT INTO payroll (emp_id,last_name,first_name) VALUES ('$pempid','$lastname','$firstname')";
      $result3 = mysqli_query($conn,$sqlquery3) or die (mysql_error());
    }
**/
   

      /**$auditinfo = "Added new profile";

      $auditquery = "INSERT INTO audittrail (emp_id, audit_info) VALUES ('$uempid','$auditinfo')";
      $auditresult = mysqli_query($conn,$auditquery) or die(mysql_error());*/

      
    } else {
      //$errType = "danger";
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
      //$errType = "danger";
      //$_SESSION['addprofilenotif'] = "Something went wrong. Make sure you accomplish all the required fields.";
    }
  }



?>






<script type ="text/javascript">
  
  $( function() {
      $( "#datepicker" ).datepicker({ dateFormat: 'yy-mm-dd'});
      } );
  $( function() {
      $( "#birthdate" ).datepicker({ 
        changeYear: true,
        yearRange: "1940:2040",
        dateFormat: 'yy-mm-dd'});
      } );
  $( function() {
      $( "#dateregularized" ).datepicker({ 
        changeYear: true,
        yearRange: "1990:2040",
        dateFormat: 'yy-mm-dd'});
      } );

  $( function() {
      $( "#dateresigned" ).datepicker({ 
        changeYear: true,
        yearRange: "1990:2040",
        dateFormat: 'yy-mm-dd'});
      } );
</script>

<body>

<!--Header-part-->

<?php
INCLUDE ('NAVBAR.php');
?>


<div id="content">

  <div id="content-header">
    <div id="breadcrumb"> 
      <a href="adminDASHBOARD.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a>
      <a href ="adminMasterfile.php" class="tip-bottom"><i class ="icon-file"></i> Employee Masterlist</a>
      <a href="#" class="tip-bottom"><i class = "icon-edit"></i>Edit Profile</a>
    </div>
	
  </div>

  <div class="container-fluid">
  
  <?php
                if( isset($errMSG)){
                  ?>
                  <div class="form-group">
                    <div class="alert alert=<?php echo ($errType=="success") ? "success" : $errType; ?>">
                      <font color="green" size ="3px"><span class="glyphicon glyphicon-info-sign"></span> <?php echo $errMSG; ?></font>
                    </div>
                  </div>
                <?php
                }    
                ?>
	
    <div class="row-fluid">
          <div class = "span1">
          </div>
          <div class="span6">
            <h3>Edit Profile</h3>
            <div class="widget-box">
              <div class="widget-title"> <span class="icon"> <i class="icon-user"></i> </span>
                <h5>Personal Information</h5>
              </div>
              <div class="widget-content">
                <form action="adminEDITMasterfile.php?id=<?php echo $idres;?>" method="POST" class="form-horizontal" enctype="multipart/form-data">
                  <div class="control-group">
                    <label class="control-label">Employee ID :</label>
                      <div class="controls">
                      <input type="text" class="span11"  value ="<?php echo $currprefixid; echo $currempid;?>"name="employeeID" readonly/>
                        <!-- <span class ="label label-important"><?php echo $nameError; ?></span> -->
                      </div>         
                  </div>
                  <div class="control-group">
                    <label class="control-label">Last Name :</label>
                      <div class="controls">
                      <input type="text" class="span11" value="<?php echo $currlastname;?>" name="lastname"/>
                        <!-- <span class ="label label-important"><?php echo $nameError; ?></span> -->
                      </div>         
                  </div>
                  <div class="control-group">
                    <label class="control-label">First Name :</label>
                      <div class="controls">
                      <input type="text" class="span11" value="<?php echo $currfirstname;?>" name="firstname" />
                        <!-- <span class ="label label-important"><?php echo $nameError; ?></span> -->
                      </div>
                  </div>
                  <div class="control-group">
                    <label class="control-label">Middle Name</label>
                      <div class="controls">
                      <input type="text"  class="span11" value="<?php echo $currmiddlename;?>" name="middlename" />
                        <!-- <span class ="label label-important"><?php echo $nameError; ?></span> -->
                      </div>
                  </div>
                  <div class="control-group">
                    <label class="control-label">Username:</label>
                      <div class="controls">
                      <input type="text" class="span11" value="<?php echo $currusername;?>" name="username"/>
                        <!-- <span class ="label label-important"><?php echo $usernameError; ?></span> -->
                      </div>
                  </div>

                  <div class ="control-group">
                    <label class="control-label">Date of Birth: </label>
                      <div class="controls">
                      <input type="text" class = "span3" id="birthdate" name ="dob" placeholder="<?php echo $currdateofbirth;?>" value="<?php echo $currdateofbirth;?>">       
                        <!-- <span class ="label label-important"><?php echo $birthdateerror; ?></span> -->
                      </div>
                  </div>
                  <div class="control-group">
                    <label class="control-label">Gender:</label>
                      <div class="controls">
                        <label>
                          <?php
                          if ($currgender=="Male"){
                            ?>
                            <input type="radio" name="genderoption" value="Male" checked/>
                          Male</label>
                        <label>
                          <input type="radio" name="genderoption" value="Female"/>
                          Female</label>

                            <?php

                          }
                          else{
                            ?>
                            <input type="radio" name="genderoption" value="Male" />
                          Male</label>
                        <label>
                          <input type="radio" name="genderoption" value="Female"checked/>
                          Female</label>

                            <?php

                          }
                          ?>
                        
                          <!-- <span class ="label label-important"><?php echo $gendererror; ?></span> -->
                      </div>
                  </div>

                  <div class ="control-group">
                    <label class="control-label">Marital Status: </label>
                      <div class="controls">
                        <select name="maritaloption">
                        <option><?php echo $currmaritalstatus; ?></option>
                          <option>Single</option>
                          <option>Married</option>
                          <option>Widowed</option>
                        </select>
                        <!-- <span class ="label label-important"><?php echo $maritalerror; ?></span> -->
                      </div>
                  </div>

                  <div class="control-group">
                    <label class="control-label">Name of Spouse:</label>
                      <div class="controls">
                      <input type="text" class="span11" value="<?php echo $currspouse;?>"name="spousename"/>
                        <span class = "label"><small>*Fill-up only if married.*</small></span>
                         <!-- <span class ="label label-important"><?php echo $spouseerror; ?></span> -->
                      </div>
                  </div>

                  <div class="control-group">
                      <label class="control-label">Number of Children: </label>
                      <div class="controls">
                        <select class="span2" name="numberofchild" id="numberofchild" onchange="toggleChildFields()" required>
                          <option><?php echo $currchildnum?></option>
                          <option>00</option>
                          <option>1</option>
                          <option>2</option>
                          <option>3</option>
                          <option>4</option>
                        </select>
                      </div>
                    </div>

                    <div class="control-group">
                      <label class="control-label"></label>
                      <div class="controls">
                        <span class="label"><small>*Fill-up according to number of children.*</small></span>
                      </div>
                    </div>

                    <div id="childFieldsContainer"></div>

                  <div class="control-group">
                    <label class="control-label">Address:</label>
                      <div class="controls">
                      <input type="text" class="span11" value = "<?php echo $curraddress;?>" placeholder="Child 4" name="address"/>
                         <!-- <span class ="label label-important"><?php echo $addresserror; ?></span> -->
                      </div>
                  </div>

                  <div class="control-group">
                    <label class="control-label">Cellphone Number:</label>
                      <div class="controls">
                      <input type="text" class="span11" value="<?php echo $currcontact;?>" name="cellphonenumber" pattern="[0]{1}[9]{1}[0-9]{9}"/>
                        <!-- <span class ="label label-important"><?php echo $cellphoneerror; ?></span> -->
                      </div>
                  </div>

                  <div class="control-group">
                    <label class="control-label">Nationality:</label>
                      <div class="controls">
                      <input type="text" class="span6" value="<?php echo $currnationality;?>" name="nationality"/>
                        <!-- <span class ="label label-important"><?php echo $nationalityerror; ?></span> -->
                      </div>
                  </div>

                   <div class="control-group">
                    <label class="control-label">GSIS ID No:</label>
                      <div class="controls">
                      <input type="text" class="span6" value="<?php echo $currgsis;?>" name="gsisidno" pattern="[EP]-\d{4}-\d{2}-\d{2}-\d{5}"/>
                        <!-- <span class ="label label-important"><?php echo $gsisidnoerror; ?></span> -->
                      </div>
                  </div>

                  <div class="control-group">
                    <label class="control-label">Philhealth Number:</label>
                      <div class="controls">
                      <input type="text" class="span6" value ="<?php echo $currphilhealth; ?>" name="philhealthnumber" pattern="[0-9]{12}"/>
                        <!-- <span class ="label label-important"><?php echo $philhealtherror; ?></span> -->
                      </div>
                  </div>

                  <div class="control-group">
                    <label class="control-label">PAG-IBIG Number:</label>
                      <div class="controls">
                      <input type="text" class="span6" placeholder="PAG-IBIG Number" name="pagibignumber" value ="<?php echo $currpagibig;?>" pattern="[0-9]{12}"/>
                        <!-- <span class ="label label-important"><?php echo $pagibigerror; ?></span> -->
                      </div>
                  </div>

                  <div class="control-group">
                    <label class="control-label">TIN:</label>
                      <div class="controls">
                      <input type="text" class="span6" value="<?php echo $currtin; ?>" name="tin" pattern="[0-9]{9}"/>
                        <!-- <span class ="label label-important"><?php echo $tinerror; ?></span> -->
                      </div>
                  </div><br>
:
                  <div class = "form-actions">
                  </div> 
              </div>
            </div>
          </div>

          <div class="span4">
            <div class="widget-box">
              <div class="widget-title">
                <span class="icon">
                  <i class="icon-th-list"></i>
                </span>
                <h5>Account Details</h5>
              </div>
              <div class="widget-content">

              <div class="control-group">
    <label class="control-label">Account Type:</label>
    <div class="controls">
        <?php
        if ($master) {
            // Display Administrator, Employee, and Master radio buttons
            echo '
                <label>
                    <input type="radio" name="acctoption" value="Administrator" ' . ($curracctype == "Administrator" ? 'checked' : '') . ' />
                    Administrator
                </label>

                <label>
                    <input type="radio" name="acctoption" value="Employee" ' . ($curracctype == "Employee" ? 'checked' : '') . ' />
                    Employee
                </label>

                <label>
                    <input type="radio" name="acctoption" value="Master" ' . ($curracctype == "Master" ? 'checked' : '') . ' />
                    Master
                </label>';
        } else {
            // Display Administrator and Employee radio buttons
            echo '
                <label>
                    <input type="radio" name="acctoption" value="Administrator" ' . ($curracctype == "Administrator" ? 'checked' : '') . ' />
                    Administrator
                </label>

                <label>
                    <input type="radio" name="acctoption" value="Employee" ' . ($curracctype == "Employee" ? 'checked' : '') . ' />
                    Employee
                </label>';
        }
        ?>
        <!-- <span class="label label-important"><?php echo $acctError; ?></span> -->
    </div>
</div>


                  
      <?php
      $departmentsquery = "SELECT * FROM DEPARTMENT";
      $departmentsexecqry = mysqli_query($conn, $departmentsquery) or die ("FAILED TO EXECUTE DEPT. QUERY ".mysql_error());
      ?>
                  <div class ="control-group">
                    <label class="control-label">Department: </label>
                      <div class="controls">
                        <select name="deptoption">
                        <option><?php echo $currdeptname; ?></option>
                        <?php  while($deptchoice = mysqli_fetch_array($departmentsexecqry)):;?>
                          <option><?php echo $deptchoice['dept_NAME'];?></option>
                          <?php endwhile; ?>
                        </select>
                      </div>
                  </div>
        
      <?php
      $shiftsquery = "SELECT * FROM SHIFT";
      $shiftsexecqry = mysqli_query($conn, $shiftsquery) or die ("FAILED TO EXECUTE SHIFTS. QUERY ".mysql_error());
      ?>
                  <div class ="control-group">
                    <label class="control-label">Shift: </label>
                      <div class="controls">
                        <select name ="shifttime">
                        <option><?php echo $currshiftsched; ?></option>

                        <?php while($shiftchoice = mysqli_fetch_array($shiftsexecqry)):;?>
                          <option><?php echo $shiftchoice['shift_SCHEDULE'];?></option>
      <?php endwhile; ?>
                        </select>
                      </div>
                  </div>
                  <?php
              $emptypesquery = "SELECT * FROM employmenttypes";
              $emptypesexecqry = mysqli_query($conn, $emptypesquery) or die ("FAILED TO EXECUTE DEPT. QUERY ".mysql_error());
              ?>
                          
                          <div class ="control-group">
                            <label class="control-label">Employment Type: </label>
                              <div class="controls">
                                <select name="employoption" id= "employoption" required onchange="updatePositionDropdown()">
                                  <option><?php echo $curremptype;?></option>
                                  <?php  while($emptypechoice = mysqli_fetch_array($emptypesexecqry)):;?>
                                  <option><?php echo $emptypechoice['employment_TYPE'];?></option>
                                  <?php endwhile; ?>
                                </select>
                              </div>
                  </div>
                  <?php
                        $positionquery = "SELECT * FROM position";
                        $positionexecqry = mysqli_query($conn, $positionquery) or die ("FAILED TO EXECUTE DEPT. QUERY ".mysqli_error($conn));
                    ?>
                                              
                    <div class="control-group">
                        <label class="control-label">Position:</label>
                        <div class="controls">
                            <select name="position" id="position" onchange="updateSalaryGrade()">
                                <option selected><?php echo $currposition; ?></option>
                                <?php while($positionchoice = mysqli_fetch_array($positionexecqry)):;?>
                                    <option><?php echo $positionchoice['position_name'];?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">Salary Grade:</label>
                        <div class="controls">
                            <input type="text" id="salaryGrade" name="salaryGrade" value ="<?php echo $currsalary; ?>"  readonly>
                        </div>
                    </div>
                    <label class="control-label">Daily Rate :</label>
                <div class="controls">
                  <input type="text" class="span5" name="dailyRate" id="dailyRate" value ="<?php echo $currdaily; ?>" required/>
                  <!-- <span class ="label label-important"><?php echo $baserateerror; ?></span> -->
                </div>
              </div> 

                  <div class ="control-group">
                    <label class="control-label">Date Hired: </label>
                      <div class="controls">
                      <input type="text" id="datepicker" name ="dphired" placeholder="<?php echo $currdatehired; ?>" value="<?php echo $currdatehired; ?>">
                        <!-- <span class ="label label-important"><?php echo $datehirederror; ?></span> -->
                      </div>
                  </div>

                  <div class ="control-group">
                    <label class="control-label">Date Regularized: </label>
                      <div class="controls">
                      <input type="text" id="dateregularized" name ="dreg" placeholder="<?php echo $currdateregularized;?>" value="<?php echo $currdateregularized;?>">
                        <!-- <span class ="label label-important"><?php echo $dateregularizederror; ?></span> -->
                      </div>
                  </div>

                  <div class ="control-group">
                    <label class="control-label">Date Resigned: </label>
                      <div class="controls">
                      <input type="text" id="dateresigned" name ="dres" placeholder="<?php echo $currdateresigned;?>" value="<?php echo $currdateresigned;?>">
                        <!-- <span class ="label label-important"><?php echo $dateresignederror; ?></span> -->
                      </div>
                  </div>

                  <div class = "control-group">
                    <label class="control-label">Picture: </label>
                      <div class="controls">
                        <span class="uinfotab2"><img height = "100" width="157" src="data:image;base64,<?php echo $currimg?>"></span>
                        <input type="file" name="image"/>
                        <!-- <span class ="label label-important"><?php echo $imgerror; ?></span> -->
                      </div>
                  </div>

                 <div class ="control-group">
                    <label class="control-label">Employee Status: </label>
                      <div class="controls">
                        <select name="empstatusoption">
                          <option><?php echo $currempstatus; ?></option>
                          <option>Active</option>
                          <option>Inactive</option>
                        </select>
                        <!-- <span class ="label label-important"><?php echo $empstatusError; ?></span> -->
                      </div>
                  </div>
        
                  <!-- <div class ="control-group">
                    <label class="control-label">fingerprint: </label>
                      <div class="controls">
                      <input type ="password" placeholder ="fingerprint" name="fingerprint" class="span5" maxLength="20" value="<?php echo $currfingerprintnumber;?>"/>
                        <span class ="label label-important"><?php echo $fingerprintError; ?></span> -->
                      <!-- </div>
                  </div> -->
            
                  <div class="form-actions">
                    <button type="submit" class="btn btn-success" name = "submit_btn" style="float:right;">Update</button>
                  </div>


                
              </div><!--widget content-->
            </form>
            
          </div>
        </div>
    </div>

    <div class="row-fluid">
  <div id="footer" class="span12"> 2023 &copy; WEB-BASED TIMEKEEPING AND PAYROLL SYSTEM USING FINGERPRINT BIOMETRICS</div>
</div>
<?php
unset($_SESSION['addprofilenotif']);
?>

<script src="../js/maruti.dashboard.js"></script> 
<script src="../js/excanvas.min.js"></script> 

<script src="../js/bootstrap.min.js"></script> 
<script src="../js/jquery.flot.min.js"></script> 
<script src="../js/jquery.flot.resize.min.js"></script> 
<script src="../js/jquery.peity.min.js"></script> 
<script src="../js/fullcalendar.min.js"></script> 
<script src="../js/maruti.js"></script> 
<script>
    function toggleChildFields() {
      var childValues = [
        "<?php echo $currchild1; ?>",
        "<?php echo $currchild2; ?>",
        "<?php echo $currchild3; ?>",
        "<?php echo $currchild4; ?>",
        // Add more variables as needed
      ];
      var numberOfChildren = document.getElementById("numberofchild").value;
      var childFieldsContainer = document.getElementById("childFieldsContainer");

      // Clear previous child fields
      childFieldsContainer.innerHTML = "";

      // Generate new child name fields
      for (var i = 1; i <= numberOfChildren; i++) {
        var label = document.createElement("label");
        label.textContent = "Child " + i + ":";

        var input = document.createElement("input");
        input.type = "text";
        input.class = "span11";
        input.placeholder = "Child " + i;
        input.name = "child" + i + "name";
        input.value = childValues[i-1];

        var controlGroup = document.createElement("div");
        controlGroup.class = "control-group";
        controlGroup.appendChild(label);
        controlGroup.appendChild(document.createElement("br"));
        controlGroup.appendChild(input);

        childFieldsContainer.appendChild(controlGroup);
      }
    }
    toggleChildFields();
  </script>

<script>
  function updatePositionDropdown() {
    var employmentTypeDropdown = document.getElementById('employoption');
    var positionDropdown = document.getElementById('position');
    var salaryGradeInput = document.getElementById('salaryGrade');

    // Get the selected value in the Employment Type dropdown
    var selectedEmploymentType = employmentTypeDropdown.value;

    // Check if the selected value is "contractual"
    if (selectedEmploymentType.toLowerCase() === 'contractual') {
      // If contractual, make the Position dropdown and Salary Grade input null and disabled
      positionDropdown.value = '';
      positionDropdown.disabled = true;
      salaryGradeInput.value = '';
      salaryGradeInput.disabled = true;
    } else {
      // If not contractual, enable the Position dropdown and Salary Grade input
      positionDropdown.disabled = false;
      salaryGradeInput.disabled = false;
    }
  }
</script>

  
<script>
    function updateSalaryGrade() {
        var position = document.getElementById("position").value;

        if (position !== "") {
            // AJAX request to fetch the salary grade and daily rate
            fetch(`salarygrade.php?position=${position}&ajax=true`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById("salaryGrade").value = data.monthlySalary;

                    // Format the daily rate to two decimal places
                    var formattedDailyRate = data.dailyRate.toFixed(2);
                    document.getElementById("dailyRate").value = formattedDailyRate;
                })
                .catch(error => console.error("Error fetching salary grade:", error));
        } else {
            document.getElementById("salaryGrade").value = "";
            document.getElementById("dailyRate").value = "";
        }
    }
</script>




</body>
</html>

