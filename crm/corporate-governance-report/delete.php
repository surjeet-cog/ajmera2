<?php
ob_start();

require_once '../auth.php';
require_once '../../dbc.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$cgrDeleteId = (int)($_POST['id'] ?? 0);

if ($cgrDeleteId <= 0) {
    header('Location: index.php');
    exit;
}

$cgrFetch = $conn->prepare("
    SELECT cgr_report_pdf
    FROM cgr_reports
    WHERE cgr_report_id = ?
");
$cgrFetch->bind_param('i', $cgrDeleteId);
$cgrFetch->execute();
$cgrDeleteData = $cgrFetch->get_result()->fetch_assoc();
$cgrFetch->close();

if (!$cgrDeleteData) {
    header('Location: index.php');
    exit;
}

$cgrDelete = $conn->prepare("
    DELETE FROM cgr_reports
    WHERE cgr_report_id = ?
");
$cgrDelete->bind_param('i', $cgrDeleteId);

if ($cgrDelete->execute()) {

    if (!empty($cgrDeleteData['cgr_report_pdf'])) {
        $cgrFile = dirname(__DIR__, 2) . '/' .
            ltrim($cgrDeleteData['cgr_report_pdf'], '/');

        if (is_file($cgrFile)) {
            @unlink($cgrFile);
        }
    }

    header('Location: index.php?deleted=1');
    exit;
}

$cgrDelete->close();

header('Location: index.php');
exit;