<?php
require_once __DIR__ . '/src/Database/database.php';
require_once __DIR__ . '/src/passwordord/verifyRequest.php';
require_once __DIR__ . '/vendor/autoload.php';

header('Content-Type: application/json');

$database = new Database();
$db = $database->connect();

// Get token from the URL
if (!isset($_GET['token']) || empty($_GET['token'])) {
    http_response_code(400);
    echo json_encode(['message' => 'Token is required.']);
    exit;
}

$token = $_GET['token'];

// Check if the token is valid
$stmt = $db->prepare("SELECT user_id FROM password_resets WHERE token = :token AND expires_at > NOW()");
$stmt->execute(['token' => $token]);

if ($stmt->rowCount() === 1) {
    // Token is valid
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    $userId = $user['user_id']; 
    
    http_response_code(200);
    echo json_encode(['message' => 'Token is valid. You can now reset your password.']);
} else {
    // Token is invalid or expired
    http_response_code(400);
    echo json_encode(['message' => 'Invalid or expired token.']);
}
?>