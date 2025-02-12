<?php
session_start();
require '../config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $building_id = $_POST['building_id'];
    $room_name = $_POST['room_name'];
    $rental_price = $_POST['rental_price'];
    $area = $_POST['area'];
    $room_status = $_POST['room_status'];
    $photo_urls = '';
    
    
    if (isset($_FILES["photo_urls"]) && $_FILES["photo_urls"]["error"] == UPLOAD_ERR_OK) {
        $target_dir = "../uploads/rooms/";
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

    $sql = "INSERT INTO rooms (building_id, room_name, area, rental_price, room_status, photo_urls) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssss", $building_id, $room_name, $area, $rental_price, $room_status, $photo_urls);
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