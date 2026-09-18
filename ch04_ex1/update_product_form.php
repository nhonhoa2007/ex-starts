<?php
require_once('database.php');

$product_id = filter_input(INPUT_POST, 'product_id', FILTER_VALIDATE_INT);
if ($product_id == null || $product_id == false) {
    $product_id = filter_input(INPUT_GET, 'product_id', FILTER_VALIDATE_INT);
}

if ($product_id == null || $product_id == false) {
    $error = "Invalid product ID.";
    include('error.php');
    exit();
}

$queryProduct = 'SELECT * FROM products
                 WHERE productID = :product_id';
$statement1 = $db->prepare($queryProduct);
$statement1->bindValue(':product_id', $product_id);
$statement1->execute();
$product = $statement1->fetch();
$statement1->closeCursor();

if (!$product) {
    $error = "Product not found.";
    include('error.php');
    exit();
}

$queryCategories = 'SELECT * FROM categories
                    ORDER BY categoryID';
$statement2 = $db->prepare($queryCategories);
$statement2->execute();
$categories = $statement2->fetchAll();
$statement2->closeCursor();
?>
<!DOCTYPE html>
<html>
<head>
    <title>My Guitar Shop</title>
    <link rel="stylesheet" type="text/css" href="main.css">
</head>
<body>
    <header><h1>Product Manager</h1></header>

    <main>
        <h1>Update Product</h1>
        <form action="update_product.php" method="post" id="add_product_form">
            <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['productID']); ?>">

            <label>Category:</label>
            <select name="category_id">
            <?php foreach ($categories as $category) : ?>
                <option value="<?php echo $category['categoryID']; ?>" <?php if ($category['categoryID'] == $product['categoryID']) echo 'selected'; ?>>
                    <?php echo htmlspecialchars($category['categoryName']); ?>
                </option>
            <?php endforeach; ?>
            </select><br>

            <label>Code:</label>
            <input type="text" name="code" value="<?php echo htmlspecialchars($product['productCode']); ?>"><br>

            <label>Name:</label>
            <input type="text" name="name" value="<?php echo htmlspecialchars($product['productName']); ?>"><br>

            <label>List Price:</label>
            <input type="text" name="price" value="<?php echo htmlspecialchars($product['listPrice']); ?>"><br>

            <label>&nbsp;</label>
            <input type="submit" value="Update Product"><br>
        </form>
        <p><a href="index.php?category_id=<?php echo $product['categoryID']; ?>">View Product List</a></p>
    </main>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> My Guitar Shop, Inc.</p>
    </footer>
</body>
</html>
