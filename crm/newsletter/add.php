<?php

ob_start();
require_once __DIR__ . '/../auth.php';
require_once __DIR__ . '/../../dbc.php';
require_once __DIR__ . '/form_helpers.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    newsletter_check_csrf();

    $name = trim($_POST['cgr_report_name'] ?? '');
    $year = trim($_POST['cgr_financial_year'] ?? '');
    $sort = filter_var($_POST['cgr_sort_order'] ?? 0, FILTER_VALIDATE_INT);
    $status = isset($_POST['cgr_status']) ? 1 : 0;

    if ($name === '' || $year === '') {
        $error = 'Newsletter title and financial year are required.';
    } else {
        try {
            $pdf = newsletter_upload_pdf('cgr_report_pdf');
            $sort = ($sort === false) ? 0 : $sort;

            $stmt = $conn->prepare("INSERT INTO newsletter_reports
                (cgr_report_name, cgr_report_pdf, cgr_financial_year, cgr_status, cgr_sort_order)
                VALUES (?, ?, ?, ?, ?)");
            if (!$stmt) throw new RuntimeException('Could not prepare database query.');
            $stmt->bind_param('sssii', $name, $pdf, $year, $status, $sort);
            $stmt->execute();

            $_SESSION['flash_message'] = 'Newsletter added successfully.';
            header('Location: index.php');
            exit;
        } catch (Throwable $e) {
            $error = $e->getMessage();
        }
    }
}
?>
<?php include __DIR__ . '/../includes/header.php'; ?>
<div class="container-fluid">
    <h3 class="mb-3">Add Newsletter</h3>
    <?php if ($error): ?><div class="alert alert-danger"><?= newsletter_h($error); ?></div><?php endif; ?>
    <form method="post" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?= newsletter_h($_SESSION['csrf_token']); ?>">
        <div class="mb-3">
            <label class="form-label">Newsletter Title *</label>
            <input type="text" name="cgr_report_name" class="form-control" required value="<?= newsletter_h($_POST['cgr_report_name'] ?? ''); ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Financial Year *</label>
            <input type="text" name="cgr_financial_year" class="form-control" placeholder="2024-25" required value="<?= newsletter_h($_POST['cgr_financial_year'] ?? ''); ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Newsletter PDF</label>
            <input type="file" name="cgr_report_pdf" class="form-control" accept="application/pdf">
            <small class="text-muted">PDF only, maximum 15 MB.</small>
        </div>
        <div class="mb-3">
            <label class="form-label">Sort Order</label>
            <input type="number" name="cgr_sort_order" class="form-control" value="<?= newsletter_h($_POST['cgr_sort_order'] ?? '0'); ?>">
        </div>
        <div class="form-check mb-3">
            <input type="checkbox" name="cgr_status" id="cgr_status" class="form-check-input" checked>
            <label for="cgr_status" class="form-check-label">Active</label>
        </div>
        <button class="btn btn-primary" type="submit">Save Newsletter</button>
        <a href="index.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>