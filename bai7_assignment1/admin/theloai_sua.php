<?php
include('../connect.php');

if (isset($_POST['Sua'])) {
    $idTL = (int)$_POST['idTL'];
    $tenTL = isset($_POST['TenTL']) ? mysqli_real_escape_string($connect, trim($_POST['TenTL'])) : '';
    $thuTu = isset($_POST['ThuTu']) ? (int)$_POST['ThuTu'] : 0;
    $anHien = isset($_POST['AnHien']) ? (int)$_POST['AnHien'] : 1;
    $old_icon = $_POST['ten_anh'] ?? '';
    $icon = $old_icon;

    $image_file = $_FILES['image'] ?? $_FILES['anh'] ?? null;
    if ($image_file && !empty($image_file['name']) && $image_file['error'] == 0) {
        $new_icon = basename($image_file['name']);
        $upload_dir = '../image/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        $target_file = $upload_dir . $new_icon;

        if (move_uploaded_file($image_file['tmp_name'], $target_file)) {
            if (!empty($old_icon) && $old_icon !== $new_icon) {
                $old_file = $upload_dir . $old_icon;
                if (is_file($old_file) && file_exists($old_file)) {
                    unlink($old_file);
                }
            }
            $icon = $new_icon;
        }
    }

    $icon_escaped = mysqli_real_escape_string($connect, $icon);
    $sl = "UPDATE theloai SET TenTL = '$tenTL', ThuTu = $thuTu, AnHien = $anHien, icon = '$icon_escaped' WHERE idTL = $idTL";

    if (mysqli_query($connect, $sl)) {
        echo "<script>alert('Sua thanh cong'); location.href='theloai.php';</script>";
        exit();
    } else {
        echo mysqli_error($connect);
    }
}

$idTL = isset($_GET['idTL']) ? (int)$_GET['idTL'] : (isset($_POST['idTL']) ? (int)$_POST['idTL'] : 0);
$sl = "SELECT * FROM theloai WHERE idTL = $idTL";
$results = mysqli_query($connect, $sl);
$row = mysqli_fetch_assoc($results);

if (!$row) {
    echo "Khong tim thay the loai!";
    exit();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sua The Loai</title>
</head>
<body>
    <form action="" method="post" enctype="multipart/form-data">
        <input type="hidden" name="idTL" value="<?php echo $row['idTL']; ?>" />
        <input type="hidden" name="ten_anh" value="<?php echo htmlspecialchars($row['icon']); ?>" />
        <table align="center" border="1" width="500" cellspacing="0" cellpadding="5">
            <tr align="center">
                <th colspan="2">SUA THE LOAI</th>
            </tr>
            <tr>
                <td width="30%">Ten The Loai</td>
                <td><input type="text" name="TenTL" id="TenTL" value="<?php echo htmlspecialchars($row['TenTL']); ?>" required /></td>
            </tr>
            <tr>
                <td>Thu Tu</td>
                <td><input type="number" name="ThuTu" id="ThuTu" value="<?php echo htmlspecialchars($row['ThuTu']); ?>" /></td>
            </tr>
            <tr>
                <td>An Hien</td>
                <td>
                    <select name="AnHien" id="AnHien">
                        <option value="0" <?php if ($row['AnHien'] == 0) echo 'selected'; ?>>An</option>
                        <option value="1" <?php if ($row['AnHien'] == 1) echo 'selected'; ?>>Hien</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Bieu tuong</td>
                <td>
                    <img src="../image/<?php echo htmlspecialchars($row['icon']); ?>" width="40" height="40" /><br />
                    <input type="file" name="image" id="anh" />
                </td>
            </tr>
            <tr align="center">
                <td colspan="2">
                    <input type="submit" name="Sua" value="Sua" />
                    <input type="reset" name="Huy" value="Huy" />
                </td>
            </tr>
            <tr align="center">
                <td colspan="2">
                    <a href="theloai.php">&laquo; Quay lai danh sach the loai</a>
                </td>
            </tr>
        </table>
    </form>
</body>
</html>
<?php
mysqli_close($connect);
?>
