<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

$request_method = $_SERVER['REQUEST_METHOD'];
$request_uri = $_SERVER['REQUEST_URI'];

// Base path for the API routes
$base_path = '/user-auth/CE1-Ecommerce/api.php';

// Register Route
if ($request_uri === $base_path . '/register' && $request_method === 'POST') {
    require 'registerUser.php';
    exit();
}
// Login Route
if ($request_uri === $base_path . '/login' && $request_method === 'POST') {
    require 'loginUser.php';
    exit();
}
// Password Reset Request
if ($request_uri === $base_path . '/password/reset/request' && $request_method === 'POST') {
    require 'passRequest.php';
    exit();
}
// Password Reset
if ($request_uri === $base_path . '/password/reset' && $request_method === 'POST') {
    require 'passReset.php';
    exit();
}

// If no route matches, return 404S
http_response_code(404);
echo json_encode(['message' => 'Endpoint not found']);