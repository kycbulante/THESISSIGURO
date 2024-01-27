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

$results_perpage = 20;

               if (isset($_GET['page'])){

                    $page = $_GET['page'];
               } else {

                    $page=1;
               }

$currentempid = $_SESSION['empID'];

$userIdpage  = $_SESSION['empID'];

$pageViewed = basename($_SERVER['PHP_SELF']);
$pageInfo = pathinfo($pageViewed);

// Get the filename without extension
$pageViewed1 = $pageInfo['filename'];



// Log the page view
logPageView($conn, $userIdpage, $pageViewed1);

// Pagination setup
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$recordsPerPage = 10; // You can adjust this number based on your preference

$startFrom = ($page - 1) * $recordsPerPage;


  $searchquery ="SELECT * FROM empactivity_log WHERE emp_id = '$currentempid' LIMIT $startFrom, $recordsPerPage";
  $search_result = filterTable($searchquery);
  
  $countQuery = "SELECT COUNT(*) AS total FROM empactivity_log where emp_id = '$currentempid'";
  $countResult = mysqli_query($conn,$countQuery) or die ("FAILED TO EXECUTE COUNT QUERY ". mysql_error());      
  $countRow = mysqli_fetch_assoc($countResult);
  $totalRecords = $countRow['total'];


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
    <div id="breadcrumb"> <a href="empDASHBOARD.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a>
      <a href ="adminMasterfile.php" class="tip-bottom"><i class ="icon-calendar"></i> Attendance Records</a></div>
  </div>
  

  <div class="container-fluid">
    <div class ="row-fluid">
      <div class = "span10">
        <h3>Employee Records</h3>        
      </div>
    </div>
   
    <div class ="row-fluid">
     <div class="span12">
        <div class="widget-box">
          <div class="widget-title">
            <ul class="nav nav-tabs" id="myTab">
              <li><a href="empDASHBOARD.php"><i class="icon-user"></i> Profile</a></li>
              <li><a href="empAPPLYOvertime.php"><i class="icon-time"></i> Overtime</a></li>
              <li><a href="empAPPLYLeave.php"><i class="icon-calendar"></i> Leave</a></li>
              <li><a href="empATTENDANCErecords.php"><i class="icon-th"></i> My Records</a></li>
              <li class="active"><a href="empActivitylogs.php"><i class="icon-time"></i> Activity Logs</a></li>
              <li class=""><a href="empLoans.php"><i class="icon-file"></i> Loans</a></li>


              
            </ul>
            </form>
                
              
            </div>
            <div class="table-responsive">
               <table class="table table-bordered data-table">
               <thead>
                <tr>
                  <th>Log ID</th>
                  <th>Employee ID</th>
                  <th>Activity</th>
                  <th>Timestamp</th>
         
                  
                </tr>
              </thead>
              <tbody>
          </div>
     </div>
          <div class="widget-content tab-content">
          <div id="tab1" class="tab-pane fade in active"><!--EMPLOYEE TAB--><!--EMPLOYEE TAB--><!--EMPLOYEE TAB--><!--EMPLOYEE TAB--><!--EMPLOYEE TAB--><!--EMPLOYEE TAB--><!--EMPLOYEE TAB--><!--EMPLOYEE TAB-->
               
                
            </div>
                </div>
            

                


                  
           

                
                  <style>
            .widget-box {
  border-radius: 10px; /* You can adjust the value to control the amount of rounding */
  border: 1px solid #ccc; /* Optional: You can add a border for further styling */
  padding: 15px; /* Optional: Add padding to the box for better appearance */
}
@media (max-width: 768px) {
  .widget-box{
    margin-top:70px;
  }
  .table {
    margin: auto;
    margin-top: auto; /* This will center the widget-box */
  }

  .tab-pane {
    /* Adjust the styles for list items inside widget-title at smaller screens */
    display: block;
    margin-bottom: 10px;
  }

  .active {
    /* Adjust the styles for the active class when the screen width is 768px or less */
    
  }
}  
          </style>
 

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
                  <td><?php echo $row1['log_id'];?></td>
                  <td><?php echo $row1['emp_id'];?></td>
                  <td><?php echo $row1['activity'];?></td>
                  <td><?php echo $row1['log_timestamp'];?></td>
                </tr>
              <?php endwhile;?>
              </tbody>
            </table>
            <div class = "represh">
                  <a href ="empactivitylogs.php" class = "btn btn-success" style = "float:center; margin-left: 4px;"><span class="icon"><i class="icon-refresh"></i></span> Refresh</a>
                  <!-- <small><?php echo $attrecordview; ?></small> -->
                </div>
            <div class="pagination">
              
    <?php
    $totalPages = ceil($totalRecords / $recordsPerPage);
    
    $currentPage = isset($_GET['page']) ? max(1, min($_GET['page'], $totalPages)) : 1;
    $pageRange = 2; 

    
    function generatePageLink($pageNumber, $text = null) {
        $text = $text ?? $pageNumber; 
        $url = "empActivitylogs.php?page=$pageNumber";
        return "<a href='$url'>$text</a>";
    }

    
    if ($currentPage - $pageRange > 2) {
        echo generatePageLink(1) . " ... ";
    }

   
    for ($i = max(1, $currentPage - $pageRange); $i <= min($totalPages, $currentPage + $pageRange); $i++) {
        echo generatePageLink($i);

        if ($i < min($totalPages, $currentPage + $pageRange)) {
            echo "";
        }
    }

    if ($currentPage + $pageRange < $totalPages - 1) {
        echo " ... " . generatePageLink($totalPages);
    }
    ?>
    
</div>

<style>
    .pagination {
        display: flex;
        justify-content: left;
        margin-top: 20px;
        font-family: 'Arial', sans-serif;
    }

    .pagination a, .pagination span {
        padding: 10px;
        margin: 0 5px;
        text-decoration: none;
        color: #fff;
        background-color: #49cced;
        border: 1px solid #fff;
        border-radius: 4px;
        transition: background-color 0.3s ease, color 0.3s ease;
    }

    .pagination a:hover {
        background-color: #0056b3;
        color: #fff;
    }

    .pagination a.active {
        background-color: #0056b3;
        color: #fff;
        cursor: default;
    }

    .pagination span.ellipsis {
        margin: 0 5px;
        color: #555;
    }
</style>


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

