<?php
require '../config/database.php';

function getAllBuildings($min_price = 0, $max_price = 10000, $selected_type = NULL, $user_id = NULL, $status_type = NULL, $city = NULL, $district = NULL) {
    global $conn;
    
    $sql = "SELECT * FROM buildings b WHERE b.rental_price BETWEEN ? AND ?";
    $params = [$min_price, $max_price];
    $binding_types = "ii";

    // if (!empty($selected_types)) {
    //     $placeholders = implode(',', array_fill(0, count($selected_types), '?'));
    //     $sql .= " AND b.building_type IN ($placeholders)";
    //     $params = array_merge($params, $selected_types);
    //     $binding_types .= str_repeat('s', count($selected_types));
    // }

    if ($selected_type !== NULL && $selected_type != '') {
        $sql .= " AND b.building_type = ?";
        $params[] = $selected_type;
        $binding_types .= "s";
    }
    if ($user_id !== NULL) {
        $sql .= " AND b.user_id = ?";
        $params[] = $user_id;
        $binding_types .= "i";
    }
    if (!empty($status_type)) {
        if ($status_type === "Hết phòng") {
            $sql .= " AND NOT EXISTS (
                        SELECT 1 
                        FROM rooms r 
                        WHERE r.building_id = b.building_id 
                        AND r.room_status = 'Còn trống'
                     )";
        } elseif ($status_type === "Còn phòng") {
            $sql .= " AND EXISTS (
                        SELECT 1 
                        FROM rooms r 
                        WHERE r.building_id = b.building_id 
                        AND r.room_status = 'Còn trống'
                     )";
        }
    }
    if ($city !== NULL && $city != '') {
        $sql .= " AND b.city = ?";
        $params[] = $city;
        $binding_types .= "s";
    }
    if ($district !== NULL && $district != '') {
        $sql .= " AND b.district = ?";
        $params[] = $district;
        $binding_types .= "s";
    }
    $stmt = $conn->prepare($sql);

    if (!empty($params)) {
        $stmt->bind_param($binding_types, ...$params);
    }

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
    // global $conn;
    // $sql = "SELECT DISTINCT building_type FROM buildings";
    // $result = $conn->query($sql);
    // $types = [];
    // while ($row = $result->fetch_assoc()) {
    //     $types[] = $row['building_type'];
    // }
    return ['Căn hộ/ Chung cư', 'Nhà ở', 'Văn phòng, Mặt bằng kinh doanh', 'Đất', 'Phòng trọ'];
}

function getInfoBuilding($building_id) {
    global $conn;
    $sql = "SELECT * FROM buildings WHERE building_id = ?"; 
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param('i', $building_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $building_info = $result->fetch_assoc();
    $stmt->close();
    return $building_info;
}
?>
