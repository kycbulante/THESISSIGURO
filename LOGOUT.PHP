<?php
include 'dbconfig.php';
include 'logfunctions.php';
session_start();

// Log the logout action
if (isset($_SESSION['empID'])) {
    $userId = $_SESSION['empID'];
    $logoutTime = date('Y-m-d H:i:s');
    
    // Assuming you have a function like logLogout in your dbconfig.php
    logLogout($conn, $userId, $logoutTime);
} 
if(isset($_SESSION['adminId'])) {
    $userId = $_SESSION['adminId'];
    $logoutTime = date('Y-m-d H:i:s');
    
    // Assuming you have a function like logLogout in your dbconfig.php
    adminlogLogout($conn, $userId, $logoutTime);
}

// Unset and destroy the session
session_unset();
session_destroy();

// Redirect to the login page
header("Location: ADMINLOGIN.php");
?>
