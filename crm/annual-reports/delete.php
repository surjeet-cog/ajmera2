<?php
ob_start();

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require_once __DIR__ . '/../../dbc.php';

// Do not regenerate the token on each request.
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

// Validate CSRF token
$token = $_POST['csrf_token'] ?? '';

if (
    empty($_SESSION['csrf_token']) ||
    !is_string($token) ||
    !hash_equals($_SESSION['csrf_token'], $token)
) {
    http_response_code(403);
    exit('Invalid request. Please refresh the page.');
}

// Validate report ID
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if (!$id || $id < 1) {
    http_response_code(400);
    exit('Invalid report ID.');
}

// Get PDF path
$stmt = $conn->prepare(
    "SELECT pdf_file FROM annual_reports WHERE id = ?"
);

if (!$stmt) {
    error_log('Select prepare failed: ' . $conn->error);
    http_response_code(500);
    exit('Database query failed.');
}

$stmt->bind_param('i', $id);

if (!$stmt->execute()) {
    error_log('Select execute failed: ' . $stmt->error);
    $stmt->close();
    http_response_code(500);
    exit('Unable to find annual report.');
}

$report = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$report) {
    header('Location: index.php?msg=notfound');
    exit;
}

// Delete database record
$stmt = $conn->prepare(
    "DELETE FROM annual_reports WHERE id = ?"
);

if (!$stmt) {
    error_log('Delete prepare failed: ' . $conn->error);
    http_response_code(500);
    exit('Unable to prepare delete query.');
}

$stmt->bind_param('i', $id);

if (!$stmt->execute()) {
    error_log('Delete execute failed: ' . $stmt->error);
    $stmt->close();
    http_response_code(500);
    exit('Unable to delete annual report.');
}

$deleted = $stmt->affected_rows;
$stmt->close();

if ($deleted !== 1) {
    header('Location: index.php?msg=notfound');
    exit;
}

// Delete the PDF only after successful database deletion.
// Restrict deletion to uploads/annual-reports/.
$relativeDir = 'uploads/annual-reports/';
$pdfPath = str_replace('\\', '/', $report['pdf_file']);

if (strpos($pdfPath, $relativeDir) === 0) {
    $filePath = __DIR__ . '/../../' . $pdfPath;

    if (is_file($filePath) && !@unlink($filePath)) {
        error_log('Could not delete annual report PDF: ' . $filePath);
    }
}

header('Location: index.php?msg=deleted');
exit;
