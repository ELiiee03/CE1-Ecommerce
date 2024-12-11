<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
session_start();
$request_method = $_SERVER['REQUEST_METHOD'];
$request_uri = $_SERVER['REQUEST_URI'];

// Base path for the API routes
$base_path = '/CE1-Ecommerce/api.php';

// Extract the path from the request URI
$parsed_url = parse_url($request_uri);
$path = $parsed_url['path'];

// Register Route   
if ($path === $base_path . '/register' && $request_method === 'POST') {
    require __DIR__ . '/controllers/user/registerUser.php';
    exit();
}

// Login Route
if ($path === $base_path . '/login' && $request_method === 'POST') {
    require __DIR__ . '/controllers/user/loginUser.php';
    exit();
}

// Logout Route
if ($path === $base_path . '/logout' && $request_method === 'POST') {
    require __DIR__ . '/controllers/logoutUser.php';
    exit();
}

// Password Reset Request Route
if ($path === $base_path . '/password/reset/request' && $request_method === 'POST') {
    require __DIR__ . '/controllers/passRequest.php';
    exit();
}
// Password Reset Route
if ($path === $base_path . '/password/reset' && $request_method === 'POST') {
    require __DIR__ . '/controllers/passReset.php';
    exit();
}

// Change Password Route
if ($path === $base_path . '/password/change' && $request_method === 'POST') {
    require __DIR__ . '/controllers/changePassword.php';
    exit();
}

// Profile Update Route
if ($path === $base_path . '/profile/update' && $request_method === 'POST') {
    require __DIR__ . '/controllers/profileUpdate.php';
    exit();
}

// Get User Profile Route
if ($path === $base_path . '/user/profile' && $request_method === 'GET') {
    require __DIR__ . '/controllers/getUserProfile.php';
    exit();
}

// Assign Role to User Route
if ($path === $base_path . '/role/assign' && $request_method === 'POST') {
    require __DIR__ . '/controllers/assignRole.php';
    exit();
}

// Revoke Role from User Route
if ($path === $base_path . '/role/revoke' && $request_method === 'POST') {
    require __DIR__ . '/controllers/revokeUser.php';
    exit();
}

// Profile Photo Upload Route
if ($path === $base_path . '/profile/photo/upload' && $request_method === 'POST') {
    require __DIR__ . '/controllers/uploadProfilePicture.php';
    exit();
}

// Add Address
if ($path === $base_path . '/address' && $request_method === 'POST') {
    require __DIR__ . '/controllers/addAddress.php';
    exit();
}

// Update Address
if ($path === $base_path . '/update/address' && $request_method === 'POST') {
    require __DIR__ . '/controllers/updateAddress.php';
    exit();
}

// Delete Address
if ($path === $base_path . '/delete/address' && $request_method === 'DELETE') {
    require __DIR__ . '/controllers/deleteAddress.php';
    exit();
}

// List Specific User Roles
if (preg_match("#^" . $base_path . "/roles/([a-zA-Z0-9]+)$#", $path, $matches) && $request_method === 'GET') {
    $user_id = $matches[1]; // Extract the user ID from the URL
    require __DIR__ . '/controllers/getUserRoles.php';
    exit();
}

// List All User
if ($path === $base_path . "/all/users" && $request_method === 'GET') {
    require __DIR__ . '/controllers/listAllUsers.php';
    exit();
}

// -----------------Products Routes ---------------------//

// Users Product
if ($path === $base_path . '/profile/products' && $request_method === 'GET') {
    require __DIR__ . '/controllers/product/getUserProducts.php';
    exit();
}

// Add Product
if ($path === $base_path . '/product/add' && $request_method === 'POST') {
    require __DIR__ . '/controllers/product/addProduct.php';
    exit();
}

// Delete Product
if ($path === $base_path . '/product/delete' && $request_method === 'DELETE') {
    require __DIR__ . '/controllers/product/deleteProduct.php';
    exit();
}

// Update Product
if ($path === $base_path . '/product/update' && $request_method === 'PUT') {
    require __DIR__ . '/controllers/product/updateProduct.php';
    exit();
}

// Get Product Details
if (preg_match("#^" . $base_path . "/product/([0-9]+)$#", $path, $matches) && $request_method === 'GET') {
    $product_id = (int) $matches[1];
    require __DIR__ . '/controllers/product/getProductDetails.php';
    exit();
}

// List All Products
if ($path === $base_path . '/product/search' && $request_method === 'GET') {
    require __DIR__ . '/controllers/product/searchController.php';
    exit();
}


// If no route matches, return 404S
http_response_code(404);
echo json_encode(['message' => 'Endpoint not found']);