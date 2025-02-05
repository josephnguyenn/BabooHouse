<?php

session_start();
include '../admin/getallbuilding.php';  
$your_buildings = getAllBuildingsOfUser($_SESSION['user_id']);
$buildings = getAllBuildings(0, 100000);
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

include '../includes/header.php';
$notifications->data_seek(0);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BabooHouse</title>
    <link rel="stylesheet" href="../assets/css/home.css">
</head>
<body>
    <div class="head-container">
        <div class="main-content">
            <div class="manage-head">
                <h1>Trang chủ</h1>      
            </div>
            <div class="grid-template">
                <div class="grid-item">
                    <h2>Thông báo hệ thống</h2>
                    <div class="content-grid">
                        <?php if ($notifications->num_rows > 0): ?>
                            <?php while ($notification = $notifications->fetch_assoc()): ?>
                                <form action="../admin/mark_as_read.php" method="POST" class="notification-item <?php echo $notification['is_read'] ? '' : 'unread' ?>">
                                    <input type="hidden" name="id" value="<?php echo $notification['id']; ?>">
                                    <button type="submit" class="notification-message" style="color: #111; background: none; border: none; text-align: left; width: 100%; padding: 0; cursor: pointer;">
                                    <?php echo htmlspecialchars($notification['message']); ?></button>
                                    <div class="notification-time" style="font-size: 13px; margin-top: 5px;"><?php echo formatTime($notification['created_at'])?></div>
                                </form>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <div class="notification-message">Không có thông báo</div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="grid-item">
                    <h2>Xếp hạng</h2>
                    <div class="content-grid">
                    </div>  
                </div>
                <div class="grid-item">
                    <h2>Các toà đang quản lý</h2>
                    <div class="content-grid">
                        <div class="table-wrap">
                            <table>
                                <tr>
                                    <th>Tên</th>
                                    <th>Giá</th>
                                    <th>Khu Vực</th>
                                    <th>Tình trạng</th>
                                </tr>
                                <?php if ($your_buildings->num_rows > 0): ?>
                                    <?php while ($building = $your_buildings->fetch_assoc()): ?>
                                        <tr onclick="location.href='edit_rooms.php?building_id=<?php echo htmlspecialchars($building['building_id']); ?>'" style="cursor: pointer;">
                                            <td><?php echo htmlspecialchars($building['name']); ?></td>
                                            <td><?php echo htmlspecialchars($building['rental_price']); ?></td>
                                            <td><?php echo htmlspecialchars($building['address']); ?></td>
                                            <td><?php echo htmlspecialchars($building['approved'] ? 'Đã duyệt' : 'Chưa duyệt') ?></td> 
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="10">Chưa có toà nhà nào</td>
                                    </tr>
                                <?php endif; ?>
                            </table>
                        </div>
                        <button class="create" onclick="location.href='manage_buildings.php'">Xem thêm</button>
                    </div>  
                </div>
                <div class="grid-item">
                    <h2>Thông tin lưu trú</h2>
                    <div class="content-grid">
                        <div class="table-wrap">
                            <table>
                                <tr>
                                    <th>Tên</th>
                                    <th>Giá</th>
                                    <th>Khu Vực</th>
                                </tr>
                                <?php if ($buildings->num_rows > 0): ?>
                                    <?php while ($building = $buildings->fetch_assoc()): ?>
                                        <tr onclick="location.href='edit_rooms.php?building_id=<?php echo htmlspecialchars($building['building_id']); ?>'" style="cursor: pointer;">
                                            <td><?php echo htmlspecialchars($building['name']); ?></td>
                                            <td><?php echo htmlspecialchars($building['rental_price']); ?></td>
                                            <td><?php echo htmlspecialchars($building['address']); ?></td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="10">Chưa có toà nhà nào</td>
                                    </tr>
                                <?php endif; ?>
                            </table>
                        </div>
                        <button class="create" onclick="location.href='accommodation_info.php'">Xem thêm</button>
                    </div>  
                </div>
            </div>
        </div>
        <?php include '../includes/sidebar.php'; ?>
    </div>
</body>
</html>