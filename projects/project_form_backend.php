
<?php

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../dbc.php';

function sendResponse($status, $message, $httpCode = 200) {
    http_response_code($httpCode);

    echo json_encode([
        'status' => $status,
        'message' => $message
    ]);

    exit;
}

// Only POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendResponse('error', 'Invalid request method.', 405);
}

// Get form values
$firstName = trim($_POST['first_name'] ?? '');
$lastName  = trim($_POST['last_name'] ?? '');
$email     = trim($_POST['email'] ?? '');
$phone     = trim($_POST['phone'] ?? '');
$project   = trim($_POST['project'] ?? '');
$message   = trim($_POST['message'] ?? '');

// Validate form
if (
    $firstName === '' ||
    $lastName === '' ||
    !filter_var($email, FILTER_VALIDATE_EMAIL) ||
    !preg_match('/^[0-9]{10}$/', $phone) ||
    $project === '' ||
    $message === ''
) {
    sendResponse('error', 'Please fill all required fields correctly.', 422);
}

/*
|--------------------------------------------------------------------------
| 1. SAVE ENQUIRY IN WEBSITE DATABASE
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("
    INSERT INTO project_enquiries
    (first_name, last_name, email, phone, project, message)
    VALUES (?, ?, ?, ?, ?, ?)
");

if (!$stmt) {
    error_log('Enquiry prepare error: ' . $conn->error);
    sendResponse('error', 'Unable to save enquiry. Please try again.', 500);
}

$stmt->bind_param(
    "ssssss",
    $firstName,
    $lastName,
    $email,
    $phone,
    $project,
    $message
);

if (!$stmt->execute()) {
    error_log('Enquiry insert error: ' . $stmt->error);
    $stmt->close();

    sendResponse('error', 'Unable to save enquiry. Please try again.', 500);
}

$leadId = $conn->insert_id;
$stmt->close();

/*
|--------------------------------------------------------------------------
| 2. FARVISION CRM API
|--------------------------------------------------------------------------
*/

$apiUrl = 'https://farvisioncloud.com/sfasync/api/syncleads/website';

$dumpObjectId = date('YmdHis') . bin2hex(random_bytes(4));

$payload = [
    'firstName'          => $firstName,
    'lastName'           => $lastName,
    'email'              => $email,
    'mobilePhone'        => '+91' . $phone,
    'comments'           => $message,
    'originFrom'         => 'Website',
    'product'            => $project,
    'campaign'           => 'Ajmera Corporate Website',
    'isUpdatefromUIDate' => false,
    'isImported'         => true,
    'DumpdataObjectId'   => $dumpObjectId,
    'tenantId'           => 103
];

$crmSynced = false;
$crmResponse = '';
$crmError = '';
$httpCode = 0;

$jsonPayload = json_encode($payload);

if ($jsonPayload === false) {

    $crmError = 'JSON encoding failed: ' . json_last_error_msg();

} elseif (!function_exists('curl_init')) {

    $crmError = 'PHP cURL extension is not enabled.';

} else {

    $ch = curl_init($apiUrl);

    curl_setopt_array($ch, [
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => $jsonPayload,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER     => [
            'Content-Type: application/json'
        ],
        CURLOPT_CONNECTTIMEOUT => 10,
        CURLOPT_TIMEOUT        => 20,
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_SSL_VERIFYHOST => 2
    ]);

    $crmResponse = curl_exec($ch);
    $crmError = curl_error($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    curl_close($ch);
}

// SAVE FARVISION API TEST LOG
$logData = [
    'Date'       => date('Y-m-d H:i:s'),
    'Website Lead ID' => $leadId,
    'DumpdataObjectId' => $dumpObjectId,
    'HTTP Code'  => $httpCode,
    'Response'   => $crmResponse,
    'cURL Error' => $crmError,
    'CRM Synced' => false
];

if (
    $crmResponse !== false &&
    $httpCode >= 200 &&
    $httpCode < 300
) {
    $responseData = json_decode($crmResponse, true);

    if (
        is_array($responseData) &&
        isset($responseData['status']) &&
        $responseData['status'] === true
    ) {
        $crmSynced = true;
    }
}

$logData['CRM Synced'] = $crmSynced;

$logFile = __DIR__ . '/farvision-test.log';

$logEntry = print_r($logData, true);
$logEntry .= PHP_EOL . "----------------------------------------" . PHP_EOL;

$logSaved = file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX);

if ($logSaved === false) {
    error_log('Unable to write Farvision test log. Check folder permissions.');
}

// Log CRM result
if ($crmSynced) {

    error_log('Farvision CRM success. Website Lead ID: ' . $leadId);

} else {

    error_log(
        'Farvision CRM failed. Website Lead ID: ' .
        $leadId .
        ' | HTTP: ' . $httpCode .
        ' | Error: ' . $crmError .
        ' | Response: ' . (is_string($crmResponse) ? $crmResponse : '')
    );
}

/*
|--------------------------------------------------------------------------
| 3. RETURN JSON RESPONSE TO AJAX
|--------------------------------------------------------------------------
*/

// Website database save successful.
// CRM failure will not stop website submission.

sendResponse(
    'success',
    'Your enquiry has been submitted successfully.'
);

?>
