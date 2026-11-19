<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();

function e($value): string {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}
function analyst_notes_csrf_token(): string {
    if (empty($_SESSION['analyst_notes_csrf'])) {
        $_SESSION['analyst_notes_csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['analyst_notes_csrf'];
}
function analyst_notes_verify_csrf(): void {
    $token = $_POST['csrf_token'] ?? '';
    if (!is_string($token) || empty($_SESSION['analyst_notes_csrf']) ||
        !hash_equals($_SESSION['analyst_notes_csrf'], $token)) {
        http_response_code(403);
        exit('Invalid request token. Please go back and try again.');
    }
}
function analyst_notes_upload_pdf(array $file): ?string {
    if (!isset($file['error']) || $file['error'] === UPLOAD_ERR_NO_FILE) return null;
    if ($file['error'] !== UPLOAD_ERR_OK) throw new RuntimeException('PDF upload failed.');
    if (($file['size'] ?? 0) > 15 * 1024 * 1024) throw new RuntimeException('PDF size must be 15 MB or less.');
    $tmp = $file['tmp_name'] ?? '';
    if (!is_uploaded_file($tmp)) throw new RuntimeException('Invalid uploaded file.');
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    if ($finfo->file($tmp) !== 'application/pdf') throw new RuntimeException('Only PDF files are allowed.');

    $dir = __DIR__ . '/../../uploads/analyst-notes/';
    if (!is_dir($dir) && !mkdir($dir, 0755, true)) throw new RuntimeException('Unable to create upload directory.');
    $name = 'analyst-note-' . bin2hex(random_bytes(12)) . '.pdf';
    if (!move_uploaded_file($tmp, $dir . $name)) throw new RuntimeException('Unable to save PDF.');
    return 'uploads/analyst-notes/' . $name;
}
function analyst_notes_delete_pdf(?string $file): void {
    if (!$file) return;
    $dir = realpath(__DIR__ . '/../../uploads/analyst-notes/');
    if (!$dir) return;
    $path = realpath(__DIR__ . '/../../' . ltrim($file, '/'));
    if ($path && strpos($path, $dir . DIRECTORY_SEPARATOR) === 0 && is_file($path)) @unlink($path);
}
