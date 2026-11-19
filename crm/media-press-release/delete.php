<?php
ob_start();
require_once __DIR__ . '/../auth.php';
require_once __DIR__ . '/../../dbc.php';
require_once __DIR__ . '/form_helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}
media_verify_csrf();

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    $_SESSION['error'] = 'Invalid press release.';
    header('Location: index.php');
    exit;
}

$stmt = $conn->prepare("SELECT pdf_file FROM media_press_releases WHERE id = ? LIMIT 1");
$stmt->bind_param('i', $id);
$stmt->execute();
$report = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$report) {
    $_SESSION['error'] = 'Press release not found.';
    header('Location: index.php');
    exit;
}

$stmt = $conn->prepare("DELETE FROM media_press_releases WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$stmt->close();

media_delete_pdf($report['pdf_file'] ?? null);
$_SESSION['success'] = 'Press release deleted successfully.';
header('Location: index.php');
exit;
