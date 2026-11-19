<?php
ob_start();
require_once __DIR__ . '/../auth.php';
require_once __DIR__ . '/../../dbc.php';
require_once __DIR__ . '/form_helpers.php';
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) { header('Location: index.php'); exit; }
$stmt = $conn->prepare("SELECT id, title, pdf_file, sort_order, status FROM ncds WHERE id = ? LIMIT 1");
$stmt->bind_param('i', $id); $stmt->execute();
$record = $stmt->get_result()->fetch_assoc(); $stmt->close();
if (!$record) { header('Location: index.php'); exit; }
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    try {
        $title = trim($_POST['title'] ?? '');
        if ($title === '') throw new RuntimeException('Title is required.');
        $oldPdf = $record['pdf_file'];
        $pdf = ncds_upload_pdf('pdf_file', $oldPdf);
        $sort = (int)($_POST['sort_order'] ?? 0);
        $status = isset($_POST['status']) ? 1 : 0;
        $stmt = $conn->prepare("UPDATE ncds SET title = ?, pdf_file = ?, sort_order = ?, status = ? WHERE id = ?");
        $stmt->bind_param('ssiii', $title, $pdf, $sort, $status, $id);
        $stmt->execute(); $stmt->close();
        if ($pdf !== $oldPdf) ncds_delete_pdf($oldPdf);
        header('Location: index.php?msg=' . urlencode('Record updated successfully')); exit;
    } catch (Throwable $ex) { $error = $ex->getMessage(); }
}
include __DIR__ . '/../includes/header.php';
?>
<div class="container-fluid"><h2>Edit NCD Document</h2>
<?php if ($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>
<?php include __DIR__ . '/form.php'; ?></div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
