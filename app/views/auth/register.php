<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng ký - Mini Note</title>
</head>
<body>

    <h1>Đăng ký tài khoản</h1>

    <!-- 
        Nếu có lỗi đăng ký, ví dụ:
        - Chưa nhập đủ thông tin
        - Email không hợp lệ
        - Email đã tồn tại
        thì AuthController sẽ lưu lỗi vào $_SESSION["error"].
        Ở đây ta hiển thị lỗi đó ra màn hình.
    -->
    <?php if (isset($_SESSION["error"])): ?>
        <p style="color: red;">
            <?= $_SESSION["error"]; ?>
        </p>

        <!-- 
            Sau khi hiển thị lỗi xong thì xóa lỗi khỏi session.
            Nếu không xóa, reload trang vẫn hiện lỗi cũ.
        -->
        <?php unset($_SESSION["error"]); ?>
    <?php endif; ?>

    <!-- 
        Form đăng ký.
        method="POST" nghĩa là dữ liệu form sẽ gửi ngầm trong body request.
        action="index.php?page=register-post" nghĩa là khi submit,
        request sẽ gửi về index.php với page=register-post.
    -->
    <form action="index.php?page=register-post" method="POST">

        <div>
            <label>Họ tên:</label><br>
            <input type="text" name="name" placeholder="Nhập họ tên">
        </div>

        <br>

        <div>
            <label>Email:</label><br>
            <input type="email" name="email" placeholder="Nhập email">
        </div>

        <br>

        <div>
            <label>Mật khẩu:</label><br>
            <input type="password" name="password" placeholder="Nhập mật khẩu">
        </div>

        <br>

        <button type="submit">Đăng ký</button>
    </form>

    <p>
        Đã có tài khoản?
        <a href="index.php?page=login">Đăng nhập</a>
    </p>

</body>
</html>