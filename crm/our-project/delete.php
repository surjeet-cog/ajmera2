<?php
ob_start();

error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../dbc.php';
require_once __DIR__ . '/form_helpers.php';

if (!isset($conn) || !($conn instanceof mysqli)) {
    die('Database connection failed. Check dbc.php.');
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

if (!project_verify_csrf()) {
    header('Location: index.php?error=csrf');
    exit;
}

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if (!$id || $id < 1) {
    header('Location: index.php?error=invalid_id');
    exit;
}

/* Get the image path before deleting the record. */
$stmt = $conn->prepare(
    "SELECT project_image FROM projects WHERE id = ? LIMIT 1"
);

if (!$stmt) {
    die('Could not prepare project lookup: ' . e($conn->error));
}

$stmt->bind_param('i', $id);
$stmt->execute();

$result = $stmt->get_result();
$project = $result->fetch_assoc();
$stmt->close();

if (!$project) {
    header('Location: index.php?error=not_found');
    exit;
}

/* Delete project record. */
$delete = $conn->prepare("DELETE FROM projects WHERE id = ?");

if (!$delete) {
    die('Could not prepare delete query: ' . e($conn->error));
}

$delete->bind_param('i', $id);

if ($delete->execute()) {
    $delete->close();

    if (!empty($project['project_image'])) {
        delete_project_image_file($project['project_image']);
    }

    header('Location: index.php?success=deleted');
    exit;
}

$delete->close();

header('Location: index.php?error=delete_failed');
exit;