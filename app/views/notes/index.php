<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Danh sách Notes - Mini Note</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <h1>Mini Note</h1>

    <!-- 
        Hiển thị tên user đang đăng nhập.
        $_SESSION["user_name"] được lưu khi đăng nhập thành công.
    -->
    <p>
        Xin chào, 
        <strong><?= htmlspecialchars($_SESSION["user_name"] ?? "User") ?></strong>
    </p>

    <!-- Link đăng xuất -->
    <p>
        <a href="index.php?page=logout">Đăng xuất</a>
    </p>

    <hr>

    <!-- 
        Hiển thị thông báo thành công.
        Ví dụ:
        - Tạo note thành công
        - Cập nhật note thành công
        - Xóa note thành công
    -->
    <?php if (isset($_SESSION["success"])): ?>
        <p style="color: green;">
            <?= htmlspecialchars($_SESSION["success"]) ?>
        </p>
        <?php unset($_SESSION["success"]); ?>
    <?php endif; ?>

    <!-- 
        Hiển thị thông báo lỗi.
        Ví dụ:
        - Không tìm thấy note
        - Không có quyền xem/sửa/xóa
    -->
    <?php if (isset($_SESSION["error"])): ?>
        <p style="color: red;">
            <?= htmlspecialchars($_SESSION["error"]) ?>
        </p>
        <?php unset($_SESSION["error"]); ?>
    <?php endif; ?>

    <!-- Nút tạo note mới -->
    <p>
        <a href="index.php?page=note-create">+ Tạo note mới</a>
    </p>

    <!-- 
        Form search note.
        Dùng method GET vì search nên hiển thị keyword trên URL.
        
        Khi submit sẽ tạo URL dạng:
        index.php?page=notes&keyword=php
    -->
    <form action="index.php" method="GET">

        <!-- 
            Input hidden này giữ page=notes.
            Nếu không có dòng này, khi submit sẽ mất page=notes.
        -->
        <input type="hidden" name="page" value="notes">

        <input 
            type="text" 
            name="keyword" 
            placeholder="Tìm theo tiêu đề hoặc nội dung"
            value="<?= htmlspecialchars($keyword ?? "") ?>"
        >

        <button type="submit">Tìm kiếm</button>

        <!-- Nếu đang search thì cho nút quay lại tất cả notes -->
        <?php if (!empty($keyword)): ?>
            <a href="index.php?page=notes">Xóa tìm kiếm</a>
        <?php endif; ?>
    </form>

    <hr>

    <h2>Danh sách ghi chú</h2>

    <!-- 
        Nếu mảng $notes rỗng, nghĩa là user chưa có note
        hoặc search không tìm thấy kết quả.
    -->
    <?php if (empty($notes)): ?>

        <p>Không có note nào.</p>

    <?php else: ?>

        <!-- Hiển thị danh sách notes -->
        <table border="1" cellpadding="8" cellspacing="0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tiêu đề</th>
                    <th>Nội dung ngắn</th>
                    <th>Ngày tạo</th>
                    <th>Cập nhật</th>
                    <th>Hành động</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($notes as $note): ?>
                    <tr>
                        <td>
                            <?= htmlspecialchars($note["id"]) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($note["title"]) ?>
                        </td>

                        <td>
                            <!-- 
                                Cắt nội dung cho ngắn lại.
                                Nếu content dài thì chỉ hiện 100 ký tự đầu.
                            -->
                            <?= htmlspecialchars(mb_substr($note["content"], 0, 100)) ?>

                            <?php if (mb_strlen($note["content"]) > 100): ?>
                                ...
                            <?php endif; ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($note["created_at"]) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($note["updated_at"]) ?>
                        </td>

                        <td>
                            <!-- Xem chi tiết note -->
                            <a href="index.php?page=note-show&id=<?= $note["id"] ?>">
                                Xem
                            </a>

                            |

                            <!-- Sửa note -->
                            <a href="index.php?page=note-edit&id=<?= $note["id"] ?>">
                                Sửa
                            </a>

                            |

                            <!-- 
                                Xóa note.
                                confirm() dùng để hỏi lại trước khi xóa.
                            -->
                            <a 
                                href="index.php?page=note-delete&id=<?= $note["id"] ?>"
                                onclick="return confirm('Bạn có chắc chắn muốn xóa note này không?')"
                            >
                                Xóa
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    <?php endif; ?>

</body>
</html>