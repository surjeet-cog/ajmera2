<?php
ob_start();
require_once __DIR__ . '/../auth.php';
require_once __DIR__ . '/../../dbc.php';
require_once __DIR__ . '/form_helpers.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) { header('Location: index.php'); exit; }

$stmt = $conn->prepare("SELECT id, form_title, pdf_file, sort_order, status FROM investor_forms WHERE id = ? LIMIT 1");
$stmt->bind_param('i', $id);
$stmt->execute();
$report = $stmt->get_result()->fetch_assoc();
$stmt->close();
if (!$report) { $_SESSION['error'] = 'Investor form not found.'; header('Location: index.php'); exit; }

$errors = [];
$formTitle = $report['form_title'];
$sortOrder = (string)$report['sort_order'];
$status = (string)$report['status'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $formTitle = trim($_POST['form_title'] ?? '');
    $sortOrder = filter_var($_POST['sort_order'] ?? 0, FILTER_VALIDATE_INT);
    $status = (($_POST['status'] ?? '0') === '1') ? 1 : 0;

    if ($formTitle === '') $errors[] = 'Please enter form title.';
    if ($sortOrder === false || $sortOrder < 0) $errors[] = 'Sort order must be zero or greater.';

    if (!$errors) {
        try {
            $pdfFile = upload_pdf($_FILES['pdf_file'] ?? [], $report['pdf_file']);
            $stmt = $conn->prepare("UPDATE investor_forms SET form_title = ?, pdf_file = ?, sort_order = ?, status = ? WHERE id = ?");
            $stmt->bind_param('ssiii', $formTitle, $pdfFile, $sortOrder, $status, $id);
            $stmt->execute();
            $stmt->close();
            $_SESSION['success'] = 'Investor form updated successfully.';
            header('Location: index.php');
            exit;
        } catch (Throwable $e) {
            $errors[] = $e->getMessage();
        }
    }
}
require_once __DIR__ . '/../includes/header.php';
?>
<div class="container-fluid py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Edit Investor Form</h3>
        <a href="index.php" class="btn btn-secondary">Back</a>
    </div>
    <?php if ($errors): ?><div class="alert alert-danger"><?php foreach ($errors as $error): ?><div><?= e($error); ?></div><?php endforeach; ?></div><?php endif; ?>
    <div class="card"><div class="card-body">
        <form method="post" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?= e(csrf_token()); ?>">
            <div class="mb-3">
                <label class="form-label">Form Title <span class="text-danger">*</span></label>
                <input type="text" name="form_title" class="form-control" value="<?= e($formTitle); ?>" required maxlength="255">
            </div>
            <div class="mb-3">
                <label class="form-label">Current PDF</label><br>
                <?php if (!empty($report['pdf_file'])): ?>
                    <a href="/ajmera/<?= e(ltrim($report['pdf_file'], '/')); ?>" target="_blank" rel="noopener">View current PDF</a>
                <?php else: ?><span class="text-muted">No PDF uploaded</span><?php endif; ?>
            </div>
            <div class="mb-3">
                <label class="form-label">Replace PDF (optional)</label>
                <input type="file" name="pdf_file" class="form-control" accept="application/pdf,.pdf">
                <small class="text-muted">PDF only, maximum 15 MB. Leave blank to keep the current PDF.</small>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Sort Order</label>
                    <input type="number" name="sort_order" class="form-control" min="0" value="<?= e($sortOrder); ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="1" <?= (string)$status === '1' ? 'selected' : ''; ?>>Active</option>
                        <option value="0" <?= (string)$status === '0' ? 'selected' : ''; ?>>Inactive</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Update Form</button>
            <a href="index.php" class="btn btn-light">Cancel</a>
        </form>
    </div></div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
