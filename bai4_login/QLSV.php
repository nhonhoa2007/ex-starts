<?php
session_start();
if (!isset($_SESSION['code'])) {
    header("Location: login.html");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Quản lý sinh viên</title>
<style>
body { font-family: Arial, sans-serif; margin: 40px auto; width: 500px; }
.card { border: 1px solid #ccc; padding: 25px; border-radius: 8px; background-color: #f9f9f9; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
h2 { color: navy; }
a { color: red; text-decoration: none; font-weight: bold; }
</style>
</head>
<body>
<div class="card">
    <h2>Trang quản lý sinh viên (QLSV)</h2>
    <p><strong>Thông tin đăng nhập:</strong> <?php echo htmlspecialchars($_SESSION['info'] ?? ''); ?></p>
    <p><strong>Mã sinh viên:</strong> <?php echo htmlspecialchars($_SESSION['code'] ?? ''); ?></p>
    <hr>
    <p><a href="logout.php">Đăng xuất</a></p>
</div>
</body>
</html>
