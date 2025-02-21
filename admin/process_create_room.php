<?php
session_start();
require '../config/database.php';
require '../config/google_drive.php'; // ✅ Include Google Drive service

// Function to write logs (ensure directory exists)
function logMessage($message) {
    $logDir = '../logs';
    if (!is_dir($logDir)) {
        mkdir($logDir, 0777, true); // ✅ Create logs directory if missing
    }

    $logFile = $logDir . '/error_log.txt';
    file_put_contents($logFile, date('Y-m-d H:i:s') . " - " . $message . "\n", FILE_APPEND);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    logMessage("🚀 Starting room creation...");

    // Capture form data
    $building_id = $_POST['building_id'];
    $room_name = $_POST['room_name'];
    $rental_price = $_POST['rental_price'];
    $area = $_POST['area'];
    $room_status = $_POST['room_status'];
    $room_type = $_POST['room_type'];

    logMessage("✔ Received form data: Room - $room_name, Building ID - $building_id");

    // ✅ Insert room data (WITHOUT the photo first)
    $sql = "INSERT INTO rooms (building_id, room_name, area, rental_price, room_status, room_type) VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    
    if ($stmt === false) {
        logMessage("❌ MySQL prepare error: " . $conn->error);
        die("MySQL prepare error: " . $conn->error);
    }

    $stmt->bind_param("isssss", $building_id, $room_name, $area, $rental_price, $room_status, $room_type);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $room_id = $stmt->insert_id;
        logMessage("✅ New room created with ID: $room_id");

        // ✅ Handle file upload **AFTER** creating the room
        $googleDriveService = new GoogleDriveService($conn);
        $photo_url = null;

        if (isset($_FILES["photo_urls"]) && $_FILES["photo_urls"]["error"] == UPLOAD_ERR_OK) {
            logMessage("✔ File detected, proceeding with Google Drive upload...");

            $photo_url = $googleDriveService->uploadFileAndSave($_FILES["photo_urls"], $building_id, $room_name);

            if ($photo_url) {
                logMessage("✅ Google Drive upload successful: $photo_url");

                // ✅ Update room with photo URL
                $update_sql = "UPDATE rooms SET photo_urls = ? WHERE room_name = ? AND building_id = ?";
                $update_stmt = $conn->prepare($update_sql);

                if ($update_stmt) {
                    $update_stmt->bind_param("ssi", $photo_url, $room_name, $building_id);
                    $update_stmt->execute();
                    $update_stmt->close();
                    logMessage("✅ Database updated with photo URL.");
                } else {
                    logMessage("❌ Database update error: " . $conn->error);
                }
            } else {
                logMessage("❌ Google Drive upload failed.");
            }
        } else {
            logMessage("⚠️ No image uploaded or file error.");
        }

        // ✅ Cập nhật rental_price của buildings
        logMessage("🔄 Updating building rental price...");

        // ✅ Lấy giá thấp nhất và cao nhất của tất cả các phòng trong tòa nhà
        $price_sql = "SELECT MIN(rental_price) AS min_price, MAX(rental_price) AS max_price FROM rooms WHERE building_id = ?";
        $price_stmt = $conn->prepare($price_sql);
        $price_stmt->bind_param("i", $building_id);
        $price_stmt->execute();
        $price_stmt->bind_result($min_price, $max_price);
        $price_stmt->fetch();
        $price_stmt->close();

        if ($min_price !== null && $max_price !== null) {
            $updated_price = number_format($min_price, 0, '.', ',') . " - " . number_format($max_price, 0, '.', ',');

            // ✅ Cập nhật `rental_price` trong bảng `buildings`
            $update_building_sql = "UPDATE buildings SET rental_price = ? WHERE building_id = ?";
            $update_building_stmt = $conn->prepare($update_building_sql);
            $update_building_stmt->bind_param("si", $updated_price, $building_id);
            $update_building_stmt->execute();
            $update_building_stmt->close();

            logMessage("✅ Cập nhật giá tòa nhà: $updated_price");
        } else {
            logMessage("⚠️ Không có giá phòng hợp lệ để cập nhật.");
        }

        logMessage("✅ New room creation process completed successfully.");
    } else {
        logMessage("❌ Failed to insert room.");
        die("❌ Failed to insert room.");
    }

    $stmt->close();
    $conn->close();

    logMessage("✅ Redirecting to edit rooms page.");
    header("Location: ../templates/edit_rooms.php?building_id=" . $building_id);
    exit();
}
?>
