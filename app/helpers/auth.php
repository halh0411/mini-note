<?php

/**
 * - Kiểm tra user đã đăng nhập chưa
 * - Nếu chưa đăng nhập thì chuyển về trang login
 */


/**
 * Hàm requireLogin()
 * 
 * Hàm này dùng để bảo vệ các trang cần đăng nhập.
 * Ví dụ:
 * - Trang danh sách notes
 * - Trang tạo note
 * - Trang sửa note
 * - Trang xóa note
 * 
 * Nếu user chưa đăng nhập thì không cho vào.
 */
function requireLogin()
{
    /**
     * Sau khi đăng nhập thành công, ta đã lưu:
     * $_SESSION["user_id"] = $user["id"];
     * 
     * Vì vậy, nếu không tồn tại $_SESSION["user_id"]
     * nghĩa là user chưa đăng nhập.
     */
    if (!isset($_SESSION["user_id"])) {

        /**
         * Chuyển user về trang đăng nhập.
         */
        header("Location: index.php?page=login");

        /**
         * Dừng chương trình ngay.
         * Nếu không exit, code phía dưới vẫn có thể chạy tiếp.
         */
        exit;
    }
}


/**
 * Hàm isLoggedIn()
 * 
 * Hàm này dùng để kiểm tra nhanh user đã đăng nhập chưa.
 * 
 * Trả về:
 * - true nếu đã đăng nhập
 * - false nếu chưa đăng nhập
 */
function isLoggedIn()
{
    return isset($_SESSION["user_id"]);
}