
<?php

header('Content-Type: application/json; charset=utf-8');

function sendResponse($status, $message, $httpCode = 200) {
    http_response_code($httpCode);

    echo json_encode([
        'status' => $status,
        'message' => $message
    ]);
    exit;
}

try {

    // Database connection
    require_once __DIR__ . '/dbc.php';

    // Form values
    $firstName = trim($_POST['first_name'] ?? '');
    $lastName  = trim($_POST['last_name'] ?? '');
    $email     = trim($_POST['email'] ?? '');
    $phone     = trim($_POST['phone'] ?? '');
    $message   = trim($_POST['message'] ?? '');

    // Request validation
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        sendResponse('error', 'Invalid request method.', 405);
    }

    if (
        $firstName === '' ||
        $lastName === '' ||
        !filter_var($email, FILTER_VALIDATE_EMAIL) ||
        !preg_match('/^[0-9]{10}$/', $phone) ||
        $message === ''
    ) {
        sendResponse('error', 'Please fill all required fields correctly.', 422);
    }

    // Database insert
    $stmt = $conn->prepare("
        INSERT INTO contact_enquiries
        (first_name, last_name, email, phone, message)
        VALUES (?, ?, ?, ?, ?)
    ");

    $stmt->bind_param(
        "sssss",
        $firstName,
        $lastName,
        $email,
        $phone,
        $message
    );

    $stmt->execute();

    $leadId = $conn->insert_id;

    $stmt->close();

    // Farvision CRM API
    $apiUrl = 'https://farvisioncloud.com/sfasync/api/syncleads/website';

    $dumpObjectId = date('YmdHis') . bin2hex(random_bytes(4));

    $payload = [
        'firstName'          => $firstName,
        'lastName'           => $lastName,
        'email'              => $email,
        'mobilePhone'        => '+91' . $phone,
        'comments'           => $message,
        'originFrom'         => 'Website',
        'campaign'           => 'General Enquiries',
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

        $crmError = json_last_error_msg();

    } elseif (!function_exists('curl_init')) {

        $crmError = 'cURL extension not enabled';

    } else {

        $ch = curl_init($apiUrl);

        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $jsonPayload,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json'
            ],
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_TIMEOUT => 20,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2
        ]);

        $crmResponse = curl_exec($ch);
        $crmError = curl_error($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);
    }

    // CRM response check
    if ($crmResponse !== false && $httpCode >= 200 && $httpCode < 300) {

        $responseData = json_decode($crmResponse, true);

        if (
            is_array($responseData) &&
            isset($responseData['status']) &&
            $responseData['status'] === true
        ) {
            $crmSynced = true;
        }
    }

    // Farvision log
    $logData = [
        'Date' => date('Y-m-d H:i:s'),
        'Website Lead ID' => $leadId,
        'Payload' => $payload,
        'HTTP Code' => $httpCode,
        'Response' => $crmResponse,
        'cURL Error' => $crmError,
        'CRM Synced' => $crmSynced
    ];

    file_put_contents(
        __DIR__ . '/farvision-contact-test.log',
        print_r($logData, true) . PHP_EOL .
        "----------------------------------------" . PHP_EOL,
        FILE_APPEND | LOCK_EX
    );

    // Website submission successful
    sendResponse(
        'success',
        'Your enquiry has been submitted successfully.'
    );

} catch (Throwable $e) {

    error_log(
        date('Y-m-d H:i:s') .
        ' Contact Form Error: ' .
        $e->getMessage() .
        ' | File: ' . $e->getFile() .
        ' | Line: ' . $e->getLine()
    );

    sendResponse(
        'error',
        'Server error: ' . $e->getMessage(),
        500
    );
}
