<?php
include("../DBCONFIG.PHP");
include("../LoginControl.php");
include("../BASICLOGININFO.PHP");

session_start();

if (isset($_SESSION['masterfilenotif'])) {

  $mfnotif = $_SESSION['masterfilenotif'];
  ?>
  <script>
    alert("<?php echo $mfnotif; ?>");
  </script>
  <?php
}
$master = $_SESSION['master'];
$results_perpage = 20;

if (isset($_GET['page'])) {

  $page = $_GET['page'];
  echo "Debug: Page - $page<br>";
} else {

  $page = 1;
}

// $cntlv = "SELECT emp_id,employment_TYPE,emp_gender from employees";
// $cntlvexec = mysqli_query($conn,$cntlv) or die ("FAILED TO QRY EMPID ".mysqli_error($conn));


// while ($cntleavearray = mysqli_fetch_array($cntlvexec)){

//   $leaveid = $cntleavearray['emp_id'];
//   $emptype = $cntleavearray['employment_TYPE'];
//   $empgender = $cntleavearray['emp_gender'];

//   if($empgender == "Male"){

//     $SPLEAVE = '7';
//   }else if ($empgender =="Female"){
//     $SPLEAVE = '60';
//   }


//   if ($emptype == "Regular"){
//     $LEAVEADD = '15';
//   }else if ($emptype =="Probationary"){
//     $LEAVEADD = '0';
//   }else if ($emptype =="Contractual"){
//     $LEAVEADD ='0';
//   }

//   $lvcntadd = "SELECT * FROM LEAVES WHERE emp_id = '$leaveid' AND leaves_year = YEAR(CURDATE())";
//   $lvcntaddexec = mysqli_query($conn,$lvcntadd) or die ("FAILED TO ADD LEAVES COUNT ".mysqli_error($conn));
//   $lvcntcount = mysqli_num_rows($lvcntaddexec);

//   $newpinfoqry = "SELECT * FROM PAYROLLINFO WHERE emp_id = '$leaveid'";
//   $newpinfoexecqry = mysqli_query($conn,$newpinfoqry) or die ("FAILED TO ADD PAYROLL INFO ".mysqli_error($conn));
//   $newpinfocount = mysqli_num_rows($newpinfoexecqry);

//   if ($newpinfocount!=1){

//       $addpinfoqry = "INSERT INTO PAYROLLINFO (emp_id) VALUES ('$leaveid')";
//       $addpinfoexecqry = mysqli_query($conn,$addpinfoqry) or die ("FAILED TO ADD PAYROLL INFO2 ".mysqli_error($conn));
//   }


//   if($lvcntcount!=1){

//     $insertlvcntqry = "INSERT INTO LEAVES (emp_id,leave_count,vacleave_count,sp_lv,leaves_year) VALUES ('$leaveid','$LEAVEADD','$LEAVEADD','$SPLEAVE',YEAR(CURDATE()))";
//     $insertlvcntqryexec = mysqli_query($conn,$insertlvcntqry);
//   }

// }

if (isset($_GET['refresh'])) {
  header("Location: adminMasterfile.php");
  exit(); 
}
// If findTasks is set, apply filters
if (isset($_GET['print_btn'])) {
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
    $searchquery = "SELECT * FROM employees
          LEFT JOIN department ON department.dept_NAME = employees.dept_NAME 
          LEFT JOIN employmenttypes ON employmenttypes.employment_TYPE = employees.employment_TYPE
          LEFT JOIN shift ON shift.shift_SCHEDULE = employees.shift_SCHEDULE
          LEFT JOIN position ON position.position_name = employees.position
          WHERE " . implode(" AND ", $filterConditions);
    echo "Generated Query: $searchquery<br>";

    $start_from = ($page - 1) * $results_perpage;
    // $searchquery .= " ORDER BY emp_id ASC LIMIT $start_from, $results_perpage";

    // echo "Generated Query: $searchquery<br>";
    // print_r($_GET);


    // Count total rows in the limited result set
    // $totalrows = mysqli_num_rows($search_result);

    // Calculate total pages
    // $totalpages = ceil($totalrows / $results_perpage);

    // echo "Number of Rows: " . mysqli_num_rows($search_result) . "<br>";
  } else {
    $start_from = ($page - 1) * $results_perpage;
    $searchquery = "SELECT * FROM employees 
        LEFT JOIN department ON department.dept_NAME = employees.dept_NAME 
        LEFT JOIN employmenttypes ON employmenttypes.employment_TYPE = employees.employment_TYPE
        LEFT JOIN shift ON shift.shift_SCHEDULE = employees.shift_SCHEDULE
        LEFT JOIN position ON position.position_name = employees.position";

  }




  $searchquery .= " ORDER BY emp_id ASC LIMIT $start_from, $results_perpage";

  $search_result = filterTable($searchquery);
  $_SESSION['print_query'] = $searchquery;
  // Count total rows in the limited result set
  $totalrows = mysqli_num_rows($search_result);

  // Calculate total pages
  $totalpages = ceil($totalrows / $results_perpage);

  // echo "Number of Rows: " . mysqli_num_rows($search_result) . "<br>";

} else {
  $start_from = ($page - 1) * $results_perpage;
  $searchquery = "SELECT * FROM employees 
                        LEFT JOIN department ON department.dept_NAME = employees.dept_NAME 
                        LEFT JOIN employmenttypes ON employmenttypes.employment_TYPE = employees.employment_TYPE
                        LEFT JOIN shift ON shift.shift_SCHEDULE = employees.shift_SCHEDULE
                        LEFT JOIN position ON position.position_name = employees.position
                                        ORDER BY emp_id ASC 

                        LIMIT $start_from, $results_perpage";
  $search_result = filterTable($searchquery);
  $_SESSION['print_query'] = $searchquery;

  $countdataqry = "SELECT COUNT(emp_id) AS total FROM employees 
        LEFT JOIN department ON department.dept_NAME = employees.dept_NAME 
        LEFT JOIN employmenttypes ON employmenttypes.employment_TYPE = employees.employment_TYPE
        LEFT JOIN shift ON shift.shift_SCHEDULE = employees.shift_SCHEDULE
        LEFT JOIN position ON position.position_name = employees.position ORDER BY emp_id ASC ";
  echo $searchquery;
  $countdataqryresult = mysqli_query($conn, $countdataqry) or die("FAILED TO EXECUTE COUNT QUERY " . mysqli_error($conn));

  $row = $countdataqryresult->fetch_assoc();
  $totalpages = ceil($row['total'] / $results_perpage);
  echo $totalpages;
}
echo "Debug Query: $searchquery";


?>


<!DOCTYPE html>
<html lang="en">

<head>
  <title>Admin Masterlist</title>
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

  <!--Header-part-->

  <?php
  include('NAVBAR.php');
  ?>
  <div id="content">
    <div id="content-header">
      <div id="breadcrumb"> <a href="index.html" title="Go to Home" class="tip-bottom"><i class="icon-home"></i>
          Home</a>
      </div>
    </div>
    <span class="span6">
      <h3 style="margin-top: 30px">Employee Masterlist</h3>
    </span>

    <div class="container-fluid">
      <div class="row-fluid">
        <div class="row-fluid">
          <div class="span12">
            <div class="widget-box">
              <div class="widget-title">
                <ul class="nav nav-tabs" id="myTab">
                  <li class="active"><a href="adminMasterfile.php"><i class="icon-user"></i> Employees</a></li>
                  <li><a href="adminMasterfileDept.php"><i class="icon-th"></i> Manage Departments</a></li>
                  <li><a href="adminMasterfileShift.php"><i class="icon-time"></i> Manage Shifts</a></li>
                  <li><a href="adminMasterfileHoliday.php"><i class="icon-calendar"></i> Manage Holidays</a></li>
                  <li><a href="adminMasterfileLeaves.php"><i class="icon-calendar"></i> Manage Leaves</a></li>
                  <li><a href="adminPAYROLLPERIODS.php"><i class="icon-user"></i> Manage Payroll Periods</a></li>
                  <?php
                  if ($master) {
                    echo '
                    <li><a href="adminPositions.php"><i class="icon-th"></i> Manage Positions</a></li>
                    <li><a href="adminSalaryGrades.php"><i class="icon-th"></i> Manage Salary Grades</a></li>
                ';
                  }
                  ?>
                </ul>
              </div>
              <div class="row-fluid">

                <span class="span3">

                </span>
                <span class="span3">
                </span>
              </div>

              <div class="row-fluid">
                <div class="span2">
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

                        <div class="form-actions">
                          <button type="submit" class="btn btn-success printbtn" name="print_btn">Apply</button>
                          <!-- <a href="generate_pdf.php" class="btn btn-primary">Print all</a> -->
                          <button type="submit" class="btn btn-success printbtn" name="refresh">Refresh</button>
                          
                          <table>
  <tr>
    <td><a href="printmasterlist.php?printAll" class="btn btn-info btn-mini" target="_blank"><span class="icon"><i class="icon-print"></i></span> Print All Masterlist</a></td>
    <td><a href="printmasterlist.php?printDisplayed" class="btn btn-info btn-mini" target="_blank" style="margin-left:5px; margin-top: 2px"><span class="icon"><i class="icon-print"></i></span> Print Displayed Masterlist</a></td>
    <td><a href="adminADDprofile.php" class="btn btn-info" style="margin-left:5px"><span class="icon"><i class="icon-plus"></i></span> Add Profile</a></td>
    <td><a href="./biometricattendance1/ManageUsers.php" class="btn btn-info" style="margin-left:5px;"><span class="icon"><i class="icon-plus"></i></span> Add Fingerprint</a></td>
  </tr>
</table>
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
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <table class="table table-bordered data-table">
        <thead>
          <tr>
            <th>Employee ID</th>
            <th>Fingerprint ID</th>
            <th>Last Name</th>
            <th>First Name</th>
            <th>Middle Name</th>
            <th>Username</th>
            <th>Department</th>
            <th>Employment Type</th>
            <th>Position</th>
            <th>Shift</th>
            <th>Gender</th>
            <th>Contact Number</th>
            <th>Date Hired</th>
            <th>Date Regularized</th>
            <th>Date Resigned</th>
            <th>Actions</th>
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
              <td><a href="adminVIEWprofile.php?id=<?php echo $row1['emp_id']; ?>">
                  <?php echo $row1['prefix_ID']; ?>
                  <?php echo $row1['emp_id']; ?>
                </a></td>
              <td>
                <?php echo $row1['fingerprint_id']; ?>
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
                <?php echo $row1['user_name']; ?>
              </td>
              <td>
                <?php echo $row1['dept_NAME']; ?>
              </td>
              <td>
                <?php echo $row1['employment_TYPE']; ?>
              </td>
              <td>
                <?php echo $row1['position']; ?>
              </td>
              <td>
                <?php echo $row1['shift_SCHEDULE']; ?>
              </td>
              <td>
                <?php echo $row1['emp_gender']; ?>
              </td>
              <td>
                <?php echo $row1['contact_number']; ?>
              </td>
              <td>
                <?php echo $row1['date_hired']; ?>
              </td>
              <td>
                <?php echo $row1['date_regularized']; ?>
              </td>
              <td>
                <?php echo $row1['date_resigned']; ?>
              </td>
              <td>
                <center><a href="adminEDITMasterfile.php?id=<?php echo $row1['emp_id']; ?>"
                    class="btn btn-info btn-mini"><span class="icon"><i class="icon-edit"></i></span>
                    Edit</a>
                  <a href="adminDELETEMasterfile.php?id=<?php echo $row1['emp_id']; ?>"
                    class="btn btn-danger btn-mini"><span class="icon"><i class="icon-trash"></i></span> Delete</a>
                </center>
              </td>

            </tr>
          <?php endwhile; ?>

        </tbody>
      </table>
      <div class="pagination alternate" style="float:right;">
        <ul>
          <?php
          for ($i = 1; $i <= $totalpages; $i++) {
            echo "<li><a href=" . $_SERVER['PHP_SELF'] . "?page=" . $i;
            if ($i == $page) {
              echo " class='curPage'";
            }
            echo ">" . $i . "</a></li> ";
          }
          ?>

        </ul>
      </div>

    </div>
    <!--EMPLOYEE TAB--><!--EMPLOYEE TAB--><!--EMPLOYEE TAB--><!--EMPLOYEE TAB--><!--EMPLOYEE TAB--><!--EMPLOYEE TAB--><!--EMPLOYEE TAB--><!--EMPLOYEE TAB-->
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
    <div id="footer" class="span12"> 2023 &copy; WEB-BASED TIMEKEEPING AND PAYROLL SYSTEM USING FINGERPRINT
      BIOMETRICS</div>
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
  <script>
    // Function to update the position dropdown state
    function updatePositionDropdownState() {
      var positionDropdown = document.getElementById('position');
      var employmentTypeDropdown = document.getElementById('employmenttype');

      var isContractual = employmentTypeDropdown.value === '4001'; // Change to the actual value for contractual

      // Save the selected value before disabling
      var selectedValue = positionDropdown.value;

      // Disable/enable based on employment type
      positionDropdown.disabled = isContractual;

      // Set the selected value after updating options
      positionDropdown.value = selectedValue;
    }

    // Initial setup on page load
    updatePositionDropdownState(); // Ensure the initial state is correct

    // Event listener for changes in the employment type dropdown
    document.getElementById('employmenttype').addEventListener('change', function () {
      updatePositionDropdownState();
    });
  </script>







</body>

</html>