<?php
include_once('../connect.php');

$icon = '';
$image_file = $_FILES['image'] ?? $_FILES['anh'] ?? null;
if ($image_file && !empty($image_file['name'])) {
    $icon = basename($image_file['name']);
    $target_dir = "../image/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    if (!empty($image_file['tmp_name'])) {
        move_uploaded_file($image_file['tmp_name'], $target_dir . $icon);
    }
}

$theloai = isset($_POST['TenTL']) ? mysqli_real_escape_string($connect, trim($_POST['TenTL'])) : (isset($_POST['theloai']) ? mysqli_real_escape_string($connect, trim($_POST['theloai'])) : '');
$thutu = isset($_POST['ThuTu']) ? (int)$_POST['ThuTu'] : (isset($_POST['thutu']) ? (int)$_POST['thutu'] : 0);
$an = isset($_POST['AnHien']) ? (int)$_POST['AnHien'] : (isset($_POST['anhien']) ? (int)$_POST['anhien'] : 1);
$icon_db = mysqli_real_escape_string($connect, $icon);

$sl = "INSERT INTO theloai (TenTL, ThuTu, AnHien, icon) VALUES ('$theloai', '$thutu', '$an', '$icon_db')";

if (mysqli_query($connect, $sl)) {
    echo "<script>alert('Them thanh cong'); location.href='theloai.php';</script>";
} else {
    echo "Them that bai: " . mysqli_error($connect);
}
?>
