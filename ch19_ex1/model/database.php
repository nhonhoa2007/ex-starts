<?php
$dsn = 'mysql:host=localhost;dbname=my_guitar_shop2';
$username = 'mgs_user';
$password = 'pa55word';
$options = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);

try {
    $db = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
    try {
        $db = new PDO($dsn, 'root', '', $options);
    } catch (PDOException $e2) {
        try {
            $db = new PDO($dsn, 'nhonhoa', '', $options);
        } catch (PDOException $e3) {
            $error_message = $e->getMessage();
            if (file_exists('errors/db_error_connect.php')) {
                include 'errors/db_error_connect.php';
            } else if (file_exists('../errors/db_error_connect.php')) {
                include '../errors/db_error_connect.php';
            } else {
                echo "Database connection error: " . htmlspecialchars($error_message);
            }
            exit;
        }
    }
}

function display_db_error($error_message) {
    global $app_path;
    if (file_exists('errors/db_error.php')) {
        include 'errors/db_error.php';
    } else if (file_exists('../errors/db_error.php')) {
        include '../errors/db_error.php';
    } else {
        echo "Database error: " . htmlspecialchars($error_message);
    }
    exit;
}
?>

