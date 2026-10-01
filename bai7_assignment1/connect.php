<?php
$connect = @mysqli_connect('localhost', 'nhonhoa', '', 'tintuc');
if (!$connect) {
    $connect = @mysqli_connect('localhost', 'root', '', 'tintuc');
}

if (!$connect) {
    die("Khong the ket noi CSDL: " . mysqli_connect_error() . " (Ma loi: " . mysqli_connect_errno() . ")");
}

mysqli_set_charset($connect, 'utf8mb4');

// Aliases for compatibility
$link = $connect;
$conn = $connect;
?>
