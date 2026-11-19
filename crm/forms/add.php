<?php
ob_start();
require_once __DIR__ . '/../auth.php';
require_once __DIR__ . '/../../dbc.php';
require_once __DIR__ . '/form_helpers.php';

$errors = [];
$formTitle = '';
$sortOrder = '0';
$status = '1';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $formTitle = trim($_POST['form_title'] ?? '');
    $sortOrder = filter_var($_POST['sort_order'] ?? 0, FILTER_VALIDATE_INT);
    $status = (($_POST['status'] ?? '0') === '1') ? 1 : 0;

    if ($formTitle === '') $errors[] = 'Please enter form title.';
    if ($sortOrder === false || $sortOrder < 0) $errors[] = 'Sort order must be zero or greater.';

    if (!$errors) {
        try {
            $pdfFile = upload_pdf($_FILES['pdf_file'] ?? []);
            $stmt = $conn->prepare("INSERT INTO investor_forms (form_title, pdf_file, sort_order, status) VALUES (?, ?, ?, ?)");
            $stmt->bind_param('ssii', $formTitle, $pdfFile, $sortOrder, $status);
            $stmt->execute();
            $stmt->close();
            $_SESSION['success'] = 'Investor form added successfully.';
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
        <h3>Add Investor Form</h3>
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
                <label class="form-label">PDF Document</label>
                <input type="file" name="pdf_file" class="form-control" accept="application/pdf,.pdf">
                <small class="text-muted">PDF only, maximum 15 MB.</small>
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
            <button type="submit" class="btn btn-primary">Save Form</button>
            <a href="index.php" class="btn btn-light">Cancel</a>
        </form>
    </div></div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
