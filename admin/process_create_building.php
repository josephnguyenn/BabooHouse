<?php
session_start();
require '../config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $user_id = $_POST['user_id'];
    $street = $_POST['street'];
    $city = $_POST['city'];
    $district = $_POST['district'];
    $rental_price = $_POST['rental_price'];
    $owner_name = $_POST['owner_name'];
    $owner_phone = $_POST['owner_phone'];
    $building_type = $_POST['building_type'];
    $electricity_price = $_POST['electricity_price'];
    $water_price = $_POST['water_price']; 
    $description = $_POST['description'];

    
    $sql = "INSERT INTO buildings (user_id, name, street, city, district, rental_price, owner_name, owner_phone, building_type, electricity_price, water_price, description, last_modified) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    
    if ($stmt === false) {
        die("MySQL prepare error: " . $conn->error);
    }

    $stmt->bind_param("isssssssssss", $user_id, $name, $street, $city, $district, $rental_price, $owner_name, $owner_phone, $building_type, $electricity_price, $water_price, $description);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $building_id = $conn->insert_id;

        $admin_sql = "SELECT user_id FROM users WHERE role = 'admin'";
        $admin_result = $conn->query($admin_sql);

        if ($admin_result) {
            $message = "Yêu cầu toà nhà '$name' đang chờ duyệt.";
            while ($admin = $admin_result->fetch_assoc()) {
                $admin_id = $admin['user_id'];
                $notification_sql = "INSERT INTO notifications (user_id, building_id, message, type, created_at) VALUES (?, ?, ?, 'building', NOW())";
                $notification_stmt = $conn->prepare($notification_sql);
                
                if ($notification_stmt) {
                    $notification_stmt->bind_param("iis", $admin_id, $building_id, $message);
                    $notification_stmt->execute();
                    
                    if ($notification_stmt->affected_rows <= 0) {
                        echo "Failed to insert notification for admin ID $admin_id: " . $notification_stmt->error;
                    }

                    $notification_stmt->close();
                } else {
                    echo "MySQL prepare error for notification: " . $conn->error;
                }
            }
        } else {
            echo "Error fetching admins: " . $conn->error;
        }

        echo "New building created successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
    
    ob_start();
    header("Location: ../templates/manage_buildings.php");
    ob_end_flush();
    exit();
}
?>