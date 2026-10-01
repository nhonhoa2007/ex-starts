# IMPLEMENTATION PLAN — BÀI 7: PHP & MYSQL (bai7_assignment1)

**Tech Lead Orchestration Plan**  
Tài liệu phân công nhiệm vụ và ranh giới trách nhiệm cho các subagents chuyên biệt.

---

## 1. PHÂN CÔNG GÓI CÔNG VIỆC (WORK PACKAGES)

### Gói 1: Backend Architect (`be-coder`)
*Ranh giới: Tương tác CSDL, script database, kết nối, backend processing scripts.*
- [ ] **Tạo `connect.php`**:
  - Hỗ trợ kết nối mysqli đến CSDL `tintuc`.
  - Fallback tài khoản thông minh: thử `nhonhoa` (password rỗng), nếu không thành công fallback `root`.
  - Thiết lập charset `utf8mb4` / `utf8`.
- [ ] **Tạo `database.sql`**:
  - Script DDL tạo Database `tintuc`, Table `theloai`.
  - DML chèn 4 bản ghi mẫu (Giải trí, Pháp luật, Văn Hóa, Xã hội).
- [ ] **Tạo `admin/theloai_them_xl.php`**:
  - Nhận dữ liệu POST (`TenTL`, `ThuTu`, `AnHien`) và FILES (`image`).
  - Xử lý upload ảnh vào `../image/` an toàn.
  - Thực thi câu lệnh `INSERT INTO theloai`.
  - JavaScript alert thông báo và redirect về `theloai.php`.
- [ ] **Tạo `admin/theloai_xoa.php`**:
  - Nhận GET parameter `idTL`.
  - Lấy tên file ảnh icon hiện tại, xóa file vật lý trong `../image/` nếu tồn tại.
  - Thực thi câu lệnh `DELETE FROM theloai WHERE idTL = ...`.
  - JavaScript alert thông báo và redirect về `theloai.php`.

---

### Gói 2: Frontend Specialist (`fe-coder`)
*Ranh giới: Giao diện người dùng, forms, styles, client-side validation, redirect.*
- [ ] **Tạo `index.php`**:
  - Redirect sạch về `admin/theloai.php`.
- [ ] **Tạo `admin/theloai.php`**:
  - Bảng hiển thị danh sách thể loại: Tên Thể Loại, Thứ Tự, Ẩn/Hiện, Biểu tượng (thumbnail 40x40), Thao tác (Sửa, Xóa).
  - Nút thêm mới liên kết tới `theloai_them.php`.
  - Xác nhận JavaScript `confirm()` khi nhấn Xóa.
- [ ] **Tạo `admin/theloai_them.php`**:
  - Form thêm mới thể loại theo chuẩn mẫu giáo trình:
    - Text field: `TenTL`
    - Text field: `ThuTu`
    - Dropdown: `AnHien` (0: An, 1: Hien)
    - File upload: `image` (id `anh`)
    - Submit button `Them`, Reset button `Huy`.
- [ ] **Tạo `admin/theloai_sua.php`**:
  - Form chỉnh sửa thể loại theo `idTL`:
    - Truy vấn bản ghi cũ hiển thị lên form.
    - Hiển thị thumbnail icon hiện tại.
    - Cho phép chọn icon mới hoặc giữ nguyên icon cũ.
    - Tích hợp logic xử lý cập nhật (UPDATE query, xóa ảnh cũ nếu có ảnh mới, alert & redirect).

---

### Gói 3: QA & Testing Engineer (`tester`)
*Ranh giới: Xác minh toàn diện, kiểm thử chức năng, kiểm thử upload & xóa file.*
- [ ] Kiểm tra tính toàn vẹn cú pháp PHP (`php -l`) trên toàn bộ các tệp `.php`.
- [ ] Khởi chạy dev server `php -S 127.0.0.1:8999 -t /home/nhonhoa/year2/php-web/baitapCNWEB/bai7_assignment1`.
- [ ] Chạy automated end-to-end tests:
  1. **Test Read**: GET `/admin/theloai.php` trả về status 200 và hiển thị đủ danh sách thể loại mẫu.
  2. **Test Create**: POST dữ liệu thêm thể loại mới kèm tệp ảnh qua `/admin/theloai_them_xl.php`, kiểm tra CSDL có bản ghi mới và tệp ảnh tồn tại trong `image/`.
  3. **Test Update**: POST dữ liệu cập nhật `/admin/theloai_sua.php?idTL=...`, kiểm tra CSDL được update chính xác.
  4. **Test Delete**: GET `/admin/theloai_xoa.php?idTL=...`, kiểm tra bản ghi và tệp ảnh bị xóa sạch.
- [ ] Báo cáo nghiệm thu kết quả chi tiết.
