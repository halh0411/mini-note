<?php

/**
 * File index.php là file chạy chính của project.
 * Tất cả request sẽ đi qua file này.
 */

/**
 * Bắt đầu session.
 * Session dùng để lưu trạng thái đăng nhập.
 */
session_start();

/**
 * Nạp file kết nối database.
 * Sau dòng này ta có biến $pdo.
 */
require_once __DIR__ . "/../app/config/database.php";

/**
 * Nạp các controller.
 */
require_once __DIR__ . "/../app/controllers/AuthController.php";
require_once __DIR__ . "/../app/controllers/NoteController.php";

/**
 * Tạo object controller.
 * Truyền $pdo vào để controller có thể thao tác database.
 */
$authController = new AuthController($pdo);
$noteController = new NoteController($pdo);

/**
 * Lấy page từ URL.
 *
 * Ví dụ:
 * index.php?page=login
 * index.php?page=notes
 */
$page = $_GET["page"] ?? "login";

/**
 * Điều hướng theo page.
 */
switch ($page) {

    // Hiển thị form đăng ký
    // URL: index.php?page=register
    case "register":
        $authController->showRegister();
        break;

    // Xử lý form đăng ký, lưu user vào database
    // URL: index.php?page=register-post
    case "register-post":
        $authController->register();
        break;

    // Hiển thị form đăng nhập
    // URL: index.php?page=login
    case "login":
        $authController->showLogin();
        break;

    // Xử lý đăng nhập, kiểm tra email/password và tạo session
    // URL: index.php?page=login-post
    case "login-post":
        $authController->login();
        break;

    // Đăng xuất, xóa session và quay về login
    // URL: index.php?page=logout
    case "logout":
        $authController->logout();
        break;

    // Hiển thị danh sách notes của user đang đăng nhập
    // URL: index.php?page=notes
    case "notes":
        $noteController->index();
        break;

    // Hiển thị form tạo note mới
    // URL: index.php?page=note-create
    case "note-create":
        $noteController->create();
        break;

    // Xử lý lưu note mới vào database
    // URL: index.php?page=note-store
    case "note-store":
        $noteController->store();
        break;

    // Xem chi tiết một note theo id
    // URL: index.php?page=note-show&id=1
    case "note-show":
        $noteController->show();
        break;

    // Hiển thị form sửa note theo id
    // URL: index.php?page=note-edit&id=1
    case "note-edit":
        $noteController->edit();
        break;

    // Xử lý cập nhật note sau khi submit form sửa
    // URL: index.php?page=note-update
    case "note-update":
        $noteController->update();
        break;

    // Xóa note theo id nếu note thuộc user hiện tại
    // URL: index.php?page=note-delete&id=1
    case "note-delete":
        $noteController->delete();
        break;

    // Nếu page không khớp route nào thì báo lỗi
    default:
        echo "404 - Không tìm thấy trang.";
        break;
}