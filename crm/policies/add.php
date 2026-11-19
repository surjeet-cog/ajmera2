<?php
ob_start();

require_once '../auth.php';
require_once '../../dbc.php';

function policeAddEscape($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

$policeAddError = '';

$policeCommitteeOptions = $conn->query("
    SELECT police_committee_id, police_committee_title
    FROM police_committees
    WHERE police_committee_status = 1
    ORDER BY police_committee_sort_order ASC
");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $policeCommitteeId = (int)($_POST['police_committee_id'] ?? 0);
    $policeSrNo = 1;
    $policeDocumentName = trim($_POST['police_document_name'] ?? '');
    $policeSortOrder = (int)($_POST['police_document_sort_order'] ?? 0);

    $policePdfPath = '';

    if ($policeCommitteeId <= 0 || $policeSrNo < 1 || $policeDocumentName === '') {
        $policeAddError = 'Please fill all required fields.';
    }

    // Verify selected committee.
    if ($policeAddError === '') {
        $policeCheck = $conn->prepare("
            SELECT police_committee_id
            FROM police_committees
            WHERE police_committee_id = ?
              AND police_committee_status = 1
        ");
        $policeCheck->bind_param('i', $policeCommitteeId);
        $policeCheck->execute();
        $policeCheckResult = $policeCheck->get_result();

        if ($policeCheckResult->num_rows === 0) {
            $policeAddError = 'Invalid committee selected.';
        }

        $policeCheck->close();
    }

    // PDF upload.
    if (
        $policeAddError === '' &&
        isset($_FILES['police_document_pdf']) &&
        $_FILES['police_document_pdf']['error'] !== UPLOAD_ERR_NO_FILE
    ) {

        $policeUpload = $_FILES['police_document_pdf'];

        if ($policeUpload['error'] !== UPLOAD_ERR_OK) {
            $policeAddError = 'PDF upload failed.';
        } elseif ($policeUpload['size'] > 10 * 1024 * 1024) {
            $policeAddError = 'PDF must be less than 10 MB.';
        } elseif (
            strtolower(pathinfo($policeUpload['name'], PATHINFO_EXTENSION)) !== 'pdf' ||
            mime_content_type($policeUpload['tmp_name']) !== 'application/pdf'
        ) {
            $policeAddError = 'Only PDF files are allowed.';
        } else {

            $policeUploadDirectory = dirname(__DIR__, 2) . '/uploads/committee_pdfs/';

            if (
                !is_dir($policeUploadDirectory) &&
                !mkdir($policeUploadDirectory, 0755, true)
            ) {
                $policeAddError = 'Unable to create upload folder.';
            } else {

                $policeNewPdfName = bin2hex(random_bytes(16)) . '.pdf';
                $policeDestination = $policeUploadDirectory . $policeNewPdfName;

                if (move_uploaded_file($policeUpload['tmp_name'], $policeDestination)) {
                    $policePdfPath = 'uploads/committee_pdfs/' . $policeNewPdfName;
                } else {
                    $policeAddError = 'Unable to save PDF.';
                }
            }
        }
    }

    if ($policeAddError === '') {

        $policeInsert = $conn->prepare("
            INSERT INTO police_committee_documents
            (
                police_committee_id,
                police_document_sr_no,
                police_document_name,
                police_document_pdf,
                police_document_sort_order,
                police_document_status
            )
            VALUES (?, ?, ?, ?, ?, 1)
        ");

        $policeInsert->bind_param(
            'iissi',
            $policeCommitteeId,
            $policeSrNo,
            $policeDocumentName,
            $policePdfPath,
            $policeSortOrder
        );

        if ($policeInsert->execute()) {
            header('Location: index.php?added=1');
            exit;
        }

        $policeAddError = 'Unable to add document.';

        // Remove uploaded file if database insert failed.
        if ($policePdfPath !== '') {
            $policeOldFile = dirname(__DIR__, 2) . '/' . $policePdfPath;
            if (is_file($policeOldFile)) {
                @unlink($policeOldFile);
            }
        }

        $policeInsert->close();
    }
}

include '../includes/header.php';
?>

<div class="container-fluid">

    <h3 class="mb-4">Add Police Document</h3>

    <?php if ($policeAddError !== ''): ?>
        <div class="alert alert-danger">
            <?= policeAddEscape($policeAddError) ?>
        </div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">

        <div class="mb-3">
            <label class="form-label">Committee *</label>

            <select name="police_committee_id" class="form-control" required>
                <option value="">Select Committee</option>

                <?php if ($policeCommitteeOptions): ?>
                    <?php while ($policeOption = $policeCommitteeOptions->fetch_assoc()): ?>
                        <option value="<?= (int)$policeOption['police_committee_id'] ?>"
                            <?= (isset($_POST['police_committee_id']) &&
                                (int)$_POST['police_committee_id'] ===
                                (int)$policeOption['police_committee_id']) ? 'selected' : '' ?>>

                            <?= policeAddEscape($policeOption['police_committee_title']) ?>

                        </option>
                    <?php endwhile; ?>
                <?php endif; ?>

            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Serial Number *</label>

           <input type="number"
           name="police_document_sr_no"
           class="form-control"
           min="1"
           required
           value="1">
        </div>

        <div class="mb-3">
            <label class="form-label">Document Name *</label>

            <input type="text"
                   name="police_document_name"
                   class="form-control"
                   maxlength="255"
                   required
                   value="<?= policeAddEscape($_POST['police_document_name'] ?? '') ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Upload PDF</label>

            <input type="file"
                   name="police_document_pdf"
                   class="form-control"
                   accept=".pdf,application/pdf">
        </div>

        <div class="mb-3">
            <label class="form-label">Display Order</label>

            <input type="number"
                   name="police_document_sort_order"
                   class="form-control"
                   value="<?= policeAddEscape($_POST['police_document_sort_order'] ?? '0') ?>">
        </div>

        <button type="submit" class="btn btn-primary">Save Document</button>
        <a href="index.php" class="btn btn-secondary">Cancel</a>

    </form>

</div>

<?php include '../includes/footer.php'; ?>