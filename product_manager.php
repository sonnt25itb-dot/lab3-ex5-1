<?php
require __DIR__ . '/includes/db.php';

$categories = getCategories();
$errors = [];
$success = '';
$category = $categories[0] ?? 'Guitars';
$code = '';
$name = '';
$price = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category = trim($_POST['category'] ?? '');
    $code = trim($_POST['code'] ?? '');
    $name = trim($_POST['name'] ?? '');
    $price = trim($_POST['price'] ?? '');

    if ($category === '') {
        $errors[] = 'Category is required.';
    }
    if ($code === '') {
        $errors[] = 'Code is required.';
    }
    if ($name === '') {
        $errors[] = 'Name is required.';
    }
    if ($price === '' || !is_numeric($price)) {
        $errors[] = 'Price must be a valid number.';
    }

    if (empty($errors)) {
        try {
            $pdo = getDb();
            $stmt = $pdo->prepare('INSERT INTO products (category, code, name, price) VALUES (?, ?, ?, ?)');
            $stmt->execute([$category, $code, $name, (float) $price]);
            $success = 'Product added successfully.';

            $category = $categories[0] ?? 'Guitars';
            $code = '';
            $name = '';
            $price = '';
        } catch (PDOException $e) {
            $errors[] = 'The code already exists. Please use a different code.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Manager</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="page-wrap narrow">
        <header class="site-header">
            <h1>Product Manager</h1>
        </header>

        <main class="form-panel">
            <div class="action-row">
                <a href="index.php" class="link-button">Back to Menu</a>
                <a href="categories.php" class="link-button">Manage Categories</a>
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
                <div class="form-row">
                    <label for="category">Category</label>
                    <select id="category" name="category" required>
                        <?php foreach ($categories as $item): ?>
                            <option value="<?php echo htmlspecialchars($item); ?>" <?php echo ($item === $category) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($item); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-row">
                    <label for="code">Code</label>
                    <input id="code" name="code" type="text" value="<?php echo htmlspecialchars($code); ?>" required>
                </div>

                <div class="form-row">
                    <label for="name">Name</label>
                    <input id="name" name="name" type="text" value="<?php echo htmlspecialchars($name); ?>" required>
                </div>

                <div class="form-row">
                    <label for="price">Price</label>
                    <input id="price" name="price" type="number" step="0.01" min="0" value="<?php echo htmlspecialchars($price); ?>" required>
                </div>

                <div class="form-actions">
                    <button type="submit">Add Product</button>
                </div>
            </form>
        </main>
    </div>
</body>
</html>
