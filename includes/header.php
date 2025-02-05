<?php
include '../admin/get_notifications.php';
$notifications = get_notifications($_SESSION['user_id']);

function formatTime($timestamp) {
    date_default_timezone_set('Asia/Ho_Chi_Minh');
    $date = new DateTime($timestamp);
    $now = new DateTime();
    $diff = $now->diff($date);
    
    if ($diff->y > 0) return $diff->y . ' năm trước';
    if ($diff->m > 0) return $diff->m . ' tháng trước';
    if ($diff->d > 0) return $diff->d . ' ngày trước';
    if ($diff->h > 0) return $diff->h . ' giờ trước';
    if ($diff->i > 0) return $diff->i . ' phút trước';
    if ($diff->s > 0) return $diff->s . ' giây trước';
    return 'Vừa xong';
}

$hasUnread = false;

while ($notification = $notifications->fetch_assoc()) {
    if (!$notification['is_read']) {
        $hasUnread = true; 
    }
}
$notifications->data_seek(0);

?>
<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/header.css">
    <script src="../assets/js/script.js"></script>
</head>
<body>
<header class="header-container">
    <div class="logo-container">
        <a href="../templates/home.php"><img class="logo-img" src="../Upload/Img/logo.png" alt="Company Logo"></a>
    </div>
    <div class="func-container">
        <div class="notification">
            <a id="notificationBell"><img src="../assets/icons/<?php echo $hasUnread ? 'bell-on.svg' : 'bell.svg'; ?>"></a>
            <div id="notification-box" class="notification-box" style="display: none;">
                <div class="notification-header">
                    <h3>Thông báo</h3>
                </div>
                <div class="notification-content">
                    <?php if ($notifications->num_rows > 0): ?>
                        <?php while ($notification = $notifications->fetch_assoc()): ?>
                            <form action="../admin/mark_as_read.php" method="POST" class="notification-item <?php echo $notification['is_read'] ? '' : 'unread' ?>">
                                <input type="hidden" name="id" value="<?php echo $notification['id']; ?>">
                                <button type="submit" class="notification-message" style="color: #111; background: none; border: none; text-align: left; width: 100%; padding: 0; cursor: pointer;">
                                <?php echo htmlspecialchars($notification['message']); ?>
                                <div class="notification-time" style="font-size: 13px; margin-top: 5px;"><?php echo formatTime($notification['created_at'])?></div>
                                </button>
                            </form>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="notification-item">
                            <div class="notification-message">Không có thông báo</div>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="notification-footer">
                    <button id="markAllReadButton">Đánh dấu đã đọc tất cả</button>
                </div>
            </div>
        </div>
        <div class="user-dropdown">
        <?php if (isset($_SESSION['username'])): ?>
            <button aria-haspopup="true" aria-expanded="false" onclick="toggleDropdown()"> Xin chào
                <?php echo htmlspecialchars($_SESSION['username']); ?> <span>&#9660;</span>
            </button>
        <?php endif; ?>        
            <div class="dropdown-menu" id="userDropdownMenu" style="display: none;">
                <a href="../admin/logout.php">Đăng xuất</a>
            </div>
        </div>
    </div>
</header>
<script src="../assets/js/header.js"></script>
</body>
<?php 
$notifications->data_seek(0);
?>
