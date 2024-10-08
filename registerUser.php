<?php
require_once 'src/Database/database.php';
require_once 'src/class/register.php';
require_once 'src/class/emailservice.php';
require_once __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;
use Rakit\Validation\Validator;

header('Content-Type: application/json');

// Load .env file
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

try {
    $database = new Database();
    $db = $database->connect();
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['message' => 'Database connection failed: ' . $e->getMessage()]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

// Check if the required keys are present in the request
$requiredKeys = ['first_name', 'last_name', 'email', 'password', 'confirm_password', 'date_of_birth', 'role'];

foreach ($requiredKeys as $key) {
    if (!isset($data[$key])) {
        http_response_code(400);
        echo json_encode(['message' => 'Missing required field: ' . $key]);
        exit;
    }
}

// Initialize Rakit Validator
$validator = new Validator;

// Define validation rules
$validation = $validator->make($data, [
    'first_name'              => 'required|alpha|max:50',
    'last_name'               => 'required|alpha|max:50',
    'email'                   => 'required|email|max:100',
    'password'                => 'required|min:8|regex:/[A-Za-z]/|regex:/[0-9]/|regex:/[@$!%*?&]/', 
    'confirm_password'        => 'required|min:8',
    'date_of_birth'           => 'required|date|before:today', // Ensure it’s a valid date and in the past
    'role'                    => 'required|in:user,admin'
]);

try {
    // Run validation
    $validation->validate();

    // Check for existing email
    if ($validation->passes()) {
        $emailExistsQuery = $db->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
        $emailExistsQuery->execute(['email' => $data['email']]);
        $emailExistsCount = $emailExistsQuery->fetchColumn();

        if ($emailExistsCount > 0) {
            http_response_code(400);
            echo json_encode(['message' => 'Email is already registered.']);
            exit;
        }
    }

    // Check if password and confirmation match
    if ($data['password'] !== $data['confirm_password']) {
        http_response_code(400);
        echo json_encode(['message' => 'Passwords do not match.']);
        exit;
    }

    // Check if date_of_birth is valid
    if (DateTime::createFromFormat('Y-m-d', $data['date_of_birth']) === false) {
        http_response_code(400);
        echo json_encode(['message' => 'Invalid date format. Use YYYY-MM-DD.']);
        exit;
    }

    if ($validation->fails()) {
        // Handling validation errors
        $errors = $validation->errors();
        http_response_code(400);
        echo json_encode(['message' => 'Validation failed', 'errors' => $errors->firstOfAll()]);
        exit;
    }

    // Attempt to register the user
    $user = new Register($db);
    $result = $user->registerUser($data['first_name'], $data['last_name'], $data['email'], $data['password'], $data['date_of_birth'], $data['role']);

    if ($result['success']) {
        $tokenId = $result['token_id'];
    
        // Fetch newly registered user ID (assuming it's in $result['user_id'])
        $userId = $result['user_id']; // Assuming 'registerUser' function returns user_id
        $fullName = $data['first_name'] . ' ' . $data['last_name'];
    
        $emailService = new EmailService();
        if ($emailService->sendVerificationEmail($data['email'], $tokenId)) {
            http_response_code(201);
            echo json_encode([
                'message' => 'User registered successfully. Verification email sent.',
                'user' => [
                    'id' => $userId,
                    'name' => $fullName,
                    'email' => $data['email']
                ]
            ]);
        } else {
            http_response_code(500);
            echo json_encode(['message' => 'Verification email could not be sent.']);
        }
    } else {
        http_response_code(400);
        echo json_encode(['message' => $result['message']]);
    }
    
} catch (Exception $e) {
    // General error handling
    http_response_code(500);
    echo json_encode(['message' => 'An error occurred: ' . $e->getMessage()]);
}

ini_set('display_errors', '0');
error_reporting(0);
?>


