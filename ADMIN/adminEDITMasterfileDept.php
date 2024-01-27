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
<?php
include("../DBCONFIG.PHP");
include("../LoginControl.php");
include("../BASICLOGININFO.PHP");

session_start();

$adminId = $_SESSION['adminId'];

$_SESSION['editdeptid'] = $_GET['id'];
$idres = $_GET['id'];
$editdeptid = $_SESSION['editdeptid'];
$DELquery = "SELECT * from DEPARTMENT WHERE dept_NAME ='$idres'";
$DELselresult = mysqli_query($conn,$DELquery) or die ("Failed to search DB. ".mysql_error());
  $DELcurr = mysqli_fetch_array($DELselresult);
  $DELcount = mysqli_num_rows($DELselresult);

   if($DELcount!=0 && $DELcurr) {

        $currprefixid = $DELcurr['dept_prefix_ID'];
        $currdeptid = $DELcurr['dept_ID'];
        $currdeptname = $DELcurr['dept_NAME'];
       

    }
    else {
          $updateselecterror ="Department information not found.";
          }/*2nd else end*/

$error = false;

if(isset($_POST['submit_btn'])){

  $deptname = $_POST['deptname'];

  if(empty($deptname)){

    $error = true;
    $deptnameerror = "Please enter a department name.";

  }

  $deptnamequery = "SELECT dept_NAME FROM DEPARTMENT where dept_NAME = '$deptname'";
  $deptnameresultqry = mysqli_query($conn,$deptnamequery);
  $deptnamecount = mysqli_num_rows($deptnameresultqry);

  if ($deptnamecount !=0){
    $error = true;
    $deptnameerror = "Department already exists.";
  }

  if (!$error){

    $updateEmployeeQuery = "UPDATE employees SET dept_NAME = '$deptname' WHERE dept_NAME = '$currdeptname'";
    $updateEmployeeResult = mysqli_query($conn, $updateEmployeeQuery) or die("FAILED TO UPDATE EMPLOYEES: " . mysqli_error($conn));
    
    $newdeptqry = "UPDATE DEPARTMENT SET dept_NAME = '$deptname' WHERE dept_ID = '$currdeptid'";
    $newdeptqryresult = mysqli_query($conn,$newdeptqry) or die ("FAILED TO CREATE NEW DEPARTMENT ".mysql_error());
    $activityLog = "Edited department from $currdeptname to $deptname";
    $adminActivityQuery = "INSERT INTO adminactivity_log (emp_id, activity,log_timestamp) VALUES ('$adminId', '$activityLog', NOW())";
    $adminActivityResult = mysqli_query($conn, $adminActivityQuery);

    if($newdeptqryresult){

      ?>
      <script>
 document.addEventListener('DOMContentLoaded', function() {
     swal({
      //  title: "Good job!",
       text: "Department updated successfully",
       icon: "success",
       button: "OK",
      }).then(function() {
           window.location.href = 'adminMasterfileDept.php'; // Replace 'your_new_page.php' with the actual URL
       });
   });
</script>
      <?php
  }
} else {
  ?>
  <script>
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





<body>

<!--Header-part-->

<?php
INCLUDE ('NAVBAR.php');
?>


<div id="content">

  <div id="content-header">
    <div id="breadcrumb"> <a href="index.html" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a>
      <a href ="adminMasterfileDept.php" class="tip-bottom"><i class ="icon-th"></i> Manage Departments</a>
      <a href="#" class="tip-bottom"><i class = "icon-eye-open"></i>View Department</a>
    </div>
  </div>

  <div class="container-fluid">
    <div class = "row-fluid">
      <span class="span3">
      </span>
    <div class="span6">
      <h3>Update Department</h3>
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
            <h5>Department Information</h5>
          </div>

          <div class="widget-content nopadding">
            <form action="adminEDITMasterfileDept.php?id=<?php echo $idres;?>" method="POST" class="form-horizontal">
              <div class="control-group">
                <label class="control-label">Department ID :</label>
                <div class="controls">
                  <input type="text" class="span7" value = "<?php echo $currprefixid; echo $currdeptid;?>" name="deptname" readonly/>
                  <!-- <span class ="label label-important"><?php echo $deptnameerror; ?></span> -->
                </div>
              </div> 

              <div class="control-group">
                <label class="control-label">Department Name :</label>
                <div class="controls">
                  <input type="text" class="span7" value="<?php echo $currdeptname;?>" name="deptname"/>
                  <!-- <span class ="label label-important"><?php echo $deptnameerror; ?></span> -->
                </div>
              </div>

              <div class="form-actions">
                <button type="submit" class="btn btn-success" name = "submit_btn" style="float:right;">Update</button>
              </div>
            </form>
        </div>
    </div>
    </div>
    <div class="row-fluid">
      <div class="span12">
        <div class="widget-box">
          <div class="widget-title">
            <ul class="nav nav-tabs" id="myTab">
              <li class="active"><a href="#tab1"><i class="icon-th"></i> <?php echo $currdeptname;?></a></li>
            </ul>
          </div>
          <div class="widget-content tab-content">
          <div id="tab1" class="tab-pane fade in active">
             <h5> Employees in <?php echo $currdeptname;?> Department</h5>
               <br>
               <br>
               <table class="table table-bordered data-table">
               <thead>
                <tr>
                  <th>Employee ID</th>
                  <th>Name</th>
                  <th>Department</th>
                  <th>Shift</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody> 
              <?php

$results_perpageDEPT = 20;

if (isset($_GET['page'])){

     $pageDEPT = $_GET['page'];
} else {

     $pageDEPT=1;
}

$start_fromDEPT = ($pageDEPT-1) * $results_perpageDEPT;
$searchqueryDEPT ="SELECT * FROM employees WHERE dept_NAME = '$editdeptid' ORDER BY emp_id ASC LIMIT $start_fromDEPT,".$results_perpageDEPT;

$searchresultDEPT= filterTableDEPT($searchqueryDEPT);

function filterTableDEPT($searchqueryDEPT)
{

     $connDEPT = mysqli_connect("localhost:3307","root","","masterdb");
     $filter_ResultDEPT = mysqli_query($connDEPT,$searchqueryDEPT) or die ("failed to query masterfile ".mysql_error());
     return $filter_ResultDEPT;
}

$countdataqryDEPT = "SELECT COUNT(dept_NAME = '$editdeptid') AS total FROM employees";
$countdataqryresultDEPT = mysqli_query($conn,$countdataqryDEPT) or die ("FAILED TO EXECUTE COUNT QUERY ". mysql_error());
$rowDEPT = $countdataqryresultDEPT->fetch_assoc();
$totalpagesDEPT=ceil($rowDEPT['total'] / $results_perpageDEPT);
while($row1DEPT = mysqli_fetch_array($searchresultDEPT)):;
?>
   <tr class="gradeX">
   <td><?php echo $row1DEPT['prefix_ID'],$row1DEPT['emp_id'];?></td>
   <td><?php echo $row1DEPT['last_name'];?>, <?php echo $row1DEPT['first_name'];?> <?php echo $row1DEPT['middle_name'];?></td>
   <td><?php echo $row1DEPT['dept_NAME'];?></td>
   <td><?php echo $row1DEPT['shift_SCHEDULE'];?></td>
   <td><center><a href = "adminEDITMasterfile.php?id=<?php echo $row1DEPT['emp_id']?>" class = "btn btn-primary btn-mini"><span class="icon"><i class="icon-edit"></i></span> Assign to another department</a></center></td>
  
   
     
 </tr>
<?php endwhile;?>
             
              
              </tbody>
            </table>
               <div class = "pagination alternate" style="float:right;">
               <ul>
               <!-- <?php

                    for ($iDEPT=1; $iDEPT<=$totalpagesDEPT; $iDEPT++){
                         echo "<li><a href='adminEDITMasterfileDept.php?page=".$iDEPT."'";
                         if ($iDEPT==$pageDEPT) echo " class='curPage'";
                              echo ">".$iDEPT."</a></li> ";
                         };
               ?> -->
               </ul>
               </div>

                  </div>
            </div>
        </div>
      </div>
      


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

</body>
</html>
