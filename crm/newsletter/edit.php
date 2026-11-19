<?php

ob_start();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../auth.php';

require_once __DIR__ . '/../../dbc.php';
require_once __DIR__ . '/form_helpers.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);


$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    header('Location: index.php');
    exit;
}

$stmt = $conn->prepare("SELECT * FROM newsletter_reports WHERE cgr_report_id = ? LIMIT 1");
$stmt->bind_param('i', $id);
$stmt->execute();
$record = $stmt->get_result()->fetch_assoc();
if (!$record) {
    header('Location: index.php');
    exit;
}

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
            $pdf = newsletter_upload_pdf('cgr_report_pdf', $record['cgr_report_pdf'] ?? null);
            $sort = ($sort === false) ? 0 : $sort;

            $update = $conn->prepare("UPDATE newsletter_reports SET
                cgr_report_name = ?, cgr_report_pdf = ?, cgr_financial_year = ?,
                cgr_status = ?, cgr_sort_order = ? WHERE cgr_report_id = ?");
            if (!$update) throw new RuntimeException('Could not prepare database query.');
            $update->bind_param('sssiii', $name, $pdf, $year, $status, $sort, $id);
            $update->execute();

            $_SESSION['flash_message'] = 'Newsletter updated successfully.';
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
    <h3 class="mb-3">Edit Newsletter</h3>
    <?php if ($error): ?><div class="alert alert-danger"><?= newsletter_h($error); ?></div><?php endif; ?>
    <form method="post" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?= newsletter_h($_SESSION['csrf_token']); ?>">
        <div class="mb-3">
            <label class="form-label">Newsletter Title *</label>
            <input type="text" name="cgr_report_name" class="form-control" required value="<?= newsletter_h($_POST['cgr_report_name'] ?? $record['cgr_report_name']); ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Financial Year *</label>
            <input type="text" name="cgr_financial_year" class="form-control" required value="<?= newsletter_h($_POST['cgr_financial_year'] ?? $record['cgr_financial_year']); ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Replace PDF</label>
            <input type="file" name="cgr_report_pdf" class="form-control" accept="application/pdf">
            <?php if (!empty($record['cgr_report_pdf'])): ?>
                <small><a target="_blank" href="../../uploads/newsletter/<?= rawurlencode(basename($record['cgr_report_pdf'])); ?>">View current PDF</a></small>
            <?php endif; ?>
        </div>
        <div class="mb-3">
            <label class="form-label">Sort Order</label>
            <input type="number" name="cgr_sort_order" class="form-control" value="<?= newsletter_h($_POST['cgr_sort_order'] ?? $record['cgr_sort_order']); ?>">
        </div>
        <div class="form-check mb-3">
            <input type="checkbox" name="cgr_status" id="cgr_status" class="form-check-input" <?= (int)$record['cgr_status'] === 1 ? 'checked' : ''; ?>>
            <label for="cgr_status" class="form-check-label">Active</label>
        </div>
        <button class="btn btn-primary" type="submit">Update Newsletter</button>
        <a href="index.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>