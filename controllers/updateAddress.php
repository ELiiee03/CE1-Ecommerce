<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/database/database.php';
require_once __DIR__ . '/../src/class/address.php'; 

use Dotenv\Dotenv;

header('Content-Type: application/json');

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

try {
    // Establish a connection to the database
    $database = new Database();
    $db = $database->connect();
    $address = new Address($db);  // Instantiate the Address class
} catch (Exception $e) {
    http_response_code(500);  // Internal Server Error
    echo json_encode(['message' => 'Database connection failed: ' . $e->getMessage()]);
    exit;
}

// Get the input data
$data = json_decode(file_get_contents("php://input"));

// Validate input data
if (
    empty($data->address_id) ||
    empty($data->user_id) ||  // Validate user_id
    empty($data->unit_number) ||
    empty($data->street_address) ||
    empty($data->city) ||
    empty($data->region) ||
    empty($data->postal_code)
) {
    http_response_code(400);  // Bad Request
    echo json_encode(['message' => 'Incomplete data']);
    exit;
}

// Verify user ID and role
$user = $address->verifyUserIdAndRole($data->user_id);
if (!$user) {
    http_response_code(404);  // Not Found
    echo json_encode(['message' => 'User ID not found']);
    exit;
} elseif ($user['role'] === NULL) {
    http_response_code(403);  // Forbidden
    echo json_encode(['message' => 'User does not have a valid role to update an address']);
    exit;
}

// Verify user ID and address ID association
if (!$address->verifyUserAddress($data->user_id, $data->address_id)) {
    http_response_code(403);  // Forbidden
    echo json_encode(['message' => 'User ID does not have permission to update this address']);
    exit;
}

// Update the address
if ($address->updateAddress($data)) {
    http_response_code(200);  // OK
    echo json_encode(['message' => 'Address updated successfully']);
} else {
    http_response_code(500);  // Internal Server Error
    echo json_encode(['message' => 'Failed to update address']);
}

// Disable error display for production
ini_set('display_errors', '0');
error_reporting(0);