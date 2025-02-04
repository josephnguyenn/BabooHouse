<?php
session_start(); 
include '../admin/get_notifications.php';
$notifications->data_seek(0);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Thông Báo</title>
</head>
<body>
    <?php include '../includes/header.php'; ?>
    <div class="head-container">
        <div class="main-content">
            <div class="manage-head">
                <h1>Quản Lý Thông Báo</h1>
            </div>
            <div class="notification-content">
                <?php if ($notifications->num_rows > 0): ?>
                    <?php while ($notification = $notifications->fetch_assoc()): ?>
                        <?php echo htmlspecialchars($notification['message']) ?>
                        <?php echo htmlspecialchars(formatTime($notification['created_at'])) ?>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="notification-item">Không có thông báo</td>
                <?php endif; ?>
            </div>
        </div>
        <?php include '../includes/sidebar.php'; ?>
    </div>
</body>
</html>