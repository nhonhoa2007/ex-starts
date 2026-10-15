<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/class/class.db.theloai.php';

if (isset($_POST['submit'])) {
    $db_theloai = new DB_TheLoai();
    $ten_theloai = isset($_POST['ten_theloai']) ? trim($_POST['ten_theloai']) : "";
    $thu_tu = isset($_POST['thu_tu']) ? (int)$_POST['thu_tu'] : 0;
    $an_hien = isset($_POST['an_hien']) ? (int)$_POST['an_hien'] : 1;

    if ($ten_theloai === "") {
        echo '<script>alert("Tên thể loại không được để trống!"); history.back();</script>';
        exit();
    } else {
        $result = $db_theloai->them_theloai($ten_theloai, $thu_tu, $an_hien);
        if ($result) {
            echo '<script>alert("Thêm thành công!"); window.location="theloai.php";</script>';
            exit();
        } else {
            echo '<script>alert("Thêm không thành công!"); window.location="theloai.php";</script>';
            exit();
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>THỂ LOẠI | QUẢN TRỊ</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
</head>
<body>
    <div class="container mt-4">
        <h1 class="mb-4">Thêm thể loại</h1>
        <form name="them_theloai" method="POST">
            <table class="table table-bordered" style="max-width: 600px;">
                <tr>
                    <td style="width: 150px;">Tên thể loại:</td>
                    <td><input type="text" class="form-control" name="ten_theloai" required></td>
                </tr>
                <tr>
                    <td>Thứ tự:</td>
                    <td><input type="number" class="form-control" name="thu_tu" value="0"></td>
                </tr>
                <tr>
                    <td>Ẩn/Hiện:</td>
                    <td>
                        <select class="form-control" name="an_hien">
                            <option value="1" selected>Hiện</option>
                            <option value="0">Ẩn</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <input type="submit" class="btn btn-primary" value="Thêm thể loại" name="submit">
                        <a href="theloai.php" class="btn btn-secondary ml-2">Quay lại</a>
                    </td>
                </tr>
            </table>
        </form>
    </div>
</body>
</html>

