<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
function e($value): string {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}
function csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}
function verify_csrf(): void {
    $token = $_POST['csrf_token'] ?? '';
    if (!is_string($token) || empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $token)) {
        http_response_code(419);
        exit('Invalid security token. Please go back and try again.');
    }
}
function ensure_upload_dir(): string {
    $dir = __DIR__ . '/../../uploads/iepf-documents/';
    if (!is_dir($dir) && !mkdir($dir, 0755, true) && !is_dir($dir)) {
        throw new RuntimeException('Unable to create upload directory.');
    }
    return $dir;
}
function delete_pdf(?string $relativePath): void {
    if (!$relativePath) return;
    $prefix = 'uploads/iepf-documents/';
    if (strpos($relativePath, $prefix) !== 0) return;
    $base = realpath(__DIR__ . '/../../uploads/iepf-documents/');
    $target = realpath(__DIR__ . '/../../' . $relativePath);
    if ($base && $target && strpos($target, $base . DIRECTORY_SEPARATOR) === 0 && is_file($target)) {
        @unlink($target);
    }
}
function upload_pdf(array $file, ?string $oldFile = null): ?string {
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) return $oldFile;
    if (($file['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK) throw new RuntimeException('PDF upload failed.');
    if (($file['size'] ?? 0) > 15 * 1024 * 1024) throw new RuntimeException('PDF must be 15 MB or smaller.');
    $tmp = $file['tmp_name'] ?? '';
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    if (!$tmp || $finfo->file($tmp) !== 'application/pdf') throw new RuntimeException('Only PDF files are allowed.');
    $dir = ensure_upload_dir();
    $name = bin2hex(random_bytes(16)) . '.pdf';
    if (!move_uploaded_file($tmp, $dir . $name)) throw new RuntimeException('Unable to save uploaded PDF.');
    if ($oldFile) delete_pdf($oldFile);
    return 'uploads/iepf-documents/' . $name;
}
