<?php
session_start();
require '../config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    var_dump($_POST);
    $user_id = $_SESSION['user_id'];
    $title = $_POST['title'];
    $content = $_POST['content'];
    
    $sql = "INSERT INTO notifications (user_id, title, message, type) VALUES (?, ?, ?, 'admin')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $user_id, $title, $content);
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
