<?php

header('Content-Type: application/json; charset=UTF-8');

require_once __DIR__ . '/dbc.php';

// Only POST requests allowed
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);

    echo json_encode([
        'status' => false,
        'message' => 'Invalid request method.'
    ]);
    exit;
}

// Get form values
$firstName = trim($_POST['first_name'] ?? '');
$email     = trim($_POST['email'] ?? '');
$phone     = trim($_POST['phone'] ?? '');
$message   = trim($_POST['message'] ?? '');

// Server-side validation
if (
    $firstName === '' ||
    strlen($firstName) < 2 ||
    strlen($firstName) > 100 ||
    $email === '' ||
    !filter_var($email, FILTER_VALIDATE_EMAIL) ||
    strlen($email) > 150 ||
    !preg_match('/^[0-9]{10}$/', $phone)
) {
    http_response_code(422);

    echo json_encode([
        'status' => false,
        'message' => 'Please enter valid form details.'
    ]);
    exit;
}

// Database connection check
if (!isset($conn) || $conn->connect_error) {
    error_log('Database connection error.');

    http_response_code(500);

    echo json_encode([
        'status' => false,
        'message' => 'Database connection failed.'
    ]);
    exit;
}

// Insert enquiry
$stmt = $conn->prepare("
    INSERT INTO enquiries
    (first_name, email, phone, message)
    VALUES (?, ?, ?, ?)
");

if (!$stmt) {
    error_log('Prepare error: ' . $conn->error);

    http_response_code(500);

    echo json_encode([
        'status' => false,
        'message' => 'Database query failed.'
    ]);
    exit;
}

$stmt->bind_param(
    "ssss",
    $firstName,
    $email,
    $phone,
    $message
);

if ($stmt->execute()) {

    $stmt->close();

    // Dynamically get website root URL
    $protocol = (
        !empty($_SERVER['HTTPS']) &&
        $_SERVER['HTTPS'] !== 'off'
    ) ? 'https://' : 'http://';

    $host = $_SERVER['HTTP_HOST'];

    // Backend Ajmera root folder mein hai
    $baseUrl = $protocol . $host . '/ajmera/';

    echo json_encode([
        'status' => true,
        'message' => 'Enquiry submitted successfully.',
        'redirect' => $baseUrl . 'thankyou.php'
    ]);

    exit;

} else {

    error_log('Insert error: ' . $stmt->error);

    $stmt->close();

    http_response_code(500);

    echo json_encode([
        'status' => false,
        'message' => 'Unable to save enquiry. Please try again.'
    ]);
    exit;
}