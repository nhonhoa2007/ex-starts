<?php include '../view/header.php'; ?>
<main>
    <aside>
        <h1>Categories</h1>
        <?php include '../view/category_nav.php'; ?>
    </aside>
    <section>
        <h1><?php echo htmlspecialchars($name); ?></h1>
        <div id="left_column">
            <p>
                <img src="<?php echo htmlspecialchars($image_filename); ?>"
                    alt="<?php echo htmlspecialchars($image_alt); ?>" />
            </p>
        </div>

        <div id="right_column">
            <p><b>List Price:</b> $<?php echo number_format($list_price, 2); ?></p>
            <p><b>Discount:</b> <?php echo $discount_percent; ?>%</p>
            <p><b>Your Price:</b> $<?php echo $unit_price_f; ?>
                 (You save $<?php echo $discount_amount_f; ?>)</p>
            <form action="<?php echo '../cart' ?>" method="post">
                <input type="hidden" name="action" value="add">
                <input type="hidden" name="product_id"
                       value="<?php echo $product_id; ?>">
                <b>Quantity:</b>
                <input id="quantity" type="text" name="quantity"
                       value="1" size="2">
                <br><br>
                <input type="submit" value="Add to Cart">
            </form>
            <br>
            <p><a href="?category_id=<?php echo $product['categoryID']; ?>">View products in this category</a></p>
            <p><a href="index.php">Product Catalog</a></p>
            <p class="last_paragraph"><a href="../index.php">Home / Menu</a></p>
        </div>
    </section>
</main>
<?php include '../view/footer.php'; ?>
