<?php

/**
 * File index.php là file chạy chính của project.
 * 
 * Mọi request sẽ đi qua file này.
 * Ví dụ:
 * - index.php?page=login
 * - index.php?page=register
 * - index.php?page=logout
 */

/**
 * Bắt đầu session.
 * 
 * Session dùng để lưu trạng thái đăng nhập.
 * Ví dụ sau khi login thành công:
 * $_SESSION["user_id"] = 1;
 */
session_start();

/**
 * Nạp file kết nối database.
 * 
 * Sau khi nạp file này, ta có biến $pdo.
 * Biến $pdo dùng để thao tác với MySQL.
 */
require_once __DIR__ . "/../app/config/database.php";

/**
 * Nạp AuthController.
 * 
 * AuthController xử lý:
 * - Đăng ký
 * - Đăng nhập
 * - Đăng xuất
 */
require_once __DIR__ . "/../app/controllers/AuthController.php";

/**
 * Tạo object AuthController.
 * 
 * Truyền $pdo vào để AuthController có thể dùng database.
 */
$authController = new AuthController($pdo);

/**
 * Lấy tham số page trên URL.
 * 
 * Ví dụ URL:
 * index.php?page=login
 * thì $page = "login"
 * 
 * Nếu không có page, mặc định cho về trang login.
 */
$page = $_GET["page"] ?? "login";

/**
 * Điều hướng request.
 * 
 * Dựa vào $page để gọi đúng hàm trong Controller.
 */
switch ($page) {

    /**
     * Hiển thị form đăng ký.
     * URL: index.php?page=register
     */
    case "register":
        $authController->showRegister();
        break;

    /**
     * Xử lý dữ liệu đăng ký.
     * URL: index.php?page=register-post
     * Form register.php sẽ gửi dữ liệu về đây bằng POST.
     */
    case "register-post":
        $authController->register();
        break;

    /**
     * Hiển thị form đăng nhập.
     * URL: index.php?page=login
     */
    case "login":
        $authController->showLogin();
        break;

    /**
     * Xử lý dữ liệu đăng nhập.
     * URL: index.php?page=login-post
     * Form login.php sẽ gửi dữ liệu về đây bằng POST.
     */
    case "login-post":
        $authController->login();
        break;

    /**
     * Đăng xuất.
     * URL: index.php?page=logout
     */
    case "logout":
        $authController->logout();
        break;

    /**
     * Tạm thời tạo route notes để test sau khi login thành công.
     * Phần notes thật sẽ code ở bước sau.
     */
    case "notes":
        if (!isset($_SESSION["user_id"])) {
            header("Location: index.php?page=login");
            exit;
        }

        echo "<h1>Đăng nhập thành công!</h1>";
        echo "<p>Xin chào, " . $_SESSION["user_name"] . "</p>";
        echo "<p>Trang danh sách notes sẽ code ở bước tiếp theo.</p>";
        echo "<a href='index.php?page=logout'>Đăng xuất</a>";
        break;

    /**
     * Nếu page không hợp lệ thì báo 404.
     */
    default:
        echo "404 - Không tìm thấy trang.";
        break;
}