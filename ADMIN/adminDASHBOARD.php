<?php
include("../DBCONFIG.PHP");
include("../LoginControl.php");
include("../BASICLOGININFO.PHP");

if (isset($_GET['daterange_start']) && isset($_GET['daterange_end'])) {
  $_SESSION['start_date'] = $_GET['daterange_start'];
  $_SESSION['end_date'] = $_GET['daterange_end'];
}

$timeconv = strtotime("NOW");
$currtime = date("F d, Y", $timeconv); //january 01, 2000
$currdate = date("Y-m-d", $timeconv); //2000-01/01
$curryear = date("Y", $timeconv); //23
/** CHECK PAYROLL PERIOD **/

$checkpperiod = "SELECT pperiod_range FROM payperiods WHERE CURDATE() BETWEEN pperiod_start and pperiod_end";
$checkpperiodexec = mysqli_query($conn, $checkpperiod) or die("FAILED TO CHECK PAYPERIOD " . mysqli_error($conn));
$pperiodarray = mysqli_fetch_array($checkpperiodexec);
if ($pperiodarray) {
  $currpperiod = $pperiodarray['pperiod_range'];
} else {
  $currpperiod = "No Current Pay Period";
}
/** CHECK PAYROLL PERIOD **/
/** CHECK OVERTIME APPLICATIONS **/
$checkotapp = "SELECT COUNT(emp_id) as otapps FROM OVER_TIME WHERE ot_remarks = 'For approval'";
$checkotappexec = mysqli_query($conn, $checkotapp) or die("FAILED TO CHECK OT APPS " . mysqli_error($conn));
$otapparray = mysqli_fetch_array($checkotappexec);
if ($otapparray) {
  $otapps = $otapparray['otapps'];
}
/** CHECK OVERTIME APPLICATIONS **/
/** CHECK LEAVE APPLICATIONS **/
$checkleavesapp = "SELECT COUNT(emp_id) as leaveapps FROM LEAVES_APPLICATION WHERE leave_status = 'For approval'";
$checkleavesappexec = mysqli_query($conn, $checkleavesapp) or die("FAILED TO CHECK LEAVE APPS");
$leaveapparray = mysqli_fetch_array($checkleavesappexec);
if ($leaveapparray) {
  $leaveapps = $leaveapparray['leaveapps'];
}
/**CHECK LEAVE APPLICATIONS **/


if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["submit"])) {

  $selectedDepartment = isset($_GET["department"]) ? $_GET["department"] : "";
  $selectedEmploymentType = isset($_GET["employmenttype"]) ? $_GET["employmenttype"] : "";
  $selectedposition = isset($_GET["position"]) ? $_GET["position"] : "";
  $selectedGender = isset($_GET["gender"]) ? $_GET["gender"] : "";


  $checkattendancemorning = "SELECT COUNT(t.emp_id) as morningatt
FROM TIME_KEEPING t
JOIN employees d ON t.emp_id = d.emp_id
WHERE DATE(t.timekeep_day) BETWEEN '{$_SESSION['start_date']}' AND '{$_SESSION['end_date']}'
  " . (!empty($selectedDepartment) ? "AND d.dept_NAME = '$selectedDepartment'" : "") . "
  " . (!empty($selectedposition) ? "AND d.position = '$selectedposition'" : "") . "
  " . (!empty($selectedGender) ? "AND d.emp_gender = '$selectedGender'" : "") . "
  " . (!empty($selectedEmploymentType) ? "AND d.employment_type= '$selectedEmploymentType'" : "");


  $checkattendancemorningexecquery = mysqli_query($conn, $checkattendancemorning) or die("FAILED TO CHECK MORNING ATTENDANCE " . mysqli_error($conn));
  $morningattarray = mysqli_fetch_array($checkattendancemorningexecquery);
  if ($morningattarray) {
    $morningatt = $morningattarray['morningatt'];
    echo "Generated Query: $checkattendancemorning";
  }
  //absences
  $checkabsences = "SELECT COUNT(d.emp_id) as numemps
FROM employees d
WHERE " . (!empty($selectedDepartment) ? "d.dept_NAME = '$selectedDepartment' AND " : "")
    . (!empty($selectedposition) ? "d.position = '$selectedposition' AND " : "")
    . (!empty($selectedGender) ? "d.emp_gender = '$selectedGender' AND " : "")
    . (!empty($selectedEmploymentType) ? "d.employment_type = '$selectedEmploymentType' AND " : "")
    . "d.emp_status = 'Active'";


  $checkabsencesexec = mysqli_query($conn, $checkabsences) or die("FAILED TO CHECK ABSENCES " . mysqli_error($conn));
  $absencesarray = mysqli_fetch_array($checkabsencesexec);

  if ($absencesarray) {
    $activeemps = $absencesarray['numemps'];

    $absencestoday = $absencesarray['numemps'] - $morningatt;
    echo "Generated Query: $checkabsences";
    echo "Generated Query: $activeemps";
    echo "Generated Query: $absencestoday";
  }

  //gender
  $genderQuery = "SELECT 
                    COUNT(emp_id) as totalEmps,
                    SUM(CASE WHEN emp_gender = 'Male' THEN 1 ELSE 0 END) as numMales,
                    SUM(CASE WHEN emp_gender = 'Female' THEN 1 ELSE 0 END) as numFemales
                FROM employees 
                WHERE emp_status = 'Active'
                " . (!empty($selectedDepartment) ? "AND dept_NAME = '$selectedDepartment' " : "")
    . (!empty($selectedGender) ? "AND emp_gender = '$selectedGender' " : "")
    . (!empty($selectedposition) ? "AND position = '$selectedposition' " : "")
    . (!empty($selectedEmploymentType) ? "AND employment_type = '$selectedEmploymentType' " : "");

  $genderExec = mysqli_query($conn, $genderQuery) or die("FAILED TO CHECK ABSENCES " . mysqli_error($conn));
  $genderArray = mysqli_fetch_array($genderExec);

  if ($genderArray) {
    $totalEmps = $genderArray['totalEmps'];
    $numMales = $genderArray['numMales'];
    $numFemales = $genderArray['numFemales'];
  }

  // Check late
  $late = "SELECT COUNT(e.emp_id) as late FROM TIME_KEEPING t
         JOIN employees e ON t.emp_id = e.emp_id
         WHERE late_hours > 0 
         AND DATE(t.timekeep_day) BETWEEN '{$_SESSION['start_date']}' AND '{$_SESSION['end_date']}'
         " . (!empty($selectedDepartment) ? "AND e.dept_NAME = '$selectedDepartment' " : "")
    . (!empty($selectedGender) ? "AND e.emp_gender = '$selectedGender' " : "")
    . (!empty($selectedposition) ? "AND e.position = '$selectedposition' " : "")
    . (!empty($selectedEmploymentType) ? "AND e.employment_type = '$selectedEmploymentType' " : "");

  $lateExecQuery = mysqli_query($conn, $late) or die("FAILED TO CHECK LATE ATTENDANCE " . mysqli_error($conn));
  $latearray = mysqli_fetch_array($lateExecQuery);

  if ($latearray) {
    $lateAtt = $latearray['late'];
  }

  //leaves
  $leave = "SELECT COUNT(e.emp_id) as numLeaves
          FROM leaves_application l
          JOIN employees e ON l.emp_id = e.emp_id
          WHERE l.leave_status = 'Approved' 
          AND DATE(l.leave_datestart) <= '{$_SESSION['end_date']}'
          AND DATE(l.leave_dateend) >= '{$_SESSION['start_date']}'
          " . (!empty($selectedDepartment) ? "AND e.dept_NAME = '$selectedDepartment'" : "") . "
          " . (!empty($selectedGender) ? "AND e.emp_gender = '$selectedGender'" : "") . "
          " . (!empty($selectedposition) ? "AND e.position = '$selectedposition'" : "") . "
          " . (!empty($selectedEmploymentType) ? "AND e.employment_type = '$selectedEmploymentType'" : "");

  $leavesExec = mysqli_query($conn, $leave) or die("FAILED TO CHECK LEAVES " . mysqli_error($conn));
  $leavesArray = mysqli_fetch_array($leavesExec);

  if ($leavesArray) {
    $numLeaves = $leavesArray['numLeaves'];
  }

  //undertime
  $undertime = "SELECT COUNT(e.emp_id) as undertime 
              FROM time_keeping t
             JOIN employees e ON t.emp_id = e.emp_id
              WHERE t.undertime_hours > 0 
              AND DATE(timekeep_day) BETWEEN '{$_SESSION['start_date']}' AND '{$_SESSION['end_date']}'
              " . (!empty($selectedDepartment) ? "AND e.dept_NAME = '$selectedDepartment' " : "")
    . (!empty($selectedGender) ? "AND e.emp_gender = '$selectedGender' " : "")
    . (!empty($selectedposition) ? "AND e.position = '$selectedposition' " : "")
    . (!empty($selectedEmploymentType) ? "AND e.employment_type = '$selectedEmploymentType' " : "");

  $undertimeExecQuery = mysqli_query($conn, $undertime) or die("FAILED TO CHECK UNDERTIME ATTENDANCE " . mysqli_error($conn));
  $undertimearray = mysqli_fetch_array($undertimeExecQuery);

  if ($undertimearray) {
    $undertimeatt = $undertimearray['undertime'];
  }






} else {
  $checkattendancemorning = "SELECT COUNT(emp_id) as morningatt FROM TIME_KEEPING WHERE DATE(timekeep_day) = CURDATE()";
  $checkattendancemorningexecquery = mysqli_query($conn, $checkattendancemorning) or die("FAILED TO CHECK MORNING ATTENDANCE " . mysqli_error($conn));
  $morningattarray = mysqli_fetch_array($checkattendancemorningexecquery);
  if ($morningattarray) {
    $morningatt = $morningattarray['morningatt'];
  }
  /** CHECK ATTENDANCE **/
  /** CHECK ABSENCES **/
  $checkabsences = "SELECT COUNT(emp_id) as numemps FROM employees WHERE emp_status = 'Active'";
  $checkabsencesexec = mysqli_query($conn, $checkabsences) or die("FAILED TO CHECK ABSENCES " . mysqli_error($conn));
  $absencesarray = mysqli_fetch_array($checkabsencesexec);
  if ($absencesarray) {
    $activeemps = $absencesarray['numemps'];

    $absencestoday = $activeemps - $morningatt;
  }

  //check gender
  $genderQuery = "SELECT 
                    COUNT(emp_id) as totalEmps,
                    SUM(CASE WHEN emp_gender = 'Male' THEN 1 ELSE 0 END) as numMales,
                    SUM(CASE WHEN emp_gender = 'Female' THEN 1 ELSE 0 END) as numFemales
                FROM employees 
                WHERE emp_status = 'Active'";

  $genderExec = mysqli_query($conn, $genderQuery) or die("FAILED TO CHECK ABSENCES " . mysqli_error($conn));
  $genderArray = mysqli_fetch_array($genderExec);

  if ($genderArray) {
    $totalEmps = $genderArray['totalEmps'];
    $numMales = $genderArray['numMales'];
    $numFemales = $genderArray['numFemales'];

  }


  //check late
  $late = "SELECT COUNT(emp_id) as late FROM TIME_KEEPING WHERE late_hours > 0 AND DATE(timekeep_day) = CURDATE()";
  $lateexecquery = mysqli_query($conn, $late) or die("FAILED TO CHECK MORNING ATTENDANCE " . mysqli_error($conn));
  $latearray = mysqli_fetch_array($lateexecquery);
  if ($latearray) {
    $lateatt = $latearray['late'];
  }



  //check on leave employees
  $leave = "SELECT COUNT(emp_id) as numLeaves
          FROM leaves_application
          WHERE leave_status = 'Approved' AND CURDATE() >= leave_datestart AND CURDATE() <= leave_dateend";

  $leavesExec = mysqli_query($conn, $leave) or die("FAILED TO CHECK ABSENCES " . mysqli_error($conn));
  $leavesArray = mysqli_fetch_array($leavesExec);

  if ($leavesArray) {
    $leavesArray['numLeaves'];


  }

  //check undertime
  $undertime = "SELECT COUNT(emp_id) as undertime FROM TIME_KEEPING WHERE undertime_hours > 0 AND DATE(timekeep_day) = CURDATE()";
  $undertimeexecquery = mysqli_query($conn, $undertime) or die("FAILED TO CHECK MORNING ATTENDANCE " . mysqli_error($conn));
  $undertimearray = mysqli_fetch_array($undertimeexecquery);
  if ($undertimearray) {
    $undertimeatt = $undertimearray['undertime'];
  }
}




















/** CHECK ATTENDANCE **/

$departmentQuery = "SELECT DISTINCT dept_NAME FROM department";
$departmentResult = $conn->query($departmentQuery);
$emptypeQuery = "SELECT DISTINCT employment_TYPE FROM employmenttypes";
$emptypeResult = $conn->query($emptypeQuery);
?>

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
      <div id="breadcrumb"> <a href="#" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a></div>

    </div>

    <div class="container-fluid">
      <div class="row-fluid">
        <div class="span12">
          <div class="widget-box">
            <div class="widget-title">
              <ul class="nav nav-tabs" id="myTab">
                <li class="active"><a href="adminDashboard.php"><i class="icon-file"></i> Dashboard</a></li>
                <li><a href="adminATTENDANCEDaily.php"><i class="icon-calendar"></i> Daily Attendance</a></li>
                <li><a href="adminACTIVITYLogs.php"><i class="icon-time"></i> Activity Logs</a></li>

              </ul>
            </div>
            <span class="span3">

            </span>
            <!--Header-->
            <span class="span6">
              <h3>
                <center>Daily Statistics</center>
              </h3>
              <h4>
                <center><b>
                    <?php echo $currtime; ?>
                  </b></center>
              </h4>
            </span>
            <span class="span3">

            </span>
          </div>

          <div class="row-fluid">
            <span class="span3">



            </span>
            <span class="span3">

            </span>
          </div>

          <center>

            <form action="adminDASHBOARD.php" method="GET">
              <?php
              $query2 = "SELECT * FROM department";
              $total_row_departments = mysqli_query($conn, $query2) or die('error');
              ?>

              <label for="department">Select Department:</label>

              <?php
              if (mysqli_num_rows($total_row_departments) > 0) {
                ?>
                <select name="department">
                  <option value="">Select Department</option>
                  <?php
                  foreach ($total_row_departments as $row) {
                    $selected = (isset($_GET['department']) && $_GET['department'] == $row['dept_NAME']) ? 'selected' : '';
                    ?>
                    <option value="<?php echo $row['dept_NAME']; ?>" <?php echo $selected; ?>>
                      <?php echo $row['dept_NAME']; ?>
                    </option>
                    <?php
                  }
                  ?>
                </select>
                <?php
              } else {
                echo 'No Data Found';
              }
              ?>

              <?php
              $query1 = "SELECT * FROM employmenttypes";
              $total_row = mysqli_query($conn, $query1) or die('error');
              ?>

              <h6></h6>

              <?php
              if (mysqli_num_rows($total_row) > 0) {
                ?>
                <select name="employmenttype">
                  <option value="">Select Employment Type</option>
                  <?php
                  foreach ($total_row as $row) {
                    $selected = (isset($_GET['employmenttype']) && $_GET['employmenttype'] == $row['employment_TYPE']) ? 'selected' : '';
                    ?>
                    <option value="<?php echo $row['employment_TYPE']; ?>" <?php echo $selected; ?>>
                      <?php echo $row['employment_TYPE']; ?>
                    </option>
                    <?php
                  }
                  ?>
                </select>
                <?php
              } else {
                echo 'No Data Found';
              }
              ?>
              <?php
              $query3 = "SELECT * FROM position";
              $total_row = mysqli_query($conn, $query3) or die('error');
              ?>

              <h6>Employment Type</h6>

              <?php
              if (mysqli_num_rows($total_row) > 0) {
                ?>
                <select name="position">
                  <option value="">Select Position: </option>
                  <?php
                  foreach ($total_row as $row) {
                    $selected = (isset($_GET['position']) && $_GET['position'] == $row['position_name']) ? 'selected' : '';
                    ?>
                    <option value="<?php echo $row['position_name']; ?>" <?php echo $selected; ?>>
                      <?php echo $row['position_name']; ?>
                    </option>
                    <?php
                  }
                  ?>
                </select>
                <?php
              } else {
                echo 'No Data Found';
              }
              ?>

              <label for="Gender">Select Gender:</label>
              <select name="gender" id="gender">
                <option value="">Select Gender</option>
                <option value="Male" <?php echo ($_GET['gender'] == 'Male') ? 'selected' : ''; ?>>Male</option>
                <option value="Female" <?php echo ($_GET['gender'] == 'Female') ? 'selected' : ''; ?>>Female</option>
              </select>

              <div class="controls">
                <!-- <input type="text" id="daterange" name="daterange" value="" autocomplete="off" /> -->
                <input type="text" id="daterange" name="daterange"
                  value="<?php echo (isset($_SESSION['start_date']) ? htmlspecialchars($_SESSION['start_date'] . ' - ' . ($_SESSION['end_date'] ?? '')) : date("Y-m-d")); ?>" />
              </div>
        </div>

        <center>
          <input type="text" id="start_date" name="daterange_start"
            value="<?php echo htmlspecialchars(isset($_SESSION['start_date']) ? $_SESSION['start_date'] : date("Y-m-d")); ?>" />
          <input type="text" id="end_date" name="daterange_end"
            value="<?php echo htmlspecialchars(isset($_SESSION['end_date']) ? $_SESSION['end_date'] : date("Y-m-d")); ?>" />
          <input type="submit" value="submit" name="submit">
        </center>
        </form>
        <a href="adminDASHBOARD.php" class="btn btn-success" style="float:right; margin-left: 4px;">
          <span class="icon"><i class="icon-refresh"></i></span> Refresh
        </a>
        </center>



        <!--Graph-->
        <center>
          <div class="chart-container" style="position: relative; height:50vh; width:70vw;">
            </script>
            <canvas id="genderChart"></canvas>

            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script>
              var ctxGender = document.getElementById('genderChart').getContext('2d');

              var genderData = {
                labels: ['Male', 'Female'],
                datasets: [{
                  data: [<?php echo $numMales; ?>, <?php echo $numFemales; ?>],
                  backgroundColor: ['#3498db', '#e74c3c'],
                }]
              };

              var genderChart = new Chart(ctxGender, {
                type: 'pie',
                data: genderData,
                options: {
                  title: {
                    display: true,
                    text: 'Gender Distribution'
                  }
                }
              });
            </script>
        </center>





        <div class="row-fluid">
          <span class="span2">
          </span>

          <span class="span4">
            <div class="widget-box">
              <div class="widget-title"><span class="icon"><i class="icon-user"></i></span>
                <h5> Attendance </h5>
              </div>
              <div class="widget-content no padding">

                <span class="row-fluid">

                  <span class="span4">
                    <h5>
                      <center>Present Employees</center>
                    </h5>
                  </span>
                  <span class="span4">
                    <h5>
                      <center>Absences</center>
                    </h5>
                  </span>

                  <span class="span4">
                  </span>

                </span>

                <span class="row-fluid">

                  <span class="span4">
                    <h1>
                      <center>
                        <?php echo $morningatt ?>
                      </center>
                    </h1>
                    <!-- <h1><center>0</center></h1> -->
                  </span>
                  <span class="span4">
                    <h1>
                      <center>
                        <?php echo $absencestoday ?>
                      </center>
                    </h1>
                    <!-- <h1><center>0</center></h1> -->
                  </span>
                  <span class="span4">
                    <a href="adminAttendanceDaily.php" class="btn btn-info"><span class="icon"><i
                          class="icon-calendar"></i></span> Daily Attendance</a>
                  </span>
                </span>
                <!-- attendance -->

              </div>
            </div>
          </span>

          <!--gender  -->
          <div style="position: relative; bottom:20px; left:-180px;">
          <div class="row-fluid">
            <span class="span2">
            </span>

            <span class="span4">
              <div class="widget-box">
                <div class="widget-title"><span class="icon"><i class="icon-user"></i></span>
                  <h5> GENDER </h5>
                </div>
                <div class="widget-content no padding">

                  <span class="row-fluid">

                    <span class="span4">
                      <h5>
                        <center>Male</center>
                      </h5>
                    </span>
                    <span class="span4">
                      <h5>
                        <center>Female</center>
                      </h5>
                    </span>

                    <span class="span4">
                    </span>

                  </span>

                  <span class="row-fluid">

                    <span class="span4">
                      <h1>
                        <center>
                          <?php echo ($genderArray['numMales'] == 0) ? '0' : $genderArray['numMales']; ?>
                        </center>
                      </h1> <!-- <h1><center>0</center></h1> -->
                    </span>
                    <span class="span4">
                      <h1>
                        <center>
                          <?php echo ($genderArray['numFemales'] == 0) ? '0' : $genderArray['numFemales']; ?>
                        </center>
                      </h1> <!-- <h1><center>0</center></h1> -->
                    </span>
                    <span class="span4">
                      <a href="adminAttendanceDaily.php" class="btn btn-info"><span class="icon"><i
                            class="icon-calendar"></i></span> Daily Attendance</a>
                    </span>
                  </span>
                </div>
              </div>
            </span>
          </div>
          </div>
          <span class="span2">
          </span>
        </div>




        <hr>

        <div class="row-fluid">
          <span class="span2">
          </span>

          <span class="span4">
            <div class="widget-box">
              <div class="widget-title"><span class="icon"><i class="icon-user"></i></span>
                <h5> Total Employees </h5>
              </div>
              <div class="widget-content no padding">
                <span class="row-fluid">

                  <span class="span6">
                    <h5>
                      <center>You have</center>
                    </h5>
                  </span>
                  <span class="span6">

                  </span>

                </span>

                <span class="row-fluid">

                  <span class="span6">
                    <h1>
                      <center>
                        <?php echo $absencesarray['numemps']; ?>
                      </center>
                    </h1>
                    <!-- <h1><center>1</center></h1> -->
                  </span>
                  <span class="span1">
                  </span>
                  <span class="span5">
                    <!-- <a href ="OVERTIME/adminOT.php" class = "btn btn-success"><span class="icon"><i class="icon-time"></i></span> Manage Overtimes</a> -->
                  </span>

                </span>
                <span class="row-fluid">

                  <span class="span6">
                    <h5>
                      <center>employees</center>
                    </h5>
                  </span>

                  <span class="span6">

                  </span>

                </span>


              </div>
            </div>
          </span>
          <!-- late -->

          <span class="span2">
          </span>
          <div style="position: relative; bottom:50px; left:50px;">
          <div class="row-fluid">
            <span class="span2">
            </span>

            <span class="span4">
              <div class="widget-box">
                <div class="widget-title"><span class="icon"><i class="icon-user"></i></span>
                  <h5> Total Late </h5>
                </div>
                <div class="widget-content no padding">
                  <span class="row-fluid">

                    <span class="span6">
                      <h5>
                        <center>You have</center>
                      </h5>
                    </span>
                    <span class="span6">

                    </span>

                  </span>

                  <span class="row-fluid">

                    <span class="span6">
                      <h1>
                        <center>
                          <?php echo $latearray['late']; ?>
                        </center>
                      </h1>
                      <!-- <h1><center>1</center></h1> -->
                    </span>
                    <span class="span1">
                    </span>
                    <span class="span5">
                      <!-- <a href ="OVERTIME/adminOT.php" class = "btn btn-success"><span class="icon"><i class="icon-time"></i></span> Manage Overtimes</a> -->
                    </span>

                  </span>
                  <span class="row-fluid">

                    <span class="span6">
                      <h5>
                        <center>late employees</center>
                      </h5>
                    </span>

                    <span class="span6">

                    </span>

                  </span>


                </div>
              </div>
            </span>
            <!-- leave -->
            <span class="span2">
            </span>

          </div>
          </div>


          <div class="row-fluid">
            <span class="span2">
            </span>

            <span class="span4">
              <div class="widget-box">
                <div class="widget-title"><span class="icon"><i class="icon-user"></i></span>
                  <h5> Total Employee on Leave </h5>
                </div>
                <div class="widget-content no padding">
                  <span class="row-fluid">

                    <span class="span6">
                      <h5>
                        <center>You have</center>
                      </h5>
                    </span>
                    <span class="span6">

                    </span>

                  </span>

                  <span class="row-fluid">

                    <span class="span6">
                      <h1>
                        <center>
                          <?php echo $leavesArray['numLeaves']; ?>
                        </center>
                      </h1>
                      <!-- <h1><center>1</center></h1> -->
                    </span>
                    <span class="span1">
                    </span>
                    <span class="span5">
                      <!-- <a href ="OVERTIME/adminOT.php" class = "btn btn-success"><span class="icon"><i class="icon-time"></i></span> Manage Overtimes</a> -->
                    </span>

                  </span>
                  <span class="row-fluid">

                    <span class="span6">
                      <h5>
                        <center>employees on leave</center>
                      </h5>
                    </span>

                    <span class="span6">

                    </span>

                  </span>


                </div>
              </div>
            </span>

            <!-- udnertime -->
            <span class="span2">
            </span>


            <div style="position: relative; bottom:50px; left:50px;">
            <div class="row-fluid">
              <span class="span2">
              </span>

              <span class="span4">
                <div class="widget-box">
                  <div class="widget-title"><span class="icon"><i class="icon-user"></i></span>
                    <h5> Total Undertime </h5>
                  </div>
                  <div class="widget-content no padding">
                    <span class="row-fluid">

                      <span class="span6">
                        <h5>
                          <center>You have</center>
                        </h5>
                      </span>
                      <span class="span6">

                      </span>

                    </span>

                    <span class="row-fluid">

                      <span class="span6">
                        <h1>
                          <center>
                            <?php echo $undertimearray['undertime'];
                            ; ?>
                          </center>
                        </h1>
                        <!-- <h1><center>1</center></h1> -->
                      </span>
                      <span class="span1">
                      </span>
                      <span class="span5">
                        <!-- <a href ="OVERTIME/adminOT.php" class = "btn btn-success"><span class="icon"><i class="icon-time"></i></span> Manage Overtimes</a> -->
                      </span>

                    </span>
                    <span class="row-fluid">

                      <span class="span6">
                        <h5>
                          <center>undertime employees</center>
                        </h5>
                      </span>

                      <span class="span6">

                      </span>

                    </span>


                  </div>
                </div>
              </span>
              <!-- udnertime -->




            </div>
            </div>


          </div>
          <hr>
          <div class="row-fluid">
            <span class="span2">
            </span>

            <span class="span4">
              <div class="widget-box">
                <div class="widget-title"><span class="icon"><i class="icon-user"></i></span>
                  <h5> Overtime Applications </h5>
                </div>
                <div class="widget-content no padding">
                  <span class="row-fluid">

                    <span class="span6">
                      <h5>
                        <center>You have</center>
                      </h5>
                    </span>
                    <span class="span6">

                    </span>

                  </span>

                  <span class="row-fluid">

                    <span class="span6">
                      <h1>
                        <center>
                          <?php echo $otapps; ?>
                        </center>
                      </h1>
                      <!-- <h1><center>1</center></h1> -->
                    </span>
                    <span class="span1">
                    </span>
                    <span class="span5">
                      <a href="OVERTIME/adminOT.php" class="btn btn-success"><span class="icon"><i
                            class="icon-time"></i></span> Manage Overtimes</a>
                    </span>

                  </span>
                  <span class="row-fluid">

                    <span class="span6">
                      <h5>
                        <center>OVERTIME applications</center>
                      </h5>
                    </span>

                    <span class="span6">

                    </span>

                  </span>


                </div>
              </div>
            </span>

            <div style="position: relative; bottom:-1px; left:90px;">
            <span class="span4">
              <div class="widget-box">
                <div class="widget-title"><span class="icon"><i class="icon-calendar"></i></span>
                  <h5> Leave Applications </h5>
                </div>
                <div class="widget-content no padding">
                  <span class="row-fluid">

                    <span class="span6">
                      <h5>
                        <center>You have</center>
                      </h5>
                    </span>
                    <span class="span6">

                    </span>

                  </span>

                  <span class="row-fluid">

                    <span class="span6">
                      <!-- <h1><center><?php echo $leaveapps; ?></center></h1> -->
                      <h1>
                        <center>0</center>
                      </h1>
                    </span>
                    <span class="span1">
                    </span>
                    <span class="span5">
                      <a href="LEAVES/adminLEAVES.php" class="btn btn-success"><span class="icon"><i
                            class="icon-calendar"></i></span> Manage Leaves</a>
                    </span>

                  </span>
                  <span class="row-fluid">

                    <span class="span6">
                      <h5>
                        <center>LEAVE applications</center>
                      </h5>
                    </span>

                    <span class="span6">

                    </span>

                  </span>



                </div>
              </div>
              </div>
            </span>



            <!-- end -->

            </span>
            <span class="span2">
            </span>
          </div>
        </div>

        
        <span class="span4">
        <div style="position: relative; bottom:-20px; left:500px;">
        <div class="widget-box">
            <div class="widget-title"><span class="icon"><i class="icon-calendar"></i></span>
              <h5> Payroll Period </h5>
            </div>
            <div class="widget-content no padding">
              <br>
              <span>
                <center>Current Payroll Period:</center>
              </span>
              <h3>
                <center>
                  <?php echo $currpperiod; ?>
                </center>
              </h3>
              <!-- <h3><center>2018-02-01 to 2018-02-15</center></h3> -->


            </div>
          </div>
          </div>
        </span>
        <span class="span2">
        </span>
            
            

        <!-- <?php
        // unset($_SESSION['masterfilenotif']);
        ?> -->




      </div>

    </div>
  </div>
  <div class="row-fluid">
    <div id="footer" class="span12"> 2023 &copy; WEB-BASED TIMEKEEPING AND PAYROLL SYSTEM USING FINGERPRINT BIOMETRICS
    </div>
  </div>

  <script src="../js/maruti.dashboard.js"></script>
  <script>
    $(document).ready(function () {
      // Initialize the daterangepicker with the default values
      $('#daterange').daterangepicker({
        opens: 'left',
        locale: {
          format: 'YYYY-MM-DD'
        }
      });

      // Set the initial values directly to the input fields
      var startDateInput = $('#start_date');
      var endDateInput = $('#end_date');

      // Update the values when the date range changes
      $('#daterange').on('apply.daterangepicker', function (ev, picker) {
        if (picker.startDate && picker.endDate) {
          startDateInput.val(picker.startDate.format('YYYY-MM-DD'));
          endDateInput.val(picker.endDate.format('YYYY-MM-DD'));
        }
      });

      // Trigger the apply event to set the initial values
      $('#daterange').trigger('apply.daterangepicker');

      // Set the initial values for start_date and end_date from PHP
      var start_date_php = '<?php echo isset($_GET['daterange_start']) ? htmlspecialchars($_GET['daterange_start']) : (isset($_SESSION['start_date']) ? htmlspecialchars($_SESSION['start_date']) : date("Y-m-d")); ?>';
      var end_date_php = '<?php echo isset($_GET['daterange_end']) ? htmlspecialchars($_GET['daterange_end']) : (isset($_SESSION['end_date']) ? htmlspecialchars($_SESSION['end_date']) : date("Y-m-d")); ?>';

      startDateInput.val(start_date_php);
      endDateInput.val(end_date_php);

      console.log('Start Date:', start_date_php);
      console.log('End Date:', end_date_php);
    });



</body >
</html >