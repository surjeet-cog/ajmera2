<?php
ob_start();
require_once __DIR__ . '/../auth.php';
require_once __DIR__ . '/../../dbc.php';
require_once __DIR__ . '/form_helpers.php';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    try {
        $year = trim($_POST['financial_year'] ?? '');
        $title = trim($_POST['title'] ?? '');
        if ($year === '' || $title === '') throw new RuntimeException('Financial year and title are required.');
        $pdf = scr_upload_pdf('pdf_file');
        $status = isset($_POST['status']) ? 1 : 0;
        $stmt = $conn->prepare("INSERT INTO secretarial_compliance_reports (financial_year, title, pdf_file, status) VALUES (?, ?, ?, ?)");
        $stmt->bind_param('sssi', $year, $title, $pdf, $status);
        $stmt->execute(); $stmt->close();
        header('Location: index.php?msg=' . urlencode('Report added successfully')); exit;
    } catch (Throwable $ex) { $error = $ex->getMessage(); }
}
include __DIR__ . '/../includes/header.php';
?>
<div class="container-fluid"><h2>Add Secretarial Compliance Report</h2>
<?php if ($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>
<?php include __DIR__ . '/form.php'; ?></div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
