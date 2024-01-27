<?php

include("DBCONFIG.PHP");
include("logfunctions.PHP");

session_start();

if (isset($_POST['login_btn'])) {
    $username = $_POST['adminUser'];
    $password = $_POST['adminPass'];
    $account = '1';

    $_SESSION['uname'] = $username;

    $username = stripcslashes($username);
    $password = stripcslashes($password);
    $username = mysqli_real_escape_string($conn, $username);
    $password = mysqli_real_escape_string($conn, $password);

    $result = mysqli_query($conn, "SELECT * FROM employees WHERE user_name = '$username' AND pass_word = '$password'")
        or die("Failed to query database " . mysqli_error($conn));

    $row = mysqli_fetch_array($result);
    if ($row['user_name'] == $username && $row['pass_word'] == $password && $row['acct_type'] == "Administrator") {
        $userId = getUserId($conn, $username);
        $_SESSION['adminId'] = $userId;
        $_SESSION['empId'] = $userId;
        $_SESSION['master'] = false;
        logActivity($conn, $userId, 'Administrator Login');
        adminlogActivity($conn, $userId, 'Administrator Login');
        header("location: Admin/admindashboard.php");
    } else if ($row['user_name'] == $username && $row['pass_word'] == $password && $row['acct_type'] == "Employee") {
        $userId = getUserId($conn, $username);
        $_SESSION['empId'] = $userId;
        logActivity($conn, $userId, 'Employee Login');
        header("Location: Employee/empDASHBOARD.php");
    } else if ($row['user_name'] == $username && $row['pass_word'] == $password && $row['acct_type'] == "Master") {
        $userId = getUserId($conn, $username);
        $_SESSION['adminId'] = $userId;
        $_SESSION['empId'] = $userId;
        $_SESSION['master'] = true;
        logActivity($conn, $userId, 'Master Administrator Login');
        adminlogActivity($conn, $userId, 'Master Administrator Login');
        header("location: Admin/admindashboard.php");
    }else {
        
        // <script type="text/javascript">
            // alert("Your account does not exist.");
            // history.back();
        // </script> 
        $_SESSION ['status'] = "Invalid Username or Password.";
        header('location: adminlogin.php');

    }
}


?>
