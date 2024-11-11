<?php
session_start();
require_once __DIR__ . '/../src/class/ShoppingCart.php';
require_once __DIR__ . '/../src/database/database.php';

// Ensure session is started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Set a default cart_id if not already set
if (!isset($_SESSION['cart_id'])) {
    $_SESSION['cart_id'] = uniqid(); // or use a UUID generator
}

$db = new Database(); // Assuming you have a Database class for DB connection
$pdo = $db->connect(); // Get the PDO instance
$cart = new ShoppingCart($pdo); // Pass the PDO instance to the ShoppingCart class
$cart->cart_id = $_SESSION['cart_id']; // Assuming you store cart_id in session

// Get the input data
$data = json_decode(file_get_contents("php://input"), true);
$product_id = $data['product_id'] ?? null;
$quantity = $data['quantity'] ?? null;

// Validate input
if (is_null($product_id) || is_null($quantity) || $quantity <= 0) {
    http_response_code(400);
    echo json_encode(['message' => 'Invalid product ID or quantity.']);
    exit();
}

// Add item to cart
if ($cart->addItem($product_id, $quantity)) {
    echo json_encode(['message' => 'Item added to cart']);
} else {
    http_response_code(500);
    echo json_encode(['message' => 'Failed to add item']);
}
?>