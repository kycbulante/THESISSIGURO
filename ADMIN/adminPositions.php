
<!DOCTYPE html>
<html lang="en">
<head>
<title>Manage Positions</title>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<link rel="stylesheet" href="../css/bootstrap.min.css" />
<link rel="stylesheet" href="../css/bootstrap-responsive.min.css" />
<link rel="stylesheet" href="../css/fullcalendar.css" />
<link rel="stylesheet" href="../css/maruti-style.css" />
<link rel="stylesheet" href="../css/maruti-media.css" class="skin-color" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker@3.1.0/daterangepicker.css" />
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker@3.1.0/daterangepicker.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<?php
include("../DBCONFIG.PHP");
include("../LoginControl.php");
include("../BASICLOGININFO.PHP");

// session_start();

// if(isset($_SESSION['masterfilenotif'])){

// $mfnotif = $_SESSION['masterfilenotif'];
// 
$master = $_SESSION['master'];
?>  
<script>
// alert("<?php echo $mfnotif;?>");
// </script>
<?php
// }

// -->
if (isset($_POST['addPosition'])) {
    $positionName = $_POST['positionName'];
    $salaryGrade = $_POST['salaryGrade'];

    // Perform the database insertion
    $insertQuery = "INSERT INTO position (position_name, salarygrade) VALUES ('$positionName', '$salaryGrade')";
    $insertResult = mysqli_query($conn, $insertQuery);

    if ($insertResult) {
        ?>
   
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            swal({
             //  title: "Good job!",
              text: "Position inserted successfully",
              icon: "success",
              button: "OK",
             }).then(function() {
                window.location.href = 'adminPositions.php'; // Replace 'your_new_page.php' with the actual URL
            });
        });
     </script>
         <?php
    } else {
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

    // Prevent further execution to avoid duplicate alerts
    exit();
}

?> 



</head>

<body>

<!--Header-part-->

<?php
INCLUDE ('NAVBAR.php');
?>

<div id="content">

<div id="content-header">
  <div id="breadcrumb"> <a href="index.html" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a></div>
</div>
<span class="span6">
      <h3 style="margin-top: 30px">Manage Positions</h3>
    </span>
   
<div class="container-fluid">
  <div class = "row-fluid">
  <div class ="row-fluid">
     <div class="span12">
        <div class="widget-box">
          <div class="widget-title">
            <ul class="nav nav-tabs" id="myTab">
              <li><a href="adminMasterfile.php"><i class="icon-user"></i> Employees</a></li>
              <li><a href="adminMasterfileDept.php"><i class="icon-th"></i> Manage Departments</a></li>
              <li><a href="adminMasterfileShift.php"><i class="icon-time"></i> Manage Shifts</a></li>
              <li><a href="adminMasterfileHoliday.php"><i class="icon-calendar"></i> Manage Holidays</a></li>
              <li><a href="adminMasterfileLeaves.php"><i class="icon-calendar"></i> Manage Leaves</a></li>
              <li><a href="adminPAYROLLPERIODS.php"><i class="icon-user"></i> Manage Payroll Periods</a></li>
              <?php
            if ($master) {
                echo '
                    <li class="active"><a href="adminPositions.php"><i class="icon-th"></i> Manage Positions</a></li>
                    <li><a href="adminSalaryGrades.php"><i class="icon-th"></i> Manage Salary Grades</a></li>
                ';
            }
            ?>
            </ul>
          </div>
          </div>
      </div>
  </div>
          
          <div class = "row-fluid">
               <table class="table table-bordered data-table">
               <thead>
                <tr>

                  <th>Position</th>
                  <th>Salary Grade</th>
                  <th>Monthly Salary</th>
                </tr>
              </thead>
              <tbody> 
                <?php
                $searchquery ="SELECT * FROM position
                JOIN salarygrade ON position.salarygrade = salarygrade.salarygrade";                
                $searchresult= filterTable($searchquery);

               function filterTable($searchquery)
               {

                    $conn = mysqli_connect("localhost:3307","root","","masterdb");
                    $filter_Result = mysqli_query($conn,$searchquery) or die ("failed to query Holidays".mysql_error());
                    return $filter_Result;
               }while($row1 = mysqli_fetch_array($searchresult)):;
               ?>
                  <tr class="gradeX">
                  <td><?php echo $row1['position_name'];?></td>
                  <td><?php echo $row1['salarygrade'];?></td>
                  <td><?php echo $row1['monthlysalary'];?></td>

                 
</tr>
              <?php endwhile;?>
              </tbody>
            </table>
              
               </div>
                            <div id="tab1" class="tab-pane fade in active">
                    <a href="adminMasterfileHoliday.php" class="btn btn-success" style="float:right; margin-left: 4px;">
                        <span class="icon"><i class="icon-refresh"></i></span> Refresh
                    </a>
                    <br>
                    <br>
                    <form method="post" action="" class="form-horizontal">
                        <div class="control-group">
                            <label class="control-label" for="positionName">Position:</label>
                            <div class="controls">
                                <input type="text" name="positionName" id="positionName" required>
                            </div>
                        </div>
                        <div class="control-group">
                        <label class="control-label" for="salaryGrade">Salary Grade:</label>
                        <div class="controls">
                            <select name="salaryGrade" id="salaryGrade" required>
                                <?php
                                $conn = mysqli_connect("localhost:3307", "root", "", "masterdb");

                                // Query to fetch salary grades from the table
                                $query = "SELECT salarygrade FROM salarygrade";
                                $result = mysqli_query($conn, $query);

                                // Check if there are rows in the result set
                                if ($result && mysqli_num_rows($result) > 0) {
                                    // Loop through the result set and create options for the dropdown
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo "<option value='{$row['salarygrade']}'>{$row['salarygrade']}</option>";
                                    }
                                } else {
                                    // If no salary grades found, display a default option
                                    echo "<option value='' disabled>No salary grades available</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label" for="monthlySalary">Monthly Salary:</label>
                        <div class="controls">
                            <input type="text" name="monthlySalary" id="monthlySalary" readonly required>
                        </div>
                    </div>
                        <div class="control-group">
                            <div class="controls">
                                <button type="submit" class="btn btn-primary" name="addPosition">Add Position</button>
                            </div>
                        </div>
                    </form>
                    <br>

                  </div>
                </div>
                
          
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Add this script to your HTML file -->
<script>
$(document).ready(function() {
    // Function to update the monthly salary based on the selected salary grade
    function updateMonthlySalary() {
        // Get the selected salary grade from the dropdown
        var selectedSalaryGrade = $('#salaryGrade').val();

        // Use AJAX to fetch the corresponding monthly salary from the server
        $.get('get_monthly_salary.php', { salaryGrade: selectedSalaryGrade }, function(response) {
            // Update the monthlySalary input field with the fetched value
            $('#monthlySalary').val(response);
        })
        .fail(function() {
            console.error('Error fetching monthly salary');
        });
    }

    // Trigger the update on page load
    updateMonthlySalary();
});
</script>

<script>
    $(document).ready(function () {
        // Add change event listener to the salaryGrade dropdown
        $('#salaryGrade').change(function () {
            // Get the selected value
            var selectedSalaryGrade = $(this).val();

            // Make an AJAX request to fetch the corresponding monthly salary
            $.get('get_monthly_salary.php', { salaryGrade: selectedSalaryGrade }, function (response) {
                // Update the monthlySalary input field with the fetched salary
                $('#monthlySalary').val(response);
            })
            .fail(function () {
                console.error('Error fetching monthly salary');
            });
        });
    });
</script>
     

<?php
// unset($_SESSION['masterfilenotif']);
?>

<div class="row-fluid">
<div id="footer" class="span12"> 2023 &copy; WEB-BASED TIMEKEEPING AND PAYROLL SYSTEM USING FINGERPRINT BIOMETRICS</div>
</div>

<script src="../js/maruti.dashboard.js"></script> 

</html>

