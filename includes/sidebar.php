<div class="sidebar">
    <ul>
        <li class="accordion-item"> 
            <a href="javascript:void(0)" class="accordion-toggle">Lưu trú</a>
            <div class="accordion-content">
                <a href="../templates/accommodation_info.php">Thông tin lưu trú</a>
                <a href="../templates/manage_buildings.php">Quản lý toà nhà</a>
                <?php if (isset($_SESSION['role']) && in_array($_SESSION['role'],['manager','admin'])): ?>
                    <a href="../templates/pending_buildings.php">Toà nhà chưa duyệt</a>
                <?php endif; ?>
            </div>
        </li>
        <li class="accordion-item">
            <a href="javascript:void(0)" class="accordion-toggle">Doanh thu</a>
            <div class="accordion-content">
                <a href="#">Doanh thu Sale</a>
                <a href="#">Quản lý</a>
            </div>
        </li>
        <li><a href="../templates/manage_contract.php">Hợp đồng</a></li>
        <?php if (isset($_SESSION['role']) && in_array($_SESSION['role'],['manager','admin'])): ?>
        <li><a href="../templates/manage_users.php">Quản lý người dùng</a></li>
        <li><a href="../templates/post.php">Đăng bài</a></li>
        <?php endif; ?>
    </ul>
</div>
