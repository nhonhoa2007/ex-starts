<?php
$css_path = file_exists('main.css') ? 'main.css' : (file_exists('../main.css') ? '../main.css' : ($app_path . 'main.css'));
?>
<!DOCTYPE html>
<html>
    <head>
        <title>My Guitar Shop</title>
        <link rel="stylesheet" type="text/css"
              href="<?php echo $css_path; ?>" />
    </head>
    <body>

