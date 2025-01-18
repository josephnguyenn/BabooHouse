<?php
session_start();

// Ensure the user is logged in, otherwise redirect to login page
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

// Include the header
include '../includes/header.php';
?>

