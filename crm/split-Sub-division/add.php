<?php
ob_start();
require_once __DIR__ . '/../auth.php';
require_once __DIR__ . '/../../dbc.php';
require_once __DIR__ . '/form_helpers.php';

$title = '';
$sortOrder = 0;
$status = 1;
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $title = trim($_POST['title'] ?? '');
    $sortOrder = max(0, (int)($_POST['sort_order'] ?? 0));
    $status = (($_POST['status'] ?? '1') === '1') ? 1 : 0;
    try {
        if ($title === '') throw new RuntimeException('Title is required.');
        $pdfFile = upload_pdf($_FILES['pdf_file'] ?? []);
        if (!$pdfFile) throw new RuntimeException('Please upload a PDF document.');
        $stmt = $conn->prepare("INSERT INTO split_subdivisions (title, pdf_file, sort_order, status) VALUES (?, ?, ?, ?)");
        $stmt->bind_param('ssii', $title, $pdfFile, $sortOrder, $status);
        $stmt->execute();
        $stmt->close();
        header('Location: index.php?success=1');
        exit;
    } catch (Throwable $ex) { $error = $ex->getMessage(); }
}
$isEdit = false;
$pdfFile = '';
include __DIR__ . '/../includes/header.php';
?>
<div class="container-fluid">
    <h3 class="mb-3">Add Split / Sub-division Document</h3>
    <?php if ($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>
    <form method="post" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
        <?php include __DIR__ . '/form.php'; ?>
        <button type="submit" class="btn btn-primary">Save</button>
        <a href="index.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
