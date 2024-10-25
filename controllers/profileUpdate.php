<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/database/database.php';
require_once __DIR__ . '/../src/class/profile_update.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Dotenv\Dotenv;
use Rakit\Validation\Validator; 

header('Content-Type: application/json');

$dotenv = Dotenv::createImmutable(__DIR__ . '/../'); 
$dotenv->load();

try {
    // Establish a connection to the database
    $database = new Database();
    $db = $database->connect();
} catch (Exception $e) {
    http_response_code(500);  // Internal Server Error
    echo json_encode(['message' => 'Database connection failed: ' . $e->getMessage()]);
    exit;
}

// Get the JWT token from the Authorization header
$headers = apache_request_headers();
        if (!isset($headers['Authorization'])) {
            http_response_code(401);  // Unauthorized
            echo json_encode(['message' => 'Authorization token not found']);
            exit;
        }

$jwt = str_replace('Bearer ', '', $headers['Authorization']);

try {
    // Decode the JWT
    $decoded = JWT::decode($jwt, new Key($_ENV['JWT_SECRET'], 'HS256'));
    $userId = $decoded->data->id;
} catch (Exception $e) {
    http_response_code(401);  // Unauthorized
    echo json_encode(['message' => 'Invalid token: ' . $e->getMessage()]);
    exit;
}

// Get the input data
$data = json_decode(file_get_contents("php://input"));

        if (!$data) {
            http_response_code(400);  // Bad Request
            echo json_encode(['message' => 'No input data provided']);
            exit;
        }

$validator = new Validator();

$validation = $validator->make((array)$data, [
    'first_name'        => 'required|alpha|max:50',
    'last_name'         => 'required|alpha|max:50',
    'date_of_birth'     => 'required|regex:/^\d{4}-\d{2}-\d{2}$/'
]);

$validation->validate();

        if ($validation->fails()) {
            // Return validation errors
            http_response_code(400);  // Bad Request
            echo json_encode(['message' => 'Validation errors', 'errors' => $validation->errors()->firstOfAll()]);
            exit;
        }

$profileUpdate = new ProfileUpdate($db, $userId, $data, $jwt);
$result = $profileUpdate->updateProfile();

        if ($result['success']) {
            http_response_code(200);  // OK
            echo json_encode(['message' => $result['message']]);
        } else {
            http_response_code(400);  // Bad Request
            echo json_encode(['message' => $result['message']]);
        }

// Disable error display for production
ini_set('display_errors', '0');
error_reporting(0);

?>
