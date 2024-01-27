<?php
// Assuming you have a database connection established
require 'connectDB.php';

// Your database query
$query = "SELECT emp_id, user_name FROM employees WHERE fingerprint_id = 0";
$result = mysqli_query($conn1, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link rel="stylesheet" type="text/css" href="css/manageusers.css">
    <script src="https://code.jquery.com/jquery-3.3.1.js"
            integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="
            crossorigin="anonymous"></script>
    <script src="js/jquery-2.2.3.min.js"></script>
    <script src="js/manage_users.js"></script>
    <script>
        $(document).ready(function () {
            $.ajax({
                url: "manage_users_up.php"
            }).done(function (data) {
                $('#manage_users').html(data);
            });

            setInterval(function () {
                $.ajax({
                    url: "manage_users_up.php"
                }).done(function (data) {
                    $('#manage_users').html(data);
                });
            }, 5000);
        });

        // Function to update the user_name input field based on the selected emp_id
        function updateUserName() {
            var selectedEmpId = $('#emp_id').val();

            // Use AJAX to fetch the corresponding employee name from the server
            $.get('get_employee_name.php', {id: selectedEmpId}, function (response) {
                // Update the user_name input field with the fetched name
                $('#user_name').val(response);
            }).fail(function () {
                console.error('Error fetching employee name');
            });
        }

        // Trigger the update on page load
        $(document).ready(function () {
            updateUserName();
        });

        // Add event listener to the emp_id dropdown to update on change
        $('#emp_id').change(function () {
            updateUserName();
        });
    </script>
</head>
<body>
<?php include 'header.php'; ?>
<main>
    <h1 class="slideInDown animated">USER MANAGER <br> (ADMIN)</h1>
    <div class="form-style-5 slideInDown animated">
        <div class="alert">
            <label id="alert"></label>
        </div>
        <form>
            <fieldset>
                <legend><span class="number">1</span> User Fingerprint ID:</legend>
                <label>Enter Fingerprint ID between 1 & 127:</label>
                <input type="number" name="fingerid" id="fingerid" placeholder="User Fingerprint ID...">
                <label for="emp_id">Select Employee:</label>
                <select name="emp_id" id="emp_id">
                    <?php
                    // Check if there are rows in the result set
                    if ($result && mysqli_num_rows($result) > 0) {
                        // Loop through the query result and create options
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<option value='{$row['emp_id']}'>{$row['emp_id']}</option>";
                        }
                    } else {
                        // No rows in the result set, handle accordingly (e.g., display a default option)
                        echo "<option value='' disabled>No employees available</option>";
                    }
                    ?>
                </select>

                <label for="emp_name">Employee Name:</label>
                <input type="text" name="user_name" id="user_name" readonly>
                <button type="button" name="fingerid_add" class="fingerid_add">Add Fingerprint ID</button>
            </fieldset>
            <fieldset>
                <!-- Additional fields or form elements go here -->
            </fieldset>
            <fieldset>
                <!-- Additional fields or form elements go here -->
            </fieldset>
        </form>
    </div>

    <div class="section">
        <!-- User table -->
        <div class="tbl-header slideInRight animated">
            <table cellpadding="0" cellspacing="0" border="0">
                <thead>
                <tr>
                    <th>Fingerprint ID</th>
                    <th>Emp ID</th>
                    <th>Username</th>
                    <th>Action</th>
                </tr>
                </thead>
            </table>
        </div>
        <div class="tbl-content slideInRight animated">
            <table cellpadding="0" cellspacing="0" border="0">
                <div id="manage_users"></div>
            </table>
        </div>
    </div>
</main>
</body>
</html>
