<?php
ob_start();
require_once __DIR__ . '/../auth.php';
require_once __DIR__ . '/../../dbc.php';
require_once __DIR__ . '/form_helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}
verify_csrf();

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    $_SESSION['error'] = 'Invalid investor form.';
    header('Location: index.php');
    exit;
}

$stmt = $conn->prepare("SELECT pdf_file FROM investor_forms WHERE id = ? LIMIT 1");
$stmt->bind_param('i', $id);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$row) {
    $_SESSION['error'] = 'Investor form not found.';
    header('Location: index.php');
    exit;
}

$stmt = $conn->prepare("DELETE FROM investor_forms WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$stmt->close();

delete_pdf($row['pdf_file'] ?? null);
$_SESSION['success'] = 'Investor form deleted successfully.';
header('Location: index.php');
exit;
