<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chi tiết note - Mini Note</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <h1>Chi tiết note</h1>

    <p>
        <a href="index.php?page=notes">← Quay lại danh sách</a>
    </p>

    <!-- Hiển thị thông báo thành công nếu có -->
    <?php if (isset($_SESSION["success"])): ?>
        <p style="color: green;">
            <?= htmlspecialchars($_SESSION["success"]) ?>
        </p>
        <?php unset($_SESSION["success"]); ?>
    <?php endif; ?>

    <!-- 
        Biến $note được truyền từ NoteController->show().
        Nó chứa thông tin note cần xem.
    -->
    <h2>
        <?= htmlspecialchars($note["title"]) ?>
    </h2>

    <p>
        <strong>Ngày tạo:</strong>
        <?= htmlspecialchars($note["created_at"]) ?>
    </p>

    <p>
        <strong>Cập nhật lần cuối:</strong>
        <?= htmlspecialchars($note["updated_at"]) ?>
    </p>

    <hr>

    <h3>Nội dung</h3>

    <!-- 
        nl2br() giúp xuống dòng đúng như người dùng nhập trong textarea.
        htmlspecialchars() giúp tránh lỗi XSS khi hiển thị nội dung.
    -->
    <p>
        <?= nl2br(htmlspecialchars($note["content"])) ?>
    </p>

    <hr>

    <!-- Link sửa note -->
    <a href="index.php?page=note-edit&id=<?= $note["id"] ?>">
        Sửa note
    </a>

    |

    <!-- Link xóa note -->
    <a 
        href="index.php?page=note-delete&id=<?= $note["id"] ?>"
        onclick="return confirm('Bạn có chắc chắn muốn xóa note này không?')"
    >
        Xóa note
    </a>

</body>
</html>