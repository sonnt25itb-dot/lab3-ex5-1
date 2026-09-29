<?php

function getDb(): PDO
{
    $dbFile = __DIR__ . '/../data/guitar_shop.sqlite';
    $dir = dirname($dbFile);

    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }

    $pdo = new PDO('sqlite:' . $dbFile);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS categories (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL UNIQUE
        )"
    );

    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS products (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            category TEXT NOT NULL,
            code TEXT NOT NULL UNIQUE,
            name TEXT NOT NULL,
            price REAL NOT NULL DEFAULT 0
        )"
    );

    $pdo->exec(
        "INSERT OR IGNORE INTO categories (name) VALUES ('Guitars')"
    );

    return $pdo;
}

function getCategories(): array
{
    $pdo = getDb();
    $stmt = $pdo->query('SELECT name FROM categories ORDER BY name ASC');
    return $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
}
