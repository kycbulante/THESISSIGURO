<?php
// Assuming you have a database connection established
require 'connectDB.php';

if (isset($_GET['id'])) {
    $empId = $_GET['id'];

    // Your database query to get the employee name based on the ID
    $query = "SELECT user_name FROM employees WHERE emp_id = $empId";
    $result = mysqli_query($conn1, $query);

    if ($result) {
        $row = mysqli_fetch_assoc($result);

        if ($row) {
            // Return the employee name
            echo $row['user_name'];
        } else {
            // No result found, return an empty string or default value
            echo '';
        }
    } else {
        // Database query error
        echo '';
    }
} else {
    // Invalid or missing employee ID parameter
    echo 'Invalid or missing employee ID parameter';
}
?>
