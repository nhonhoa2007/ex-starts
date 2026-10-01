<?php
include_once('../connect.php');

if (isset($_GET['idTL'])) {
    $id = (int)$_GET['idTL'];

    // Retrieve current icon before deleting
    $sl_icon = "SELECT icon FROM theloai WHERE idTL = $id";
    $result_icon = mysqli_query($connect, $sl_icon);
    if ($result_icon && $row = mysqli_fetch_assoc($result_icon)) {
        $icon = $row['icon'];
        if (!empty($icon)) {
            $image_path = "../image/" . $icon;
            if (is_file($image_path) && file_exists($image_path)) {
                unlink($image_path);
            }
        }
    }

    $sl = "DELETE FROM theloai WHERE idTL = $id";
    if (mysqli_query($connect, $sl)) {
        echo "<script>alert('Xoa thanh cong'); location.href='theloai.php';</script>";
    } else {
        echo "Xoa that bai: " . mysqli_error($connect);
    }
} else {
    header("Location: theloai.php");
    exit();
}
?>
