<?php
session_start();
require '../admin/getallbooking.php';

$booking_id = $_GET['booking_id'];

$sql = "SELECT * FROM bookings WHERE booking_id = ?";
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die('Prepare failed: ' . htmlspecialchars($conn->error));
}
$stmt->bind_param("i", $booking_id);
$stmt->execute();
$result = $stmt->get_result();
$booking = $result->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Hợp Đồng</title>
    <link rel="stylesheet" href="../assets/css/column.css">
</head>
<body>
    <?php include '../includes/header.php'; ?> 
    <div class="head-container">
        <div class="main-content" id="edit-booking">
            <h1>Thông Tin Hợp Đồng</h1>
            <div class="column-container">
                <div class="column">
                    <div class="info-row">
                        <div class="info-label">Tên khách hàng:</div>
                        <div class="info-value"><?php echo htmlspecialchars($booking['guest_name']) ?></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Số điện thoại:</div>
                        <div class="info-value"><?php echo htmlspecialchars($booking['phone']) ?></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">CCCD:</div>
                        <div class="info-value"><?php echo htmlspecialchars($booking['identification_card']) ?></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Ngày ký:</div>
                        <div class="info-value"><?php echo htmlspecialchars($booking['signed_date']) ?></div>
                    </div>
                </div>
                <div class="column">
                    <div class="info-row">
                        <div class="info-label">Kỳ hạn thanh toán:</div>
                        <div class="info-value"><?php echo htmlspecialchars($booking['payment_term']) ?></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Ngày bắt đầu:</div>
                        <div class="info-value"><?php echo htmlspecialchars($booking['lease_start_date']) ?></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Ngày kết thúc:</div>
                        <div class="info-value"><?php echo htmlspecialchars($booking['lease_end_date']) ?></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Ngày cọc:</div>
                        <div class="info-value"><?php echo htmlspecialchars($booking['deposit_term']) ?></div>
                    </div>
                </div>
            </div>
            <table>
                <thead>
                    <tr>
                        <th></th>
                        <th>Kỳ hạn</th>
                        <th>Tình trạng thanh toán</th>
                        <th>Hoa hồng thực nhận</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="5">Chưa có thông tin</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <?php include '../includes/sidebar.php'; ?>
    </div>
</body>
</html>