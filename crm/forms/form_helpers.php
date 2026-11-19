<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

function e($value): string {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function csrf_token(): string {
    if (empty($_SESSION['investor_forms_csrf'])) {
        $_SESSION['investor_forms_csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['investor_forms_csrf'];
}

function verify_csrf(): void {
    $token = $_POST['csrf_token'] ?? '';
    if (!is_string($token) || empty($_SESSION['investor_forms_csrf']) ||
        !hash_equals($_SESSION['investor_forms_csrf'], $token)) {
        http_response_code(403);
        exit('Invalid request token. Please go back and try again.');
    }
}

function upload_pdf(array $file, ?string $oldFile = null): ?string {
    if (!isset($file['error']) || $file['error'] === UPLOAD_ERR_NO_FILE) {
        return $oldFile;
    }
    if ($file['error'] !== UPLOAD_ERR_OK) {
        throw new RuntimeException('PDF upload failed. Please try again.');
    }
    if (($file['size'] ?? 0) > 15 * 1024 * 1024) {
        throw new RuntimeException('PDF size must be 15 MB or less.');
    }

    $tmp = $file['tmp_name'] ?? '';
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($tmp);
    if ($mime !== 'application/pdf') {
        throw new RuntimeException('Only PDF files are allowed.');
    }

    $uploadDir = __DIR__ . '/../../uploads/investor-forms/';
    if (!is_dir($uploadDir) && !mkdir($uploadDir, 0755, true)) {
        throw new RuntimeException('Unable to create the upload directory.');
    }

    $filename = 'investor-form-' . bin2hex(random_bytes(12)) . '.pdf';
    if (!move_uploaded_file($tmp, $uploadDir . $filename)) {
        throw new RuntimeException('Unable to save the uploaded PDF.');
    }

    if ($oldFile) {
        $oldName = basename($oldFile);
        $oldPath = $uploadDir . $oldName;
        if (is_file($oldPath)) {
            @unlink($oldPath);
        }
    }

    return 'uploads/investor-forms/' . $filename;
}

function delete_pdf(?string $file): void {
    if (!$file) return;
    $uploadDir = realpath(__DIR__ . '/../../uploads/investor-forms/');
    if (!$uploadDir) return;
    $path = realpath(__DIR__ . '/../../' . ltrim($file, '/'));
    if ($path && strpos($path, $uploadDir . DIRECTORY_SEPARATOR) === 0 && is_file($path)) {
        @unlink($path);
    }
}
