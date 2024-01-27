<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker@3.1.0/daterangepicker.css" />
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
<!-- <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script> -->
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker@3.1.0/daterangepicker.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<style>/* Override Bootstrap styles for dropdown */
#notification-icon:hover .dropdown-menu {
  display: none;
}

#notification-icon.open .dropdown-menu {
  display: block;
}
</style>
<div id="header">
  
</div>


<div id="sidebar"><a href="#" class="visible-phone"><i class="icon icon-home"></i> Dashboard</a><ul>
    <li class="active"><a href="empDASHBOARD.php"><i class="icon icon-home"></i> <span>Home</span></a> </li>
    <li class="dropdown" id="notification-icon">
      <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <i class="icon icon-bell"></i>
        <span class="badge"></span>
      </a>
      <ul class="dropdown-menu">
        <!-- The list of notifications will be dynamically added here -->
      </ul>
      <li class=""><a title="" href="../LOGOUT.PHP"><i class="icon icon-share-alt"></i> <span class="text">Logout</span></a></li>
    </li>
  </ul>
</div>
    <!--<li> <a href="charts.html"><i class="icon icon-signal"></i> <span>Charts &amp; graphs</span></a> </li>
    <li> <a href="widgets.html"><i class="icon icon-inbox"></i> <span>Widgets</span></a> </li>-->
    <!-- <li class="submenu"><a href=""><i class="icon icon-file"></i> <span>Control Panel</span></a> -->
		<!-- <ul>
		<li><a href="empAPPLYOvertime.php">Apply Overtime</a></li>
		<li><a href="empAPPLYLeave.php">Apply Leave</a></li>
    <li><a href="empATTENDANCErecords.php">View Employee Records</a></li>
    
		</ul>
		</li> -->
    
    
   
  </ul>
</div>
<script>
// Function to update the notification count and items
function updateNotifications() {
  // Make an AJAX request to your notifications.php
  $.ajax({
    url: 'empnotification.php',
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
            link = 'empAPPLYOvertime.php';
          } else if (notification.type === 'Leave') {
            link = 'empAPPLYLeave.php';
          } else if (notification.type === 'Loan') {
            link = 'empLoans.php';
          } else if (notification.type === 'Payroll') {
            link = 'empPAYROLLrecords.php';
          }

          // Make notifications clickable and link to the appropriate page
          dropdownMenu.append('<li><a href="' + link + '">' + notification.message + '</a></li>');
        });
      }

      // Add "See All Notifications" link
      dropdownMenu.append('<li class="see-all"><a href="all_notifications.php">See All Notifications</a></li>');
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
    url: 'empnotification.php',
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