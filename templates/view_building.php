<?php
session_start();
require '../config/database.php';
require '../admin/getallroom.php';
include '../admin/getalluser.php';

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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['room_id'])) {
    $room_id = $_POST['room_id'];
    $building_id = $_POST['building_id'];
    $room_name = $_POST['room_name'];
    $rental_price = $_POST['rental_price'];
    $area = $_POST['area'];
    $room_status = $_POST['room_status'];

    $sql = "UPDATE rooms SET room_name = ?, rental_price = ?, area = ?, room_status = ? WHERE room_id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }
    $stmt->bind_param("ssssi", $room_name, $rental_price, $area, $room_status, $room_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "Room updated successfully.";
    } else {
        echo "Error updating room: " . htmlspecialchars($stmt->error);
    }
    $stmt->close();
    // Redirect to edit_rooms.php
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
<body>
    <?php include '../includes/header.php'; ?>
    <div class="head-container">
        <div class="main-content">
            <div class="building-info-container">
                <img src="">
                <div>
                    <p>Tên toà nhà: <?php echo htmlspecialchars($building['name']); ?></p>
                    <p>Địa chỉ: <?php echo htmlspecialchars($building['address']); ?></p>
                    <p>Số điện thoại chủ nhà: <?php echo htmlspecialchars($building['owner_phone']); ?></p>
                    <p>Tên chủ nhà: <?php echo htmlspecialchars($building['owner_name']); ?></p>
                    <p>Tên quản lý: <?php echo htmlspecialchars(getUsernameById($building['user_id'])); ?></p>
                    <p>Tiện nghi: <?php echo htmlspecialchars($building['description']); ?></p>
                </div>  
            </div>    
            <div class="manage-head">
                <h3>Phòng</h3>
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
                    <?php if ($building['approved']): ?> 
                        <form action="../admin/approve_room.php" method="POST">
                            <input type="hidden" name="action" value="stop">
                            <input type="hidden" name="user_id" value="<?php echo $building['user_id']; ?>">
                            <input type="hidden" name="name" value="<?php echo $building['name']; ?>">
                            <input type="hidden" name="building_id" value="<?php echo $building_id; ?>">
                            <button type="submit" class="create">Ngưng toà nhà</button>
                        </form>
                    <?php else: ?>
                        <form action="../admin/approve_room.php" method="POST">
                            <input type="hidden" name="action" value="approve">
                            <input type="hidden" name="user_id" value="<?php echo $building['user_id']; ?>">
                            <input type="hidden" name="name" value="<?php echo $building['name']; ?>">
                            <input type="hidden" name="building_id" value="<?php echo $building_id; ?>">
                            <button type="submit" class="create">Duyệt toà nhà</button>
                        </form>
                    <?php endif; ?>
                <?php endif; ?>    
            </div>
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
                        <td><?php echo htmlspecialchars($room['rental_price']); ?></td>
                        <td><?php echo htmlspecialchars($room['area']); ?> m&#178;</td>
                        <td><?php echo htmlspecialchars($room['room_status']); ?></td>
                        <?php if (isset($_SESSION['role']) && $_SESSION['role'] != 'admin'): ?>
                        <td>
                        <?php if ($room['room_status'] == 'Còn trống'): ?>
                            <button class="create" onclick="location.href='book_room.php?building_id=<?php echo $room['building_id']; ?>&room_id=<?php echo $room['room_id']; ?>'">Đặt phòng</button>
                        <?php endif; ?>
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
        <?php include '../includes/sidebar.php'; ?>
    </div>
</body>
</html>