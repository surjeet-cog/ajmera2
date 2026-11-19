<?php

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/dbc.php';

function sendResponse($status, $message, $httpCode = 200) {

    http_response_code($httpCode);

    echo json_encode([
        'status' => $status,
        'message' => $message
    ]);

    exit;
}

// Only POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendResponse('error', 'Invalid request method.', 405);
}

// Get email
$email = trim($_POST['email'] ?? '');

// Validate email
if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    sendResponse('error', 'Please enter a valid email address.', 422);
}

// Check duplicate email
$stmt = $conn->prepare("
    SELECT id FROM newsletter_subscribers
    WHERE email = ?
    LIMIT 1
");

if (!$stmt) {
    error_log('Newsletter select error: ' . $conn->error);
    sendResponse('error', 'Unable to process your request.', 500);
}

$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->close();
    sendResponse('error', 'This email is already subscribed.');
}

$stmt->close();

// Insert email
$stmt = $conn->prepare("
    INSERT INTO newsletter_subscribers (email)
    VALUES (?)
");

if (!$stmt) {
    error_log('Newsletter prepare error: ' . $conn->error);
    sendResponse('error', 'Unable to subscribe. Please try again.', 500);
}

$stmt->bind_param("s", $email);

if (!$stmt->execute()) {

    error_log('Newsletter insert error: ' . $stmt->error);
    $stmt->close();

    sendResponse('error', 'Unable to subscribe. Please try again.', 500);
}

$stmt->close();

// Redirect will be handled by AJAX
sendResponse('success', 'Thank you for subscribing successfully.');

?>