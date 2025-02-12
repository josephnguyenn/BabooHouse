<?php
session_start();
require '../config/database.php';
include '../admin/getallbuilding.php';  

$building_types = getDistinctBuildingTypes();

if (isset($_GET['building_id'])) {
    $building_id = $_GET['building_id'];
    $sql = "SELECT * FROM buildings WHERE building_id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }
    $stmt->bind_param("i", $building_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $building = $result->fetch_assoc();
    $stmt->close();
} else {
    die("building ID not provided.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $street = $_POST['street'];
    $city = $_POST['city'];
    $district = $_POST['district'];
    $rental_price = $_POST['rental_price'];
    $owner_phone = $_POST['owner_phone'];
    $owner_name = $_POST['owner_name'];
    $building_type = $_POST['building_type'];
    $electricity_price = $_POST['electricity_price'];
    $water_price = $_POST['water_price'];
    $description = $_POST['description'];
    $approved = $_POST['approved'];
    $sql = "UPDATE buildings SET name = ?, street = ?, city = ?, district = ?, rental_price = ?, owner_phone = ?, owner_name = ?, building_type = ?, electricity_price = ?, water_price = ?, description = ?, last_modified = NOW(), approved = 0 WHERE building_id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }
    $stmt->bind_param("sssssssssssi", $name, $street, $city, $district, $rental_price, $owner_phone, $owner_name, $building_type, $electricity_price, $water_price, $description, $building_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $admin_sql = "SELECT user_id FROM users WHERE role = 'admin'";
        $admin_result = $conn->query($admin_sql);

        if ($admin_result) {
            $message = $approved ? "Yêu cầu chỉnh sửa toà nhà '$name' đang chờ duyệt." : "Yêu cầu thêm toà nhà '$name' đang chờ duyệt.";
            while ($admin = $admin_result->fetch_assoc()) {
                $admin_id = $admin['user_id'];
                $notification_sql = "UPDATE notifications SET message = ?, created_at = NOW() WHERE building_id = ?";
                $notification_stmt = $conn->prepare($notification_sql);
                
                if ($notification_stmt) {
                    $notification_stmt->bind_param("si", $message, $building_id);
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

        echo "building updated successfully.";
    } else {
        echo "Error updating building: " . htmlspecialchars($stmt->error);
    }
    $stmt->close();
    $conn->close();
    header("Location: manage_buildings.php");
    exit();

}
$data = [
    'Đà Nẵng' => ['Hải Châu', 'Thanh Khê', 'Sơn Trà', 'Ngũ Hành Sơn', 'Liên Chiểu', 'Cẩm Lệ', 'Hòa Vang'],
    'Hồ Chí Minh' => ['Quận 1', 'Quận 2', 'Quận 3', 'Quận 4', 'Quận 5', 'Quận 6', 'Quận 7'],
];
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chỉnh Sửa Toà Nhà</title>
</head>
<body>
    <?php include '../includes/header.php'; ?> 

    <div class="head-container">
        <div class="main-content" id="edit-building">
            <h1>Chỉnh Sửa Toà Nhà</h1>
            <form action="edit_building.php?building_id=<?php echo $building_id; ?>" method="post">
                <div class="form-group">
                    <label for="name">Tên toà nhà:</label>
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($building['name']); ?>" required>
                </div>
                <div class="flex-wrap">
                    <div class="form-group">
                        <label for="city">Thành phố:</label>
                        <select id="city" name="city" required>
                            <option value="Other">Chọn thành phố</option>
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
                    <input type="text" id="street" name="street" placeholder="Tên đường, số nhà" value="<?php echo htmlspecialchars($building['street']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="rental_price">Giá thuê:</label>
                    <input type="float" id="rental_price" name="rental_price" value="<?php echo htmlspecialchars($building['rental_price']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="owner_phone">Số điện thoại chủ nhà:</label>
                    <input type="text" id="owner_phone" name="owner_phone" value="<?php echo htmlspecialchars($building['owner_phone']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="owner_name">Tên chủ nhà:</label>
                    <input type="text" id="owner_name" name="owner_name" value="<?php echo htmlspecialchars($building['owner_name']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="building_type">Loại hình cho thuê</label>
                    <select id="building_type" name="building_type">
                        <option value="Trống" selected>Trống</option>
                        <?php foreach ($building_types as $type): ?>
                            <option value="<?php echo htmlspecialchars($type); ?>" <?php echo ($building['building_type'] == $type) ? 'selected' : ''; ?>><?php echo htmlspecialchars($type); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="electricity_price">Tiền điện:</label>
                    <input type="float" id="electricity_price" name="electricity_price" value="<?php echo htmlspecialchars($building['electricity_price']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="water_price">Tiền nước:</label>
                    <input type="float" id="water_price" name="water_price" value="<?php echo htmlspecialchars($building['water_price']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="description">Tiện nghi:</label>
                    <input type="text" id="description" name="description" value="<?php echo htmlspecialchars($building['description']); ?>">
                </div>
                <button type="submit">Lưu</button>
                <button class="cancel-btn" onclick="window.history.back();">Hủy</button>
            </form>
        </div>
        <?php include '../includes/sidebar.php'; ?>
    </div>
<script>
    const districtsData = <?php echo json_encode($data); ?>;
    const selectedDistrict = '<?php echo htmlspecialchars($building['district']); ?>';
    const selectedCity = '<?php echo htmlspecialchars($building['city']); ?>'; 

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
                    if (district === selectedDistrict) {
                        option.selected = true; 
                    }
                    districtSelect.appendChild(option);
                });
            }
        });

        if (selectedCity) {
            citySelect.value = selectedCity;
            citySelect.dispatchEvent(new Event('change'));
        }
    });
</script>
</body>
</html>