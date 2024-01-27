<?php
include("../DBCONFIG.PHP");
include("../LoginControl.php");
include("../BASICLOGININFO.PHP");

session_start();

if (isset($_SESSION['payrollprocess'])) {
  // Output JavaScript alert using the session variable
  echo "<script>alert('{$_SESSION['payrollprocess']}');</script>";

  // Unset the session variable to avoid showing the alert on page reload
  unset($_SESSION['payrollprocess']);
}

$page = isset($_GET['page']) ? $_GET['page'] : 1;
$results_perpage = 10;
$searchby = isset($_GET['searchoption']) ? $_GET['searchoption'] : "";
$searchvalue = isset($_GET['searchoption']) ? $_GET['searchoption'] : "";

if (isset($_GET['findTasks'])) {

}

if (isset($_GET['print_btn'])) {

  $payperiod = isset($_GET['payperiod']) ? mysqli_real_escape_string($conn, $_GET['payperiod']) : "";

  if ($payperiod !== "" && $payperiod !== "novalue") {

    $pperiodquery = "SELECT * FROM payperiods WHERE pperiod_range = '$payperiod'";
    $pperiodexecquery = mysqli_query($conn, $pperiodquery) or die("FAILED TO SET PAY PERIOD " . mysqli_error($conn));
    $pperiodarray = mysqli_fetch_array($pperiodexecquery);

    if ($pperiodarray) {
      $_SESSION['payperiodfrom'] = $pperiodarray['pperiod_start'];
      $_SESSION['payperiodto'] = $pperiodarray['pperiod_end'];
      $_SESSION['payperiodrange'] = $pperiodarray['pperiod_range'];
      $payperiod_to = $pperiodarray['pperiod_start'];
      $payperiod_from = $pperiodarray['pperiod_end'];
    }

    $deptchecked = isset($_GET['dept']) ? $_GET['dept'] : '';
    $emptypechecked = isset($_GET['employmenttype']) ? $_GET['employmenttype'] : '';
    $shiftchecked = isset($_GET['shifts']) ? $_GET['shifts'] : '';
    $positionchecked = isset($_GET['position']) ? $_GET['position'] : '';
    $gender = isset($_GET['Gender']) ? $_GET['Gender'] : '';
    $employeeStatus = isset($_GET['employee_status']) ? $_GET['employee_status'] : '';
    $selectedMonth = isset($_GET['month']) ? $_GET['month'] : '';
    $selectedDay = isset($_GET['day']) ? $_GET['day'] : '';
    $selectedYear = isset($_GET['year']) ? $_GET['year'] : '';
    $filterBy = isset($_GET['filter_by']) ? $_GET['filter_by'] : '';  // New parameter
    $searchValue = isset($_GET['search_value']) ? $_GET['search_value'] : '';  // New parameter

    $deptFilter = $deptchecked ? $deptchecked : '';
    $emptypeFilter = $emptypechecked ? $emptypechecked : '';
    $shiftFilter = $shiftchecked ? $shiftchecked : '';
    $positionFilter = $positionchecked ? $positionchecked : '';
    $genderFilter = $gender ? "'" . $gender . "'" : ''; // Assuming gender is a string in the database
    $employeeStatusFilter = $employeeStatus ? "'" . $employeeStatus . "'" : ''; // Assuming employee_status is a string in the database

    $monthFilter = $selectedMonth ? "'" . $selectedMonth . "'" : '';
    $dayFilter = $selectedDay ? "'" . $selectedDay . "'" : '';
    $yearFilter = $selectedYear ? "'" . $selectedYear . "'" : '';

    $filterByFilter = $filterBy ? $filterBy : '';  // New parameter
    $searchValueFilter = $searchValue ? "" . $searchValue . "" : '';

    $filterConditions = [];

    if ($deptFilter) {
      $filterConditions[] = "department.dept_ID IN ($deptFilter)";
    }

    if ($emptypeFilter) {
      $filterConditions[] = "employmenttypes.employment_ID IN ($emptypeFilter)";
    }

    if ($shiftFilter) {
      $filterConditions[] = "shift.shift_ID IN ($shiftFilter)";
    }
    if ($positionFilter) {
      $filterConditions[] = "position.position_id IN ($positionFilter)";
    }
    if ($genderFilter) {
      $filterConditions[] = "employees.emp_gender = $genderFilter";
    }

    if ($employeeStatusFilter) {
      $filterConditions[] = "employees.emp_status = $employeeStatusFilter";
    }

    if ($monthFilter) {
      $filterConditions[] = "MONTH(employees.date_hired) = $monthFilter";
    }

    if ($dayFilter) {
      $filterConditions[] = "DAY(employees.date_hired) = $dayFilter";
    }

    if ($yearFilter) {
      $filterConditions[] = "YEAR(employees.date_hired) = $yearFilter";
    }

    if ($filterByFilter && $searchValueFilter) {
      // Add a condition for the specific search based on the selected field
      $filterConditions[] = "LOWER(employees.$filterByFilter)  LIKE LOWER ('%$searchValueFilter%')";
    }
    if (!empty($filterConditions)) {

      $searchquery = "SELECT DISTINCT employees.emp_id, employees.*
                        FROM employees
                        JOIN TIME_KEEPING ON employees.emp_id = TIME_KEEPING.emp_id
                        JOIN department ON department.dept_NAME = employees.dept_NAME 
                        JOIN employmenttypes ON employmenttypes.employment_TYPE = employees.employment_TYPE
                        JOIN shift ON shift.shift_SCHEDULE = employees.shift_SCHEDULE
                        LEFT JOIN position ON position.position_name = employees.position
                        WHERE employees.emp_status = 'Active'
                          AND TIME_KEEPING.timekeep_day BETWEEN '$payperiod_to' AND '$payperiod_from'
                          AND " . implode(" AND ", $filterConditions);
    } else {
      $searchquery = "SELECT DISTINCT employees.emp_id, employees.*
                        FROM employees
                        JOIN TIME_KEEPING ON employees.emp_id = TIME_KEEPING.emp_id
                        JOIN department ON department.dept_NAME = employees.dept_NAME 
                        JOIN employmenttypes ON employmenttypes.employment_TYPE = employees.employment_TYPE
                        JOIN shift ON shift.shift_SCHEDULE = employees.shift_SCHEDULE
                        LEFT JOIN position ON position.position_name = employees.position
                        WHERE employees.emp_status = 'Active'
                          AND TIME_KEEPING.timekeep_day BETWEEN '$payperiod_to' AND '$payperiod_from'";

    }

    $searchquery .= " ORDER BY employees.emp_id";
    // echo "Generated Query: $searchquery<br>";
    $_SESSION['processallpayroll'] = $searchquery;

    $search_result = filterTable($searchquery);
    // Count total rows in the limited result set
    // $totalrows = mysqli_num_rows($search_result);

    // Calculate total pages
    // $totalpages = ceil($totalrows / $results_perpage);

    // echo "Number of Rows: " . mysqli_num_rows($search_result) . "<br>";



  } else {
    $searchvalue = isset($_GET['searchoption']) ? $_GET['searchoption'] : "";
    $searchquery = "SELECT * from employees WHERE emp_id = '0' ORDER BY emp_id";
    $search_result = filterTable($searchquery);

  }

} else {

  $searchvalue = isset($_GET['searchoption']) ? $_GET['searchoption'] : "";
  $searchquery = "SELECT * from employees WHERE emp_id = '0' ORDER BY emp_id";
  $search_result = filterTable($searchquery);
  $_SESSION['processallpayroll'] = $searchquery;
  // $start_from = ($page-1) * $results_perpage;
  //       // $searchvalue = $_POST['searchvalue'];
  //       $searchquery = "SELECT * from employees WHERE emp_id = '0' ORDER BY emp_id ASC LIMIT $start_from,".$results_perpage;
  //       $search_result = filterTable($searchquery); $start_from = ($page-1) * $results_perpage;
  //       // $searchvalue = $_GET['searchvalue'];
  //       $searchvalue = isset($_GET['searchvalue']) ? $_GET['searchvalue'] : '';
  //       $searchquery = "SELECT * from employees WHERE emp_id = '0' ORDER BY emp_id ASC LIMIT $start_from,".$results_perpage;
  //       $search_result = filterTable($searchquery);
  //       echo "Generated Query: $searchquery";
  //       $_SESSION['printdtr_query'] = $searchquery;
  //       // Generated Query: SELECT * from employees WHERE emp_id = '0' ORDER BY emp_id ASC LIMIT 0,20

}







?>




<!DOCTYPE html>
<html lang="en">

<head>
  <title>Process Payrolls</title>
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
</head>

<body>
  <style>



  </style>
  <!--Header-part-->




  <?php
  include('NAVBAR.php');
  ?>

  <div id="content">

    <div id="content-header">
      <div id="breadcrumb"> <a href="adminDASHBOARD.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i>
          Home</a>
        <a href="<?php $_SERVER['PHP_SELF']; ?>" title="Process Employee Payroll" class="tip-bottom"><i
            class="icon-th"></i> Process Employee Payroll</a>

      </div>
    </div>
    <span class="span6">
      <h3 style="margin-top: 30px">Payroll Processing</h3>
    </span>

    <div class="container-fluid">
      <div class="row-fluid">
        <div class="row-fluid">
          <div class="span12">
            <div class="widget-box">
              <div class="widget-title">
                <ul class="nav nav-tabs" id="myTab">
                  <li class=""><a href="./adminPAYROLLINFO.php"><i class="icon-user"></i> Employees</a></li>
                  <li class=""><a href="./GOVTTABLES/adminGOVTTables.php"><i class="icon-th"></i> Government
                      Contribution Table</a></li>
                  <li class=""><a href="./LOANS/adminSSSLoans.php"><i class="icon-th"></i> SSS Loans</a></li>
                  <li class=""><a href="./LOANS/adminPAGIBIGLoans.php"><i class="icon-th"></i> Pagibig Loans</a></li>
                  <li class="active"><a href="../adminPAYROLLProcess.php"><i class="icon-user"></i> Process Employee
                      Payrolls</a></li>
                  <li class=""><a href="./admin13thmonth.php"><i class="icon-th"></i> Compute 13th Month Pay</a></li>
                </ul>
              </div>
              <div class="row-fluid">

                <span class="span3">

                </span>
                <span class="span3">
                </span>

              </div>

              <div class="row-fluid">

                <div class="widget-box">
                  <div class="widget-title"><span class="icon"> <i class="icon-align-justify"></i> </span>
                    <h5>PROCESS EMPLOYEE</h5>
                  </div>
                  <div class="widget-content nopadding">
                    <form method="GET" action="">
                      <?php
                      $deptchecked = isset($_GET['dept']) ? $_GET['dept'] : '';
                      $emptypechecked = isset($_GET['employmenttype']) ? $_GET['employmenttype'] : '';
                      $shiftchecked = isset($_GET['shifts']) ? $_GET['shifts'] : '';
                      $positionchecked = isset($_GET['position']) ? $_GET['position'] : '';
                      $gender = isset($_GET['gender']) ? $_GET['gender'] : '';
                      $employeeStatus = isset($_GET['employee_status']) ? $_GET['employee_status'] : '';
                      $month = isset($_GET['month']) ? $_GET['month'] : '';
                      $filterBy = isset($_GET['filter_by']) ? $_GET['filter_by'] : '';  // New parameter
                      $searchValue = isset($_GET['search_value']) ? $_GET['search_value'] : '';  // New parameter
                      
                      $query = "SELECT * FROM department";
                      $total_row = mysqli_query($conn, $query) or die('error');
                      ?>

                      <div class="selections">
                        <style>
                          /* Add CSS styles here */

                          .selections {
                            display: flex;
                            flex-direction: center;
                          }

                          .select-container {
                            display: flex;
                            flex-direction: column;
                            margin-bottom: 100px;
                            /* Add margin between each set */
                          }

                          .select-container h6,
                          .select-container select {
                            margin-left: 20px;
                            /* Adjust the margin as needed */
                          }

                          .select-container2 {
                            display: flex;
                            flex-direction: column;
                            margin-bottom: 20px;
                            /* Add margin between each set */
                          }

                          .select-container2 h6,
                          .select-container2 select {
                            margin-left: 20px;
                            /* Adjust the margin as needed */
                          }

                          .select-container3 {
                            display: flex;
                            flex-direction: column;
                            margin-bottom: 20px;
                            /* Add margin between each set */
                          }

                          .select-container3 h6,
                          .select-container3 select {
                            margin-left: 20px;
                            /* Adjust the margin as needed */
                          }

                          .select-container4 {
                            display: flex;
                            flex-direction: column;
                            margin-bottom: 20px;
                            /* Add margin between each set */
                          }

                          .select-container4 h6,
                          .select-container4 select {
                            margin-left: 20px;
                            /* Adjust the margin as needed */
                          }

                          input {
                            margin-left: 20px;
                            border: 2px solid red;

                          }

                          @media screen and (max-width: 768px) {
                            .selections {
                              flex-direction: column;
                              /* Change to column on smaller screens */
                              align-items: stretch;
                              /* Stretch items to full width */
                            }

                            .select-container,
                            .select-container2,
                            .select-container3,
                            .select-container4 {
                              flex-direction: column;
                              /* Change to column on smaller screens */
                              margin-bottom: 2px;
                              /* Add a 5px margin between each set */
                            }
                          }
                        </style>

                        <div class="select-container">
                          <h6 style="margin-top: 20px">Department</h6>
                          <select name="dept">
                            <option value="">Select Department</option>
                            <?php
                            if (mysqli_num_rows($total_row) > 0) {
                              foreach ($total_row as $row) {
                                ?>
                                <option value="<?php echo $row['dept_ID']; ?>" <?php if ($deptchecked == $row['dept_ID'])
                                     echo "selected"; ?>>
                                  <?php echo $row['dept_NAME']; ?>
                                </option>
                                <?php
                              }
                            } else {
                              echo 'No Data Found';
                            }
                            ?>
                          </select>
                          <?php
                          $query1 = "SELECT * FROM employmenttypes";
                          $total_row = mysqli_query($conn, $query1) or die('error');
                          ?>
                          <h6 style="margin-top: 15px">Employment Type</h6>
                          <select name="employmenttype" id="employmenttype" style="">
                            <option value="">Select Employment Type</option>
                            <?php
                            if (mysqli_num_rows($total_row) > 0) {
                              foreach ($total_row as $row) {
                                ?>
                                <!-- <li style="color:#333; font-size:12px; font-family: 'Roboto', sans-serif;"> -->
                                <option value="<?php echo $row['employment_ID']; ?>" <?php if ($emptypechecked == $row['employment_ID'])
                                     echo "selected"; ?>>
                                  <?php echo $row['employment_TYPE']; ?>
                                </option>
                                <?php

                              }
                            } else {
                              echo 'No Data Found';
                            }
                            ?>
                          </select>

                          <?php
                          $query3 = "SELECT * FROM position";
                          $total_row = mysqli_query($conn, $query3) or die('error');
                          ?>
                          <h6 style="margin-top: 15px">Position</h6>
                          <select name="position" id="position">
                            <option value="">Select Position</option>
                            <?php
                            if (mysqli_num_rows($total_row) > 0) {
                              foreach ($total_row as $row) {
                                ?>
                                <!-- <li style="color:#333; font-size:12px; font-family: 'Roboto', sans-serif;"> -->
                                <option value="<?php echo $row['position_id']; ?>" <?php if ($positionchecked == $row['position_id'])
                                     echo "selected"; ?>>
                                  <?php echo $row['position_name']; ?>
                                </option>
                                <?php

                              }
                            } else {
                              echo 'No Data Found';
                            }
                            ?>
                          </select>
                        </div>

                        <?php
                        $query2 = "SELECT * FROM shift";
                        $total_row = mysqli_query($conn, $query2) or die('error');
                        ?>
                        <div class="select-container2">
                          <h6 style="margin-top: 20px">Shift</h6>
                          <select name="shifts" disabled>
                            <!-- <option value="">Select Shift</option> -->
                            <option value="2">8AM to 5PM</option>
                            <?php
                            if (mysqli_num_rows($total_row) > 0) {
                              foreach ($total_row as $row) {
                                ?>
                                <!-- <li style="color:#333; font-size:12px; font-family: 'Roboto', sans-serif;"> -->

                                <option value="<?php echo $row['shift_ID']; ?>" <?php if ($positionchecked == $row['shift_ID'])
                                     echo "selected"; ?>>
                                  <?php echo $row['shift_SCHEDULE']; ?>
                                </option>
                                <?php

                              }
                            } else {
                              echo 'No Data Found';
                            }
                            ?>
                          </select>
                          <h6 style="margin-top: 15px">Gender</h6>
                          <select name="Gender">
                            <option value="" <?php if (isset($_GET['Gender']) && $_GET['Gender'] == '')
                              echo 'selected'; ?>>Select Gender</option>
                            <option value="Male" <?php if (isset($_GET['Gender']) && $_GET['Gender'] == 'Male')
                              echo 'selected'; ?>>Male</option>
                            <option value="Female" <?php if (isset($_GET['Gender']) && $_GET['Gender'] == 'Female')
                              echo 'selected'; ?>>Female</option>
                          </select>

                          <h6 style="margin-top: 15px">Employee Status</h6>
                          <select name="employee_status">
                            <option value="" <?php if (isset($_GET['employee_status']) && $_GET['employee_status'] == '')
                              echo 'selected'; ?>>Select Employee Status</option>
                            <option value="Active" <?php if (isset($_GET['employee_status']) && $_GET['employee_status'] == 'Active')
                              echo 'selected'; ?>>Active</option>
                            <option value="Inactive" <?php if (isset($_GET['employee_status']) && $_GET['employee_status'] == 'Inactive')
                              echo 'selected'; ?>>Inactive</option>
                          </select>
                        </div>
                        <div class="select-container3">
                          <h6 style="margin-top: 20px">Date Hired</h6>
                          <select name="month">
                            <option value="">Select Month</option>
                            <?php
                            $months = [
                              'January' => 1,
                              'February' => 2,
                              'March' => 3,
                              'April' => 4,
                              'May' => 5,
                              'June' => 6,
                              'July' => 7,
                              'August' => 8,
                              'September' => 9,
                              'October' => 10,
                              'November' => 11,
                              'December' => 12
                            ];

                            foreach ($months as $monthName => $monthNumber) {
                              $selected = (isset($_GET['month']) && $_GET['month'] == $monthNumber) ? 'selected' : '';
                              echo '<option value="' . $monthNumber . '" ' . $selected . '>' . $monthName . '</option>';
                            }
                            ?>
                          </select>

                          <select name="day">
                            <option value="">Select Day</option>
                            <?php
                            // Adding options for days (assuming up to 31 for simplicity)
                            for ($day = 1; $day <= 31; $day++) {
                              $selected = (isset($_GET['day']) && $_GET['day'] == sprintf('%02d', $day)) ? 'selected' : '';
                              echo '<option value="' . sprintf('%02d', $day) . '" ' . $selected . '>' . sprintf('%02d', $day) . '</option>';
                            }
                            ?>
                          </select>

                          <select name="year">
                            <option value="">Select Year</option>
                            <?php
                            // Adding options for years (current year - 5 to current year + 5)
                            $currentYear = date("Y");
                            $startYear = $currentYear - 5;
                            $endYear = $currentYear + 5;

                            for ($year = $startYear; $year <= $endYear; $year++) {
                              $selected = (isset($_GET['year']) && $_GET['year'] == $year) ? 'selected' : '';
                              echo '<option value="' . $year . '" ' . $selected . '>' . $year . '</option>';
                            }
                            ?>
                          </select>
                        </div>

                          <div class="select-container4">
                          <h6 style="margin-top: 20px">Search by:</h6>
                          <select name="filter_by">
                            <option value="" <?php if (isset($_GET['filter_by']) && $_GET['filter_by'] == '')
                              echo 'selected'; ?>>Search by</option>
                            <option value="emp_id" <?php if (isset($_GET['filter_by']) && $_GET['filter_by'] == 'emp_id')
                              echo 'selected'; ?>>Employee ID</option>
                            <option value="last_name" <?php if (isset($_GET['filter_by']) && $_GET['filter_by'] == 'last_name')
                              echo 'selected'; ?>>Last Name</option>
                            <option value="first_name" <?php if (isset($_GET['filter_by']) && $_GET['filter_by'] == 'first_name')
                              echo 'selected'; ?>>First Name</option>
                            <option value="user_name" <?php if (isset($_GET['filter_by']) && $_GET['filter_by'] == 'user_name')
                              echo 'selected'; ?>>Username</option>
                          </select>


                          <div id="search">
                            <input type="text" placeholder="Search" name="search_value"
                              value="<?php echo isset($_GET['search_value']) ? htmlspecialchars($_GET['search_value']) : ''; ?>"
                              style="width: 220px; height: 30px;">
                          </div>
                          </div>

                  <div class="select-container5">                      
                <h5 style="margin-top: 20px; margin-left:50px">Select Payroll Period</h5>     
              <div class="widget-content nopadding">
                <div class="control-group">

                  <?php
                    $payperiodsquery = "SELECT * FROM payperiods";
                    $payperiodsexecquery = mysqli_query($conn, $payperiodsquery) or die("FAILED TO EXECUTE PAYPERIOD QUERY " . mysqli_error($conn));
                    ?>
                    <label class="control-label" style="margin-top: 5px; margin-left:50px">Select Payroll Period: </label>
                    <div class="controls"  style="margin-left: 50px">
                      <select name="payperiod">
                
                        <option value="novalue" selected disabled> Select Payroll Period</option>
                        <?php while ($payperiodchoice = mysqli_fetch_array($payperiodsexecquery)):
                          ;
                          $selected = ($payperiod == $payperiodchoice['pperiod_range']) ? 'selected' : ''; ?>
                          <option <?php echo $selected; ?>>
                            <?php echo $payperiodchoice['pperiod_range']; ?>
                          </option>
                        <?php endwhile; ?>
                      </select>

                      <div class="form-actions">
                          <table>
  <tr>
    <td class="form-actions"><button type="submit" class="btn btn-success" name="printAll">Process All</button></td>
    <td class="form-actions"><button type="submit" class="btn btn-success printbtn" name = "print_btn" style="float:right">Submit</button></form></td>
    <td class="span6"><a href="adminPAYROLLProcess.php" class="btn btn-success" style="float:right; margin-left: 4px;"><span class="icon"><i class="icon-refresh"></i></span> Refresh</a></td>
  </tr>
</table>
                  </div>
                  </div>
                  
                  </form>
                </div>
              </div>
            </div>
          </div>
                            <style>
                              .printbtn1 a {
                                color: white;
                                text-decoration: none;
                              }

                              .printbtn a {
                                color: white;
                                text-decoration: none;
                              }


                              .printbtn {
                                background-color: green;
                                border-color: green;
                              }


                              .printbtn:hover {
                                background-color: darkgreen;
                                border-color: darkgreen;
                              }

                              @media screen and (max-width: 600px) {
                                .form-actions {
                                  display: flex;
                                  flex-direction: column;
                                  /* Stack buttons vertically on smaller screens */
                                  justify-content: space-between;
                                  /* Add space between buttons */
                                }

                                .form-actions button {
                                  margin-bottom: 10px;
                                  /* Add margin between buttons */
                                  width: 100%;
                                  /* Make buttons full-width on smaller screens */
                                }
                              }
                            </style>


                            <!-- <small>*shown here are the attendance record for today</small> -->
                          </div>

                    </form>
                  </div>
                </div>
              </div>
            </div>

        
          <div class="widget-box">
            <table class="table table-bordered data-table">
              <div class="widget-title">
                <ul class="nav nav-tabs" id="myTab">
                  <li class="active"><a href="#tab1"><i class="icon-time"></i> Employees</a></li>
                </ul>
              </div>
              <thead>
                <tr>
                  <th>Employee ID</th>
                  <th>Last Name</th>
                  <th>First Name</th>
                  <th>Middle Name</th>
                  <th>Department</th>
                  <th>Shift</th>
                  <th>Employment Type</th>
                  <th>Action</th>

                </tr>
              </thead>
              <tbody>

          </div>
          <div class="widget-content tab-content">
            <div id="tab1" class="tab-pane fade in active">
              <!--EMPLOYEE TAB--><!--EMPLOYEE TAB--><!--EMPLOYEE TAB--><!--EMPLOYEE TAB--><!--EMPLOYEE TAB--><!--EMPLOYEE TAB--><!--EMPLOYEE TAB--><!--EMPLOYEE TAB-->



              <div id="search">



              </div>

            </div>
            <div class="row-fluid">

            </div>

            <?php



            function filterTable($searchquery)
            {

              $conn1 = mysqli_connect("localhost:3307", "root", "", "masterdb");
              $filter_Result = mysqli_query($conn1, $searchquery) or die("failed to query masterfile " . mysqli_error($conn1));
              return $filter_Result;
            }


            while ($row1 = mysqli_fetch_array($search_result)):
              ;
              ?>
              <tr class="gradeX">
                <td>
                  <?php echo $row1['prefix_ID']; ?>
                  <?php echo $row1['emp_id']; ?>
                </td>
                <td>
                  <?php echo $row1['last_name']; ?>
                </td>
                <td>
                  <?php echo $row1['first_name']; ?>
                </td>
                <td>
                  <?php echo $row1['middle_name']; ?>
                </td>
                <td>
                  <?php echo $row1['dept_NAME']; ?>
                </td>
                <td>
                  <?php echo $row1['shift_SCHEDULE']; ?>
                </td>
                <td>
                  <?php echo $row1['employment_TYPE']; ?>
                </td>
                <td>
                  <center><a href="adminCOMPUTEPayroll.php?id=<?php echo $row1['emp_id']; ?>"
                      class="btn btn-info btn-mini" target="_blank"><span class="icon"><i
                          class="icon-calendar"></i></span> Process Payroll</a>


              </tr>
            <?php endwhile; ?>
            </tbody>
            </table>

          </div>
          <!--EMPLOYEE TAB--><!--EMPLOYEE TAB--><!--EMPLOYEE TAB--><!--EMPLOYEE TAB--><!--EMPLOYEE TAB--><!--EMPLOYEE TAB--><!--EMPLOYEE TAB--><!--EMPLOYEE TAB-->
        </div>


      </div>
    </div>
  </div>
  </div>
  </div>
  <div class="row-fluid">
    <div id="footer" class="span12"> 2023 &copy; WEB-BASED TIMEKEEPING AND PAYROLL SYSTEM USING FINGERPRINT BIOMETRICS
    </div>
  </div>