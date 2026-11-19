<?php
ob_start();
require_once __DIR__ . '/../auth.php';
require_once __DIR__ . '/../../dbc.php';
require_once __DIR__ . '/form_helpers.php';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    try {
        $title = trim($_POST['title'] ?? '');
        if ($title === '') throw new RuntimeException('Title is required.');
        $pdf = ncds_upload_pdf('pdf_file');
        $sort = (int)($_POST['sort_order'] ?? 0);
        $status = isset($_POST['status']) ? 1 : 0;
        $stmt = $conn->prepare("INSERT INTO ncds (title, pdf_file, sort_order, status) VALUES (?, ?, ?, ?)");
        $stmt->bind_param('ssii', $title, $pdf, $sort, $status);
        $stmt->execute(); $stmt->close();
        header('Location: index.php?msg=' . urlencode('Record added successfully')); exit;
    } catch (Throwable $ex) { $error = $ex->getMessage(); }
}
include __DIR__ . '/../includes/header.php';
?>
<div class="container-fluid"><h2>Add NCD Document</h2>
<?php if ($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>
<?php include __DIR__ . '/form.php'; ?></div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
