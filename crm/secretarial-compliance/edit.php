<?php
ob_start();
require_once __DIR__ . '/../auth.php';
require_once __DIR__ . '/../../dbc.php';
require_once __DIR__ . '/form_helpers.php';
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) { header('Location: index.php'); exit; }
$stmt = $conn->prepare("SELECT id, financial_year, title, pdf_file, status FROM secretarial_compliance_reports WHERE id = ? LIMIT 1");
$stmt->bind_param('i', $id); $stmt->execute();
$record = $stmt->get_result()->fetch_assoc(); $stmt->close();
if (!$record) { header('Location: index.php'); exit; }
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    try {
        $year = trim($_POST['financial_year'] ?? '');
        $title = trim($_POST['title'] ?? '');
        if ($year === '' || $title === '') throw new RuntimeException('Financial year and title are required.');
        $oldPdf = $record['pdf_file'];
        $pdf = scr_upload_pdf('pdf_file', $oldPdf);
        $status = isset($_POST['status']) ? 1 : 0;
        $stmt = $conn->prepare("UPDATE secretarial_compliance_reports SET financial_year = ?, title = ?, pdf_file = ?, status = ? WHERE id = ?");
        $stmt->bind_param('sssii', $year, $title, $pdf, $status, $id);
        $stmt->execute(); $stmt->close();
        if ($pdf !== $oldPdf) scr_delete_pdf($oldPdf);
        header('Location: index.php?msg=' . urlencode('Report updated successfully')); exit;
    } catch (Throwable $ex) { $error = $ex->getMessage(); }
}
include __DIR__ . '/../includes/header.php';
?>
<div class="container-fluid"><h2>Edit Secretarial Compliance Report</h2>
<?php if ($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>
<?php include __DIR__ . '/form.php'; ?></div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
