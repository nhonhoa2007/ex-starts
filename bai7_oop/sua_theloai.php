<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/class/class.db.theloai.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : -1;
if ($id <= 0) {
    header("Location: theloai.php");
    exit();
}

$db_theloai = new DB_TheLoai();

if (isset($_POST['submit'])) {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : $id;
    $ten_theloai = isset($_POST['ten_theloai']) ? trim($_POST['ten_theloai']) : "";
    $thu_tu = isset($_POST['thu_tu']) ? (int)$_POST['thu_tu'] : 0;
    $an_hien = isset($_POST['an_hien']) ? (int)$_POST['an_hien'] : 1;

    if ($ten_theloai === "") {
        echo '<script>alert("Tên thể loại không được để trống!"); history.back();</script>';
        exit();
    } else {
        $result = $db_theloai->sua_theloai($id, $ten_theloai, $thu_tu, $an_hien);
        if ($result) {
            echo '<script>alert("Cập nhật thành công!"); window.location="theloai.php";</script>';
            exit();
        } else {
            echo '<script>alert("Cập nhật không thành công!"); window.location="theloai.php";</script>';
            exit();
        }
    }
}

$theloai_arr = $db_theloai->get_theloai($id);
if (empty($theloai_arr)) {
    echo '<script>alert("Thể loại không tồn tại!"); window.location="theloai.php";</script>';
    exit();
}

$ten_theloai = $theloai_arr[0]['TenTL'];
$thu_tu = $theloai_arr[0]['ThuTu'];
$an_hien = $theloai_arr[0]['AnHien'];
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
        <h1 class="mb-4">Cập nhật thể loại</h1>
        <form name="sua_theloai" method="POST">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <table class="table table-bordered" style="max-width: 600px;">
                <tr>
                    <td style="width: 150px;">Tên thể loại:</td>
                    <td><input type="text" class="form-control" name="ten_theloai" value="<?php echo htmlspecialchars($ten_theloai); ?>" required></td>
                </tr>
                <tr>
                    <td>Thứ tự:</td>
                    <td><input type="number" class="form-control" name="thu_tu" value="<?php echo htmlspecialchars((string)$thu_tu); ?>"></td>
                </tr>
                <tr>
                    <td>Ẩn/Hiện:</td>
                    <td>
                        <select class="form-control" name="an_hien">
                            <option value="1" <?php echo ($an_hien == 1) ? 'selected' : ''; ?>>Hiện</option>
                            <option value="0" <?php echo ($an_hien == 0) ? 'selected' : ''; ?>>Ẩn</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <input type="submit" class="btn btn-warning" value="Cập nhật thể loại" name="submit">
                        <a href="theloai.php" class="btn btn-secondary ml-2">Quay lại</a>
                    </td>
                </tr>
            </table>
        </form>
    </div>
</body>
</html>

