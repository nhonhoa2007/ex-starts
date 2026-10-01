<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Them The Loai</title>
</head>
<body>
    <form action="theloai_them_xl.php" method="post" enctype="multipart/form-data">
        <table align="center" border="1" width="500" cellspacing="0" cellpadding="5">
            <tr align="center">
                <th colspan="2">THEM THE LOAI</th>
            </tr>
            <tr>
                <td width="30%">Ten The Loai</td>
                <td><input type="text" name="TenTL" id="TenTL" required /></td>
            </tr>
            <tr>
                <td>Thu Tu</td>
                <td><input type="number" name="ThuTu" id="ThuTu" value="0" /></td>
            </tr>
            <tr>
                <td>An Hien</td>
                <td>
                    <select name="AnHien" id="AnHien">
                        <option value="0">An</option>
                        <option value="1" selected>Hien</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>icon</td>
                <td><input type="file" name="image" id="anh" /></td>
            </tr>
            <tr align="center">
                <td colspan="2">
                    <input type="submit" name="Them" value="Them" />
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
