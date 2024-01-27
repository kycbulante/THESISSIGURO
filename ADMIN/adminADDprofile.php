
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
<!-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script> -->
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

echo "adminId in this file: ".$_SESSION['adminId'];
$adminId = $_SESSION['adminId'];
$error = false;
$master = $_SESSION['master'];

if (isset($_POST['submit_btn']) ){

  $dateofbirth = $_POST['dob'];
  $datehired = $_POST['dphired'];
  $nationality = $_POST['nationality'];

  $dhired = strtotime($datehired);
  $dt = strtotime('+1 month',$dhired);
  $date13th = date("Y-m-d",$dt);

  // $fingerprintnumber = $_POST['fingerprint'];

  $address = trim($_POST['address']);
  $address = strip_tags($address);
  $address = htmlspecialchars($address,ENT_QUOTES);

  $username = trim($_POST['username']);
  $username = strip_tags($username);
  $username = htmlspecialchars($username);

  $lastname = trim($_POST['lastname']);
  $lastname = strip_tags($lastname);
  $lastname = htmlspecialchars($lastname);

  $firstname = trim($_POST['firstname']);
  $firstname = strip_tags($firstname);
  $firstname = htmlspecialchars($firstname);

  $middlename = trim($_POST['middlename']);
  $middlename = strip_tags($middlename);
  $middlename = htmlspecialchars($middlename);

  $gsisidno = ($_POST['gsisidno']);
  $philhealthnumber = ($_POST['philhealthnumber']);
  $tin = ($_POST['tin']);
  $pagibig = ($_POST['pagibignumber']);
  $contact = ($_POST['cellphonenumber']);

  $employoptionvar = ($_POST['employoption']);

  $positionvar = $_POST['position'] ?? '';

  $genderoptionvar = ($_POST['genderoption']);

  $acctoptionvar = ($_POST['acctoption']);
  
  $deptoptionvar = ($_POST['deptoption']);

  $empstatus = ($_POST['empstatusoption']);

  $maritalstatus = ($_POST['maritaloption']);

  $spousename = ($_POST['spousename']);

  $shiftoptionvar = ($_POST['shifttime']);

  $numberofchild = ($_POST['numberofchild']);

  // $child1 = ($_POST['child1name']);

  // $child2 = ($_POST['child2name']);

  // $child3 = ($_POST['child3name']);

  // $child4 = ($_POST['child4name']);

  $files = ($_FILES['image']['tmp_name']);

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

  // } else if ($numberofchild == 0){

  //   $error = false;
  // }

  if(strlen($address)< 5){
    $error = true;
    $addresserror = "Please provide your complete address.";
  }

  $query ="SELECT user_name FROM employees WHERE user_name ='$username'";
  $result = mysqli_query($conn,$query);
  $count = mysqli_num_rows($result);

  // if(empty($employoptionvar)){
  //   $error=true;
  //   $employoptionerror = "Please indicate employment type.";
  // } else if($employoptionvar == "Probationary"){
  //   $leaves = 0;
  // }else if($employoptionvar == "Contractual"){
  //   $leaves = 0;

  // }else if($employoptionvar == "Regular"){

  //   $leaves = 15;
  // }

  if ($genderoptionvar == "Male"){
    $splv = 7;
  }else if ($genderoptionvar == "Female"){
    $splv = 60;
  }

  if (empty($files)){
    $error = true;
    $imgerror = "Please provide an image.";
  } else{
      $image = addslashes($_FILES['image']['tmp_name']);
      $name = addslashes($_FILES['image']['name']);
      $image = file_get_contents($image);
      $image = base64_encode($image);
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
  
  if ($count!=0){
    $error = true;
    $usernameError = "Username is already in use.";
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
    $sqlquery = "INSERT INTO employees (user_name, last_name, first_name, middle_name, contact_number, acct_type, fingerprint_id, dept_NAME, shift_SCHEDULE, date_hired, prefix_ID, date_of_birth, emp_address, emp_nationality, emp_gender, employment_TYPE, position, img_name, img_tmp, GSIS_idno, PHILHEALTH_idno, PAGIBIG_idno, TIN_number, emp_status, rel_status, rel_partner, num_children, date_plus1month) VALUES 
    ('$username','$lastname','$firstname','$middlename','$contact','$accounttype', '0' ,'$deptoptionvar','$shiftoptionvar','$datehired','$idprefix','$dateofbirth', '$address','$nationality','$genderoptionvar', '$employoptionvar','$positionvar', '$name','$image', '$gsisidno','$philhealthnumber','$pagibig','$tin', '$empstatus', '$maritalstatus', '$spousename', '$numberofchild','$date13th')";
    
    $result = mysqli_query($conn, $sqlquery) or die ("FAILED TO INSERT " . mysqli_error($conn));
    $lastid = mysqli_insert_id($conn);
    $payrollinfoqry = "INSERT INTO PAYROLLINFO (emp_id,base_pay,daily_rate,hourly_rate,gsisEE,gsisER, gsisTOTAL, ph_EE,ph_ER,ph_TOTAL, pagibig_EE,pagibig_ER,pagibig_TOTAL) VALUES ('$lastid','$mrate','$basicpay','$hrate','$gsisEE','$gsisER', '$gsisTOTAL', '$philhealthdeductEE','$philhealthdeductER','$philhealthdeductTOTAL', '$pagibigdeductEE','$pagibigdeductER','$pagibigdeductTOTAL')";
    $payrollinfoexecqry = mysqli_query($conn,$payrollinfoqry) or die ("FAILED TO ADD NEW PAY INFO ".mysqli_error($conn));
    
    $leaveinfoqry = "INSERT INTO leaves (emp_id, leave_count, leaves_year) VALUES ('$lastid', '$leaves', '$currentYear')";
    $leaveexecqry = mysqli_query($conn,$leaveinfoqry) or die ("FAILED TO ADD NEW PAY INFO ".mysqli_error($conn));

    $activityLog = "Added a new employee profile (ID: $lastid)";
    $adminActivityQuery = "INSERT INTO adminactivity_log (emp_id, activity,log_timestamp) VALUES ('$adminId', '$activityLog', NOW())";
    $adminActivityResult = mysqli_query($conn, $adminActivityQuery);
    
    if ($result) {
        for ($i = 1; $i <= $numberofchild; $i++) {
            $childname = $_POST["child" . $i . "name"];
            $childsql = "UPDATE employees SET child_" . $i . " = '$childname' WHERE emp_id = $lastid";
            
            if ($conn->query($childsql) !== TRUE) {
                echo "Error: " . $childsql . "<br>" . $conn->error;
            }
        }
    }
    
   
   ?>
   
   <script>
   document.addEventListener('DOMContentLoaded', function() {
       swal({
        //  title: "Good job!",
         text: "Data inserted successfully",
         icon: "success",
         button: "OK",
        }).then(function() {
           window.location.href = 'adminMasterfile.php'; // Replace 'your_new_page.php' with the actual URL
       });
   });
</script>
    <?php
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
      $( "#datepicker" ).datepicker({ dateFormat: 'yy-mm-dd'});
      } );
  $( function() {
      $( "#birthdate" ).datepicker({ 
        changeYear: true,
        yearRange: "1940:2023",
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
    <div id="breadcrumb"> 
      <a href="adminDASHBOARD.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a>
      <a href ="adminMasterfile.php" class="tip-bottom"><i class ="icon-file"></i> Employee Masterlist</a>
      <a href="#" class="tip-bottom"><i class = "icon-plus"></i>Add Profile</a>
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
            <div class="widget-box">
              <div class="widget-title"> <span class="icon"> <i class="icon-user"></i> </span>
                <h5>Personal Information</h5>
              </div>
              <div class="widget-content">
                <form action="adminADDprofile.php" method="POST" class="form-horizontal" enctype="multipart/form-data">
                  <div class="control-group">
                    <label class="control-label">Last Name :</label>
                      <div class="controls">
                        <input type="text" class="span11" placeholder="Last name" name="lastname" required/>
                        <!-- <span class ="label label-important"><?php echo $nameError; ?></span> -->
                      </div>         
                  </div>
                  <div class="control-group">
                    <label class="control-label">First Name :</label>
                      <div class="controls">
                        <input type="text" class="span11" placeholder="First name" name="firstname" required/>
                        <!-- <span class ="label label-important"><?php echo $nameError; ?></span> -->
                      </div>
                  </div>
                  <div class="control-group">
                    <label class="control-label">Middle Name</label>
                      <div class="controls">
                        <input type="text"  class="span11" placeholder="Middle name" name="middlename"/>
                        <!-- <span class ="label label-important"><?php echo $nameError; ?></span> -->
                      </div>
                  </div>
                  <div class="control-group">
                    <label class="control-label">Username:</label>
                      <div class="controls">
                        <input type="text" class="span11" placeholder="Username" name="username" required/>
                        <!-- <span class ="label label-important"><?php echo $usernameError; ?></span> -->
                      </div>
                  </div>

                  <div class ="control-group">
                    <label class="control-label">Date of Birth: </label>
                      <div class="controls">
                        <input type="text" class = "span3" id="birthdate" name ="dob" placeholder="Date of Birth" value="" required>       
                        <!-- <span class ="label labelmarital-important"><?php echo $birthdateerror; ?></span> -->
                      </div>
                  </div>
                  <div class="control-group">
                    <label class="control-label">Gender:</label>
                      <div class="controls">
                        <label>
                          <input type="radio" name="genderoption" value="Male" checked/>
                          Male</label>
                        <label>
                          <input type="radio" name="genderoption" value="Female"/>
                          Female</label>
                        
                          <!-- <span class ="label label-important"><?php echo $gendererror; ?></span> -->
                      </div>
                  </div>

                  <div class ="control-group">
                    <label class="control-label">Marital Status: </label>
                      <div class="controls">
                        <select name="maritaloption" required>
                          <option></option>
                          <option>Single</option>
                          <option>Married</option>
                          <option>Widowed</option>
                        </select>
                        <!-- <span class ="label label-important"><?php echo $maritalerror; ?></span> -->
                      </div>
                  </div>
                
                  <div class="control-group">
                    <label class="control-label">Name of spouse:</label>
                      <div class="controls">
                        <input type="text" class="span11" placeholder="Name of spouse" name="spousename"/>
                        <span class = "label"><small>*Fill-up only if married.*</small></span>
                         <!-- <span class ="label label-important"><?php echo $spouseerror; ?></span> -->
                      </div>
                  </div>


                  <div class="control-group">
                      <label class="control-label">Number of Children: </label>
                      <div class="controls">
                        <select class="span2" name="numberofchild" id="numberofchild" onchange="toggleChildFields()" required>
                          <option></option>
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

                  <!-- <div class="control-group">
                    <label class="control-label">Child 1:</label>
                      <div class="controls">
                        <input type="text" class="span11" placeholder="Child 1" name="child1name"/>
                         <<span class ="label label-important"><?php echo $child1error; ?></span> -->
                      <!-- </div> -->
                  <!-- </div>  -->

                  <!-- <div class="control-group">
                    <label class="control-label">Child 2:</label>
                      <div class="controls">
                        <input type="text" class="span11" placeholder="Child 2" name="child2name"/>
                         <span class ="label label-important"><?php echo $child2error; ?></span>
                      </div>
                  </div> -->

                  <!-- <div class="control-group">
                    <label class="control-label">Child 3:</label>
                      <div class="controls">
                        <input type="text" class="span11" placeholder="Child 3" name="child3name"/>
                         <span class ="label label-important"><?php echo $child3error; ?></span>
                      </div>
                  </div> -->

                  <!-- <div class="control-group">
                    <label class="control-label">Child 4:</label>
                      <div class="controls">
                        <input type="text" class="span11" placeholder="Child 4" name="child4name"/>
                         <span class ="label label-important"><?php echo $child4error; ?></span>
                      </div>
                  </div> -->

                  <div class="control-group">
                    <label class="control-label">Address:</label>
                      <div class="controls">
                        <input type="text" class="span11" placeholder="Address" name="address" required/>
                         <!-- <span class ="label label-important"><?php echo $addresserror; ?></span> -->
                      </div>
                  </div>

                  <div class="control-group">
                    <label class="control-label">Cellphone Number:</label>
                      <div class="controls">
                      <input type="text" class="span11" placeholder="Cellphone number" name="cellphonenumber" pattern="[0]{1}[9]{1}[0-9]{9}" required />
                        <!-- <span class ="label label-important"><?php echo $cellphoneerror; ?></span> -->
                      </div>
                  </div>

                  <div class="control-group">
                    <label class="control-label">Nationality:</label>
                      <div class="controls">
                        <input type="text" class="span6" placeholder="Nationality" name="nationality" required>
                      </div>
                  </div>

                  <div class="control-group">
                    <label class="control-label">GSIS ID No:</label>
                      <div class="controls">
                      <input type="text" class="span6" placeholder="GSIS ID Number" name="gsisidno" pattern="[EP]-\d{4}-\d{2}-\d{2}-\d{5}" title="Enter a valid GSIS ID (e.g., E-2022-01-01-12345)" required>
                        <!-- <span class ="label label-important"><?php echo $sssidnoerror; ?></span> -->
                      </div>
                  </div>

                  <!-- Philhealth Number -->
                  <div class="control-group">
                      <label class="control-label">Philhealth Number:</label>
                      <div class="controls">
                          <input type="text" class="span6" placeholder="Philhealth Number" name="philhealthnumber" pattern="[0-9]{12}" title="Please enter a valid 12-digit Philhealth Number" required/>
                      </div>
                  </div>

                  <!-- PAG-IBIG Number -->
                  <div class="control-group">
                      <label class="control-label">PAG-IBIG Number:</label>
                      <div class="controls">
                          <input type="text" class="span6" placeholder="PAG-IBIG Number" name="pagibignumber" pattern="[0-9]{12}" title="Please enter a valid 12-digit PAG-IBIG Number" required/>
                      </div>
                  </div>

                  <!-- TIN -->
                  <div class="control-group">
                      <label class="control-label">TIN:</label>
                      <div class="controls">
                          <input type="text" class="span6" placeholder="TIN" name="tin" pattern="[0-9]{9}" title="Please enter a valid 9-digit TIN" required/>
                      </div>
                  </div>
                  <br>

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
                                <input type="radio" name="acctoption" value="Administrator" />
                                Administrator
                            </label>

                            <label>
                                <input type="radio" name="acctoption" value="Employee" />
                                Employee
                            </label>

                            <label>
                                <input type="radio" name="acctoption" value="Master" checked/>
                                Master
                            </label>';
                    } else {
                        // Display only the Employee radio button
                        echo '
                            <label>
                                <input type="radio" name="acctoption" value="Employee" checked/>
                                Employee
                            </label>';
                    }
                    ?>


                        <!-- <span class ="label label-important"><?php echo $acctError; ?></span> -->
                    </div>
                </div>


                 
      <?php
      $departmentsquery = "SELECT * FROM DEPARTMENT";
      $departmentsexecqry = mysqli_query($conn, $departmentsquery) or die ("FAILED TO EXECUTE DEPT. QUERY ".mysql_error());
      ?>
                  <div class ="control-group">
                    <label class="control-label">Department: </label>
                      <div class="controls">
                        <select name="deptoption" required>
                          <option></option>
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
                        <select name ="shifttime" required>
                          <option></option>
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
                                  <option></option>
                                  <?php  while($emptypechoice = mysqli_fetch_array($emptypesexecqry)):;?>
                                  <option><?php echo $emptypechoice['employment_TYPE'];?></option>
                                  <?php endwhile; ?>
                                </select>
                              </div>
                  </div>
                  <?php
              $positionquery = "SELECT * FROM position";
              $positionexecqry = mysqli_query($conn, $positionquery) or die ("FAILED TO EXECUTE DEPT. QUERY ".mysql_error());
              ?>
                          
                          <div class ="control-group">
                            <label class="control-label">Position: </label>
                              <div class="controls">
                                <select name="position" id= "position" onchange="updateSalaryGrade()">
                                  <option></option>
                                  <?php  while($positionchoice = mysqli_fetch_array($positionexecqry)):;?>
                                  <option><?php echo $positionchoice['position_name'];?></option>
                                  <?php endwhile; ?>
                                </select>
                              </div>
                  </div>
                  <div class="control-group">
                      <label class="control-label">Salary Grade:</label>
                      <div class="controls">
                          <input type="text" id="salaryGrade" name="salaryGrade" readonly>
                      </div>
                  </div>

                  <div class="control-group">

                <label class="control-label">Daily Rate :</label>
                <div class="controls">
                  <input type="text" class="span5" name="dailyRate" id="dailyRate" required/>
                  <!-- <span class ="label label-important"><?php echo $baserateerror; ?></span> -->
                </div>
              </div> 
            


                  <div class ="control-group">
                    <label class="control-label">Date Hired: </label>
                      <div class="controls">
                        <input type="text" id="datepicker" name ="dphired" placeholder="Date Hired" value="" required>
                        <!-- <span class ="label label-important"><?php echo $datehirederror; ?></span> -->
                      </div>
                  </div>
        
                  <div class = "control-group">
                    <label class="control-label">Picture: </label>
                      <div class="controls">
                        <input type="file" name="image" required/>
                        <!-- <span class ="label label-important"><?php echo $imgerror; ?></span> -->
                      </div>
                  </div>

                   <div class="control-group">
                    <label class="control-label">Employee Status:</label>
                      <div class="controls">
                        <label>
                          <input type="radio" name="empstatusoption" value="Active" checked />
                          Active</label>
                        
                        <label>
                          <input type="radio" name="empstatusoption" value="Inactive"/>
                          Inactive</label>
                          <!-- <span class ="label label-important"><?php echo $empstatusError; ?></span><br> -->
                      </div>
                  </div>

                  <!-- <div class ="control-group">
                    <label class="control-label">Fingerprint: </label>
                      <div class="controls">
                        <input type ="password" placeholder ="fingerprint" name="fingerprint" class="span5" maxLength="20"/>
                         <span class ="label label-important"><?php echo $fingerprintError; ?></span> -->
                      <!-- </div>
                  </div>  -->
            

                  <div class="form-actions">
                    <button type="submit" class="btn btn-success" name = "submit_btn" style="float:right;">Submit</button>
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

        var controlGroup = document.createElement("div");
        controlGroup.class = "control-group";
        controlGroup.appendChild(label);
        controlGroup.appendChild(document.createElement("br"));
        controlGroup.appendChild(input);

        childFieldsContainer.appendChild(controlGroup);
      }
    }
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

