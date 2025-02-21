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
    $owner_name = $_POST['owner_name'];
    $owner_phone = $_POST['owner_phone'];
    $building_type = $_POST['building_type'];
    $electricity_price = $_POST['electricity_price'];
    $water_price = $_POST['water_price'];
    $service_price = $_POST['service_price'];
    $description = $_POST['description'];
    $rooms = $_POST['rooms'];

    logMessage("✔ Received form data: Name - $name, User ID - $user_id");

    // ✅ Insert building data (WITHOUT the photo first)
    $sql = "INSERT INTO buildings (user_id, name, street, city, district, owner_name, owner_phone, building_type, electricity_price, water_price, service_price, description, last_modified)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

    $stmt = $conn->prepare($sql);
    
    if ($stmt === false) {
        logMessage("❌ MySQL prepare error: " . $conn->error);
        die("MySQL prepare error: " . $conn->error);
    }

    $stmt->bind_param("isssssssssss", $user_id, $name, $street, $city, $district, $owner_name, $owner_phone, $building_type, $electricity_price, $water_price, $service_price, $description);
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

        if (!empty($_POST['rooms'])) {
            $rooms = json_decode($_POST['rooms'], true);
            $stmtRoom = $conn->prepare("INSERT INTO rooms (building_id, room_name, rental_price, area, room_type, room_status) VALUES (?, ?, ?, ?, ?, ?)");

            foreach ($rooms as $room) {
                $stmtRoom->bind_param("isdsss", $building_id, $room['name'], $room['price'], $room['area'], $room['type'], $room['status']);
                $stmtRoom->execute();
            }
            $stmtRoom->close();
        }
        logMessage("✅ New building creation process completed successfully.");
        $price_sql = "SELECT MIN(rental_price) AS min_price, MAX(rental_price) AS max_price FROM rooms WHERE building_id = ?";
        $price_stmt = $conn->prepare($price_sql);
        $price_stmt->bind_param("i", $building_id);
        $price_stmt->execute();
        $price_stmt->bind_result($min_price, $max_price);
        $price_stmt->fetch();
        $price_stmt->close();

        if ($min_price !== null && $max_price !== null) {
            $updated_price = number_format($min_price, 0, '.', ',') . " - " . number_format($max_price, 0, '.', ',');

            $update_building_sql = "UPDATE buildings SET rental_price = ? WHERE building_id = ?";
            $update_building_stmt = $conn->prepare($update_building_sql);
            $update_building_stmt->bind_param("si", $updated_price, $building_id);
            $update_building_stmt->execute();
            $update_building_stmt->close();
        }
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
