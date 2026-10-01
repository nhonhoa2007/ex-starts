<?php
    $css_path = file_exists('main.css') ? 'main.css' : (file_exists('../main.css') ? '../main.css' : '/main.css');
?>
<!DOCTYPE html>
<html>
<!-- the head section -->
<head>
    <title>My Guitar Shop</title>
    <link rel="stylesheet" type="text/css" href="<?php echo $css_path; ?>">
</head>

<!-- the body section -->
<body>
<header><h1>My Guitar Shop</h1></header>
