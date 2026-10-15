<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/class/class.db.theloai.php';

if (isset($_GET['id'])) {
    $db_theloai = new DB_TheLoai();
    $id = (int)$_GET['id'];

    $result = $db_theloai->xoa_theloai($id);
    if ($result) {
        echo '<script>alert("Xóa thành công!"); window.location="theloai.php";</script>';
        exit();
    } else {
        echo '<script>alert("Xóa không thành công!"); window.location="theloai.php";</script>';
        exit();
    }
} else {
    header("Location: theloai.php");
    exit();
}
?>

