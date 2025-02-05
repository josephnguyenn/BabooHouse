<?php
require '../config/database.php';
function getAllBuildings($min_price = 0, $max_price = 5000, $selected_types = [], $user_id = NULL) {
    global $conn;
    
    $sql = "SELECT * FROM buildings WHERE rental_price BETWEEN ? AND ?";

    
    if (!empty($selected_types)) {
        $placeholders = implode(',', array_fill(0, count($selected_types), '?'));
        $sql .= " AND building_type IN ($placeholders)";
    }

    if ($user_id) {
        $sql .= " AND user_id = $user_id";
    }
    
    $stmt = $conn->prepare($sql);

    $params = [$min_price, $max_price];

    if (!empty($selected_types)) {
        $params = array_merge($params, $selected_types);
    }

    $binding_types = str_repeat('i', 2); // For min_price and max_price
    if (!empty($selected_types)) {
        $binding_types .= str_repeat('s', count($selected_types)); // For selected building types
    }

    $stmt->bind_param($binding_types, ...$params);

    $stmt->execute();
    $result = $stmt->get_result();
    return $result;
}

function getAllBuildingsOfUser($user_id) {
    global $conn;
    
    $sql = "SELECT * FROM buildings WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result;
}

function getDistinctBuildingTypes() {
    global $conn;
    $sql = "SELECT DISTINCT building_type FROM buildings";
    $result = $conn->query($sql);
    $types = [];
    while ($row = $result->fetch_assoc()) {
        $types[] = $row['building_type'];
    }
    return $types;
}
?>

