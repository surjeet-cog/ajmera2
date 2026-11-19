
<?php

header('Content-Type: application/json; charset=UTF-8');

error_reporting(E_ALL);
ini_set('display_errors', '0');


/* =====================================================
   JSON RESPONSE FUNCTION
===================================================== */

function sendResponse($status, $message, $redirect = null)
{
    $response = [
        'status'  => $status,
        'message' => $message
    ];

    if ($redirect !== null) {
        $response['redirect'] = $redirect;
    }

    echo json_encode($response);
    exit;
}


/* =====================================================
   DATABASE CONNECTION
===================================================== */

try {

    require_once __DIR__ . '/dbc.php';

} catch (Throwable $e) {

    error_log('Project enquiry DB connection error: ' . $e->getMessage());

    sendResponse('error', 'Database connection failed. Please try again.');
}


/* =====================================================
   ONLY POST REQUEST
===================================================== */

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendResponse('error', 'Invalid request.');
}


/* =====================================================
   GET FORM DATA
===================================================== */

$first_name = trim($_POST['first_name'] ?? '');
$email      = trim($_POST['email'] ?? '');
$phone      = trim($_POST['phone'] ?? '');
$project    = trim($_POST['project'] ?? '');
$message    = trim($_POST['message'] ?? '');


/* =====================================================
   SERVER SIDE VALIDATION
===================================================== */

// First Name

if ($first_name === '') {
    sendResponse('error', 'Please enter your name.');
}

if (mb_strlen($first_name) < 2) {
    sendResponse('error', 'Name must be at least 2 characters.');
}

if (mb_strlen($first_name) > 100) {
    sendResponse('error', 'Name cannot exceed 100 characters.');
}


// Email

if ($email === '') {
    sendResponse('error', 'Please enter your email.');
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    sendResponse('error', 'Please enter a valid email address.');
}


// Phone

if (!preg_match('/^[0-9]{10}$/', $phone)) {
    sendResponse('error', 'Please enter a valid 10-digit phone number.');
}


// Project

if ($project === '') {
    sendResponse('error', 'Please select a project.');
}


// Message

if (mb_strlen($message) > 1000) {
    sendResponse('error', 'Message cannot exceed 1000 characters.');
}


/* =====================================================
   ALLOWED PROJECTS
===================================================== */

$allowedProjects = [

    'Ajmera Sikova',
    'Ajmera Heritage City',
    'Ajmera Summit',
    'Ajmera i Land',
    'Ajmera Greenfinity',
    'Bliss',
    'Ajmera Kalyan Extravaganza',
    'Ajmera Exotica',
    'Manhattan',
    'Arham',
    'Ajmera Nucleus',
    'Ajmera Nucleus Commercial',
    'Lugaano',
    'Ajmera Florenza',
    'Biznext',
    'Zenica',
    'Vann By Ajmera',
    'Ajmera Shikhar',
    'Ajmera Solis',
    'Ajmera Vihara',
    'Ajmera Arham',
    'Ajmera Aria',
    'Ajmera Elevate'

];


/* =====================================================
   VALIDATE SELECTED PROJECT
===================================================== */

if (!in_array($project, $allowedProjects, true)) {
    sendResponse('error', 'Invalid project selected.');
}


/* =====================================================
   DATABASE INSERT
===================================================== */

try {

    $stmt = $conn->prepare("
        INSERT INTO project_enquiries
        (
            first_name,
            email,
            phone,
            project,
            message
        )
        VALUES (?, ?, ?, ?, ?)
    ");

    if (!$stmt) {
        throw new Exception($conn->error);
    }

    $stmt->bind_param(
        "sssss",
        $first_name,
        $email,
        $phone,
        $project,
        $message
    );

    if (!$stmt->execute()) {
        throw new Exception($stmt->error);
    }

    $stmt->close();
    $conn->close();

    sendResponse(
        'success',
        'Your enquiry has been submitted successfully.',
        'thankyou.php'
    );

} catch (Throwable $e) {

    error_log('Project enquiry insert error: ' . $e->getMessage());

    if (isset($stmt) && $stmt instanceof mysqli_stmt) {
        $stmt->close();
    }

    if (isset($conn) && $conn instanceof mysqli) {
        $conn->close();
    }

    sendResponse(
        'error',
        'Unable to submit your enquiry. Please try again.'
    );
}

?>
