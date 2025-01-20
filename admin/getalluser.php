<?php
require '../config/database.php';

function getAllUsers($search = '') {
    global $conn;
    $sql = "SELECT user_id, name, address, hometown, birthdate, phone, email, username, role, last_access FROM users";
    if ($search) {
        $search = '%' . $conn->real_escape_string($search) . '%';
        $sql .= " WHERE name LIKE ? OR username LIKE ?";
    }
    $stmt = $conn->prepare($sql);
    if ($search) {
        $stmt->bind_param("ss", $search, $search);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    return $result;
}
?>