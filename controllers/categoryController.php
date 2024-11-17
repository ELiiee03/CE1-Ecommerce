<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/database/database.php';
require_once __DIR__ . '/../src/class/product.php';

use Dotenv\Dotenv;

header("Content-Type: application/json");

// Load environment variables
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

try {
    $database = new Database();
    $db = $database->connect();
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['message' => 'Database connection failed: ' . $e->getMessage()]);
    exit();
}

// Instantiate the Product class
$product = new Product($db);

// Get the input data
$data = json_decode(file_get_contents("php://input"), true);
$keyword = $data['keyword'] ?? null;
$page = $data['page'] ?? 1; // Default to page 1

if ($keyword) {
    try {
        $results = $product->searchProducts($keyword, $page, $perPage);

        if (empty($results)) {
            http_response_code(404);
            echo json_encode(['message' => 'No products found.']);
        } else {
            http_response_code(200);
            echo json_encode([
                'current_page' => $page,
                'per_page' => $perPage,
                'results' => $results,
            ]);
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['message' => 'Error executing query: ' . $e->getMessage()]);
    }
} else {
    http_response_code(400);
    echo json_encode(['message' => 'Keyword is required.']);
}
