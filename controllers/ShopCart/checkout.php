<?php
require_once '../src/class/ShoppingCart.php';
require_once '../src/class/Order.php'; // Ensure this path is correct and the file exists

// class Order {
//     private $db;

//     public function __construct($db) {
//         $this->db = $db;
//     }

//     public function checkout($cart_id, $user_id, $payment_method_id, $address_id) {
//         // Implement the checkout logic here
//         return true; // Return true for successful checkout, false otherwise
//     }
// }
$db = new Database(); // Assuming you have a Database class for DB connection
$cart = new ShoppingCart($db);
// $order = new Order($db);
$cart->cart_id = $_SESSION['cart_id']; // Assuming you store cart_id in session
$user_id = $_SESSION['user_id']; // Assuming you store user_id in session

// Get the input data
$data = json_decode(file_get_contents("php://input"), true);
$payment_method_id = $data['payment_method_id'] ?? null;
$address_id = $data['address_id'] ?? null;

// Validate input
if (is_null($payment_method_id) || is_null($address_id)) {
    http_response_code(400);
    echo json_encode(['message' => 'Invalid payment method or address.']);
    exit();
}

// Proceed to checkout
if ($order->checkout($cart->cart_id, $user_id, $payment_method_id, $address_id)) {
    echo json_encode(['message' => 'Checkout successful']);
} else {
    http_response_code(500);
    echo json_encode(['message' => 'Checkout failed']);
}
?>