<?php

// Enable CORS for testing (optional)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

$request_method = $_SERVER['REQUEST_METHOD'];
$request_uri = $_SERVER['REQUEST_URI'];

// Define base path for the API routes
$base_path = '/user-auth/CE1-Ecommerce/api.php';

// Register Route
if ($request_uri === $base_path . '/register' && $request_method === 'POST') {
    require 'registerUser.php';
    exit();
}
if ($request_uri === $base_path . '/login' && $request_method === 'POST') {
    require 'loginUser.php';
    exit();
}

// If no route matches, return 404
http_response_code(404);
echo json_encode(['message' => 'Endpoint not found']);