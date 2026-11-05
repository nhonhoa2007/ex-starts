<?php
$op = $_GET["chon"] ?? '';
if ($op != "") {
    include("database.class.php");
    $dao = new Dao("root", "", "udn");

    $allowed = ['giaovien', 'sinhvien', 'hocphan'];
    if (in_array($op, $allowed)) {
        $sql = "select * from {$op}";
        $header = "DANH SÁCH {$op}";
        $dao->table($sql, $header);
    }
}
?>
