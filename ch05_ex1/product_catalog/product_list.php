<?php include '../view/header.php'; ?>
<main>
    <aside>
        <h1>Categories</h1>
        <?php include '../view/category_nav.php'; ?>
    </aside>
    <section>
        <h1><?php echo htmlspecialchars($category_name); ?></h1>
        <ul class="nav">
            <!-- display links for products in selected category -->
            <?php foreach ($products as $product) : ?>
            <li>
                <a href="?action=view_product&amp;product_id=<?php
                          echo $product['productID']; ?>">
                    <?php echo htmlspecialchars($product['productName']); ?>
                </a>
            </li>
            <?php endforeach; ?>
        </ul>
        <p class="last_paragraph"><a href="../index.php">Home / Menu</a></p>
    </section>
</main>
<?php include '../view/footer.php'; ?>
