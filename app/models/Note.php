<?php

/**
 * Class Note
 * 
 * File này là Model của bảng notes.
 * Model có nhiệm vụ thao tác trực tiếp với database.
 * 
 * Note model xử lý:
 * - Lấy danh sách note của user
 * - Search note của user
 * - Tạo note mới
 * - Xem chi tiết 1 note
 * - Cập nhật note
 * - Xóa note
 */

class Note
{
    /**
     * Biến $pdo dùng để lưu kết nối database.
     * 
     * $pdo được truyền từ file database.php thông qua controller.
     */
    private $pdo;

    /**
     * Hàm khởi tạo.
     * 
     * Khi tạo object Note, ta truyền kết nối database vào.
     * Ví dụ:
     * $noteModel = new Note($pdo);
     */
    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Lấy tất cả notes của một user.
     * 
     * Hàm này dùng cho trang danh sách notes.
     * Nếu có keyword thì sẽ search theo title hoặc content.
     * Nếu không có keyword thì lấy toàn bộ notes của user.
     */
    public function getAllByUser($userId, $keyword = "")
    {
        /**
         * Nếu keyword khác rỗng thì thực hiện search.
         */
        if ($keyword !== "") {

            /**
             * Câu SQL tìm note theo user_id và keyword.
             * 
             * Điều kiện quan trọng:
             * user_id = :user_id
             * 
             * Điều này đảm bảo user chỉ search trong notes của chính mình.
             */
            $sql = "SELECT * FROM notes
                    WHERE user_id = :user_id
                    AND (title LIKE :keyword OR content LIKE :keyword)
                    ORDER BY created_at DESC";

            $stmt = $this->pdo->prepare($sql);

            $searchKeyword = "%" . $keyword . "%";
            $stmt->execute([
                ":user_id" => $userId,
                ":keyword" => $searchKeyword
            ]);

        } else {

            /**
             * Nếu không search thì lấy toàn bộ notes của user.
             */
            $sql = "SELECT * FROM notes
                    WHERE user_id = :user_id
                    ORDER BY created_at DESC";

            /**
             * Chuẩn bị câu SQL.
             */
            $stmt = $this->pdo->prepare($sql);

            /**
             * Thực thi câu SQL.
             */
            $stmt->execute([
                ":user_id" => $userId
            ]);
        }

        /**
         * fetchAll() lấy tất cả dòng dữ liệu.
         * PDO::FETCH_ASSOC trả về dạng mảng theo tên cột.
         */
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Tạo note mới.
     * 
     * Hàm này dùng khi user submit form tạo note.
    
     */
    public function create($userId, $title, $content)
    {
        /**
         * Câu SQL thêm note mới.
         * 
         * Không cho user tự nhập user_id từ form.
         * user_id phải lấy từ session.
         */
        $sql = "INSERT INTO notes (user_id, title, content)
                VALUES (:user_id, :title, :content)";

        /**
         * Chuẩn bị câu SQL.
         */
        $stmt = $this->pdo->prepare($sql);

        /**
         * Thực thi câu SQL và truyền dữ liệu vào placeholder.
         */
        return $stmt->execute([
            ":user_id" => $userId,
            ":title" => $title,
            ":content" => $content
        ]);
    }

    /**
     * Tìm một note theo id và user_id.
     * 
     * Hàm này dùng cho:
     * - Xem chi tiết note
     * - Mở form sửa note
     * - Kiểm tra quyền trước khi sửa/xóa
     * 
     * @param int $id ID của note
     * @param int $userId ID của user đang đăng nhập
     * 
     * @return array|false Trả về note nếu tìm thấy, false nếu không có
     */
    public function findByIdAndUser($id, $userId)
    {
        /**
         * Câu SQL tìm note.
         * 
         * Điều kiện:
         * id = :id
         * user_id = :user_id
         * 
         * Nghĩa là chỉ lấy note nếu note đó thuộc user hiện tại.
         */
        $sql = "SELECT * FROM notes
                WHERE id = :id AND user_id = :user_id
                LIMIT 1";

        /**
         * Chuẩn bị câu SQL.
         */
        $stmt = $this->pdo->prepare($sql);

        /**
         * Thực thi câu SQL.
         */
        $stmt->execute([
            ":id" => $id,
            ":user_id" => $userId
        ]);

        /**
         * Trả về 1 note.
         * Nếu không tìm thấy thì trả về false.
         */
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Cập nhật note.
     * 
     * Hàm này dùng khi user submit form sửa note.
     
    */
    public function update($id, $userId, $title, $content)
    {
        /**
         * Câu SQL cập nhật note.
         * 
         * Vẫn phải có user_id để đảm bảo:
         * user chỉ sửa được note của chính mình.
         */
        $sql = "UPDATE notes
                SET title = :title,
                    content = :content
                WHERE id = :id AND user_id = :user_id";

        /**
         * Chuẩn bị câu SQL.
         */
        $stmt = $this->pdo->prepare($sql);

        /**
         * Thực thi câu SQL.
         */
        return $stmt->execute([
            ":id" => $id,
            ":user_id" => $userId,
            ":title" => $title,
            ":content" => $content
        ]);
    }

    /**
     * Xóa note.
     * 
     * Hàm này dùng khi user bấm xóa note.
     */
    public function delete($id, $userId)
    {
        /**
         * Câu SQL xóa note.
         * 
         * Điều kiện:
         * id = :id
         * user_id = :user_id
         * 
         * Điều này giúp user không xóa được note của người khác.
         */
        $sql = "DELETE FROM notes
                WHERE id = :id AND user_id = :user_id";

        /**
         * Chuẩn bị câu SQL.
         */
        $stmt = $this->pdo->prepare($sql);

        /**
         * Thực thi câu SQL.
         */
        return $stmt->execute([
            ":id" => $id,
            ":user_id" => $userId
        ]);
    }
}