<?php
require __DIR__ . '/includes/db.php';
getDb();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guitar Shop</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="page-wrap">
        <header class="site-header">
            <h1>Guitar Shop</h1>
        </header>

        <main class="menu-panel">
            <p class="menu-title">Select an application</p>
            <div class="menu-list">
                <a href="product_manager.php" class="menu-button">Product Manager</a>
                <a href="product_catalog.php" class="menu-button">Product Catalog</a>
                <a href="categories.php" class="menu-button">Manage Categories</a>
            </div>
        </main>
    </div>
</body>
</html>
