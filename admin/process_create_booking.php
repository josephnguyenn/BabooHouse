<?php
session_start();
require '../config/database.php';
include '../admin/getallbuilding.php';
include '../admin/getallroom.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION["user_id"];
    
    $building_id = $_POST["building_id"];
    $room_id = $_POST["room_id"];
    $guest_name = $_POST["guest_name"] ?? '';
    $phone = $_POST["phone"] ?? '';
    $identification_card = $_POST["identification_card"] ?? '';
    $deposit_term = $_POST["deposit_term"] ?? '';
    $signed_date = $_POST["signed_date"] ?? ''; 
    $payment_term = $_POST["payment_term"] ?? 0;
    $lease_start_date = $_POST["lease_start_date"] ?? '';   
    $photo_urls = '';

    if (!empty($lease_start_date) && is_numeric($payment_term)) {
        $start_date = new DateTime($lease_start_date);
        $start_date->modify("+$payment_term months");
        $lease_end_date = $start_date->format('Y-m-d');
    } else {
        echo "Invalid lease_start_date or payment_term.";
        exit();
    }

    if (isset($_FILES["photo_urls"]) && $_FILES["photo_urls"]["error"] == UPLOAD_ERR_OK) {
        $target_dir = "../uploads/contracts/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $file_name = time() . "_" . basename($_FILES["photo_urls"]["name"]);
        $target_file = $target_dir . $file_name;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $allowed_types = ["jpg", "jpeg", "png", "gif"];
        if (in_array($imageFileType, $allowed_types)) {
            if (move_uploaded_file($_FILES["photo_urls"]["tmp_name"], $target_file)) {
                $photo_urls = $target_file; 
            } else {
                echo "Error uploading photo.";
                exit();
            }
        } else {
            echo "Invalid file type.";
            exit();
        }
    }

    $sql = "INSERT INTO bookings (building_id, user_id, room_id, guest_name, phone, identification_card, deposit_term, signed_date, payment_term, lease_start_date, lease_end_date, photo_urls) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("iiisssssssss", $building_id, $user_id, $room_id, $guest_name, $phone, $identification_card, $deposit_term, $signed_date, $payment_term, $lease_start_date, $lease_end_date, $photo_urls);

    if ($stmt->execute()) {
        echo "New booking created successfully.";
        
        $booking_id = $conn->insert_id;

        $update_sql = "UPDATE rooms SET room_status = 'Đã thuê' WHERE room_id = ?";
        $update_stmt = $conn->prepare($update_sql);
        if (!$update_stmt) {
            die("Prepare failed: " . $conn->error);
        }

        $update_stmt->bind_param("i", $room_id);
        
        if ($update_stmt->execute()) {
            echo "Room status updated successfully.";
        } else {
            echo "Error updating room status: " . $update_stmt->error;
        }

        $update_stmt->close();

        $building = getInfoBuilding($building_id);
        $room = getInfoRoom($room_id);
        $message = "Đặt phòng: " . htmlspecialchars($building['name']) . " [" . htmlspecialchars($room['room_name']) . "] thành công";

        $notification_sql = "INSERT INTO notifications (user_id, booking_id, message, type, created_at) VALUES (?, ?, ?, 'booking', NOW())";
        $notification_stmt = $conn->prepare($notification_sql);

        if (!$notification_stmt) {
            echo "Prepare failed for notification: " . $conn->error;
        } else {
            $notification_stmt->bind_param("iis", $user_id, $booking_id, $message);
            
            if (!$notification_stmt->execute()) {
                echo "Error creating notification: " . $notification_stmt->error;
            }

            $notification_stmt->close();
        }

        $message = "Nhận yêu cầu đặt phòng: " . htmlspecialchars($building['name']) . " [" . htmlspecialchars($room['room_name']) . "] thành công";
        $notification_sql = "INSERT INTO notifications (user_id, booking_id, message, type, created_at) VALUES (?, ?, ?, 'booking', NOW())";
        $notification_stmt = $conn->prepare($notification_sql);

        if (!$notification_stmt) {
            echo "Prepare failed for notification: " . $conn->error;
        } else {
            $notification_stmt->bind_param("iis", $building['user_id'], $booking_id, $message);
            
            if (!$notification_stmt->execute()) {
                echo "Error creating notification: " . $notification_stmt->error;
            }

            $notification_stmt->close();
        }

        $admin_sql = "SELECT user_id FROM users WHERE role = 'admin'";
        $admin_result = $conn->query($admin_sql);

        if ($admin_result) {
            $message = "Vừa có một hợp đồng mới từ toà nhà ".$building['name'];
            while ($admin = $admin_result->fetch_assoc()) {
                $admin_id = $admin['user_id'];
                $notification_sql = "INSERT INTO notifications (user_id, booking_id, message, type, created_at) VALUES (?, ?, ?, 'booking', NOW())";
                $notification_stmt = $conn->prepare($notification_sql);
                
                if ($notification_stmt) {
                    $notification_stmt->bind_param("iis", $admin_id, $booking_id, $message);
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

    } else {
        echo "Error creating booking: " . $stmt->error;
    }

    $stmt->close(); 
    $conn->close();

    ob_start();
    header("Location: ../templates/view_building.php?building_id=" . $building_id);
    ob_end_flush();
    exit();    
}
?>