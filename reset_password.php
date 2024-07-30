<?php
header('Content-Type: application/json');

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['message' => 'Invalid request method']);
    exit();
}

// Get the JSON input data
$input = json_decode(file_get_contents('php://input'), true);

// Check if all required fields are present
if (empty($input['email']) || empty($input['new_password']) || empty($input['confirm_password'])) {
    http_response_code(400);
    echo json_encode(['message' => 'All fields are required']);
    exit();
}

$email = $input['email'];
$new_password = $input['new_password'];
$confirm_password = $input['confirm_password'];

// Check if the new password and confirm password match
if ($new_password !== $confirm_password) {
    http_response_code(400);
    echo json_encode(['message' => 'Passwords do not match']);
    exit();
}

// Database credentials
$db_host = 'localhost';
$db_user = 'root';
$db_password = '';
$db_name = 'tvflix';

// Create a database connection
$conn = new mysqli($db_host, $db_user, $db_password, $db_name);
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['message' => 'Database connection failed']);
    exit();
}

// Check if the email exists in the database
$query = "SELECT * FROM users WHERE email = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    http_response_code(404);
    echo json_encode(['message' => 'Email not found']);
    exit();
}

// Reset the user's password in the database
$hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
$query = "UPDATE users SET password = ? WHERE email = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ss", $hashed_password, $email);

if ($stmt->execute()) {
    http_response_code(200);
    echo json_encode(['message' => 'Password reset successful']);
} else {
    http_response_code(500);
    echo json_encode(['message' => 'Failed to reset password']);
}

// Close the database connection
$conn->close();
