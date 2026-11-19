
<?php
// Must be the first PHP code in this file.
// Save this file as UTF-8 without BOM.

ob_start();

error_reporting(E_ALL);
ini_set('display_errors', '0');
ini_set('log_errors', '1');

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Database connection
require_once __DIR__ . '/../dbc.php';

// Brochure configuration
require_once __DIR__ . '/brochure_config.php';

try {

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: /ajmera/');
        exit;
    }

    if (!isset($conn) || !($conn instanceof mysqli)) {
        throw new Exception('Database connection failed.');
    }

    // Form values
    $first_name = trim($_POST['first_name'] ?? '');
    $email      = trim($_POST['email'] ?? '');
    $phone      = trim($_POST['phone'] ?? '');
    $project    = trim($_POST['project'] ?? '');
    $message    = trim($_POST['message'] ?? '');

    // Validation
    if ($first_name === '') {
        throw new Exception('Please enter your name.');
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Please enter a valid email address.');
    }

    if (!preg_match('/^[0-9]{10}$/', $phone)) {
        throw new Exception('Please enter a valid 10-digit phone number.');
    }

    // Verify project slug
    $validProject = false;

    foreach ($brochureProjects as $item) {
        if (
            isset($item['slug']) &&
            $item['slug'] === $project
        ) {
            $validProject = true;
            break;
        }
    }

    if (!$validProject) {
        throw new Exception('Invalid project selected.');
    }

    // Database insert
    $stmt = $conn->prepare("
        INSERT INTO download_brochure_enquiries
        (first_name, email, phone, project, message)
        VALUES (?, ?, ?, ?, ?)
    ");

    $stmt->bind_param(
        "sssss",
        $first_name,
        $email,
        $phone,
        $project,
        $message
    );

    $stmt->execute();
    $stmt->close();

    // Prepare redirect URL
$redirectUrl = '/ajmera/thankyou.php?project=' . urlencode($project);

// Redirect after successful database insert
if (!headers_sent()) {
    header('Location: ' . $redirectUrl, true, 303);
    exit;
}

// Log if headers were already sent
error_log('Brochure redirect failed: Headers already sent.');

echo '<script>
    window.location.href = ' . json_encode($redirectUrl) . ';
</script>';
exit;

 } catch (Throwable $e) {

    error_log('Brochure Enquiry Error: ' . $e->getMessage());

    http_response_code(500);

    echo 'Something went wrong. Please try again.';
    exit;
}
