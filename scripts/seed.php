<?php
// scripts/seed.php
// Creates the database/tables (if missing) and inserts demo products.

require __DIR__ . '/../app/config.php';
require __DIR__ . '/../app/db.php';

function println($msg) {
  echo $msg . PHP_EOL;
}

try {
  // Connect without DB selected
  $pdo = db(false);

  // Create database
  $pdo->exec('CREATE DATABASE IF NOT EXISTS `' . DB_NAME . '` CHARACTER SET ' . DB_CHARSET . ' COLLATE utf8mb4_unicode_ci');
  println('Database ensured: ' . DB_NAME);

  // Connect with DB selected
  $pdo = db(true);

  // Create tables
  $pdo->exec('CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL
  )');

  $pdo->exec('CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    price DECIMAL(10,2) NOT NULL
  )');

  println('Tables ensured: users, products');

  // Insert products if empty
  $count = (int)($pdo->query('SELECT COUNT(*) AS c FROM products')->fetch()['c'] ?? 0);
  if ($count > 0) {
    println('Products already exist (' . $count . '). Nothing to seed.');
    exit(0);
  }

  $products = [
    ['Fender Stratocaster (1998)', 850.00],
    ['Yamaha Acoustic Guitar', 245.00],
    ['Roland Digital Piano 88-key', 650.00],
    ['Pearl Export Drum Kit', 425.00],
    ['Ibanez Bass Guitar', 320.00],
    ['Korg Synthesizer', 380.00],
    ['Bach Trumpet (Silver)', 520.00],
    ['Yamaha Clarinet', 295.00],
    ['Gibson Les Paul (2005)', 1250.00],
    ['Taylor Acoustic 12-String', 580.00],
    ['Fender Precision Bass', 475.00],
    ['Casio Keyboard 61-Key', 180.00],
    ['Ludwig Snare Drum', 220.00],
    ['Selmer Alto Saxophone', 890.00],
    ['Yamaha Flute (Silver)', 340.00],
    ['Martin Acoustic Guitar', 720.00],
  ];

  $stmt = $pdo->prepare('INSERT INTO products (name, price) VALUES (?, ?)');
  foreach ($products as $p) {
    $stmt->execute($p);
  }

  println('Inserted demo instruments: ' . count($products));
  println('Done. Run: php -S localhost:8000 -t public');

} catch (Exception $e) {
  println('Seed failed: ' . $e->getMessage());
  exit(1);
}
