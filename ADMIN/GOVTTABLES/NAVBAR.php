<style>/* Override Bootstrap styles for dropdown */
#notification-icon:hover .dropdown-menu {
  display: none;
}

#notification-icon.open .dropdown-menu {
  display: block;
}
</style>

<div id="header">
  <h1><a href="dashboard.html">Admin Home</a></h1>
</div>

<div id="user-nav" class="navbar navbar-inverse">
  <ul class="nav"> 
    <li class=""><a title="" href="../LOGOUT.PHP"><i class="icon icon-share-alt"></i> <span class="text">Logout</span></a></li>
  </ul>
</div>
<div id="sidebar"><a href="#" class="visible-phone"><i class="icon icon-home"></i> Dashboard</a><ul>
    <li><a href="admindashboard.php"><i class="icon icon-home"></i> <span>Home</span></a> </li>
    <li><a href="../adminMasterfile.php"><i class="icon icon-file"></i> <span>Data Management</span></a> </li>
    <li><a href="../adminATTENDANCErecords.php"><i class="icon icon-calendar"></i> <span>Attendance Management</span></a> </li>
    <li><a href="../adminPAYROLLINFO.php"><i class="icon icon-tasks"></i> <span>Payroll</span></a> </li>
    <li><a href="../adminTimesheet.php"><i class="icon icon-tasks"></i> <span>Reports</span></a> </li>
    <li class="dropdown" id="notification-icon">
      <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <i class="icon icon-bell"></i>
        <span class="badge"></span>
      </a>
      <ul class="dropdown-menu">
        <!-- The list of notifications will be dynamically added here -->
      </ul>
    </li>
  </ul>
</div>
    <!-- <li class="submenu"><a href="adminMasterfile.php"><i class="icon icon-file"></i> <span></span></a> -->
		<ul>
		<!-- <li><a href="adminMasterfile.php">Employee Masterlist</a></li>
		<li><a href="adminMasterfileDept.php">Manage Departments</a></li>
		<li><a href="adminMasterfileShift.php">Manage Shifts</a></li>
    <li><a href="adminMasterfileHoliday.php">Manage Holidays</a></li>
    <li><a href="adminMasterfileLeaves.php">Manage Leaves</a></li> -->
		</ul>
		</li>
    <!-- <li class = "submenu"><a href="adminATTENDANCErecords.php"><i class="icon icon-calendar"></i> <span>Attendance Management</span></a> -->
      <ul>
        <!-- <li><a href="adminATTENDANCEdaily.php">Daily Attendance</a></li> -->
        <!-- <li><a href="adminATTENDANCErecords.php">Attendance Records</a></li> -->
        <!-- <li><a href="OVERTIME/adminOT.php">Overtimes</a></li> -->
        <!-- <li><a href="LEAVES/adminLEAVES.php">Leaves</a></li> -->

      </ul>
    </li>
    <!-- <li class="submenu"> <a href="adminPAYROLLINFO.php"><i class="icon icon-tasks"></i> <span>Payroll</span> <span class="label label-important"></span></a> -->
      <ul>
        <!-- <li><a href="adminPAYROLLPERIODS.php">Manage Payroll Periods</a></li>
        <li><a href="adminPAYROLLINFO.php">Employee Payroll Information</a></li>
        <li><a href="GOVTTABLES/adminGOVTTables.php">Government Contribution Tables</a></li>
        <li><a href="LOANS/adminSSSLoans.php">SSS Loans</a></li>
        <li><a href="LOANS/adminPAGIBIGLoans.php">PAG-IBIG Loans</a></li>
        <li><a href="adminPAYROLLProcess.php">Process Employee Payrolls</a></li>
        <li><a href="admin13thmonth.php">Compute 13th Month Pay</a></li> -->
      </ul>
    </li>

    <!-- <li class="submenu"> <a href="adminTimesheet.php"><i class="icon icon-tasks"></i> <span>Reports</span> <span class="label label-important"></span></a> -->
      <ul>
        <!-- <li><a href="adminTIMESHEET.php">Timesheets</a></li>
        <li><a href="adminDTR.php">Daily Time Records</a></li>
        <li><a href="adminPAYROLLRegister.php">Payroll Register</a></li>
        <li><a href="adminPAYROLLPrintPayslip.php">Payslips</a></li>
        <li><a href="REPORTS/adminGOVTReports.php">Government Contribution Reports</a></li>
        <li><a href="REPORTS/adminREPORT13thmonth.php">13th Month</a></li>
        <li><a href="REPORTS/adminREPORTyearly.php">Yearly Report</a></li> -->
      </ul>
    </li>
   
   
  </ul>
</div>
<script>
// Function to update the notification count and items
function updateNotifications() {
  // Make an AJAX request to your notifications.php
  $.ajax({
    url: '../notifications.php',
    type: 'GET',
    dataType: 'json',
    success: function(response) {
      // Update or create dropdown items
      var dropdownMenu = $('#notification-icon .dropdown-menu');
      dropdownMenu.empty();

      // If no unread notifications, display a message
      if (response.count === 0) {
        dropdownMenu.append('<li><a href="#">No new notifications</a></li>');
        // Hide the badge when there are no notifications
        $('#notification-icon .badge').hide();
      } else {
        // Update the badge count and show it
        $('#notification-icon .badge').text(response.count).show();

        // Iterate through each notification in the response
        response.notifications.forEach(function(notification) {
          // Specify the appropriate links for each notification based on its type
          var link = '';

          if (notification.type === 'Overtime') {
            link = '../OVERTIME/adminOT.php';
          } else if (notification.type === 'Leave') {
            link = '../LEAVES/adminLEAVES.php';
          }

          // Make notifications clickable and link to the appropriate page
          dropdownMenu.append('<li><a href="' + link + '">' + notification.message + '</a></li>');
        });
      }

      // Add "See All Notifications" link
      dropdownMenu.append('<li class="see-all"><a href="../all_notifications.php">See All Notifications</a></li>');
    },
    error: function(error) {
      console.error('Error checking notifications:', error.responseText);
    }
  });
}

// Use Bootstrap's built-in methods to handle dropdown toggle
$('#notification-icon').on('click', function (e) {
  e.stopPropagation(); // Prevent the event from reaching the document click handler
  $(this).toggleClass('open');

  // Clear the badge count when dropdown is opened
  $('#notification-icon .badge').text('');

  // Mark notifications as read when dropdown is clicked
  if ($(this).hasClass('open')) {
    markNotificationsAsRead();
  }
});

// Close the dropdown when clicking outside
$(document).on('click', function (e) {
  if (!$(e.target).closest('.dropdown').length) {
    $('#notification-icon').removeClass('open');
  }
});

// Function to mark notifications as read
function markNotificationsAsRead() {
  $.ajax({
    url: '../notifications.php',
    type: 'GET',
    data: { mark_as_read: true }, // Send a parameter to indicate marking as read
    success: function(response) {
      // Do something if needed
    },
    error: function(error) {
      console.error('Error marking notifications as read:', error.responseText);
    }
  });
}

// Initial update
updateNotifications();

// Set an interval to periodically update notifications (every 1 minute, adjust as needed)
setInterval(updateNotifications, 60000);

</script>
