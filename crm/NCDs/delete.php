<?php
ob_start();
require_once __DIR__ . '/../auth.php';
require_once __DIR__ . '/../../dbc.php';
require_once __DIR__ . '/form_helpers.php';
verify_csrf();
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if ($id) {
    $stmt = $conn->prepare("SELECT pdf_file FROM ncds WHERE id = ? LIMIT 1");
    $stmt->bind_param('i', $id); $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc(); $stmt->close();
    $stmt = $conn->prepare("DELETE FROM ncds WHERE id = ?");
    $stmt->bind_param('i', $id); $stmt->execute(); $stmt->close();
    if ($row && !empty($row['pdf_file'])) ncds_delete_pdf($row['pdf_file']);
}
header('Location: index.php?msg=' . urlencode('Record deleted')); exit;
