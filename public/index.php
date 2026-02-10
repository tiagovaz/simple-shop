<?php
// public/index.php
// Front controller

// Start output buffering - allows headers to be sent even after content is generated
ob_start();

session_start();

require __DIR__ . '/../app/config.php';
require __DIR__ . '/../app/db.php';
require __DIR__ . '/../app/products.php';
require __DIR__ . '/../app/cart.php';
require __DIR__ . '/../app/auth.php';

$action = $_GET['action'] ?? 'home';

require __DIR__ . '/../app/views/header.php';

switch ($action) {
  case 'home':
    show_products();
    break;

  case 'add_to_cart':
    add_to_cart();
    break;

  case 'remove_from_cart':
    remove_from_cart();
    break;

  case 'cart':
    show_cart();
    break;

  case 'login':
    login();
    break;

  case 'register':
    register();
    break;

  case 'logout':
    logout();
    break;

  default:
    echo '<p>Page not found</p>';
}

require __DIR__ . '/../app/views/footer.php';

// Send the buffered output
ob_end_flush();
