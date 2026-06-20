<?php

/**
 * NoteController
 *
 * Controller này xử lý toàn bộ chức năng liên quan đến ghi chú:
 * - Hiển thị danh sách note
 * - Tìm kiếm note
 * - Hiển thị form tạo note
 * - Lưu note mới
 * - Xem chi tiết note
 * - Hiển thị form sửa note
 * - Cập nhật note
 * - Xóa note
 */

/**
 * Nạp Note model để thao tác với bảng notes.
 */
require_once __DIR__ . "/../models/Note.php";

/**
 * Nạp helper auth.php để dùng hàm requireLogin().
 * Hàm requireLogin() giúp chặn người chưa đăng nhập truy cập trang notes.
 */
require_once __DIR__ . "/../helpers/auth.php";

class NoteController
{
    /**
     * Biến $noteModel dùng để gọi các hàm trong Note.php.
     */
    private $noteModel;

    /**
     * Hàm khởi tạo NoteController.
     *
     * Khi tạo NoteController, ta truyền kết nối database $pdo vào.
     * Sau đó tạo Note model để thao tác với bảng notes.
     */
    public function __construct($pdo)
    {
        $this->noteModel = new Note($pdo);
    }

    /**
     * Hiển thị danh sách notes của user đang đăng nhập.
     *
     * Hàm này cũng xử lý luôn chức năng search.
     */
    public function index()
    {
        /**
         * Bắt buộc user phải đăng nhập.
         * Nếu chưa đăng nhập thì tự chuyển về trang login.
         */
        requireLogin();

        /**
         * Lấy id của user đang đăng nhập từ session.
         *
         * Sau khi login thành công, AuthController đã lưu:
         * $_SESSION["user_id"] = $user["id"];
         */
        $userId = $_SESSION["user_id"];

        /**
         * Lấy keyword tìm kiếm từ URL.
         *
         * Ví dụ:
         * index.php?page=notes&keyword=php
         *
         * Nếu không có keyword thì mặc định là chuỗi rỗng.
         */
        $keyword = trim($_GET["keyword"] ?? "");

        /**
         * Gọi Note model để lấy danh sách notes.
         *
         * Nếu keyword rỗng: lấy toàn bộ notes của user.
         * Nếu keyword có dữ liệu: search trong notes của user.
         */
        $notes = $this->noteModel->getAllByUser($userId, $keyword);

        /**
         * Gọi view hiển thị danh sách notes.
         *
         * Biến $notes và $keyword sẽ được dùng trong file view này.
         */
        require __DIR__ . "/../views/notes/index.php";
    }

    /**
     * Hiển thị form tạo note mới.
     */
    public function create()
    {
        /**
         * Chỉ user đã đăng nhập mới được tạo note.
         */
        requireLogin();

        /**
         * Gọi view form tạo note.
         */
        require __DIR__ . "/../views/notes/create.php";
    }

    /**
     * Xử lý lưu note mới vào database.
     *
     * Hàm này chạy khi user submit form tạo note.
     */
    public function store()
    {
        /**
         * Chỉ user đã đăng nhập mới được lưu note.
         */
        requireLogin();

        /**
         * Lấy dữ liệu từ form gửi lên.
         */
        $title = trim($_POST["title"] ?? "");
        $content = trim($_POST["content"] ?? "");

        /**
         * Lấy user_id từ session.
         *
         * Không lấy user_id từ form để tránh user giả mạo id người khác.
         */
        $userId = $_SESSION["user_id"];

        /**
         * Kiểm tra tiêu đề không được để trống.
         */
        if ($title === "") {
            $_SESSION["error"] = "Tiêu đề note không được để trống.";
            header("Location: index.php?page=note-create");
            exit;
        }

        /**
         * Gọi Note model để thêm note mới vào database.
         */
        $this->noteModel->create($userId, $title, $content);

        /**
         * Lưu thông báo thành công.
         */
        $_SESSION["success"] = "Tạo note thành công.";

        /**
         * Chuyển về trang danh sách notes.
         */
        header("Location: index.php?page=notes");
        exit;
    }

    /**
     * Hiển thị chi tiết một note.
     */
    public function show()
    {
        /**
         * Chỉ user đã đăng nhập mới được xem note.
         */
        requireLogin();

        /**
         * Lấy id note từ URL.
         *
         * Ví dụ:
         * index.php?page=note-show&id=3
         */
        $id = (int)($_GET["id"] ?? 0);

        /**
         * Lấy user_id hiện tại từ session.
         */
        $userId = $_SESSION["user_id"];

        /**
         * Nếu id không hợp lệ thì chuyển về danh sách.
         */
        if ($id <= 0) {
            $_SESSION["error"] = "Note không hợp lệ.";
            header("Location: index.php?page=notes");
            exit;
        }

        /**
         * Tìm note theo id và user_id.
         *
         * Điều này đảm bảo user chỉ xem được note của chính mình.
         */
        $note = $this->noteModel->findByIdAndUser($id, $userId);

        /**
         * Nếu không tìm thấy note, có thể là:
         * - Note không tồn tại
         * - Note thuộc user khác
         */
        if (!$note) {
            $_SESSION["error"] = "Không tìm thấy note hoặc bạn không có quyền xem.";
            header("Location: index.php?page=notes");
            exit;
        }

        /**
         * Gọi view hiển thị chi tiết note.
         */
        require __DIR__ . "/../views/notes/show.php";
    }

    /**
     * Hiển thị form sửa note.
     */
    public function edit()
    {
        /**
         * Chỉ user đã đăng nhập mới được sửa note.
         */
        requireLogin();

        /**
         * Lấy id note từ URL.
         */
        $id = (int)($_GET["id"] ?? 0);

        /**
         * Lấy user_id hiện tại.
         */
        $userId = $_SESSION["user_id"];

        /**
         * Kiểm tra id hợp lệ.
         */
        if ($id <= 0) {
            $_SESSION["error"] = "Note không hợp lệ.";
            header("Location: index.php?page=notes");
            exit;
        }

        /**
         * Tìm note theo id và user_id.
         *
         * Nếu note không thuộc user hiện tại thì không cho sửa.
         */
        $note = $this->noteModel->findByIdAndUser($id, $userId);

        if (!$note) {
            $_SESSION["error"] = "Không tìm thấy note hoặc bạn không có quyền sửa.";
            header("Location: index.php?page=notes");
            exit;
        }

        /**
         * Gọi view form sửa note.
         *
         * Biến $note sẽ được dùng để đổ dữ liệu cũ vào form.
         */
        require __DIR__ . "/../views/notes/edit.php";
    }

    /**
     * Xử lý cập nhật note.
     *
     * Hàm này chạy khi user submit form sửa note.
     */
    public function update()
    {
        /**
         * Chỉ user đã đăng nhập mới được cập nhật note.
         */
        requireLogin();

        /**
         * Lấy dữ liệu từ form sửa note.
         */
        $id = (int)($_POST["id"] ?? 0);
        $title = trim($_POST["title"] ?? "");
        $content = trim($_POST["content"] ?? "");

        /**
         * Lấy user_id hiện tại.
         */
        $userId = $_SESSION["user_id"];

        /**
         * Kiểm tra id note hợp lệ.
         */
        if ($id <= 0) {
            $_SESSION["error"] = "Note không hợp lệ.";
            header("Location: index.php?page=notes");
            exit;
        }

        /**
         * Kiểm tra tiêu đề không được rỗng.
         */
        if ($title === "") {
            $_SESSION["error"] = "Tiêu đề note không được để trống.";
            header("Location: index.php?page=note-edit&id=" . $id);
            exit;
        }

        /**
         * Kiểm tra note có tồn tại và có thuộc user hiện tại không.
         */
        $note = $this->noteModel->findByIdAndUser($id, $userId);

        if (!$note) {
            $_SESSION["error"] = "Không tìm thấy note hoặc bạn không có quyền cập nhật.";
            header("Location: index.php?page=notes");
            exit;
        }

        /**
         * Gọi Note model để cập nhật note.
         */
        $this->noteModel->update($id, $userId, $title, $content);

        /**
         * Lưu thông báo thành công.
         */
        $_SESSION["success"] = "Cập nhật note thành công.";

        /**
         * Chuyển sang trang xem chi tiết note vừa sửa.
         */
        header("Location: index.php?page=note-show&id=" . $id);
        exit;
    }

    /**
     * Xóa note.
     */
    public function delete()
    {
        /**
         * Chỉ user đã đăng nhập mới được xóa note.
         */
        requireLogin();

        /**
         * Lấy id note từ URL.
         *
         * Ví dụ:
         * index.php?page=note-delete&id=3
         */
        $id = (int)($_GET["id"] ?? 0);

        /**
         * Lấy user_id hiện tại.
         */
        $userId = $_SESSION["user_id"];

        /**
         * Kiểm tra id hợp lệ.
         */
        if ($id <= 0) {
            $_SESSION["error"] = "Note không hợp lệ.";
            header("Location: index.php?page=notes");
            exit;
        }

        /**
         * Kiểm tra note có thuộc user hiện tại không.
         */
        $note = $this->noteModel->findByIdAndUser($id, $userId);

        if (!$note) {
            $_SESSION["error"] = "Không tìm thấy note hoặc bạn không có quyền xóa.";
            header("Location: index.php?page=notes");
            exit;
        }

        /**
         * Gọi Note model để xóa note.
         */
        $this->noteModel->delete($id, $userId);

        /**
         * Lưu thông báo thành công.
         */
        $_SESSION["success"] = "Xóa note thành công.";

        /**
         * Chuyển về danh sách notes.
         */
        header("Location: index.php?page=notes");
        exit;
    }
}