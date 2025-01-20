<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/style.css">
    <script src="../assets/js/script.js"></script>
</head>
<body>
<header class="header-container">
    <div class="logo-container">
        <a href="../templates/home.php"><img class="logo-img" src="../Upload/Img/logo.png" alt="Company Logo"></a>
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
</header>
</body>
