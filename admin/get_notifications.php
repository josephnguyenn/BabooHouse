<?php
require '../config/database.php';

function get_notifications($user_id) {
    global $conn;
    $sql = "SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    return $result;
}
?>