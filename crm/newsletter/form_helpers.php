<?php
// Shared helpers for newsletter add/edit/delete.
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

function newsletter_h($value): string {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function newsletter_check_csrf(): void {
    $token = $_POST['csrf_token'] ?? '';
    if (!$token || !hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
        http_response_code(403);
        exit('Invalid request token. Please go back and try again.');
    }
}

function newsletter_upload_pdf(string $field, ?string $oldFile = null): ?string {
    if (empty($_FILES[$field]) || $_FILES[$field]['error'] === UPLOAD_ERR_NO_FILE) {
        return $oldFile;
    }
    if ($_FILES[$field]['error'] !== UPLOAD_ERR_OK) {
        throw new RuntimeException('PDF upload failed.');
    }
    if ($_FILES[$field]['size'] > 15 * 1024 * 1024) {
        throw new RuntimeException('PDF must be 15 MB or smaller.');
    }

    $tmp = $_FILES[$field]['tmp_name'];
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    if ($finfo->file($tmp) !== 'application/pdf') {
        throw new RuntimeException('Only PDF files are allowed.');
    }

    $uploadDir = __DIR__ . '/../../uploads/newsletter/';
    if (!is_dir($uploadDir) && !mkdir($uploadDir, 0755, true)) {
        throw new RuntimeException('Could not create upload directory.');
    }

    $fileName = 'newsletter_' . bin2hex(random_bytes(12)) . '.pdf';
    if (!move_uploaded_file($tmp, $uploadDir . $fileName)) {
        throw new RuntimeException('Could not save uploaded PDF.');
    }

    if ($oldFile) {
        $oldPath = $uploadDir . basename($oldFile);
        if (is_file($oldPath)) @unlink($oldPath);
    }
    return $fileName;
}

function newsletter_delete_pdf(?string $file): void {
    if (!$file) return;
    $path = __DIR__ . '/../../uploads/newsletter/' . basename($file);
    if (is_file($path)) @unlink($path);
}
?>