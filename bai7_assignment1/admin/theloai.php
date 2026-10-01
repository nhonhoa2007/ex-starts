<?php
include('../connect.php');
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quan Ly The Loai</title>
</head>
<body>
    <table align="center" border="1" width="600" cellspacing="0" cellpadding="5">
        <tr align="center">
            <th>Ten The Loai</th>
            <th>Thu Tu</th>
            <th>An Hien</th>
            <th>Bieu tuong</th>
            <th colspan="2"><a href="theloai_them.php">Them</a></th>
        </tr>
        <?php
        $sl = "select * from theloai";
        $results = mysqli_query($connect, $sl);
        while ($rows = mysqli_fetch_assoc($results)) {
        ?>
        <tr align="center">
            <td><?php echo $rows['TenTL']; ?></td>
            <td><?php echo $rows['ThuTu']; ?></td>
            <td>
                <?php
                if ($rows['AnHien'] == 1) {
                    echo "Hien";
                } else {
                    echo "An";
                }
                ?>
            </td>
            <td><img src="../image/<?php echo $rows['icon']; ?>" width="40" height="40" /></td>
            <td><a href="theloai_sua.php?idTL=<?php echo $rows['idTL']; ?>">Sua</a></td>
            <td><a href="theloai_xoa.php?idTL=<?php echo $rows['idTL']; ?>" onclick="return confirm('Ban co chac chan muon xoa the loai nay?');">xoa</a></td>
        </tr>
        <?php
        }
        ?>
    </table>
</body>
</html>
<?php
mysqli_close($connect);
?>
