<?php

ob_start();


require_once __DIR__ . '/../auth.php';
require_once __DIR__ . '/../../dbc.php';
require_once __DIR__ . '/form_helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}
newsletter_check_csrf();

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    header('Location: index.php');
    exit;
}

$stmt = $conn->prepare("SELECT cgr_report_pdf FROM newsletter_reports WHERE cgr_report_id = ? LIMIT 1");
$stmt->bind_param('i', $id);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();

if ($row) {
    $delete = $conn->prepare("DELETE FROM newsletter_reports WHERE cgr_report_id = ?");
    $delete->bind_param('i', $id);
    $delete->execute();
    newsletter_delete_pdf($row['cgr_report_pdf'] ?? null);
    $_SESSION['flash_message'] = 'Newsletter deleted successfully.';
}

header('Location: index.php');
exit;
?>