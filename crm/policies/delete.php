<?php
ob_start();

require_once '../auth.php';
require_once '../../dbc.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$policeDeleteId = (int)($_POST['police_document_id'] ?? 0);

if ($policeDeleteId <= 0) {
    header('Location: index.php');
    exit;
}

// Get PDF path before deleting the database row.
$policeDeleteFetch = $conn->prepare("
    SELECT police_document_pdf
    FROM police_committee_documents
    WHERE police_document_id = ?
");

$policeDeleteFetch->bind_param('i', $policeDeleteId);
$policeDeleteFetch->execute();

$policeDeleteResult = $policeDeleteFetch->get_result();
$policeDeleteData = $policeDeleteResult->fetch_assoc();

$policeDeleteFetch->close();

if (!$policeDeleteData) {
    header('Location: index.php');
    exit;
}

// Delete document record.
$policeDeleteStmt = $conn->prepare("
    DELETE FROM police_committee_documents
    WHERE police_document_id = ?
");

$policeDeleteStmt->bind_param('i', $policeDeleteId);

if ($policeDeleteStmt->execute()) {

    // Delete associated PDF after successful database deletion.
    if (!empty($policeDeleteData['police_document_pdf'])) {

        $policeDeleteFile = dirname(__DIR__, 2) . '/' .
            ltrim($policeDeleteData['police_document_pdf'], '/');

        if (is_file($policeDeleteFile)) {
            @unlink($policeDeleteFile);
        }
    }

    header('Location: index.php?deleted=1');
    exit;
}

$policeDeleteStmt->close();

header('Location: index.php');
exit;