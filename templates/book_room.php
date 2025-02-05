<?php
session_start();
?>

<!DOCTYPE html>
<html lang="vi"></html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt phòng</title>
</head>
<body>
    <?php include '../includes/header.php'; ?> 
    <div class="head-container">
        <div class="main-content" id="create-booking">
            <div class="manage-head">
                <h1>Đặt phòng</h1>
            </div> <br>
            <form action="../admin/process_create_booking.php" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="guest_name">Tên khách hàng:</label>
                    <input type="text" id="guest_name" name="guest_name" required>
                </div>
                <div class="form-group">
                    <label for="phone">Số điện thoại:</label>
                    <input type="text" id="phone" name="phone" required>
                </div>
                <div class="form-group">
                    <label for="identification_card">Căn cước công dân:</label>
                    <input type="text" id="identification_card" name="identification_card">
                </div>
                <div class="form-group">
                    <label for="deposit_date">Ngày cọc:</label>
                    <input type="date" id="deposit_date" name="deposit_date">
                </div>
                <div class="form-group">
                    <label for="lease_term">Thời hạn hợp đồng:</label>
                    <input type="date" id="lease_term" name="lease_term">
                </div>
                <div class="form-group">
                    <label for="payment_term">Kỳ hạn thanh toán</label>
                        <select id="payment_term" name="payment_term">
                            <option value="Trả trước" selected>Trả trước</option>
                            <option value="Trả hàng tháng">Trả hàng tháng</option>
                            <option value="Trả hàng năm">Trả hàng năm</option>
                        </select>
                </div>
                <div class="form-group">
                    <label for="start_date">Ngày bắt đầu:</label>
                    <input type="date" id="start_date" name="start_date" required>
                </div>
                <div class="form-group">
                    <label for="image">Tải ảnh hợp đồng:</label>
                    <input type="file" id="image" name="image" accept="image/*" required>
                </div>
                <button type="submit">Xác nhận đặt phòng</button>
                <button class="cancel-btn" onclick="window.history.back();">Hủy</button>
            </form>
        </div>
        <?php include '../includes/sidebar.php'; ?> <!-- Bao gồm thanh bên -->
    </div>
</body>
</html>
