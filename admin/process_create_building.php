<?php
session_start();
require '../config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $address = $_POST['address'];
    $rental_price = $_POST['rental_price'];
    $owner_name = $_POST['owner_name'];
    $owner_phone = $_POST['owner_phone'];
    $building_type = $_POST['building_type'];
    $electricity_price = $_POST['electricity_price'];
    $water_price = $_POST['water_price']; // Consider hashing this water_price
    $description = $_POST['description'];

    $sql = "INSERT INTO buildings (name, address, rental_price, owner_name, owner_phone, building_type, electricity_price, water_price, description, last_modified) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssss", $name, $address, $rental_price, $owner_name, $owner_phone, $building_type, $electricity_price, $water_price, $description);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "New building created successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
    $conn->close();

    // Ensure no output before redirection
    ob_start();
    header("Location: ../templates/manage_buildings.php");
    ob_end_flush();
    exit();
}
?>