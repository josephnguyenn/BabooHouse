<?php
// Set database connection credentials
$host = 'localhost';  // Host name
$username = 'root';  // Username for MySQL (default for XAMPP)
$password = '';  // Password for MySQL (empty by default in XAMPP)
$dbname = 'baboohouse';  // Database name

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    echo "Connected successfully to the database.";
}

// Optionally close the connection
$conn->close();
?>
