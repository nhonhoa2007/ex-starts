<?php include '../view/header.php'; ?>
<main>
    <h1>Error</h1>
    <p class="first_paragraph"><?php echo htmlspecialchars($error); ?></p>
    <p><a href="javascript:history.back()">Go Back</a></p>
    <p class="last_paragraph"><a href="../index.php">Home / Menu</a></p>
</main>
<?php include '../view/footer.php'; ?>
