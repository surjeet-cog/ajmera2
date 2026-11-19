<?php
ob_start();

require_once '../auth.php';
require_once '../../dbc.php';

$cgrAddError = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $cgrYear = trim($_POST['cgr_financial_year'] ?? '');
    $cgrName = trim($_POST['cgr_report_name'] ?? '');
    $cgrOrder = (int)($_POST['cgr_sort_order'] ?? 0);
    $cgrPdfPath = '';

    if (!preg_match('/^\d{4}-\d{4}$/', $cgrYear) || $cgrName === '') {
        $cgrAddError = 'Please enter a valid financial year and report name.';
    }

    if (
        $cgrAddError === '' &&
        isset($_FILES['cgr_report_pdf']) &&
        $_FILES['cgr_report_pdf']['error'] !== UPLOAD_ERR_NO_FILE
    ) {

        $cgrUpload = $_FILES['cgr_report_pdf'];

        if ($cgrUpload['error'] !== UPLOAD_ERR_OK) {
            $cgrAddError = 'PDF upload failed.';
        } elseif ($cgrUpload['size'] > 10 * 1024 * 1024) {
            $cgrAddError = 'PDF size must be below 10 MB.';
        } elseif (
            strtolower(pathinfo($cgrUpload['name'], PATHINFO_EXTENSION)) !== 'pdf' ||
            mime_content_type($cgrUpload['tmp_name']) !== 'application/pdf'
        ) {
            $cgrAddError = 'Only PDF files are allowed.';
        } else {

            $cgrUploadDir = dirname(__DIR__, 2) . '/uploads/committee_pdfs/';

            if (!is_dir($cgrUploadDir) && !mkdir($cgrUploadDir, 0755, true)) {
                $cgrAddError = 'Unable to create upload folder.';
            } else {

                $cgrFileName = bin2hex(random_bytes(16)) . '.pdf';
                $cgrDestination = $cgrUploadDir . $cgrFileName;

                if (move_uploaded_file($cgrUpload['tmp_name'], $cgrDestination)) {
                    $cgrPdfPath = 'uploads/committee_pdfs/' . $cgrFileName;
                } else {
                    $cgrAddError = 'Unable to save PDF.';
                }
            }
        }
    }

    if ($cgrAddError === '') {

        $cgrInsert = $conn->prepare("
            INSERT INTO cgr_reports
            (
                cgr_financial_year,
                cgr_report_name,
                cgr_report_pdf,
                cgr_sort_order,
                cgr_status
            )
            VALUES (?, ?, ?, ?, 1)
        ");

        $cgrInsert->bind_param('sssi', $cgrYear, $cgrName, $cgrPdfPath, $cgrOrder);

        if ($cgrInsert->execute()) {
            header('Location: index.php?added=1');
            exit;
        }

        $cgrAddError = 'Unable to add report.';

        if ($cgrPdfPath !== '') {
            $cgrFile = dirname(__DIR__, 2) . '/' . $cgrPdfPath;
            if (is_file($cgrFile)) {
                @unlink($cgrFile);
            }
        }

        $cgrInsert->close();
    }
}

include '../includes/header.php';
?>

<div class="container-fluid">

    <h3>Add Corporate Governance Report</h3>

    <?php if ($cgrAddError !== ''): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($cgrAddError, ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">

        <div class="mb-3">
            <label>Financial Year *</label>
            <input type="text" name="cgr_financial_year"
                   class="form-control" placeholder="2026-2027"
                   pattern="\d{4}-\d{4}" required
                   value="<?= htmlspecialchars($_POST['cgr_financial_year'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
        </div>

        <div class="mb-3">
            <label>Report Name *</label>
            <input type="text" name="cgr_report_name"
                   class="form-control" maxlength="255" required
                   value="<?= htmlspecialchars($_POST['cgr_report_name'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
        </div>

        <div class="mb-3">
            <label>Upload PDF</label>
            <input type="file" name="cgr_report_pdf"
                   class="form-control" accept=".pdf,application/pdf">
        </div>

        <div class="mb-3">
            <label>Display Order</label>
            <input type="number" name="cgr_sort_order"
                   class="form-control"
                   value="<?= htmlspecialchars($_POST['cgr_sort_order'] ?? '0', ENT_QUOTES, 'UTF-8') ?>">
        </div>

        <button type="submit" class="btn btn-primary">Save Report</button>
        <a href="index.php" class="btn btn-secondary">Cancel</a>

    </form>
</div>

<?php include '../includes/footer.php'; ?>