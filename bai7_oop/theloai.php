<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/class/class.db.theloai.php';

$db_theloai = new DB_TheLoai();
$theloai_arr = $db_theloai->get_theloai();

$theloai_html = "";
if (count($theloai_arr) == 0) {
    $theloai_html = '<div class="alert alert-info">Không có thể loại. <a href="theloai_them.php" class="btn btn-sm btn-primary">THÊM THỂ LOẠI</a></div>';
} else {
    $theloai_html = '<h1 class="my-4 text-center">Quản trị thể loại</h1>';
    $theloai_html .= '<div class="table-responsive">';
    $theloai_html .= '<table class="table table-bordered table-striped" id="bang_quantri">';
    $theloai_html .= '<thead class="thead-dark"><tr><th>Tên thể loại</th><th>Thứ tự</th><th>Ẩn hiện</th><th colspan="2" class="text-center"><a href="theloai_them.php" class="btn btn-sm btn-success">Thêm Thể Loại</a></th></tr></thead>';
    $theloai_html .= '<tbody>';
    foreach ($theloai_arr as $item) {
        $theloai_html .= '<tr>';
        $theloai_html .= '<td>' . htmlspecialchars($item['TenTL']) . '</td>';
        $theloai_html .= '<td>' . htmlspecialchars((string)$item['ThuTu']) . '</td>';
        $theloai_html .= '<td>' . ($item['AnHien'] == 1 ? 'Hiện' : 'Ẩn') . '</td>';
        $theloai_html .= '<td class="text-center"><a href="sua_theloai.php?id=' . $item['idTL'] . '" class="btn btn-sm btn-warning">Sửa</a></td>';
        $theloai_html .= '<td class="text-center"><a href="delete.php?id=' . $item['idTL'] . '" onclick="return confirm(\'Bạn có thực sự muốn xóa?\');" class="btn btn-sm btn-danger">Xóa</a></td>';
        $theloai_html .= '</tr>';
    }
    $theloai_html .= '</tbody>';
    $theloai_html .= '</table>';
    $theloai_html .= '</div>';
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>THỂ LOẠI | QUẢN TRỊ</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <?php echo $theloai_html; ?>
    </div>
</body>
</html>

