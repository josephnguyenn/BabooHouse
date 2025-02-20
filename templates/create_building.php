<?php
session_start(); 
require '../config/database.php';
include '../admin/getallbuilding.php';  
$building_types = getDistinctBuildingTypes();
$user_id = $_SESSION['user_id'];
$data = [
    'Đà Nẵng' => ['Hải Châu', 'Thanh Khê', 'Sơn Trà', 'Ngũ Hành Sơn', 'Liên Chiểu', 'Cẩm Lệ', 'Hòa Vang'],
    'Hồ Chí Minh' => ['Quận 1', 'Quận 2', 'Quận 3', 'Quận 4', 'Quận 5', 'Quận 6', 'Quận 7'],
];
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
            <form action="../admin/process_create_building.php" method="post" enctype="multipart/form-data"> <!-- ✅ Added enctype -->
                <input type="hidden" id="user_id" name="user_id" value="<?php echo $user_id?>"> 
                <div class="form-group">
                    <label for="name">Tên:</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <!-- ✅ Added file upload input -->
                <div class="form-group">
                    <label for="building_image">Ảnh tòa nhà:</label>
                    <input type="file" id="building_image" name="building_image" accept="image/*">
                </div>
                <div class="flex-wrap">
                <div class="form-group">
                    <label for="city">Thành phố:</label>
                    <select id="city" name="city" required>
                        <option value="">Chọn thành phố</option>
                        <?php foreach ($data as $city => $districts): ?>
                            <option value="<?php echo htmlspecialchars($city); ?>"><?php echo htmlspecialchars($city); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="district">Quận:</label>
                    <select id="district" name="district" required>
                        <option value="">Chọn quận</option>
                    </select>
                </div>
                </div>
                <div class="form-group">
                    <label for="street">Địa chỉ:</label>
                    <input type="text" id="street" name="street" placeholder="Tên đường, số nhà" required>
                </div>
                <div class="form-group">
                    <label for="rental_price">Giá thuê (triệu/tháng):</label>
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
                    <label for="service_price">Tiền dịch vụ (đồng):</label>
                    <input type="text" id="service_price" name="service_price" value="" required>
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
    
    <script>
        const districtsData = <?php echo json_encode($data); ?>;
        document.addEventListener('DOMContentLoaded', function() {
            const citySelect = document.getElementById('city');
            const districtSelect = document.getElementById('district');
            citySelect.addEventListener('change', function() {
                districtSelect.innerHTML = '<option value="">Chọn quận</option>';
                const selectedCity = this.value;
                if (selectedCity && districtsData[selectedCity]) {
                    districtsData[selectedCity].forEach(function(district) {
                        const option = document.createElement('option');
                        option.value = district;
                        option.textContent = district;
                        districtSelect.appendChild(option);
                    });
                }
            });
        });
    </script>
</body>
</html>
