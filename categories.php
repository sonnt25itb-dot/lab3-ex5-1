<?php
require __DIR__ . '/includes/db.php';

$errors = [];
$success = '';
$newCategory = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'add') {
        $newCategory = trim($_POST['category_name'] ?? '');
        if ($newCategory === '') {
            $errors[] = 'Category name is required.';
        } else {
            try {
                $pdo = getDb();
                $stmt = $pdo->prepare('INSERT INTO categories (name) VALUES (?)');
                $stmt->execute([$newCategory]);
                $success = 'Category added successfully.';
                $newCategory = '';
            } catch (PDOException $e) {
                $errors[] = 'That category already exists.';
            }
        }
    }

    if (isset($_POST['action']) && $_POST['action'] === 'delete') {
        $name = trim($_POST['category_name'] ?? '');
        if ($name !== '') {
            try {
                $pdo = getDb();
                $pdo->prepare('DELETE FROM categories WHERE name = ?')->execute([$name]);
                $pdo->prepare('DELETE FROM products WHERE category = ?')->execute([$name]);
                $success = 'Category deleted successfully.';
            } catch (PDOException $e) {
                $errors[] = 'Unable to delete category.';
            }
        }
    }
}

$categories = getCategories();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Categories</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="page-wrap narrow">
        <header class="site-header">
            <h1>Manage Categories</h1>
        </header>

        <main class="form-panel">
            <div class="action-row">
                <a href="index.php" class="link-button">Back to Menu</a>
                <a href="product_manager.php" class="link-button">Add Product</a>
            </div>

            <?php if ($success !== ''): ?>
                <div class="message success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>

            <?php if (!empty($errors)): ?>
                <div class="message error">
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="post" class="product-form">
                <input type="hidden" name="action" value="add">
                <div class="form-row">
                    <label for="category_name">New Category</label>
                    <input id="category_name" name="category_name" type="text" value="<?php echo htmlspecialchars($newCategory); ?>" required>
                </div>
                <div class="form-actions">
                    <button type="submit">Add Category</button>
                </div>
            </form>

            <hr>

            <h2>Current Categories</h2>
            <?php if (empty($categories)): ?>
                <p class="empty-state">No categories found.</p>
            <?php else: ?>
                <ul class="category-list">
                    <?php foreach ($categories as $category): ?>
                        <li>
                            <span><?php echo htmlspecialchars($category); ?></span>
                            <form method="post" class="inline-delete">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="category_name" value="<?php echo htmlspecialchars($category); ?>">
                                <button type="submit" class="danger-btn">Delete</button>
                            </form>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </main>
    </div>
</body>
</html>
