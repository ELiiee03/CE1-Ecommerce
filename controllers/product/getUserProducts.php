<?php

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../src/database/database.php';
require_once __DIR__ . '/../../src/class/product.php';


use Dotenv\Dotenv;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

header("Content-Type: application/json");

$dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

$jwt_secret_key = $_ENV['JWT_SECRET'];

try {
    $database = new Database();
    $db = $database->connect();

    $product = new Product($db);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['message' => 'Database connection failed: ' . $e->getMessage()]);
    exit();
}

$headers = apache_request_headers();
if (!isset($headers['Authorization'])) {
    http_response_code(401);  // Unauthorized
    echo json_encode(['message' => 'Authorization header missing']);
    exit();
}

$authHeader = $headers['Authorization'];
$token = str_replace('Bearer ', '', $authHeader);


try {
    // Decode the token
    $decoded = JWT::decode($token, new Key($jwt_secret_key, 'HS256'));
    $userId = $decoded->data->id;

    // Check user role
    $stmt = $db->prepare("SELECT role FROM users WHERE user_id = :id");
    $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$result || strtolower($result['role']) !== 'vendor') {
        http_response_code(403); // Forbidden
        echo json_encode(['message' => 'You are not a vendor.']);
        exit();
    }
} catch (Exception $e) {
    http_response_code(401); // Unauthorized
    echo json_encode(['message' => 'Invalid token', 'error' => $e->getMessage()]);
    exit();
}


// Fetch user products
try {
    $userProducts = $product->userProducts($userId);

    if ($userProducts) {
        http_response_code(200);
        echo json_encode($userProducts);
    } else {
        http_response_code(404);
        echo json_encode(['message' => 'No products found for this user.']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['message' => 'Failed to fetch products.', 'error' => $e->getMessage()]);
}
