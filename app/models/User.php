<?php

/**
 * Class User
 * 
 * File này là Model của bảng users.
 * Model có nhiệm vụ làm việc trực tiếp với database.
 * 
 * Cụ thể User model sẽ xử lý:
 * - Thêm user mới khi đăng ký
 * - Tìm user theo email khi đăng nhập
 * - Tìm user theo id nếu cần lấy thông tin user
 */

class User
{
    /**
     * Biến $pdo dùng để lưu kết nối database.
     * Kết nối này được truyền từ file database.php sang.
     */
    private $pdo;
    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Hàm tạo user mới.
     * Hàm này dùng khi người dùng đăng ký tài khoản.
     */
    public function create($name, $email, $password)
    {
        $sql = "INSERT INTO users (name, email, password)
                VALUES (:name, :email, :password)";

        $stmt = $this->pdo->prepare($sql);
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        return $stmt->execute([
            ":name" => $name,
            ":email" => $email,
            ":password" => $hashedPassword
        ]);
    }

    /**
     * Hàm tìm user theo email.
     * 
     * Dùng trong chức năng đăng nhập.
     * Khi user nhập email, mình sẽ tìm xem email đó có tồn tại không.
     */
    public function findByEmail($email)
    {
        $sql = "SELECT * FROM users
                WHERE email = :email
                LIMIT 1";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ":email" => $email
        ]);

        /**
         * fetch(PDO::FETCH_ASSOC) lấy 1 dòng dữ liệu dạng mảng kết hợp.
         * 
         * Ví dụ kết quả:
         * [
         *   "id" => 1,
         *   "name" => "Nguyen Anh",
         *   "email" => "anh@gmail.com",
         *   "password" => "...",
         *   "created_at" => "2026-06-18 10:00:00"
         * ]
         */
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Hàm tìm user theo id.
     * 
     * Dùng khi đã đăng nhập và muốn lấy thông tin user hiện tại.
     **/
    public function findById($id)
    {
       
        $sql = "SELECT * FROM users
                WHERE id = :id
                LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ":id" => $id
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}