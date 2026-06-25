<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng nhập - Mini Note</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <h1>Đăng nhập</h1>

    <!-- 
        Hiển thị thông báo thành công.
        Ví dụ sau khi đăng ký xong, AuthController sẽ lưu:
        $_SESSION["success"] = "Đăng ký thành công..."
    -->
    <?php if (isset($_SESSION["success"])): ?>
        <p style="color: green;">
            <?= $_SESSION["success"]; ?>
        </p>

        <!-- Xóa thông báo sau khi hiển thị -->
        <?php unset($_SESSION["success"]); ?>
    <?php endif; ?>

    <!-- 
        Hiển thị lỗi đăng nhập.
        Ví dụ:
        - Chưa nhập email/password
        - Email hoặc mật khẩu sai
    -->
    <?php if (isset($_SESSION["error"])): ?>
        <p style="color: red;">
            <?= $_SESSION["error"]; ?>
        </p>

        <!-- Xóa lỗi sau khi hiển thị -->
        <?php unset($_SESSION["error"]); ?>
    <?php endif; ?>

    <!-- 
        Form đăng nhập.
        Khi bấm submit, dữ liệu gửi về:
        index.php?page=login-post
    -->
    <form action="index.php?page=login-post" method="POST">

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

        <button type="submit">Đăng nhập</button>
    </form>

    <p>
        Chưa có tài khoản?
        <a href="index.php?page=register">Đăng ký</a>
    </p>

</body>
</html>