<?php
session_start();
require '../config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $title = $_POST['title'];
    $content = $_POST['content']; 

    $message = "[" . $title . "] " . $content;

    $sql = "INSERT INTO notifications (user_id, message, type) VALUES (?, ?, 'admin')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $user_id, $message);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "Thông báo đã được đăng.";
    } else {
        echo "Lỗi: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();

    header("Location: ../templates/home.php");
    exit();
}
?>
