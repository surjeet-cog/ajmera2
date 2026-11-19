<?php
ob_start();

require_once '../auth.php';
require_once '../../dbc.php';

function policeEditEscape($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

$policeEditId = (int)($_GET['id'] ?? 0);
$policeEditError = '';

if ($policeEditId <= 0) {
    header('Location: index.php');
    exit;
}

// Fetch existing document.
$policeEditStmt = $conn->prepare("
    SELECT
        police_document_id,
        police_committee_id,
        police_document_sr_no,
        police_document_name,
        police_document_pdf,
        police_document_sort_order
    FROM police_committee_documents
    WHERE police_document_id = ?
");

$policeEditStmt->bind_param('i', $policeEditId);
$policeEditStmt->execute();

$policeEditResult = $policeEditStmt->get_result();
$policeEditData = $policeEditResult->fetch_assoc();

$policeEditStmt->close();

if (!$policeEditData) {
    header('Location: index.php');
    exit;
}

$policeOldPdfPath = $policeEditData['police_document_pdf'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $policeCommitteeId = (int)($_POST['police_committee_id'] ?? 0);
    $policeSrNo = 1;
    $policeDocumentName = trim($_POST['police_document_name'] ?? '');
    $policeSortOrder = (int)($_POST['police_document_sort_order'] ?? 0);

    $policePdfPath = $policeOldPdfPath;
    $policeNewUploadedFile = '';

    if ($policeCommitteeId <= 0 || $policeSrNo < 1 || $policeDocumentName === '') {
        $policeEditError = 'Please fill all required fields.';
    }

    // Verify committee.
    if ($policeEditError === '') {

        $policeCommitteeCheck = $conn->prepare("
            SELECT police_committee_id
            FROM police_committees
            WHERE police_committee_id = ?
              AND police_committee_status = 1
        ");

        $policeCommitteeCheck->bind_param('i', $policeCommitteeId);
        $policeCommitteeCheck->execute();

        if ($policeCommitteeCheck->get_result()->num_rows === 0) {
            $policeEditError = 'Invalid committee selected.';
        }

        $policeCommitteeCheck->close();
    }

    // Upload replacement PDF if selected.
    if (
        $policeEditError === '' &&
        isset($_FILES['police_document_pdf']) &&
        $_FILES['police_document_pdf']['error'] !== UPLOAD_ERR_NO_FILE
    ) {

        $policeUpload = $_FILES['police_document_pdf'];

        if ($policeUpload['error'] !== UPLOAD_ERR_OK) {
            $policeEditError = 'PDF upload failed.';
        } elseif ($policeUpload['size'] > 10 * 1024 * 1024) {
            $policeEditError = 'PDF must be less than 10 MB.';
        } elseif (
            strtolower(pathinfo($policeUpload['name'], PATHINFO_EXTENSION)) !== 'pdf' ||
            mime_content_type($policeUpload['tmp_name']) !== 'application/pdf'
        ) {
            $policeEditError = 'Only PDF files are allowed.';
        } else {

            $policeUploadDirectory = dirname(__DIR__, 2) . '/uploads/committee_pdfs/';

            if (
                !is_dir($policeUploadDirectory) &&
                !mkdir($policeUploadDirectory, 0755, true)
            ) {
                $policeEditError = 'Unable to create upload folder.';
            } else {

                $policeNewPdfName = bin2hex(random_bytes(16)) . '.pdf';
                $policeDestination = $policeUploadDirectory . $policeNewPdfName;

                if (move_uploaded_file($policeUpload['tmp_name'], $policeDestination)) {
                    $policePdfPath = 'uploads/committee_pdfs/' . $policeNewPdfName;
                    $policeNewUploadedFile = $policeDestination;
                } else {
                    $policeEditError = 'Unable to save PDF.';
                }
            }
        }
    }

    if ($policeEditError === '') {

        $policeUpdateStmt = $conn->prepare("
            UPDATE police_committee_documents
            SET
                police_committee_id = ?,
                police_document_sr_no = ?,
                police_document_name = ?,
                police_document_pdf = ?,
                police_document_sort_order = ?
            WHERE police_document_id = ?
        ");

        $policeUpdateStmt->bind_param(
            'iissii',
            $policeCommitteeId,
            $policeSrNo,
            $policeDocumentName,
            $policePdfPath,
            $policeSortOrder,
            $policeEditId
        );

        if ($policeUpdateStmt->execute()) {

            // Delete old PDF only after successful update.
            if (
                $policeNewUploadedFile !== '' &&
                $policeOldPdfPath !== ''
            ) {
                $policeOldFile = dirname(__DIR__, 2) . '/' . $policeOldPdfPath;

                if (is_file($policeOldFile)) {
                    @unlink($policeOldFile);
                }
            }

            header('Location: index.php?updated=1');
            exit;

        } else {

            $policeEditError = 'Unable to update document.';

            // Remove new upload if update failed.
            if ($policeNewUploadedFile !== '' && is_file($policeNewUploadedFile)) {
                @unlink($policeNewUploadedFile);
            }
        }

        $policeUpdateStmt->close();
    }

    // Keep submitted values on error.
    $policeEditData['police_committee_id'] = $policeCommitteeId;
    $policeEditData['police_document_sr_no'] = $policeSrNo;
    $policeEditData['police_document_name'] = $policeDocumentName;
    $policeEditData['police_document_pdf'] = $policePdfPath;
    $policeEditData['police_document_sort_order'] = $policeSortOrder;
}

// Committee dropdown.
$policeEditCommittees = $conn->query("
    SELECT police_committee_id, police_committee_title
    FROM police_committees
    WHERE police_committee_status = 1
    ORDER BY police_committee_sort_order ASC
");

include '../includes/header.php';
?>

<div class="container-fluid">

    <h3 class="mb-4">Edit Police Document</h3>

    <?php if ($policeEditError !== ''): ?>
        <div class="alert alert-danger">
            <?= policeEditEscape($policeEditError) ?>
        </div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">

        <div class="mb-3">
            <label class="form-label">Committee *</label>

            <select name="police_committee_id" class="form-control" required>

                <?php while ($policeCommittee = $policeEditCommittees->fetch_assoc()): ?>

                    <option value="<?= (int)$policeCommittee['police_committee_id'] ?>"
                        <?= (int)$policeEditData['police_committee_id'] ===
                            (int)$policeCommittee['police_committee_id'] ? 'selected' : '' ?>>

                        <?= policeEditEscape($policeCommittee['police_committee_title']) ?>

                    </option>

                <?php endwhile; ?>

            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Serial Number *</label>

            <input type="number"
                   name="police_document_sr_no"
                   min="1"
                   required
                   class="form-control"
                   value="<?= policeEditEscape($policeEditData['police_document_sr_no']) ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Document Name *</label>

            <input type="text"
                   name="police_document_name"
                   maxlength="255"
                   required
                   class="form-control"
                   value="<?= policeEditEscape($policeEditData['police_document_name']) ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Current PDF</label>

            <?php if (!empty($policeEditData['police_document_pdf'])): ?>

                <a href="/ajmera/<?= policeEditEscape(
                    ltrim($policeEditData['police_document_pdf'], '/')
                ) ?>" target="_blank" rel="noopener noreferrer">
                    View Current PDF
                </a>

            <?php else: ?>

                <span>No PDF uploaded</span>

            <?php endif; ?>
        </div>

        <div class="mb-3">
            <label class="form-label">Replace PDF (optional)</label>

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
                   value="<?= policeEditEscape($policeEditData['police_document_sort_order']) ?>">
        </div>

        <button type="submit" class="btn btn-primary">Update Document</button>
        <a href="index.php" class="btn btn-secondary">Cancel</a>

    </form>

</div>

<?php include '../includes/footer.php'; ?>