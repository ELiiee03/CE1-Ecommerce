<?php
require_once 'src/Database/database.php';
require_once 'loginUser.php';
require_once 'registerUser.php'; 
require_once __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Ensure that the request method is valid
$requestMethod = $_SERVER['REQUEST_METHOD'];

// Capture the full request URI
$requestUri = $_SERVER['REQUEST_URI'];

$requestUri = explode('?', $requestUri)[0];
$scriptName = basename($_SERVER['SCRIPT_NAME']); // 'api.php'
$requestUri = str_replace("/$scriptName", '', $requestUri);
$requestUriParts = explode('/', trim($requestUri, '/'));

// Get the route - this assumes the route is the first part of the URI
$route = isset($requestUriParts[0]) ? $requestUriParts[0] : '';

// Define the routes
if ($route === 'login' && $requestMethod === 'POST') {
    // Process login
    require 'loginUser.php';
} 
elseif ($route === 'register' && $requestMethod === 'POST') {
    // Process registration
    require 'registerUser.php'; // Add the registration route
} 
else {
    // If no route matches, return a 404 response
    http_response_code(404); // Not Found
    echo json_encode(['message' => 'Not Found']);
}