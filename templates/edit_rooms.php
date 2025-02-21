<?php
session_start();
require '../config/database.php';
require '../admin/getallroom.php';


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

$rooms = getAllRooms($building_id);
$room_types = getDistinctRoomTypes();
/**
 * Convert Google Drive URL to direct link
 * @param string $photo_url
 * @return string|false
 */
function getDirectGoogleDriveImage($photo_url) {
    if (preg_match('/id=([a-zA-Z0-9_-]+)/', $photo_url, $matches) || 
        preg_match('/\/d\/([a-zA-Z0-9_-]+)/', $photo_url, $matches)) {
        $file_id = $matches[1];
        return "https://lh3.googleusercontent.com/d/$file_id";
    }
    return false;
}

// Get the stored photo URL and convert it to a direct link
$photo_url = $building['photo_urls']; 
$direct_image_url = getDirectGoogleDriveImage($photo_url);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['room_id'])) {
    $room_id = $_POST['room_id'];
    $room_name = $_POST['room_name'];
    $rental_price = $_POST['rental_price'];
    $room_type = $_POST['room_type'];
    $area = $_POST['area'];
    $room_status = $_POST['room_status'];

    $sql = "UPDATE rooms SET room_name = ?, rental_price = ?, area = ?, room_status = ?, room_type = ? WHERE room_id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }
    $stmt->bind_param("sssssi", $room_name, $rental_price, $area, $room_status, $room_type, $room_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "Room updated successfully.";
    } else {
        echo "Error updating room: " . htmlspecialchars($stmt->error);
    }
    $stmt->close();
    header("Location: edit_rooms.php?building_id=" . $building_id);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Toà Nhà</title>
    <link rel="stylesheet" href="../assets/css/rooms.css">
</head>
<style>
    .building-image {
    width: 300px; /* Adjust to desired square size */
    height: 300px; /* Makes sure the image remains square */
    object-fit: cover; /* Crops automatically while keeping the aspect ratio */

}

</style>

<body>
    <?php include '../includes/header.php'; ?>
    <div class="head-container">
        <div class="main-content">
            <div class="building-info-container">
            <img src="<?php echo $direct_image_url ? $direct_image_url : 'default_image.jpg'; ?>" alt="Building Image" class="building-image">
                <div>
                    <p>Tên toà nhà: <?php echo htmlspecialchars($building['name']); ?></p>
                    <p>Địa chỉ: <?php echo htmlspecialchars($building['street']); ?>, <?php echo htmlspecialchars($building['district']); ?>, <?php echo htmlspecialchars($building['city']); ?></p>
                    <p>Số điện thoại chủ nhà: <?php echo htmlspecialchars($building['owner_phone']); ?></p>
                    <p>Tên chủ nhà: <?php echo htmlspecialchars($building['owner_name']); ?></p>
                    <p>Loại hình: <?php echo htmlspecialchars($building['building_type']); ?></p>
                    <p>Tiện nghi: <?php echo htmlspecialchars($building['description']); ?></p>
                    <p>Tiền điện: <?php echo htmlspecialchars($building['electricity_price']); ?></p>
                    <p>Tiền nước: <?php echo htmlspecialchars($building['water_price']); ?></p>
                    <p>Tiền dịch vụ: <?php echo htmlspecialchars($building['service_price']); ?></p>
                </div>  
            </div>    
            <div class="manage-head">
                <h3>Phòng</h3>
                <button class="create" onclick="location.href='create_room.php?building_id=<?php echo $building_id; ?>'">Thêm phòng mới</button>
            </div>
            <div style="overflow-x: auto; width: 100%;">
            <table>
                <thead>
                    <tr>
                        <th>Tên</th>
                        <th>Giá</th>
                        <th>Diện tích</th>
                        <th>Tình trạng</th>
                        <?php if (isset($_SESSION['role']) && $_SESSION['role'] != 'admin'): ?>
                        <th>Hành động</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                <?php if ($rooms->num_rows > 0): ?>
                    <?php foreach ($rooms as $room): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($room['room_name']); ?></td>
                        <td><?php echo htmlspecialchars($room['rental_price']); ?> triệu/tháng</td>
                        <td><?php echo htmlspecialchars($room['area']); ?> m&#178;</td>
                        <td><?php echo htmlspecialchars($room['room_status']); ?></td>
                        <?php if (isset($_SESSION['role']) && $_SESSION['role'] != 'admin'): ?>
                        <td class="crud-btn">
                            <button 
                                class="button edit" 
                                data-id="<?php echo $room['room_id']; ?>" 
                                data-name="<?php echo htmlspecialchars($room['room_name']); ?>" 
                                data-price="<?php echo htmlspecialchars($room['rental_price']); ?>" 
                                data-area="<?php echo htmlspecialchars($room['area']); ?>" 
                                data-status="<?php echo htmlspecialchars($room['room_status']); ?>"
                                data-type="<?php echo htmlspecialchars($room['room_type']); ?>"
                                onclick="openLightbox(this);"><img src="../assets/icons/edit.svg"></button>
                            <form action="../admin/delete_room.php" method="post" onsubmit="return confirm('Are you sure you want to delete this building?');" style="display:inline;">
                                <input type="hidden" name="room_id" value="<?php echo $room['room_id']; ?>">
                                <input type="hidden" name="building_id" value="<?php echo $building_id ?>">
                                <button class="delete" type="submit"><img src="../assets/icons/bin.svg"></button>
                            </form>
                        </td>
                        <?php endif; ?>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">Không tìm thấy phòng</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
        </div>
        <div class="lightbox" id="lightboxroom" style="display:none;">
            <div class="lightbox-content">
            <span class="close" onclick="closeLightbox()">&times;</span>
            <h3>Chỉnh sửa phòng</h3>
                <form action="edit_rooms.php?building_id=<?php echo $building_id; ?>" method="post">
                    <input type="hidden" id="room_id" name="room_id"> 
                    <input type="hidden" name="building_id" value="<?php echo $building_id; ?>">
                    <div class="form-group">
                        <label for="room_name">Tên phòng:</label>
                        <input type="text" id="room_name" name="room_name" placeholder="Tên phòng">
                    </div>
                    <div class="form-group">
                        <label for="room_price">Giá (triệu/tháng):</label>
                        <input type="text" id="room_price" name="rental_price" placeholder="Giá">
                    </div>
                    <div class="form-group">
                        <label for="room_area">Diện tích (m&#178;):</label>
                        <input type="text" id="room_area" name="area" placeholder="Diện tích">
                    </div>
                    <div class="form-group">
                        <label for="room_type">Loại phòng</label>
                        <select id="room_type" name="room_type">
                            <option value="" selected>Trống</option>
                            <?php foreach ($room_types as $type): ?>
                                <option value="<?php echo htmlspecialchars($type); ?>" <?php echo ($room['room_type'] == $type) ? 'selected' : ''; ?>><?php echo htmlspecialchars($type); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="room_status">Tình trạng:</label>
                        <select id="room_status" name="room_status">
                            <option value="Còn trống" selected>Còn trống</option>
                            <option value="Đã thuê">Đã thuê</option>
                        </select>
                    </div>
                    <button type="submit">Lưu</button>
                </form>
            </div>
        </div>
        <?php include '../includes/sidebar.php'; ?>
    </div>
    <script src="../assets/js/lightbox.js"></script>
</body>
</html>