<?php
session_start();

// Check if the user is already logged in, if yes then redirect to the dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: ./templates/home.php");
    exit;
}

// If not logged in, redirect to the login page
header("Location: ./admin/login.php");
exit;
?>
