<?php
session_start();
require '../config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $guest_name = $_POST["guest_name"];
    $phone = $_POST["phone"];
    $identification_card = $_POST["identification_card"];
    $deposit_date = $_POST["deposit_date"];
    $lease_term = $_POST["lease_term"];
    $payment_term = $_POST["payment_term"];
    $lease_start_date = $_POST["lease_start_date"];
    $lease_end_date = $_POST["lease_end_date"];
    $photo_urls = $_POST["photo_urls"];

    if (!empty($_FILES["image"]["name"])) {
        $target_dir = "../uploads/"; 
        $file_name = time() . "_" . basename($_FILES["image"]["name"]); 
        $target_file = $target_dir . $file_name;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $sql = "INSERT INTO bookings (guest_name, phone, identification_card, deposit_date, lease_term, payment_term, lease_start_date, lease_end_date, photo_urls) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssssssss", $guest_name, $phone, $identification_card, $deposit_date, $lease_term, $payment_term, $lease_start_date, $lease_end_date, $photo_urls);

            if ($stmt->execute()) {
                echo "Booking created successfully.";
            } else {
                echo "Error: " . $stmt->error;
            }
        } else {
            die("Error uploading image.");
        }
    } else {
        die("No image uploaded.");
    }
}
?>
