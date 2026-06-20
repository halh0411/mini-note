<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Tạo note mới - Mini Note</title>
</head>
<body>

    <h1>Tạo note mới</h1>

    <!-- Link quay lại danh sách notes -->
    <p>
        <a href="index.php?page=notes">← Quay lại danh sách</a>
    </p>

    <!-- Hiển thị lỗi nếu có -->
    <?php if (isset($_SESSION["error"])): ?>
        <p style="color: red;">
            <?= htmlspecialchars($_SESSION["error"]) ?>
        </p>
        <?php unset($_SESSION["error"]); ?>
    <?php endif; ?>

    <!-- 
        Form tạo note mới.
        Khi submit sẽ gửi dữ liệu đến:
        index.php?page=note-store
    -->
    <form action="index.php?page=note-store" method="POST">

        <div>
            <label>Tiêu đề note:</label><br>

            <!-- 
                name="title" để bên NoteController lấy bằng:
                $_POST["title"]
            -->
            <input 
                type="text" 
                name="title" 
                placeholder="Nhập tiêu đề note"
                style="width: 400px;"
            >
        </div>

        <br>

        <div>
            <label>Nội dung note:</label><br>

            <!-- 
                name="content" để bên NoteController lấy bằng:
                $_POST["content"]
            -->
            <textarea 
                name="content" 
                rows="10" 
                cols="60" 
                placeholder="Nhập nội dung note"
            ></textarea>
        </div>

        <br>

        <button type="submit">Lưu note</button>
    </form>

</body>
</html>