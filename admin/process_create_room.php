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

    logMessage("✔ Received form data: Room - $room_name, Building ID - $building_id");

    // ✅ Insert room data (WITHOUT the photo first)
    $sql = "INSERT INTO rooms (building_id, room_name, area, rental_price, room_status) VALUES (?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    
    if ($stmt === false) {
        logMessage("❌ MySQL prepare error: " . $conn->error);
        die("MySQL prepare error: " . $conn->error);
    }

    $stmt->bind_param("issss", $building_id, $room_name, $area, $rental_price, $room_status);
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

                // ✅ Update room with photo URL (Fix: Use the correct primary key column)
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
