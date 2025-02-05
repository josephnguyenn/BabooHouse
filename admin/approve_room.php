<?php
session_start();
require '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['building_id']) && isset($_POST['action'])) {
    $building_id = $_POST['building_id'];
    $action = $_POST['action'];

    if ($action === 'approve') {
        $sql = "UPDATE buildings SET approved = 1 WHERE building_id = ?";
    } elseif ($action === 'stop') {
        $sql = "UPDATE buildings SET approved = 0 WHERE building_id = ?";
    } else {
        echo "Invalid action.";
        exit();
    }

    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }
    
    $stmt->bind_param("i", $building_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        header("Location: ../templates/edit_rooms.php?building_id=" . $building_id);
        exit();
    } else {
        echo "Error updating building status or no changes made: " . htmlspecialchars($stmt->error);
    }
    
    $stmt->close();
} else {
    echo "Invalid request.";
}

$conn->close();
?>