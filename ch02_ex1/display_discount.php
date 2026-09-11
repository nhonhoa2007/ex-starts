<?php
$product_description = filter_input(INPUT_POST, 'product_description');
$list_price = filter_input(INPUT_POST, 'list_price', FILTER_VALIDATE_FLOAT);
$discount_percent = filter_input(INPUT_POST, 'discount_percent', FILTER_VALIDATE_FLOAT);
$discount_amount = $list_price * ($discount_percent / 100);
$discount_price = $list_price - $discount_amount;
?>
<!DOCTYPE html>
<html>

<head>
    <title>Product Discount Calculator</title>
    <link rel="stylesheet" type="text/css" href="main.css">
</head>

<body>
    <main>
        <h1>This page is under construction</h1>

        <label>Product Description:</label>
        <span><?php echo htmlspecialchars($product_description) ?></span><br>

        <label>List Price:</label>
        <span><?php echo htmlspecialchars(number_format($list_price, 2)); ?></span><br>

        <label>Standard Discount:</label>
        <span><?php echo htmlspecialchars(number_format($discount_percent, 2)); ?></span><br>

        <label>Discount Amount:</label>
        <span><?php echo htmlspecialchars(number_format($discount_amount, 2)); ?></span><br>

        <label>Discount Price:</label>
        <span><?php echo htmlspecialchars(number_format($discount_price, 2)); ?></span><br>
    </main>
</body>

</html>