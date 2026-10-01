<?php
    $dsn = 'mysql:host=localhost;dbname=my_guitar_shop1';
    $username = 'nhonhoa';
    $password = '';

    try {
        $db = new PDO($dsn, $username, $password);
    } catch (PDOException $e) {
        $error_message = $e->getMessage();
        if (file_exists('../errors/database_error.php')) {
            include('../errors/database_error.php');
        } else if (file_exists('errors/database_error.php')) {
            include('errors/database_error.php');
        } else {
            echo "Database Error: " . $error_message;
        }
        exit();
    }
?>
