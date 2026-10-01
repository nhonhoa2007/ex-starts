# SYSTEM DESIGN — BÀI 7: PHP & MYSQL (QUẢN LÝ THỂ LOẠI)

**Học phần:** Công nghệ Web (Web Technology / Coursework Year 2)  
**Mục tiêu:** Xây dựng module CRUD quản trị Thể loại tin tức (tintuc) sử dụng PHP thuần và MySQLi.  
**Thư mục dự án:** `/home/nhonhoa/year2/php-web/baitapCNWEB/bai7_assignment1`

---

## 1. KIẾN TRÚC TỔNG QUAN & DÒNG DỮ LIỆU (SYSTEM ARCHITECTURE)

```
[ Trình duyệt Web / Client ]
       │
       ▼
 [ admin/theloai.php ] ◄── (READ) ──┐
       │ (Navigate)                 │
       ├─► [ admin/theloai_them.php ] ─► POST ─► [ theloai_them_xl.php ] ─► (INSERT) ──┐
       │                                                                                │
       ├─► [ admin/theloai_sua.php ] ──► POST ─► [ theloai_sua.php (self) ] ─► (UPDATE) ─┼──► [ MySQL / MariaDB (tintuc) ]
       │                                                                                │
       └─► [ admin/theloai_xoa.php ] ──► GET ──► (DELETE) ──────────────────────────────┘
                                                    │
                                                    ▼
                                          [ image/ directory ]
                                          (Lưu trữ & xóa tệp icon)
```

---

## 2. THIẾT KẾ CƠ SỞ DỮ LIỆU (DATABASE SCHEMA & DATA CONTRACT)

### Database: `tintuc`
Character Set: `utf8mb4` | Collation: `utf8mb4_unicode_ci` (tương thích ngược với `utf8_unicode_ci`)

### Table: `theloai`
| Tên Cột | Kiểu Dữ Liệu | Ràng Buộc | Ý Nghĩa / Mục Đích |
| :--- | :--- | :--- | :--- |
| `idTL` | `INT(11)` | `PRIMARY KEY AUTO_INCREMENT` | Định danh duy nhất cho thể loại |
| `TenTL` | `VARCHAR(255)` | `NOT NULL UNIQUE` | Tên thể loại (Giải trí, Xã hội, v.v.) |
| `ThuTu` | `INT(11)` | `NOT NULL DEFAULT 0` | Thứ tự hiển thị ưu tiên |
| `AnHien` | `TINYINT(1)` | `NOT NULL DEFAULT 1` | Trạng thái hiển thị (1: Hiện, 0: Ẩn) |
| `icon` | `VARCHAR(255)` | `NOT NULL DEFAULT ''` | Tên tệp hình ảnh lưu trong thư mục `../image/` |

---

## 3. FILE RESPONSIBILITY MATRIX & CONTRACTS

Mỗi file đảm nhiệm một trách nhiệm cụ thể (Single Responsibility Principle) theo đúng giáo trình:

| Đường dẫn tệp | Vai trò | Input | Output / Hành vi |
| :--- | :--- | :--- | :--- |
| `connect.php` | Quản trị kết nối CSDL | Host, User, Pass, DBName | Đối tượng kết nối `$connect` (`mysqli`). Fallback linh hoạt giữa `nhonhoa` và `root`. |
| `database.sql` | DDL & DML khởi tạo | Script SQL | Tạo CSDL `tintuc`, bảng `theloai` và 4 bản ghi dữ liệu mẫu. |
| `index.php` | Router điều hướng gốc | Không có | Chuyển hướng `header('Location: admin/theloai.php');` |
| `image/` | Lưu trữ assets | Multipart uploaded files | Lưu các file ảnh icon đại diện (`.jpg`, `.png`, v.v.). |
| `admin/theloai.php` | Hiển thị danh sách | Query DB | Bảng HTML hiển thị: TenTL, ThuTu, AnHien, Bieu tuong (thumbnail), nút Sửa, nút Xóa, nút Thêm. |
| `admin/theloai_them.php` | Giao diện thêm mới | Form input | Form nhập liệu: TenTL, ThuTu, AnHien (select option), icon (file input). POST sang `theloai_them_xl.php`. |
| `admin/theloai_them_xl.php` | Xử lý thêm mới | `$_POST`, `$_FILES` | Upload file vào `../image/`, chạy `INSERT INTO theloai`, alert và chuyển về `theloai.php`. |
| `admin/theloai_sua.php` | Giao diện & Xử lý cập nhật | `$_GET['idTL']`, `$_POST`, `$_FILES` | Hiển thị thông tin cũ kèm thumbnail, cho phép thay đổi thông tin và chọn ảnh mới (nếu chọn ảnh mới thì xóa ảnh cũ bằng `unlink`), UPDATE DB, alert và chuyển hướng. |
| `admin/theloai_xoa.php` | Xử lý xóa bản ghi | `$_GET['idTL']` | Xóa file ảnh trong `../image/` nếu tồn tại, chạy `DELETE FROM theloai`, alert và chuyển về `theloai.php`. |

---

## 4. QUY TẮC BẢO MẬT & TƯƠNG THÍCH KỸ THUẬT (TECHNICAL INVARIANTS)

1. **Khả năng tương thích PHP 8+:**
   - Trong PHP 8.x, `mysqli_error()` yêu cầu đối số: `mysqli_error($connect)`.
   - Tránh ném Fatal Error bằng cách luôn truyền `$connect` vào hàm `mysqli_error`.
2. **Cơ chế Fallback Kết nối CSDL:**
   - Hỗ trợ kết nối thử nghiệm với user `nhonhoa` (môi trường hiện tại của máy chủ nhân) hoặc `root` (chuẩn XAMPP mặc định) mà không gây gián đoạn:
     ```php
     $connect = @mysqli_connect('localhost', 'nhonhoa', '', 'tintuc');
     if (!$connect) {
         $connect = mysqli_connect('localhost', 'root', '', 'tintuc');
     }
     ```
3. **An toàn Thao tác File & Xóa Ảnh Cũ:**
   - Trước khi gọi `unlink()`, luôn kiểm tra `is_file($path) && file_exists($path)`.
   - Chuẩn hóa tên file tránh lỗi ký tự đặc biệt hoặc ghi đè không mong muốn.
4. **Xác nhận Người Dùng (UX Confirmation):**
   - Nút Xóa trên bảng danh sách phải kích hoạt JavaScript: `onclick="return confirm('Ban co chac chan muon xoa the loai nay?');"`.
