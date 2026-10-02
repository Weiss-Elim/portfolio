<?php
// Set JSON response header
header('Content-Type: application/json; charset=utf-8');

// Ensure database configuration is loaded
require_once __DIR__ . '/../config/database.php';

// Allow only POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'status'  => 'error',
        'message' => 'Method Not Allowed. Please submit via POST.'
    ]);
    exit;
}

// Retrieve and sanitize input fields
$name    = isset($_POST['name']) ? trim(filter_var($_POST['name'], FILTER_SANITIZE_SPECIAL_CHARS)) : '';
$email   = isset($_POST['email']) ? trim(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL)) : '';
$subject = isset($_POST['subject']) ? trim(filter_var($_POST['subject'], FILTER_SANITIZE_SPECIAL_CHARS)) : '';
$message = isset($_POST['message']) ? trim(filter_var($_POST['message'], FILTER_SANITIZE_SPECIAL_CHARS)) : '';

// Validation checks
$errors = [];

if (empty($name) || strlen($name) < 2) {
    $errors['name'] = 'Please enter a valid name (at least 2 characters).';
}

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = 'Please enter a valid email address.';
}

if (empty($subject) || strlen($subject) < 3) {
    $errors['subject'] = 'Please enter a subject (at least 3 characters).';
}

if (empty($message) || strlen($message) < 10) {
    $errors['message'] = 'Please write a message (at least 10 characters).';
}

// If validation fails, return structured errors
if (!empty($errors)) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'errors' => $errors
    ]);
    exit;
}

// Capture client IP address for security and logging
$ipAddress = $_SERVER['REMOTE_ADDR'] ?? null;
if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ipAddress = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ipAddress = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
}

try {
    // Connect to database
    $db = getDBConnection();

    // Prepare SQL statement
    $stmt = $db->prepare("
        INSERT INTO messages (name, email, subject, message, ip_address, is_read, created_at)
        VALUES (:name, :email, :subject, :message, :ip, 0, NOW())
    ");

    // Execute with bound values
    $stmt->execute([
        ':name'    => $name,
        ':email'   => $email,
        ':subject' => $subject,
        ':message' => $message,
        ':ip'      => $ipAddress
    ]);

    // Successful response
    http_response_code(200);
    echo json_encode([
        'status'  => 'success',
        'message' => 'Thank you! Your message has been sent successfully.'
    ]);

} catch (PDOException $e) {
    // Log internal error privately
    error_log("Contact Form Database Error: " . $e->getMessage());

    // Return generic user-facing error
    http_response_code(500);
    echo json_encode([
        'status'  => 'error',
        'message' => 'An error occurred while saving your message. Please try again later.'
    ]);
}