<?php

/**
 * AuthController
 * 
 * Controller này xử lý các chức năng liên quan đến tài khoản:
 * - Đăng ký
 * - Đăng nhập
 * - Đăng xuất
 */

require_once __DIR__ . "/../models/User.php";

class AuthController
{
    /**
     * Biến $userModel dùng để gọi các hàm trong User model.
     * Ví dụ: create(), findByEmail(), findById()
     */
    private $userModel;

    /**
     * Hàm khởi tạo AuthController.
     * 
     * Khi tạo AuthController, ta truyền kết nối database $pdo vào.
     * Sau đó tạo User model để thao tác với bảng users.
     */
    public function __construct($pdo)
    {
        $this->userModel = new User($pdo);
    }

    /**
     * Hiển thị form đăng ký.
     * 
     * Hàm này chỉ có nhiệm vụ gọi view register.php.
     */
    public function showRegister()
    {
        require __DIR__ . "/../views/auth/register.php";
    }

    /**
     * Xử lý đăng ký tài khoản.
     * 
     * Hàm này chạy khi user submit form đăng ký.
     */
    public function register()
    {
        /**
         * Lấy dữ liệu từ form gửi lên bằng method POST.
         * trim() dùng để xóa khoảng trắng thừa ở đầu và cuối chuỗi.
         */
        $name = trim($_POST["name"] ?? "");
        $email = trim($_POST["email"] ?? "");
        $password = $_POST["password"] ?? "";

        /**
         * Kiểm tra người dùng có nhập đủ thông tin không.
         */
        if ($name === "" || $email === "" || $password === "") {
            $_SESSION["error"] = "Vui lòng nhập đầy đủ thông tin.";
            header("Location: index.php?page=register");
            exit;
        }

        /**
         * Kiểm tra email có đúng định dạng không.
         * Ví dụ đúng: abc@gmail.com
         * Ví dụ sai: abcgmail.com
         */
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION["error"] = "Email không hợp lệ.";
            header("Location: index.php?page=register");
            exit;
        }

        /**
         * Kiểm tra độ dài mật khẩu.
         * Ở đây yêu cầu tối thiểu 6 ký tự.
         */
        if (strlen($password) < 6) {
            $_SESSION["error"] = "Mật khẩu phải có ít nhất 6 ký tự.";
            header("Location: index.php?page=register");
            exit;
        }

        /**
         * Kiểm tra email đã tồn tại trong database chưa.
         * Nếu đã có user dùng email này rồi thì không cho đăng ký nữa.
         */
        $existingUser = $this->userModel->findByEmail($email);

        if ($existingUser) {
            $_SESSION["error"] = "Email đã tồn tại.";
            header("Location: index.php?page=register");
            exit;
        }

        /**
         * Gọi User model để tạo user mới.
         * Trong hàm create() của User.php, password sẽ được mã hóa bằng password_hash().
         */
        $this->userModel->create($name, $email, $password);

        /**
         * Lưu thông báo thành công vào session.
         * Sau đó chuyển user sang trang đăng nhập.
         */
        $_SESSION["success"] = "Đăng ký thành công. Vui lòng đăng nhập.";
        header("Location: index.php?page=login");
        exit;
    }

    /**
     * Hiển thị form đăng nhập.
     */
    public function showLogin()
    {
        require __DIR__ . "/../views/auth/login.php";
    }

    /**
     * Xử lý đăng nhập.
     * 
     * Hàm này chạy khi user submit form login.
     */
    public function login()
    {
        /**
         * Lấy email và password từ form đăng nhập.
         */
        $email = trim($_POST["email"] ?? "");
        $password = $_POST["password"] ?? "";

        /**
         * Kiểm tra không được để trống.
         */
        if ($email === "" || $password === "") {
            $_SESSION["error"] = "Vui lòng nhập email và mật khẩu.";
            header("Location: index.php?page=login");
            exit;
        }

        /**
         * Tìm user trong database theo email.
         */
        $user = $this->userModel->findByEmail($email);

        /**
         * Nếu không tìm thấy user thì báo lỗi.
         */
        if (!$user) {
            $_SESSION["error"] = "Email hoặc mật khẩu không đúng.";
            header("Location: index.php?page=login");
            exit;
        }

        /**
         * Kiểm tra mật khẩu.
         * 
         * $password là mật khẩu user vừa nhập.
         * $user["password"] là mật khẩu đã mã hóa trong database.
         * 
         * password_verify() dùng để so sánh mật khẩu thường với mật khẩu đã hash.
         */
        if (!password_verify($password, $user["password"])) {
            $_SESSION["error"] = "Email hoặc mật khẩu không đúng.";
            header("Location: index.php?page=login");
            exit;
        }

        /**
         * Nếu email và password đúng:
         * Lưu thông tin user vào session.
         * 
         * Sau này dựa vào $_SESSION["user_id"] để biết user nào đang đăng nhập.
         */
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["user_name"] = $user["name"];

        /**
         * Chuyển sang trang danh sách notes.
         */
        header("Location: index.php?page=notes");
        exit;
    }

    /**
     * Xử lý đăng xuất.
     */
    public function logout()
    {
        /**
         * Xóa toàn bộ session hiện tại.
         * Sau khi session bị xóa, user xem như đã đăng xuất.
         */
        session_destroy();

        /**
         * Chuyển về trang đăng nhập.
         */
        header("Location: index.php?page=login");
        exit;
    }
}