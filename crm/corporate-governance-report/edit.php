<?php
ob_start();

require_once '../auth.php';
require_once '../../dbc.php';

$cgrEditId = (int)($_GET['id'] ?? 0);

if ($cgrEditId <= 0) {
    header('Location: index.php');
    exit;
}

$cgrFetch = $conn->prepare("
    SELECT *
    FROM cgr_reports
    WHERE cgr_report_id = ?
");
$cgrFetch->bind_param('i', $cgrEditId);
$cgrFetch->execute();
$cgrData = $cgrFetch->get_result()->fetch_assoc();
$cgrFetch->close();

if (!$cgrData) {
    header('Location: index.php');
    exit;
}

$cgrEditError = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $cgrYear = trim($_POST['cgr_financial_year'] ?? '');
    $cgrName = trim($_POST['cgr_report_name'] ?? '');
    $cgrOrder = (int)($_POST['cgr_sort_order'] ?? 0);
    $cgrPdfPath = $cgrData['cgr_report_pdf'];
    $cgrNewUpload = '';

    if (!preg_match('/^\d{4}-\d{4}$/', $cgrYear) || $cgrName === '') {
        $cgrEditError = 'Please enter a valid financial year and report name.';
    }

    if (
        $cgrEditError === '' &&
        isset($_FILES['cgr_report_pdf']) &&
        $_FILES['cgr_report_pdf']['error'] !== UPLOAD_ERR_NO_FILE
    ) {

        $cgrUpload = $_FILES['cgr_report_pdf'];

        if ($cgrUpload['error'] !== UPLOAD_ERR_OK) {
            $cgrEditError = 'PDF upload failed.';
        } elseif ($cgrUpload['size'] > 10 * 1024 * 1024) {
            $cgrEditError = 'PDF size must be below 10 MB.';
        } elseif (
            strtolower(pathinfo($cgrUpload['name'], PATHINFO_EXTENSION)) !== 'pdf' ||
            mime_content_type($cgrUpload['tmp_name']) !== 'application/pdf'
        ) {
            $cgrEditError = 'Only PDF files are allowed.';
        } else {

            $cgrUploadDir = dirname(__DIR__, 2) . '/uploads/committee_pdfs/';

            if (!is_dir($cgrUploadDir) && !mkdir($cgrUploadDir, 0755, true)) {
                $cgrEditError = 'Unable to create upload folder.';
            } else {

                $cgrFileName = bin2hex(random_bytes(16)) . '.pdf';
                $cgrDestination = $cgrUploadDir . $cgrFileName;

                if (move_uploaded_file($cgrUpload['tmp_name'], $cgrDestination)) {
                    $cgrPdfPath = 'uploads/committee_pdfs/' . $cgrFileName;
                    $cgrNewUpload = $cgrDestination;
                } else {
                    $cgrEditError = 'Unable to save PDF.';
                }
            }
        }
    }

    if ($cgrEditError === '') {

        $cgrUpdate = $conn->prepare("
            UPDATE cgr_reports
            SET cgr_financial_year = ?,
                cgr_report_name = ?,
                cgr_report_pdf = ?,
                cgr_sort_order = ?
            WHERE cgr_report_id = ?
        ");

        $cgrUpdate->bind_param(
            'sssii',
            $cgrYear,
            $cgrName,
            $cgrPdfPath,
            $cgrOrder,
            $cgrEditId
        );

        if ($cgrUpdate->execute()) {

            if ($cgrNewUpload !== '' && $cgrData['cgr_report_pdf'] !== '') {
                $cgrOldFile = dirname(__DIR__, 2) . '/' . $cgrData['cgr_report_pdf'];

                if (is_file($cgrOldFile)) {
                    @unlink($cgrOldFile);
                }
            }

            header('Location: index.php?updated=1');
            exit;

        } else {

            $cgrEditError = 'Unable to update report.';

            if ($cgrNewUpload !== '' && is_file($cgrNewUpload)) {
                @unlink($cgrNewUpload);
            }
        }

        $cgrUpdate->close();
    }

    $cgrData['cgr_financial_year'] = $cgrYear;
    $cgrData['cgr_report_name'] = $cgrName;
    $cgrData['cgr_report_pdf'] = $cgrPdfPath;
    $cgrData['cgr_sort_order'] = $cgrOrder;
}

include '../includes/header.php';
?>

<div class="container-fluid">

    <h3>Edit Corporate Governance Report</h3>

    <?php if ($cgrEditError !== ''): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($cgrEditError, ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">

        <div class="mb-3">
            <label>Financial Year *</label>
            <input type="text" name="cgr_financial_year"
                   class="form-control" pattern="\d{4}-\d{4}" required
                   value="<?= htmlspecialchars($cgrData['cgr_financial_year'], ENT_QUOTES, 'UTF-8') ?>">
        </div>

        <div class="mb-3">
            <label>Report Name *</label>
            <input type="text" name="cgr_report_name"
                   class="form-control" maxlength="255" required
                   value="<?= htmlspecialchars($cgrData['cgr_report_name'], ENT_QUOTES, 'UTF-8') ?>">
        </div>

        <div class="mb-3">
            <label>Current PDF</label>

            <?php if ($cgrData['cgr_report_pdf']): ?>
                <a href="/ajmera/<?= htmlspecialchars(
                    ltrim($cgrData['cgr_report_pdf'], '/'),
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>" target="_blank" rel="noopener noreferrer">
                    View Current PDF
                </a>
            <?php else: ?>
                No PDF
            <?php endif; ?>
        </div>

        <div class="mb-3">
            <label>Replace PDF (optional)</label>
            <input type="file" name="cgr_report_pdf"
                   class="form-control" accept=".pdf,application/pdf">
        </div>

        <div class="mb-3">
            <label>Display Order</label>
            <input type="number" name="cgr_sort_order"
                   class="form-control"
                   value="<?= htmlspecialchars((string)$cgrData['cgr_sort_order'], ENT_QUOTES, 'UTF-8') ?>">
        </div>

        <button type="submit" class="btn btn-primary">Update Report</button>
        <a href="index.php" class="btn btn-secondary">Cancel</a>

    </form>
</div>

<?php include '../includes/footer.php'; ?>