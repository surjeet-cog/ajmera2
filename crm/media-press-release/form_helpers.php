<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

function e($value): string {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function media_csrf_token(): string {
    if (empty($_SESSION['media_press_releases_csrf'])) {
        $_SESSION['media_press_releases_csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['media_press_releases_csrf'];
}

function media_verify_csrf(): void {
    $token = $_POST['csrf_token'] ?? '';
    if (!is_string($token) || empty($_SESSION['media_press_releases_csrf']) ||
        !hash_equals($_SESSION['media_press_releases_csrf'], $token)) {
        http_response_code(403);
        exit('Invalid request token. Please go back and try again.');
    }
}

/**
 * Upload a PDF and return its relative path. Existing file is not deleted here;
 * callers delete it only after the database update succeeds.
 */
function media_upload_pdf(array $file): ?string {
    if (!isset($file['error']) || $file['error'] === UPLOAD_ERR_NO_FILE) {
        return null;
    }
    if ($file['error'] !== UPLOAD_ERR_OK) {
        throw new RuntimeException('PDF upload failed. Please try again.');
    }
    if (($file['size'] ?? 0) > 15 * 1024 * 1024) {
        throw new RuntimeException('PDF size must be 15 MB or less.');
    }

    $tmp = $file['tmp_name'] ?? '';
    if (!is_uploaded_file($tmp)) {
        throw new RuntimeException('Invalid uploaded file.');
    }
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    if ($finfo->file($tmp) !== 'application/pdf') {
        throw new RuntimeException('Only PDF files are allowed.');
    }

    $uploadDir = __DIR__ . '/../../uploads/media-press-releases/';
    if (!is_dir($uploadDir) && !mkdir($uploadDir, 0755, true)) {
        throw new RuntimeException('Unable to create upload directory.');
    }

    $filename = 'media-release-' . bin2hex(random_bytes(12)) . '.pdf';
    if (!move_uploaded_file($tmp, $uploadDir . $filename)) {
        throw new RuntimeException('Unable to save the uploaded PDF.');
    }
    return 'uploads/media-press-releases/' . $filename;
}

function media_delete_pdf(?string $relativeFile): void {
    if (!$relativeFile) return;
    $uploadDir = realpath(__DIR__ . '/../../uploads/media-press-releases/');
    if (!$uploadDir) return;

    $candidate = __DIR__ . '/../../' . ltrim($relativeFile, '/');
    $realFile = realpath($candidate);
    if ($realFile && strpos($realFile, $uploadDir . DIRECTORY_SEPARATOR) === 0 && is_file($realFile)) {
        @unlink($realFile);
    }
}

function media_valid_financial_year(string $year): bool {
    return (bool)preg_match('/^\d{4}-\d{2}$/', $year);
}
