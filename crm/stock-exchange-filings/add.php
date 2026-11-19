<?php
ob_start();
require_once __DIR__ . '/../auth.php';
require_once __DIR__ . '/../../dbc.php';
require_once __DIR__ . '/form_helpers.php';

$title = $financialYear = '';
$sortOrder = 0;
$status = 1;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $title = trim($_POST['title'] ?? '');
    $financialYear = trim($_POST['financial_year'] ?? '');
    $sortOrder = max(0, (int)($_POST['sort_order'] ?? 0));
    $status = (($_POST['status'] ?? '1') === '1') ? 1 : 0;

    try {
        if ($title === '') throw new RuntimeException('Title is required.');
        if (!valid_financial_year($financialYear)) throw new RuntimeException('Enter financial year in YYYY-YY format.');
        $pdfFile = upload_pdf($_FILES['pdf_file'] ?? []);
        if (!$pdfFile) throw new RuntimeException('Please upload a PDF document.');

        $stmt = $conn->prepare("INSERT INTO stock_exchange_filings (title, financial_year, pdf_file, sort_order, status) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param('sssii', $title, $financialYear, $pdfFile, $sortOrder, $status);
        $stmt->execute();
        $stmt->close();
        header('Location: index.php?success=1');
        exit;
    } catch (Throwable $ex) {
        $error = $ex->getMessage();
    }
}
$isEdit = false;
$pdfFile = '';
include __DIR__ . '/../includes/header.php';
?>
<div class="container-fluid">
    <h3 class="mb-3">Add Stock Exchange Filing</h3>
    <?php if ($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>
    <form method="post" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
        <div class="mb-3">
    <label class="form-label">Title <span class="text-danger">*</span></label>
    <input type="text" name="title" class="form-control" required maxlength="255" value="<?= e($title) ?>">
</div>
<div class="mb-3">
    <label class="form-label">Financial Year <span class="text-danger">*</span></label>
    <input type="text" name="financial_year" class="form-control" required placeholder="2026-27" pattern="\d{4}-\d{2}" value="<?= e($financialYear) ?>">
    <small class="text-muted">Format: YYYY-YY (example: 2026-27)</small>
</div>
<div class="mb-3">
    <label class="form-label">PDF Document <?= $isEdit ? '' : '<span class="text-danger">*</span>' ?></label>
    <input type="file" name="pdf_file" class="form-control" accept="application/pdf,.pdf" <?= $isEdit ? '' : 'required' ?>>
    <small class="text-muted">PDF only, maximum 15 MB.</small>
    <?php if ($isEdit && $pdfFile): ?><div class="mt-2">Current: <a href="/ajmera/<?= e(ltrim($pdfFile, '/')) ?>" target="_blank" rel="noopener">View PDF</a></div><?php endif; ?>
</div>
<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Sort Order</label>
        <input type="number" name="sort_order" class="form-control" min="0" value="<?= e($sortOrder) ?>">
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Status</label>
        <select name="status" class="form-control">
            <option value="1" <?= (string)$status === '1' ? 'selected' : '' ?>>Active</option>
            <option value="0" <?= (string)$status === '0' ? 'selected' : '' ?>>Inactive</option>
        </select>
    </div>
</div>

        <button type="submit" class="btn btn-primary">Save</button>
        <a href="index.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
