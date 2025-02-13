<?php
session_start();
include '../admin/getallbuilding.php';  
include '../admin/getallroom.php';
include '../admin/getalluser.php';

$min_price = isset($_GET['min_price']) ? (int)$_GET['min_price'] : getMaxAndMinPrice()['min'];
$max_price = isset($_GET['max_price']) ? (int)$_GET['max_price'] : getMaxAndMinPrice()['max'];
$selected_types = isset($_GET['building_type']) ? $_GET['building_type'] : NULL;
$status_type = isset($_GET['status_type']) ? $_GET['status_type'] : NULL;
$city = isset($_GET['city']) ? $_GET['city'] : NULL;
$district = isset($_GET['district']) ? $_GET['district'] : NULL;
$buildings = getAllBuildings($min_price, $max_price, $selected_types, $_SESSION['user_id'], $status_type, $city, $district);
$building_types = getDistinctBuildingTypes();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông tin lưu trú</title>
    <link href="../assets/css/filter.css" rel="stylesheet"> 
</head>
<body>
    <?php include '../includes/header.php'; ?>
    <div class="head-container">
        <?php include '../includes/filter_accommodation.php'; ?>
        <div class="main-content">
            <div class="manage-head">
                <h1>Thông Tin Lưu Trú</h1>
            </div>
            <div class="icon-container">
                <a id="filter-icon" aria-haspopup="true" aria-expanded="false" onclick="toggleFilter()"><img src="../assets/icons/filter.svg"></a>
            </div>
            <?php include '../includes/filter_exe_address.php' ?>
        <div style="overflow-x: auto; width: 100%;">
        <table>
                <tr>
                    <th>Tên</th>
                    <th>Giá</th>
                    <th>Khu Vực</th>
                    <th>Tình Trạng</th>
                    <th>Công Suất</th>
                    <th>Số Phòng</th>
                    <th>Người quản lý</th>
                    <th>Lần Cuối Chỉnh Sửa</th>
                </tr>
                <?php if ($buildings->num_rows > 0): ?>
                    <?php while ($building = $buildings->fetch_assoc()): ?>
                        <?php if (!$building['approved']): ?>
                            <tr onclick="location.href='view_building.php?building_id=<?php echo htmlspecialchars($building['building_id']); ?>'" style="cursor: pointer;">
                                <?php 
                                $availableRooms = getAllAvailableRooms($building['building_id']);
                                $rentedCount = $availableRooms["rented_count"];
                                $totalRooms = $availableRooms["number_rooms"];
                                ?>
                                <td><?php echo htmlspecialchars($building['name']); ?></td>
                                <td><?php echo htmlspecialchars($building['rental_price']); ?> triệu/tháng</td>
                                <td><?php echo htmlspecialchars($building['district']) . ', ' . htmlspecialchars($building['city']); ?></td>
                                <td>
                                    <?php if ($rentedCount == 0): ?>
                                        Hết phòng
                                    <?php else: ?>
                                        Còn phòng
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($totalRooms == 0): ?>
                                        N/A
                                    <?php else: ?>
                                        <?php echo htmlspecialchars(100 - ($rentedCount / $totalRooms) * 100); ?>%
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php echo htmlspecialchars($rentedCount); ?>/<?php echo htmlspecialchars($totalRooms); ?>
                                </td>
                                <td><?php echo htmlspecialchars(getUsernameById($building['user_id'])); ?></td>
                                <td><?php echo htmlspecialchars($building['last_modified']); ?></td>     
                            </tr>
                        <?php endif; ?>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="10">Không tìm thấy toà nhà</td>
                    </tr>
                <?php endif; ?>
            </table>
        </div>
        </div>
        <?php include '../includes/sidebar.php'; ?>
    </div>
    <script src="../assets/js/filter.js"></script>
</body>
</html>