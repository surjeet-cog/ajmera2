<?php

require_once 'dbc.php';

header('Content-Type: text/plain; charset=UTF-8');


/* =========================
   ONLY POST REQUEST
========================= */

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    echo "Invalid request";
    exit;

}


/* =========================
   GET FORM DATA
========================= */

$first_name = trim($_POST['first_name'] ?? '');
$email      = trim($_POST['email'] ?? '');
$department = trim($_POST['department'] ?? '');
$experience = trim($_POST['experience'] ?? '');
$location   = trim($_POST['location'] ?? '');
$phone      = trim($_POST['phone'] ?? '');
$message    = trim($_POST['message'] ?? '');


/* =========================
   REQUIRED VALIDATION
========================= */

if (
    $first_name === '' ||
    $email === '' ||
    $department === '' ||
    $experience === '' ||
    $location === '' ||
    $phone === '' ||
    $message === ''
) {

    echo "Please fill all required fields";
    exit;

}


/* =========================
   EMAIL VALIDATION
========================= */

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

    echo "Invalid email address";
    exit;

}


/* =========================
   PHONE VALIDATION
========================= */

if (!preg_match('/^[0-9]{10}$/', $phone)) {

    echo "Phone number must be 10 digits";
    exit;

}


/* =========================
   RESUME CHECK
========================= */

if (!isset($_FILES['resume'])) {

    echo "Resume file is missing";
    exit;

}


if ($_FILES['resume']['error'] !== UPLOAD_ERR_OK) {

    echo "Resume upload failed. Error code: " . $_FILES['resume']['error'];
    exit;

}


/* =========================
   RESUME DETAILS
========================= */

$fileName = $_FILES['resume']['name'];

$fileTmpName = $_FILES['resume']['tmp_name'];

$fileSize = $_FILES['resume']['size'];

$fileExtension = strtolower(
    pathinfo($fileName, PATHINFO_EXTENSION)
);


/* =========================
   ALLOWED EXTENSIONS
========================= */

$allowedExtensions = [
    'pdf',
    'doc',
    'docx'
];


if (!in_array($fileExtension, $allowedExtensions, true)) {

    echo "Only PDF, DOC or DOCX files are allowed";
    exit;

}


/* =========================
   FILE SIZE
   MAX 5 MB
========================= */

if ($fileSize > 5 * 1024 * 1024) {

    echo "Resume file must be less than 5 MB";
    exit;

}


/* =========================
   UPLOAD DIRECTORY
========================= */

$uploadDir = __DIR__ . "/uploads/resumes/";


if (!is_dir($uploadDir)) {

    if (!mkdir($uploadDir, 0755, true)) {

        echo "Unable to create upload folder";
        exit;

    }

}


/* =========================
   UNIQUE FILE NAME
========================= */

$newFileName =
    time() . '_' .
    uniqid('', true) . '.' .
    $fileExtension;


$uploadPath = $uploadDir . $newFileName;


/* =========================
   MOVE FILE
========================= */

if (!move_uploaded_file($fileTmpName, $uploadPath)) {

    echo "Unable to upload resume";
    exit;

}


/* =========================
   DATABASE INSERT
========================= */

$stmt = $conn->prepare("
    INSERT INTO career_job_profile
    (
        first_name,
        email,
        department,
        experience,
        location,
        phone,
        message,
        resume
    )
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)
");


if (!$stmt) {

    /* Delete uploaded file if DB fails */

    if (file_exists($uploadPath)) {
        unlink($uploadPath);
    }

    echo "Database prepare error: " . $conn->error;
    exit;

}


$stmt->bind_param(
    "ssssssss",
    $first_name,
    $email,
    $department,
    $experience,
    $location,
    $phone,
    $message,
    $newFileName
);


if (!$stmt->execute()) {

    /* Delete uploaded file if DB fails */

    if (file_exists($uploadPath)) {
        unlink($uploadPath);
    }

    echo "Database insert error: " . $stmt->error;
    exit;

}


$stmt->close();


/* =========================
   SEND EMAIL
========================= */

$to = "careers@yourdomain.com";

$subject = "New Career Application - " . $first_name;


$emailBody = "
New Career Application

--------------------------------

Name:
$first_name

Email:
$email

Phone:
$phone

Department:
$department

Experience:
$experience

Location:
$location

Message:
$message

Resume:
$newFileName

--------------------------------
";


$headers  = "From: noreply@yourdomain.com\r\n";
$headers .= "Reply-To: " . $email . "\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";


mail(
    $to,
    $subject,
    $emailBody,
    $headers
);


/* =========================
   SUCCESS
========================= */

echo "111";
exit;

?>