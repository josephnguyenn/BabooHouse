<?php
session_start();
require '../config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($conn !== null && !$conn->connect_error) {
        $stmt = $conn->prepare("SELECT user_id, username, password FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user && $password === $user['password']) {  // Check for plain text password match
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            header("Location: dashboard.php");
            exit();
        } else {
            echo '<div class="error">Login failed! Username or password is incorrect.</div>';
        }
        $stmt->close();
    } else {
        echo '<div class="error">Database connection is not available.</div>';
    }
    $conn->close();
}
?>
