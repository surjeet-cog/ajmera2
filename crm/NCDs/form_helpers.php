<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}
function verify_csrf() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST' ||
        empty($_POST['csrf_token']) ||
        !hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'])) {
        http_response_code(400);
        exit('Invalid request token.');
    }
}
function ncds_upload_pdf($field, $existing = '') {
    if (empty($_FILES[$field]['name'])) return $existing;
    if ($_FILES[$field]['error'] !== UPLOAD_ERR_OK) {
        throw new RuntimeException('PDF upload failed.');
    }
    if ($_FILES[$field]['size'] > 15 * 1024 * 1024) {
        throw new RuntimeException('PDF must be 15MB or smaller.');
    }
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    if ($finfo->file($_FILES[$field]['tmp_name']) !== 'application/pdf') {
        throw new RuntimeException('Only PDF files are allowed.');
    }
    $uploadDir = dirname(__DIR__, 2) . '/uploads/ncds/';
    if (!is_dir($uploadDir) && !mkdir($uploadDir, 0755, true)) {
        throw new RuntimeException('Unable to create upload directory.');
    }
    $name = bin2hex(random_bytes(16)) . '.pdf';
    if (!move_uploaded_file($_FILES[$field]['tmp_name'], $uploadDir . $name)) {
        throw new RuntimeException('Unable to save uploaded PDF.');
    }
    return 'uploads/ncds/' . $name;
}
function ncds_delete_pdf($relativePath) {
    $base = realpath(dirname(__DIR__, 2) . '/uploads/ncds');
    $file = realpath(dirname(__DIR__, 2) . '/' . ltrim((string)$relativePath, '/'));
    if ($base && $file && strpos($file, $base . DIRECTORY_SEPARATOR) === 0 && is_file($file)) {
        unlink($file);
    }
}
