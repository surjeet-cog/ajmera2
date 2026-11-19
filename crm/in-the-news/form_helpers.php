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
function investor_upload_thumbnail($field, $existing = '') {
    if (empty($_FILES[$field]['name'])) return $existing;
    if ($_FILES[$field]['error'] !== UPLOAD_ERR_OK) {
        throw new RuntimeException('Thumbnail upload failed.');
    }
    if ($_FILES[$field]['size'] > 5 * 1024 * 1024) {
        throw new RuntimeException('Thumbnail must be 5MB or smaller.');
    }
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($_FILES[$field]['tmp_name']);
    $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
    if (!isset($allowed[$mime])) {
        throw new RuntimeException('Only JPG, PNG, and WEBP images are allowed.');
    }
    $uploadDir = dirname(__DIR__, 2) . '/uploads/investor-inthenews/';
    if (!is_dir($uploadDir) && !mkdir($uploadDir, 0755, true)) {
        throw new RuntimeException('Unable to create thumbnail upload directory.');
    }
    $name = bin2hex(random_bytes(16)) . '.' . $allowed[$mime];
    if (!move_uploaded_file($_FILES[$field]['tmp_name'], $uploadDir . $name)) {
        throw new RuntimeException('Unable to save thumbnail.');
    }
    return 'uploads/investor-inthenews/' . $name;
}
function investor_delete_thumbnail($relativePath) {
    $root = dirname(__DIR__, 2);
    $base = realpath($root . '/uploads/investor-inthenews');
    $file = realpath($root . '/' . ltrim((string)$relativePath, '/'));
    if ($base && $file && strpos($file, $base . DIRECTORY_SEPARATOR) === 0 && is_file($file)) {
        unlink($file);
    }
}
