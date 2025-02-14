<?php
session_start();
require '../config/database.php';
require '../config/google_drive.php'; // ✅ Include Google Drive service

// Function to write logs
function logMessage($message) {
    $logFile = '../logs/error_log.txt'; // Log file location
    file_put_contents($logFile, date('Y-m-d H:i:s') . " - " . $message . "\n", FILE_APPEND);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    logMessage("🚀 Starting building creation...");

    // Capture form data
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

    logMessage("✔ Received form data: Name - $name, User ID - $user_id");

    // ✅ Insert building data (WITHOUT the photo first)
    $sql = "INSERT INTO buildings (user_id, name, street, city, district, rental_price, owner_name, owner_phone, building_type, electricity_price, water_price, description, last_modified)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

    $stmt = $conn->prepare($sql);
    
    if ($stmt === false) {
        logMessage("❌ MySQL prepare error: " . $conn->error);
        die("MySQL prepare error: " . $conn->error);
    }

    $stmt->bind_param("isssssssssss", $user_id, $name, $street, $city, $district, $rental_price, $owner_name, $owner_phone, $building_type, $electricity_price, $water_price, $description);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $building_id = $stmt->insert_id;
        logMessage("✅ New building created with ID: $building_id");

        // ✅ Handle file upload **AFTER** creating the building
        $googleDriveService = new GoogleDriveService($conn);
        $photo_url = null;

        if (isset($_FILES["building_image"]) && $_FILES["building_image"]["error"] == UPLOAD_ERR_OK) {
            logMessage("✔ File detected, proceeding with Google Drive upload...");

            $photo_url = $googleDriveService->uploadFileAndSave($_FILES["building_image"], $building_id);

            if ($photo_url) {
                logMessage("✅ Google Drive upload successful: $photo_url");

                // ✅ Update building with photo URL
                $update_sql = "UPDATE buildings SET photo_urls = ? WHERE building_id = ?";
                $update_stmt = $conn->prepare($update_sql);

                if ($update_stmt) {
                    $update_stmt->bind_param("si", $photo_url, $building_id);
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

        // ✅ Notify admins
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
                    if (!$notification_stmt->execute()) {
                        logMessage("⚠️ Failed to insert notification for admin ID $admin_id: " . $notification_stmt->error);
                    }
                    $notification_stmt->close();
                } else {
                    logMessage("⚠️ MySQL prepare error for notification: " . $conn->error);
                }
            }
        } else {
            logMessage("⚠️ Error fetching admins: " . $conn->error);
        }

        logMessage("✅ New building creation process completed successfully.");
    } else {
        logMessage("❌ Failed to insert building.");
        die("❌ Failed to insert building.");
    }

    $stmt->close();
    $conn->close();

    logMessage("✅ Redirecting to manage buildings page.");
    header("Location: ../templates/manage_buildings.php");
    exit();
}
?>
