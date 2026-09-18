<!DOCTYPE html>
<html>
<head>
    <title>My Guitar Shop</title>
    <link rel="stylesheet" type="text/css" href="main.css" />
</head>
<body>
    <header><h1>My Guitar Shop</h1></header>

    <main>
        <h2 class="top">Error</h2>
        <p><?php echo htmlspecialchars($error); ?></p>
    </main>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> My Guitar Shop, Inc.</p>
    </footer>
</body>
</html>
