<?php
session_start();
require '../config/database.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id']) && isset($_SESSION['user_id'])) {
        $notificationId = $_POST['id'];
        $userId = $_SESSION['user_id'];

        $sql = "UPDATE notifications SET is_read = 1 WHERE id = ? AND user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $notificationId, $userId);
        $stmt->execute();
        $sql = "SELECT building_id FROM notifications WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i",$notificationId);;
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $buildingId = $row['building_id'];
            header('Location: ../templates/edit_rooms.php?building_id=' . $buildingId); 
        } else {
            header('Location: ../templates/notifications.php?error=no_building_found');
        }
        exit;
    }
}
$conn->close();
?>