<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/database/database.php';
require_once __DIR__ . '/../src/password/change_password.php';


use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Dotenv\Dotenv;

header("Content-Type: application/json");

$dotenv = Dotenv::createImmutable(__DIR__ . '/../'); 
$dotenv->load();

$jwt_secret_key = $_ENV['JWT_SECRET'];

try {
    // Establish a connection to the database
    $database = new Database();
    $db = $database->connect();
} catch (Exception $e) {
    http_response_code(500);  // Internal Server Error
    echo json_encode(['message' => 'Database connection failed: ' . $e->getMessage()]);
    exit();
}

// Get the Authorization header from the request
$headers = apache_request_headers();
if (!isset($headers['Authorization'])) {
    http_response_code(401);
    echo json_encode(['message' => 'Authorization header missing']);
    exit();
}

$authHeader = $headers['Authorization'];
$token = str_replace('Bearer ', '', $authHeader);

try {
    // Validate and decode JWT
    $decoded = JWT::decode($token, new Key($jwt_secret_key, 'HS256'));
    $user_id = $decoded->data->id;

    // Retrieve the input data from the request
    $data = json_decode(file_get_contents("php://input"));

    if (!isset($data->current_password) || !isset($data->new_password)) {
        http_response_code(400);
        echo json_encode(['message' => 'Current password and new password are required']);
        exit();
    }

    $current_password = $data->current_password;
    $new_password = $data->new_password;

    // Initialize ChangePassword class
    $changePassword = new ChangePassword($db);

    // Check if current password matches
    if (!$changePassword->verifyCurrentPassword($user_id, $current_password)) {
        http_response_code(401);
        echo json_encode(['message' => 'Current password is incorrect']);
        exit();
    }

    // Validate new password (example: min 8 characters)
    if (strlen($new_password) < 8) {
        http_response_code(400);
        echo json_encode(['message' => 'New password must be at least 8 characters long']);
        exit();
    }

    // Update the password in the database
    if ($changePassword->updatePassword($user_id, $new_password)) {
        echo json_encode(['message' => 'Password updated successfully']);
    } else {
        http_response_code(500);
        echo json_encode(['message' => 'Failed to update password']);
    }

} catch (Exception $e) {
    http_response_code(401);
    echo json_encode(['message' => 'Access denied', 'error' => $e->getMessage()]);
}
?>