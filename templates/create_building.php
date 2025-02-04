<?php
session_start(); 
require '../config/database.php';
include '../admin/getallbuilding.php';  
$building_types = getDistinctBuildingTypes();
$user_id = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="vi"></html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Toà Nhà Mới</title>
</head>
<body>
    <?php include '../includes/header.php'; ?> <!-- Bao gồm tiêu đề -->

    <div class="head-container">
        <div class="main-content" id="create-building">
            <h1>Thêm Toà Nhà Mới</h1>
            <form action="../admin/process_create_building.php" method="post">
                <input type="hidden" id="user_id" name="user_id" value="<?php echo $user_id?>"> 
                <div class="form-group">
                    <label for="name">Tên:</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="address">Địa chỉ:</label>
                    <input type="text" id="address" name="address" required>
                </div>
                <div class="form-group">
                    <label for="rental_price">Giá thuê:</label>
                    <input type="text" id="rental_price" name="rental_price">
                </div>
                <div class="form-group">
                    <label for="owner_name">Tên chủ toà nhà:</label>
                    <input type="text" id="owner_name" name="owner_name">
                </div>
                <div class="form-group">
                    <label for="phone">Điện thoại:</label>
                    <input type="text" id="owner_phone" name="owner_phone">
                </div>
                <div class="form-group">
                    <label for="building_type">Loại hình cho thuê</label>
                    <select id="building_type" name="building_type">
                        <option value="Trống" selected>Trống</option>
                        <?php foreach ($building_types as $type): ?>
                            <option value="<?php echo htmlspecialchars($type); ?>"><?php echo htmlspecialchars($type); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="electricity_price">Tiền điện:</label>
                    <input type="float" id="electricity_price" name="electricity_price" value="" required>
                </div>
                <div class="form-group">
                    <label for="water_price">Tiền nước:</label>
                    <input type="float" id="water_price" name="water_price" value="" required>
                </div>
                <div class="form-group">
                    <label for="description">Tiện nghi:</label>
                    <input type="text" id="description" name="description" value="" required>
                </div>
                <button type="submit">Thêm toà nhà</button>
            </form>
        </div>
        <?php include '../includes/sidebar.php'; ?> <!-- Bao gồm thanh bên -->
    </div>
</body>
</html>
