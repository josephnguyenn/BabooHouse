<?php
require '../config/database.php';

function getAllBookingsOfUser($user_id) {
    global $conn;
    
    $sql = "SELECT * FROM buildings WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $building_ids[] = $row['building_id'];
    }
    $building_ids_placeholder = implode(',', array_fill(0, count($building_ids), '?'));
    $sql = "SELECT * FROM bookings WHERE building_id IN ($building_ids_placeholder) OR user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param(str_repeat('i', count($building_ids)).'i', ...[...$building_ids, $user_id]);
    $stmt->execute();
    $bookings_result = $stmt->get_result();
    return $bookings_result;
}

function getAllBookings() {
    global $conn;
    $sql = "SELECT * FROM bookings";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $bookings_result = $stmt->get_result();
    return $bookings_result;
}
?>