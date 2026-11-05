<?php
session_start();
include("connect.php");
$us = $_GET["user"] ?? '';
$pw = $_GET["pass"] ?? '';

$us = mysqli_real_escape_string($con, $us);
$pw = mysqli_real_escape_string($con, $pw);

$sql = "select * from sinhvien where masv='{$us}' and matkhau='{$pw}'";
mysqli_query($con, "SET NAMES 'utf8'");
$rs = mysqli_query($con, $sql);
if ($rs && mysqli_num_rows($rs) > 0) {
    $row = mysqli_fetch_array($rs);
    $_SESSION["code"] = $row['masv'];
    $_SESSION["info"] = $us . " - " . $row["hoten"] . " - " . $row["lop"];
    echo $us . " - " . $row['hoten'] . "&nbsp;|&nbsp;<a href='QLSV.php'>Next</a>";
} else {
    echo "Bạn nhập thông tin sai!";
}

if ($rs) {
    mysqli_free_result($rs);
}
mysqli_close($con);
?>
