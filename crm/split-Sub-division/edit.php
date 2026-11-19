<?php
ob_start();
require_once __DIR__ . '/../auth.php';
require_once __DIR__ . '/../../dbc.php';
require_once __DIR__ . '/form_helpers.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) { header('Location: index.php'); exit; }
$stmt = $conn->prepare("SELECT * FROM split_subdivisions WHERE id = ? LIMIT 1");
$stmt->bind_param('i', $id);
$stmt->execute();
$current = $stmt->get_result()->fetch_assoc();
$stmt->close();
if (!$current) { header('Location: index.php'); exit; }

$title = $current['title'];
$pdfFile = $current['pdf_file'];
$sortOrder = $current['sort_order'];
$status = $current['status'];
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $title = trim($_POST['title'] ?? '');
    $sortOrder = max(0, (int)($_POST['sort_order'] ?? 0));
    $status = (($_POST['status'] ?? '1') === '1') ? 1 : 0;
    try {
        if ($title === '') throw new RuntimeException('Title is required.');
        $pdfFile = upload_pdf($_FILES['pdf_file'] ?? [], $pdfFile);
        $stmt = $conn->prepare("UPDATE split_subdivisions SET title = ?, pdf_file = ?, sort_order = ?, status = ? WHERE id = ?");
        $stmt->bind_param('ssiii', $title, $pdfFile, $sortOrder, $status, $id);
        $stmt->execute();
        $stmt->close();
        header('Location: index.php?success=1');
        exit;
    } catch (Throwable $ex) { $error = $ex->getMessage(); }
}
$isEdit = true;
include __DIR__ . '/../includes/header.php';
?>
<div class="container-fluid">
    <h3 class="mb-3">Edit Split / Sub-division Document</h3>
    <?php if ($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>
    <form method="post" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
        <?php include __DIR__ . '/form.php'; ?>
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="index.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
