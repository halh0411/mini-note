<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sửa note - Mini Note</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <h1>Sửa note</h1>

    <p>
        <a href="index.php?page=note-show&id=<?= $note["id"] ?>">
            ← Quay lại chi tiết note
        </a>
    </p>

    <!-- Hiển thị lỗi nếu có -->
    <?php if (isset($_SESSION["error"])): ?>
        <p style="color: red;">
            <?= htmlspecialchars($_SESSION["error"]) ?>
        </p>
        <?php unset($_SESSION["error"]); ?>
    <?php endif; ?>

    <!-- 
        Form sửa note.
        Khi submit sẽ gửi dữ liệu đến:
        index.php?page=note-update
    -->
    <form action="index.php?page=note-update" method="POST">

        <!-- 
            Hidden input để gửi id note cần sửa.
            Người dùng không nhìn thấy ô này.
        -->
        <input type="hidden" name="id" value="<?= htmlspecialchars($note["id"]) ?>">

        <div>
            <label>Tiêu đề note:</label><br>

            <!-- 
                Đổ dữ liệu title cũ vào value.
                Người dùng sửa trực tiếp trên đó.
            -->
            <input 
                type="text" 
                name="title" 
                value="<?= htmlspecialchars($note["title"]) ?>"
                style="width: 400px;"
            >
        </div>

        <br>

        <div>
            <label>Nội dung note:</label><br>

            <!-- 
                Đổ nội dung cũ vào textarea.
            -->
            <textarea 
                name="content" 
                rows="10" 
                cols="60"
            ><?= htmlspecialchars($note["content"]) ?></textarea>
        </div>

        <br>

        <button type="submit">Cập nhật note</button>
    </form>

</body>
</html>