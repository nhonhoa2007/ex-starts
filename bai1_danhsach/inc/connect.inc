<?php
$server = $_SERVER['SERVER_NAME'] ?? 'localhost';
$user = "root";
$pass = "";
$db = "udn";
$con = @mysqli_connect($server, $user, $pass, $db);
if (!$con) {
    $con = mysqli_connect('localhost', get_current_user(), '', $db);
}
mysqli_set_charset($con, "utf8mb4");
?>
