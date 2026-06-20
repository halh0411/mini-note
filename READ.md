# Mini Note

Mini Note là một website ghi chú đơn giản được xây dựng bằng PHP thuần và MySQL. Hệ thống cho phép người dùng đăng ký, đăng nhập, đăng xuất và quản lý các ghi chú cá nhân của mình.

## Công nghệ sử dụng

* PHP thuần
* MySQL
* PDO
* HTML
* CSS
* XAMPP
* phpMyAdmin
* Git/GitHub

## Chức năng chính

* Đăng ký tài khoản
* Đăng nhập
* Đăng xuất
* Hiển thị danh sách notes của người dùng đang đăng nhập
* Tạo note mới
* Xem chi tiết một note
* Sửa note của mình
* Xóa note của mình
* Tìm kiếm note theo tiêu đề hoặc nội dung
* Phân quyền để người dùng chỉ thao tác được với note của chính mình

## Cấu trúc thư mục

```txt
mini-note/
│
├── public/
│   ├── index.php
│   ├── test-db.php
│   └── assets/
│       └── css/
│           └── style.css
│
├── app/
│   ├── config/
│   │   └── database.php
│   │
│   ├── controllers/
│   │   ├── AuthController.php
│   │   └── NoteController.php
│   │
│   ├── models/
│   │   ├── User.php
│   │   └── Note.php
│   │
│   ├── views/
│   │   ├── auth/
│   │   │   ├── login.php
│   │   │   └── register.php
│   │   │
│   │   └── notes/
│   │       ├── index.php
│   │       ├── create.php
│   │       ├── show.php
│   │       └── edit.php
│   │
│   └── helpers/
│       └── auth.php
│
├── database/
│   └── mini_note.sql
│
└── README.md
```

## Cách chạy project

### Bước 1: Cài XAMPP

Cài XAMPP và bật hai service:

```txt
Apache
MySQL
```

### Bước 2: Đặt project vào thư mục htdocs

Đặt project vào:

```txt
C:\xampp\htdocs\mini-note
```

### Bước 3: Tạo database

Mở phpMyAdmin:

```txt
http://localhost/phpmyadmin
```

Tạo database tên:

```txt
mini_note
```

Sau đó import hoặc chạy file:

```txt
database/mini_note.sql
```

### Bước 4: Cấu hình database

Mở file:

```txt
app/config/database.php
```

Cấu hình kết nối MySQL:

```php
$host = "localhost";
$dbname = "mini_note";
$username = "root";
$password = "";
```

Với XAMPP mặc định, username là `root` và password để rỗng.

### Bước 5: Chạy website

Mở trình duyệt và truy cập:

```txt
http://localhost/mini-note/public/index.php
```

## Các URL chính

| Chức năng         | URL                               |
| ----------------- | --------------------------------- |
| Đăng ký           | `index.php?page=register`         |
| Đăng nhập         | `index.php?page=login`            |
| Đăng xuất         | `index.php?page=logout`           |
| Danh sách notes   | `index.php?page=notes`            |
| Tạo note          | `index.php?page=note-create`      |
| Xem chi tiết note | `index.php?page=note-show&id=1`   |
| Sửa note          | `index.php?page=note-edit&id=1`   |
| Xóa note          | `index.php?page=note-delete&id=1` |

## Database

Project gồm 2 bảng chính:

### Bảng users

Dùng để lưu thông tin tài khoản người dùng.

Các cột chính:

* `id`
* `name`
* `email`
* `password`
* `created_at`

### Bảng notes

Dùng để lưu ghi chú của người dùng.

Các cột chính:

* `id`
* `user_id`
* `title`
* `content`
* `created_at`
* `updated_at`

Trong đó `user_id` là khóa ngoại liên kết với bảng `users`.

## Bảo mật cơ bản

Project có sử dụng một số xử lý bảo mật cơ bản:

* Mật khẩu được mã hóa bằng `password_hash()`
* Đăng nhập kiểm tra mật khẩu bằng `password_verify()`
* Truy vấn database bằng PDO prepared statement
* Session dùng để quản lý trạng thái đăng nhập
* Mỗi note đều gắn với `user_id`
* Người dùng chỉ được xem, sửa, xóa note của chính mình

## Luồng hoạt động chính

### Đăng ký

Người dùng nhập họ tên, email và mật khẩu. Hệ thống kiểm tra dữ liệu, kiểm tra email đã tồn tại chưa, sau đó mã hóa mật khẩu và lưu tài khoản vào bảng `users`.

### Đăng nhập

Người dùng nhập email và mật khẩu. Hệ thống tìm user theo email, kiểm tra mật khẩu bằng `password_verify()`. Nếu đúng, hệ thống lưu `user_id` và `user_name` vào session.

### Quản lý note

Sau khi đăng nhập, người dùng có thể tạo, xem, sửa, xóa và tìm kiếm ghi chú. Mọi thao tác với note đều kiểm tra `user_id` để đảm bảo người dùng chỉ thao tác với dữ liệu của chính mình.

## Tác giả

Mini Note được xây dựng cho bài thực hành Task 1 với yêu cầu sử dụng PHP và MySQL.
