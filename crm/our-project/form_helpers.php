<?php
ob_start();

error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');

// Start session before any output.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Escape output.
 */
if (!function_exists('e')) {
    function e($value): string
    {
        return htmlspecialchars(
            (string) ($value ?? ''),
            ENT_QUOTES,
            'UTF-8'
        );
    }
}

/**
 * Generate CSRF token.
 */
if (!function_exists('project_csrf_token')) {
    function project_csrf_token(): string
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            throw new RuntimeException(
                'Session is not active. Refresh the page.'
            );
        }

        if (
            !isset($_SESSION['project_csrf_token']) ||
            !is_string($_SESSION['project_csrf_token']) ||
            $_SESSION['project_csrf_token'] === ''
        ) {
            $_SESSION['project_csrf_token'] = bin2hex(
                random_bytes(32)
            );
        }

        return $_SESSION['project_csrf_token'];
    }
}

/**
 * Verify CSRF token.
 */
if (!function_exists('project_verify_csrf')) {
    function project_verify_csrf(): bool
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            return false;
        }

        $submittedToken = $_POST['csrf_token'] ?? '';
        $sessionToken = $_SESSION['project_csrf_token'] ?? '';

        if (
            !is_string($submittedToken) ||
            !is_string($sessionToken) ||
            $submittedToken === '' ||
            $sessionToken === ''
        ) {
            return false;
        }

        return hash_equals($sessionToken, $submittedToken);
    }
}

/**
 * Create slug.
 */
if (!function_exists('project_slugify')) {
    function project_slugify(string $value): string
    {
        $value = strtolower(trim($value));
        $value = preg_replace('/[^a-z0-9]+/', '-', $value);

        return trim((string) $value, '-');
    }
}

/**
 * Upload project image.
 */
if (!function_exists('upload_project_image')) {
    function upload_project_image(
        array $file,
        string $existingImage = ''
    ): string {
        if (
            !isset($file['error']) ||
            $file['error'] === UPLOAD_ERR_NO_FILE
        ) {
            return $existingImage;
        }

        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new RuntimeException(
                'Image upload failed. Please try again.'
            );
        }

        if (
            empty($file['tmp_name']) ||
            !is_uploaded_file($file['tmp_name'])
        ) {
            throw new RuntimeException('Invalid uploaded file.');
        }

        if (($file['size'] ?? 0) > 5 * 1024 * 1024) {
            throw new RuntimeException(
                'Image size must be 5 MB or less.'
            );
        }

        $imageInfo = getimagesize($file['tmp_name']);

        if ($imageInfo === false) {
            throw new RuntimeException(
                'Please upload a valid image.'
            );
        }

        $allowedTypes = [
            'image/jpeg' => 'jpg',
            'image/png'  => 'png',
            'image/webp' => 'webp',
            'image/gif'  => 'gif',
        ];

        $mime = $imageInfo['mime'] ?? '';

        if (!isset($allowedTypes[$mime])) {
            throw new RuntimeException(
                'Only JPG, PNG, WEBP and GIF images are allowed.'
            );
        }

        $projectRoot = dirname(__DIR__, 2);
        $uploadDirectory = $projectRoot . '/uploads/projects';

        if (
            !is_dir($uploadDirectory) &&
            !mkdir($uploadDirectory, 0755, true) &&
            !is_dir($uploadDirectory)
        ) {
            throw new RuntimeException(
                'Could not create image upload directory.'
            );
        }

        $filename = bin2hex(random_bytes(16))
            . '.'
            . $allowedTypes[$mime];

        $destination = $uploadDirectory . '/' . $filename;

        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            throw new RuntimeException(
                'Could not save the uploaded image.'
            );
        }

        return 'uploads/projects/' . $filename;
    }
}

/**
 * Delete project image only from uploads/projects.
 */
if (!function_exists('delete_project_image_file')) {
    function delete_project_image_file(string $imagePath): void
    {
        if ($imagePath === '') {
            return;
        }

        $normalized = str_replace('\\', '/', $imagePath);

        if (strpos($normalized, 'uploads/projects/') !== 0) {
            return;
        }

        $projectRoot = dirname(__DIR__, 2);
        $fullPath = $projectRoot . '/' . $normalized;

        if (is_file($fullPath)) {
            unlink($fullPath);
        }
    }
}

/**
 * Validate project URL as a relative path.
 */
if (!function_exists('validate_project_url')) {
    function validate_project_url(string $url): string
    {
        $url = trim($url);

        if ($url === '') {
            return '';
        }

        if (
            strpos($url, '/') === 0 ||
            strpos($url, '\\') !== false ||
            preg_match('/^[a-z][a-z0-9+.-]*:/i', $url) ||
            strpos($url, '..') !== false
        ) {
            throw new RuntimeException(
                'Enter a relative project URL, for example projects/example.php.'
            );
        }

        return $url;
    }
}
