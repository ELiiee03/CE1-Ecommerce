<?php

require_once __DIR__ . '/../vendor/autoload.php'; 
require_once __DIR__ . '/../src/database/database.php'; 
require_once __DIR__ . '/../src/class/address.php'; 

use Dotenv\Dotenv;
use Rakit\Validation\Validator;

header('Content-Type: application/json');

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();


$validator = new Validator();

$data = json_decode(file_get_contents("php://input"), true);

// Validation rules
$validation = $validator->make($data, [
    'user_id'        => 'required',
    'unit_number'    => 'required|max:50',
    'street_address' => 'required|max:50',
    'city'           => 'required|max:100',
    'region'         => 'required|max:100',
    'postal_code'    => 'required|max:20',
    'is_default'     => 'boolean'
]);

// Validate the input data
$validation->validate();

if ($validation->fails()) {
    // Validation failed, return the errors
    $errors = $validation->errors();
    echo json_encode([
        'status' => 'error',
        'message' => 'Validation failed',
        'errors' => $errors->firstOfAll()
    ]);
    exit();
}

$database = new Database();
$db = $database->connect();

// Initialize the Address object
$address = new Address($db);

// Set the address properties
$address->user_id = $data['user_id'];
$address->unit_number = $data['unit_number'];
$address->street_address = $data['street_address'];
$address->city = $data['city'];
$address->region = $data['region'];
$address->postal_code = $data['postal_code'];
$address->is_default = isset($data['is_default']) ? $data['is_default'] : 0; // Default to 0 if not set

// Add the address and send the appropriate response
if ($address->addAddress()) {
    echo json_encode([
        'status' => 'success',
        'message' => 'Address added successfully'
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'User is not yet verified, failed to add address'
    ]);
}
?>