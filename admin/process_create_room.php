<?php
session_start();
require '../config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $building_id = $_POST['building_id'];
    $room_name = $_POST['room_name'];
    $rental_price = $_POST['rental_price'];
    $area = $_POST['area'];
    $room_status = $_POST['room_status'];
    
    $sql = "INSERT INTO rooms (building_id, room_name, area, rental_price, room_type, room_status) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssss", $building_id, $room_name, $area, $rental_price, $room_type, $room_status);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "New room created successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
    $conn->close();

    ob_start();
    header("Location: ../templates/edit_rooms.php?building_id=". $building_id);
    ob_end_flush();
    exit();
}
?>