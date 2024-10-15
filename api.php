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

// Logout Route
if ($request_uri === $base_path . '/logout' && $request_method === 'POST') {
    require 'logoutUser.php';
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

// Change Password Route
if ($request_uri === $base_path . '/password/change' && $request_method === 'POST') {
    require 'changePassword.php';
    exit();
}

// Profile Update
if ($request_uri === $base_path . '/profile/update' && $request_method === 'POST') {
    require 'profileUpdate.php';
    exit();
}

// Get User Profile
if ($request_uri === $base_path . '/user/profile' && $request_method === 'GET') {
    require 'getUserProfile.php';
    exit();
}

// Assign Role to User
if ($request_uri === $base_path . '/role/assign' && $request_method === 'POST') {
    require 'userRole.php';
    exit();
}

// Revoke role from user
if ($request_uri === $base_path . '/role/revoke' && $request_method === 'POST') {
    require 'revokeUser.php';
    exit();
}

// If no route matches, return 404S
http_response_code(404);
echo json_encode(['message' => 'Endpoint not found']);