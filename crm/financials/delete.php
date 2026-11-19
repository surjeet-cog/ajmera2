<?php
ob_start();

require_once '../../dbc.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if (!$id || $id < 1) {
    header('Location: index.php?msg=error');
    exit;
}

// Fetch report PDF paths
$findReport = $conn->prepare("
    SELECT q1_pdf, q2_h1_pdf, q3_9m_pdf, q4_fy_pdf
    FROM financial_reports
    WHERE id = ?
    LIMIT 1
");

if (!$findReport) {
    header('Location: index.php?msg=error');
    exit;
}

$findReport->bind_param('i', $id);

if (!$findReport->execute()) {
    $findReport->close();
    header('Location: index.php?msg=error');
    exit;
}

$report = $findReport->get_result()->fetch_assoc();
$findReport->close();

if (!$report) {
    header('Location: index.php?msg=error');
    exit;
}

// Delete database record
$deleteReport = $conn->prepare("
    DELETE FROM financial_reports
    WHERE id = ?
");

if (!$deleteReport) {
    header('Location: index.php?msg=error');
    exit;
}

$deleteReport->bind_param('i', $id);

if (!$deleteReport->execute() || $deleteReport->affected_rows !== 1) {
    $deleteReport->close();
    header('Location: index.php?msg=error');
    exit;
}

$deleteReport->close();

// Delete associated PDFs
$baseDir = realpath(dirname(__DIR__, 2));
$uploadDir = realpath(
    dirname(__DIR__, 2) . '/uploads/financials'
);

if ($baseDir !== false && $uploadDir !== false) {
    $columns = [
        'q1_pdf',
        'q2_h1_pdf',
        'q3_9m_pdf',
        'q4_fy_pdf'
    ];

    foreach ($columns as $column) {
        $path = $report[$column] ?? '';

        if (
            $path !== '' &&
            strpos($path, 'uploads/financials/') === 0 &&
            strpos($path, '..') === false
        ) {
            $realFile = realpath($baseDir . '/' . $path);

            if (
                $realFile !== false &&
                is_file($realFile) &&
                strpos(
                    $realFile,
                    $uploadDir . DIRECTORY_SEPARATOR
                ) === 0
            ) {
                @unlink($realFile);
            }
        }
    }
}

// Return to Financials listing
header('Location: index.php?msg=deleted', true, 303);
exit;
