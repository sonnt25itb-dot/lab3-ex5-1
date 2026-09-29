<?php
require __DIR__ . '/includes/db.php';

$pdo = getDb();
$products = $pdo->query('SELECT * FROM products ORDER BY id DESC')->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Catalog</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="page-wrap wide">
        <header class="site-header">
            <h1>Product Catalog</h1>
        </header>

        <main class="catalog-panel">
            <div class="action-row">
                <a href="index.php" class="link-button">Back to Menu</a>
                <a href="product_manager.php" class="link-button">Add New Product</a>
            </div>

            <?php if (empty($products)): ?>
                <p class="empty-state">No products in the catalog yet.</p>
            <?php else: ?>
                <table class="product-table">
                    <thead>
                        <tr>
                            <th>Category</th>
                            <th>Code</th>
                            <th>Name</th>
                            <th>Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $product): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($product['category']); ?></td>
                                <td><?php echo htmlspecialchars($product['code']); ?></td>
                                <td><?php echo htmlspecialchars($product['name']); ?></td>
                                <td>$<?php echo number_format((float) $product['price'], 2); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </main>
    </div>
</body>
</html>
