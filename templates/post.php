<?php 
session_start(); 
require '../config/database.php';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng bài</title>
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <div class="head-container">
        <div class="main-content" id="create-building">
            <div class="manage-head">
                <h1>Thêm thông báo mới</h1>      
            </div>
            <form action="../admin/process_create_post.php" method="post">
                <div class="form-group">
                    <label for="title">Tiêu đề: </label>
                    <input type="text" maxlength="30" id="title" name="title" required>
                </div>
                <div class="form-group">
                    <label for="editor">Nội dung:</label>
                    <div id="editor"></div>
                    <input type="hidden" id="content" name="content">
                </div>  
                <button type="submit" onclick='document.getElementById("content").value = quill.root.innerHTML;'>Đăng</button>
            </form>

        </div>
        <?php include '../includes/sidebar.php'; ?> 
    </div>

    <script>
        var quill = new Quill('#editor', {
            theme: 'snow',
            placeholder: 'Nhập nội dung thông báo...',
            modules: {
                toolbar: [
                    [{ header: [1, 2, false] }],
                    ['bold', 'italic', 'underline'],
                    [{ list: 'ordered' }, { list: 'bullet' }],
                    ['clean']
                ]
            }
        });
    </script>
</body>
</html>
