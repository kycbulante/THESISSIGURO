<?php
include("../../DBCONFIG.PHP");
include("../../LoginControl.php");
include("BASICLOGININFO.PHP");

session_start();

$results_perpage = 20;

               if (isset($_GET['page'])){

                    $page = $_GET['page'];
               } else {

                    $page=1;
               }


// $searchby = $_POST['searchoption'];
$adminId = $_SESSION['adminId'];
if(isset($_POST['search_btn'])){
  // $searchby = $_POST['searchoption'];
  $searchby = isset($_POST['searchoption']) ? $_POST['searchoption'] : '';

  switch ($searchby) {

    case "Employee ID":

        $start_from = ($page-1) * $results_perpage;
        $searchvalue = $_POST['searchvalue'];
        $searchquery = "SELECT LEAVES_APPLICATION.*, employees.* from employees, LEAVES_APPLICATION  WHERE employees.emp_id LIKE '%$searchvalue%' AND LEAVES_APPLICATION.leave_status = 'Approved' AND employees.emp_id = LEAVES_APPLICATION.emp_id ORDER BY LEAVES_APPLICATION.emp_id DESC LIMIT $start_from,".$results_perpage;  
        $search_result = filterTable($searchquery);
        /*$auditinfo = "Search profile by Employee ID";

           $auditquery = "INSERT INTO audittrail (emp_id, audit_info) VALUES ('$empid','$auditinfo')";
            $auditresult = mysqli_query($conn,$auditquery) or die(mysql_error()); */
    
    break;

    case "Last Name":

        $start_from = ($page-1) * $results_perpage;
        $searchvalue = $_POST['searchvalue'];
        $searchquery = "SELECT LEAVES_APPLICATION.*, employees.* from employees, LEAVES_APPLICATION  WHERE employees.last_name LIKE '%$searchvalue%' AND LEAVES_APPLICATION.leave_status = 'Approved' AND employees.emp_id = LEAVES_APPLICATION.emp_id ORDER BY LEAVES_APPLICATION.emp_id DESC LIMIT $start_from,".$results_perpage;  
        $search_result = filterTable($searchquery);
        /*$auditinfo = "Search profile by Last name";

           $auditquery = "INSERT INTO audittrail (emp_id, audit_info) VALUES ('$empid','$auditinfo')";
            $auditresult = mysqli_query($conn,$auditquery) or die(mysql_error()); */

    break;

     case "First Name":

        $start_from = ($page-1) * $results_perpage;
        $searchvalue = $_POST['searchvalue'];
        $searchquery = "SELECT LEAVES_APPLICATION.*, employees.* from employees, LEAVES_APPLICATION  WHERE employees.first_name LIKE '%$searchvalue%' AND LEAVES_APPLICATION.leave_status = 'Approved' AND employees.emp_id = LEAVES_APPLICATION.emp_id ORDER BY LEAVES_APPLICATION.emp_id DESC LIMIT $start_from,".$results_perpage;  
        $search_result = filterTable($searchquery);
       /* $auditinfo = "Search profile by First name";

           $auditquery = "INSERT INTO audittrail (emp_id, audit_info) VALUES ('$empid','$auditinfo')";
            $auditresult = mysqli_query($conn,$auditquery) or die(mysql_error()); */
     

    break;

    case "Username":

        $start_from = ($page-1) * $results_perpage;
        $searchvalue = $_POST['searchvalue'];
        $searchquery = "SELECT LEAVES_APPLICATION.*, employees.* from employees, LEAVES_APPLICATION  WHERE employees.user_name LIKE '%$searchvalue%' AND LEAVES_APPLICATION.leave_status = 'Approved' AND employees.emp_id = LEAVES_APPLICATION.emp_id ORDER BY LEAVES_APPLICATION.emp_id DESC LIMIT $start_from,".$results_perpage;  
        $search_result = filterTable($searchquery);
        /*$auditinfo = "Search profile by Username";

           $auditquery = "INSERT INTO audittrail (emp_id, audit_info) VALUES ('$empid','$auditinfo')";
            $auditresult = mysqli_query($conn,$auditquery) or die(mysql_error()); */
     

    break;

    case "";

        $searchvalue = $_POST['searchvalue'];
        $searchquery = "SELECT LEAVES_APPLICATION.*, employees.* from employees, LEAVES_APPLICATION  WHERE LEAVES_APPLICATION.leave_status = 'Approved' AND employees.emp_id = LEAVES_APPLICATION.emp_id ORDER BY LEAVES_APPLICATION.emp_id";  
        $search_result = filterTable($searchquery);
        $searchError = "Please select a search criteria.";
    break;

    default:
        $searchvalue = $_POST['searchvalue'];
        $searchquery = "SELECT LEAVES_APPLICATION.*, employees.* from employees, LEAVES_APPLICATION  WHERE LEAVES_APPLICATION.leave_status = 'Approved' AND employees.emp_id = LEAVES_APPLICATION.emp_id ORDER BY LEAVES_APPLICATION.emp_id DESC LIMIT $start_from,".$results_perpage;  
        $search_result = filterTable($searchquery);
        $searchError = "No matching search results.";
        
        
    }
    } else  {
  $start_from = ($page-1) * $results_perpage;
  $searchquery = "SELECT LEAVES_APPLICATION.*, employees.* from employees, LEAVES_APPLICATION  WHERE LEAVES_APPLICATION.leave_status = 'Approved' AND employees.emp_id = LEAVES_APPLICATION.emp_id ORDER BY LEAVES_APPLICATION.emp_id DESC LIMIT $start_from,".$results_perpage;  
  $search_result = filterTable($searchquery);
  
  /*$searchquery2 = "SELECT TIME_KEEPING.*, employees.prefix_ID, employees.last_name, employees.first_name, employees.middle_name from employees, TIME_KEEPING  WHERE employees.emp_id = TIME_KEEPING.emp_id ORDER BY TIME_KEEPING.in_afternoon DESC";
  $search_result2 = filterTable2($searchquery2);*/



}

if (isset($_POST['searchbydate_btn'])){

  $start_from = ($page-1) * $results_perpage;
  $datesearch = $_POST['dphired'];
  $searchquery = "SELECT LEAVES_APPLICATION.*, employees.* from employees, LEAVES_APPLICATION  WHERE LEAVES_APPLICATION.leave_datestart = '$datesearch' AND LEAVES_APPLICATION.leave_status = 'Approved' AND employees.emp_id = LEAVES_APPLICATION.emp_id ORDER BY LEAVES_APPLICATION.emp_id DESC LIMIT $start_from,".$results_perpage;  
  $search_result = filterTable($searchquery);

}

$countdataqry = "SELECT COUNT(emp_id) AS total FROM LEAVES_APPLICATION WHERE leave_status = 'Approved'";
$countdataqryresult = mysqli_query($conn,$countdataqry) or die ("FAILED TO EXECUTE COUNT QUERY ". mysql_error());      
$row = $countdataqryresult->fetch_assoc();
$totalpages=ceil($row['total'] / $results_perpage);

 
?>


<!DOCTYPE html>
<html lang="en">
<head>
<title>Leaves</title>
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
<script type ="text/javascript">
  $( function() {
      $( "#datepicker" ).datepicker({ dateFormat: 'yy-mm-dd'});
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
    <div id="breadcrumb"> <a href="../adminDASHBOARD.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a>
      <a href ="adminLEAVES.php" class="tip-bottom"><i class ="icon-calendar"></i> Leaves</a></div>
  </div>

  <div class="container-fluid">
    <div class ="row-fluid">
      <div class = "span10">
        <h3>Leaves</h3>        
      </div>
    </div>

    <div class="row-fluid">
    <div class="span12">
    <div class="widget-box">
    <div class="widget-title">
    <ul class="nav nav-tabs" id="myTab">
    <li class=""><a href="../adminATTENDANCErecords.php"><i class="icon-calendar"></i> Records</a></li>
    <li class=""><a href="../OVERTIME/adminOT.php"><i class="icon-time"></i> Overtime</a></li>
    <li class="active"><a href="../LEAVES/adminLeaves.php"><i class="icon-calendar"></i> Leaves</a></li>
            </ul>
          </div>
   
<div class ="row-fluid">
     <div class="span12">
        <div class="widget-box">
          <div class="widget-title">
            <ul class="nav nav-tabs" id="myTab">
              <li><a href="adminLEAVES.php"><i class="icon-calendar"></i> For Approval</a></li>
              <li  class="active"><a href="adminLEAVESApproved.php"><i class="icon-calendar"></i> Approved</a></li>
              <li><a href="adminLEAVESRejected.php"><i class="icon-calendar"></i> Rejected</a></li>
            </ul>
          </div>
          <div class="widget-content tab-content">
          <div id="tab1" class="tab-pane fade in active"><!--EMPLOYEE TAB--><!--EMPLOYEE TAB--><!--EMPLOYEE TAB--><!--EMPLOYEE TAB--><!--EMPLOYEE TAB--><!--EMPLOYEE TAB--><!--EMPLOYEE TAB--><!--EMPLOYEE TAB-->
               
              <div class = "row-fluid">
                <div class = "span2">
                  <div id ="search">
                    <form action="adminLeavesApproved.php" method = "post">
                      <input type="text" placeholder="Search" name="searchvalue"/><button type="submit" class="btn btn-primary" name="search_btn" title="Search"><i class="icon-search icon-white"></i></button> 
                  </div>
                </div>

                <div class="span1">
                    <h4><small>Search by:</small></h4>
                </div>

                <div class ="span1">
                  <div class="radio">
                        <label class="radio-inline" for="eiradio"><input type="radio" name="searchoption" id="eiradio" value="Employee ID">Emp ID</label> 
                        <label for="lnradio" class="radio-inline" ><input type="radio" name="searchoption" id="lnradio" value="Last Name">Last Name </label> 
                  </div>
                </div>

                <div class = "span1">
                  <div class="radio">
                    <label for="unradio" class="radio-inline" ><input type="radio" name="searchoption" id="unradio" value="Username">Username </label>
                    <label for="fnradio" class="radio-inline" ><input type="radio" name="searchoption" id="fnradio" value="First Name">First Name </label>
                  </div>
                </div>

                <div class = "span2">


                  <div class ="control-group">
                    <label class="control-label">Search by date: </label>
                      <div class="controls">
                        <div id = "search">
                        <input class ="span8" type="text" id="datepicker" name ="dphired" placeholder="Date" value=""><button type="submit" class = "btn btn-primary" name ="searchbydate_btn"><i class ="icon-search icon-white"></i></button>
                        </div>
                        <span class ="label label-important"></span>
                      </div>
                  </div>

                </div>
                  </form>
                <div class = "span5">
                  <a href ="adminLeavesApproved.php" class = "btn btn-success" style = "float:right; margin-left: 4px;"><span class="icon"><i class="icon-refresh"></i></span> Refresh</a>
                  <small></small>
                </div>
              
            </div>

               <table class="table table-bordered data-table">
               <thead>
                <tr>
                  <th>Employee ID</th>
                  <th>Last Name</th>
                  <th>First Name</th>
                  <th>Middle Name</th>
                  <th>Shift</th>
                  <th>Leave Type</th>
                  <th>Leave Day</th>
                  <th>Remarks</th> 
                  <th>Action</th>               
                  
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
               ?>
                  <tr class="gradeX">
                  
                  <td><a href = "../adminVIEWprofile.php?id=<?php echo $row1['emp_id']; ?>"><?php echo $row1['prefix_ID'];?><?php echo $row1['emp_id'];?></a></td>
                  <td><?php echo $row1['last_name'];?></td>
                  <td><?php echo $row1['first_name'];?></td>
                  <td><?php echo $row1['middle_name']; ?></td>
                  <td><?php echo $row1['shift_SCHEDULE'];?></td>
                  <td><?php echo $row1['leave_type'];?></td>
                  <td><?php echo $row1['leave_datestart'];?></td>
                  <td><?php echo $row1['leave_status'];?></a></td>
                  <td><center><a href="LEAVEApproval.php?id=<?php echo $row1['la_id'];?>" class = "btn btn-info btn-mini"><span class="icon"><i class="icon-edit"></i></span> Review</a></center></td>
                  
                </tr>
              <?php endwhile;?>
              </tbody>
            </table>
               <div class = "pagination alternate" style="float:right;">
               <ul>
               <?php

                    for ($i=1; $i<=$totalpages; $i++){
                         echo "<li><a href='adminMasterfile.php?page=".$i."'";
                         if ($i==$page) echo " class='curPage'";
                              echo ">".$i."</a></li> ";
                         };
               ?>
               </ul>
               </div>
          </div><!--EMPLOYEE TAB--><!--EMPLOYEE TAB--><!--EMPLOYEE TAB--><!--EMPLOYEE TAB--><!--EMPLOYEE TAB--><!--EMPLOYEE TAB--><!--EMPLOYEE TAB--><!--EMPLOYEE TAB-->
          </div>
          
        </div>
      </div>
    </div>
  </div>
</div>
<?php
unset($_SESSION['OTAPPROVAL']);
?>



<div class="row-fluid">
<div id="footer" class="span12"> 2023 &copy; WEB-BASED TIMEKEEPING AND PAYROLL SYSTEM USING FINGERPRINT BIOMETRICS</div>
</div>

<script src="../../js/maruti.dashboard.js"></script> 
<script src="../../js/excanvas.min.js"></script> 

<script src="../../js/bootstrap.min.js"></script> 
<script src="../../js/jquery.flot.min.js"></script> 
<script src="../../js/jquery.flot.resize.min.js"></script> 
<script src="../../js/jquery.peity.min.js"></script> 
<script src="../../js/fullcalendar.min.js"></script> 
<script src="../../js/maruti.js"></script> 
</body>
</html>

