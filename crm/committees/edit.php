<?php

ob_start();

error_reporting(E_ALL);
ini_set('display_errors', 0);

require_once '../auth.php';
require_once '../../dbc.php';

function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

$error = '';
$id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);

if ($id < 1) {
    header('Location: index.php');
    exit;
}

// ==========================================
// LOAD COMMITTEE
// ==========================================

$stmt = $conn->prepare("
    SELECT id, title, sort_order, status
    FROM committees
    WHERE id = ?
");

$stmt->bind_param('i', $id);
$stmt->execute();

$committee = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$committee) {
    header('Location: index.php');
    exit;
}

// ==========================================
// LOAD MEMBERS
// ==========================================

function getCommitteeMembers($conn, $committeeId) {

    $stmt = $conn->prepare("
        SELECT id, sr_no, director_name, category, pdf_file
        FROM committee_members
        WHERE committee_id = ?
        ORDER BY sort_order ASC, sr_no ASC, id ASC
    ");

    $stmt->bind_param('i', $committeeId);
    $stmt->execute();

    $members = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    $stmt->close();

    return $members;
}

$members = getCommitteeMembers($conn, $id);

// ==========================================
// CSRF TOKEN
// ==========================================

if (empty($_SESSION['committee_edit_token'])) {
    $_SESSION['committee_edit_token'] = bin2hex(random_bytes(32));
}

// ==========================================
// PROCESS FORM
// ==========================================

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $token = $_POST['token'] ?? '';
    $sessionToken = $_SESSION['committee_edit_token'] ?? '';

    if (
        !is_string($token) ||
        $sessionToken === '' ||
        !hash_equals($sessionToken, $token)
    ) {
        $error = 'Invalid request. Please refresh the page.';
    }

    $title = trim($_POST['title'] ?? '');
    $sortOrder = (int)($_POST['sort_order'] ?? 0);
    $status = (int)($_POST['status'] ?? 1);

    if ($title === '') {
        $error = 'Committee title is required.';
    }

    if (!in_array($status, [0, 1], true)) {
        $status = 1;
    }

    $memberIds = $_POST['member_id'] ?? [];
    $serials = $_POST['sr_no'] ?? [];
    $names = $_POST['director_name'] ?? [];
    $categories = $_POST['category'] ?? [];

    if (
        !is_array($memberIds) ||
        !is_array($serials) ||
        !is_array($names) ||
        !is_array($categories)
    ) {
        $error = 'Invalid member data.';
    }

    // ======================================
    // VALIDATE MEMBER ROWS
    // ======================================

    $validMembers = [];

    if ($error === '') {

        foreach ($names as $i => $name) {

            $name = trim((string)$name);
            $category = trim((string)($categories[$i] ?? ''));
            $srNo = (int)($serials[$i] ?? ($i + 1));
            $memberId = (int)($memberIds[$i] ?? 0);

            $fileName = $_FILES['member_pdf']['name'][$i] ?? '';

            // Skip fully empty new rows.
            if (
                $name === '' &&
                $category === '' &&
                $fileName === '' &&
                $memberId === 0
            ) {
                continue;
            }

            if ($name === '') {
                $error = 'Director Name is required.';
                break;
            }

            if ($srNo < 1) {
                $error = 'Serial number must be at least 1.';
                break;
            }

            if ($category !== '' && $fileName !== '') {
                $error = 'Please select either Category or PDF, not both.';
                break;
            }

            if (strlen($category) > 100) {
                $error = 'Category must not exceed 100 characters.';
                break;
            }

            $validMembers[] = [
                'id' => $memberId,
                'sr_no' => $srNo,
                'name' => $name,
                'category' => $category,
                'index' => $i
            ];
        }
    }

    // ======================================
    // UPLOAD DIRECTORY
    // ======================================

    $uploadDirectory = dirname(__DIR__, 2) . '/uploads/committee_pdfs/';

    if (
        $error === '' &&
        !is_dir($uploadDirectory) &&
        !mkdir($uploadDirectory, 0755, true)
    ) {
        $error = 'Unable to create PDF upload folder.';
    }

    $uploadedFiles = [];
    $oldFilesToDelete = [];

    // ======================================
    // SAVE DATA
    // ======================================

    if ($error === '') {

        try {

            $conn->begin_transaction();

            // Update committee details.
            $stmt = $conn->prepare("
                UPDATE committees
                SET title = ?, sort_order = ?, status = ?
                WHERE id = ?
            ");

            $stmt->bind_param(
                'siii',
                $title,
                $sortOrder,
                $status,
                $id
            );

            $stmt->execute();
            $stmt->close();

            // Get current member records.
            $existingMembers = getCommitteeMembers($conn, $id);

            $existingIds = array_map(
                'intval',
                array_column($existingMembers, 'id')
            );

            $submittedIds = [];

            foreach ($validMembers as $member) {

                $memberId = $member['id'];
                $srNo = $member['sr_no'];
                $name = $member['name'];
                $category = $member['category'];
                $index = $member['index'];

                $pdfFile = '';

                // Existing member: preserve current PDF.
                if ($memberId > 0) {

                    if (!in_array($memberId, $existingIds, true)) {
                        throw new Exception('Invalid member ID.');
                    }

                    foreach ($existingMembers as $existing) {
                        if ((int)$existing['id'] === $memberId) {
                            $pdfFile = $existing['pdf_file'] ?? '';
                            break;
                        }
                    }

                    $submittedIds[] = $memberId;
                }

                // ==================================
                // CATEGORY SELECTED: REMOVE OLD PDF
                // ==================================

                if ($category !== '' && $pdfFile !== '') {
                    $oldFilesToDelete[] = $pdfFile;
                    $pdfFile = '';
                }

                // ==================================
                // NEW PDF UPLOAD
                // ==================================

                $uploadError = $_FILES['member_pdf']['error'][$index]
                    ?? UPLOAD_ERR_NO_FILE;

                if ($uploadError !== UPLOAD_ERR_NO_FILE) {

                    if ($uploadError !== UPLOAD_ERR_OK) {
                        throw new Exception('PDF upload failed.');
                    }

                    $tmpName = $_FILES['member_pdf']['tmp_name'][$index];
                    $originalName = $_FILES['member_pdf']['name'][$index];
                    $fileSize = $_FILES['member_pdf']['size'][$index];

                    if ($fileSize > 10 * 1024 * 1024) {
                        throw new Exception('PDF size must not exceed 10 MB.');
                    }

                    $extension = strtolower(
                        pathinfo($originalName, PATHINFO_EXTENSION)
                    );

                    $mimeType = mime_content_type($tmpName);

                    if (
                        $extension !== 'pdf' ||
                        $mimeType !== 'application/pdf'
                    ) {
                        throw new Exception('Only PDF files are allowed.');
                    }

                    $newFileName = bin2hex(random_bytes(16)) . '.pdf';
                    $destination = $uploadDirectory . $newFileName;

                    if (!move_uploaded_file($tmpName, $destination)) {
                        throw new Exception('Unable to save uploaded PDF.');
                    }

                    $uploadedFiles[] = $destination;

                    // New PDF replaces existing PDF.
                    if ($pdfFile !== '') {
                        $oldFilesToDelete[] = $pdfFile;
                    }

                    $pdfFile = 'uploads/committee_pdfs/' . $newFileName;
                }

                // ==================================
                // UPDATE EXISTING MEMBER
                // ==================================

                if ($memberId > 0) {

                    $stmt = $conn->prepare("
                        UPDATE committee_members
                        SET
                            sr_no = ?,
                            director_name = ?,
                            category = ?,
                            sort_order = ?,
                            pdf_file = ?
                        WHERE id = ? AND committee_id = ?
                    ");

                    $stmt->bind_param(
                        'issisii',
                        $srNo,
                        $name,
                        $category,
                        $srNo,
                        $pdfFile,
                        $memberId,
                        $id
                    );

                } else {

                    // ==================================
                    // INSERT NEW MEMBER
                    // ==================================

                    $stmt = $conn->prepare("
                        INSERT INTO committee_members
                        (
                            committee_id,
                            sr_no,
                            director_name,
                            category,
                            sort_order,
                            status,
                            pdf_file
                        )
                        VALUES (?, ?, ?, ?, ?, 1, ?)
                    ");

                    $stmt->bind_param(
                        'iissis',
                        $id,
                        $srNo,
                        $name,
                        $category,
                        $srNo,
                        $pdfFile
                    );
                }

                $stmt->execute();
                $stmt->close();
            }

            // ======================================
            // DELETE MEMBERS REMOVED FROM FORM
            // ======================================

            foreach ($existingMembers as $existing) {

                $existingId = (int)$existing['id'];

                if (!in_array($existingId, $submittedIds, true)) {

                    $stmt = $conn->prepare("
                        DELETE FROM committee_members
                        WHERE id = ? AND committee_id = ?
                    ");

                    $stmt->bind_param('ii', $existingId, $id);
                    $stmt->execute();
                    $stmt->close();

                    if (!empty($existing['pdf_file'])) {
                        $oldFilesToDelete[] = $existing['pdf_file'];
                    }
                }
            }

            $conn->commit();

            // Delete replaced/removed PDFs after successful commit.
            foreach ($oldFilesToDelete as $oldFile) {

                $oldPath = dirname(__DIR__, 2) . '/' . ltrim($oldFile, '/');

                if (is_file($oldPath)) {
                    @unlink($oldPath);
                }
            }

            unset($_SESSION['committee_edit_token']);

            header('Location: index.php?updated=1');
            exit;

        } catch (Throwable $ex) {

            $conn->rollback();

            // Remove newly uploaded files if database save failed.
            foreach ($uploadedFiles as $file) {
                if (is_file($file)) {
                    @unlink($file);
                }
            }

            error_log('Edit committee error: ' . $ex->getMessage());

            $error = $ex->getMessage();
        }
    }

    // Reload committee values for display after failed POST.
    $committee['title'] = $title;
    $committee['sort_order'] = $sortOrder;
    $committee['status'] = $status;

    // Reload saved members.
    $members = getCommitteeMembers($conn, $id);
}

// ==========================================
// HEADER
// ==========================================

include '../includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 style="font-size:24px;font-weight:normal;margin:0;">
        Edit Committee
    </h1>
    <a href="index.php" class="btn-wp text-decoration-none">Back</a>
</div>

<?php if ($error !== ''): ?>
    <div class="alert alert-danger"><?= e($error) ?></div>
<?php endif; ?>

<form method="POST"
      action="edit.php?id=<?= $id ?>"
      enctype="multipart/form-data">

    <input type="hidden" name="id" value="<?= $id ?>">

    <input type="hidden" name="token"
           value="<?= e($_SESSION['committee_edit_token']) ?>">

    <div class="wp-card">
        <h5 class="wp-card-title">Committee Details</h5>

        <div class="row g-3">
            <div class="col-md-8">
                <label class="form-label">Committee Title *</label>
                <input type="text" name="title" class="form-control"
                       maxlength="255" required
                       value="<?= e($committee['title']) ?>">
            </div>

            <div class="col-md-2">
                <label class="form-label">Display Order</label>
                <input type="number" name="sort_order" class="form-control"
                       min="0" value="<?= e($committee['sort_order']) ?>">
            </div>

            <div class="col-md-2">
                <label class="form-label">Status</label>
                <select name="status" class="form-control">
                    <option value="1"
                        <?= (int)$committee['status'] === 1 ? 'selected' : '' ?>>
                        Active
                    </option>
                    <option value="0"
                        <?= (int)$committee['status'] === 0 ? 'selected' : '' ?>>
                        Inactive
                    </option>
                </select>
            </div>
        </div>
    </div>

    <div class="wp-card">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="wp-card-title mb-0">Committee Members</h5>
            <button type="button" class="btn-wp" onclick="addMember()">
                <i class="fas fa-plus"></i> Add Member
            </button>
        </div>

        <div id="members">

            <?php foreach ($members as $member): ?>

                <div class="member-row border rounded p-3 mb-3">

                    <input type="hidden" name="member_id[]"
                           value="<?= (int)$member['id'] ?>">

                    <div class="row g-3 align-items-end">

                        <div class="col-md-2">
                            <label class="form-label">Sr. No.</label>
                            <input type="number" name="sr_no[]" class="form-control"
                                   min="1" value="<?= e($member['sr_no']) ?>">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Director Name *</label>
                            <input type="text" name="director_name[]"
                                   class="form-control" maxlength="255" required
                                   value="<?= e($member['director_name']) ?>">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Category</label>
                            <input type="text" name="category[]"
                                   class="form-control category-input"
                                   maxlength="100"
                                   placeholder="Chairman / Member"
                                   value="<?= e($member['category']) ?>">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Upload PDF</label>
                            <input type="file" name="member_pdf[]"
                                   class="form-control pdf-input"
                                   accept=".pdf,application/pdf">

                            <?php if (!empty($member['pdf_file'])): ?>
                                <small class="d-block mt-2">
                                    Existing PDF:
                                    <a href="/ajmera/<?= e(ltrim($member['pdf_file'], '/')) ?>"
                                       target="_blank" rel="noopener noreferrer">
                                        View PDF
                                    </a>
                                </small>
                            <?php endif; ?>
                        </div>

                    </div>

                    <button type="button" class="btn btn-danger mt-3"
                            onclick="this.closest('.member-row').remove()">
                        <i class="fas fa-trash"></i> Remove Member
                    </button>

                </div>

            <?php endforeach; ?>

        </div>
    </div>

    <button type="submit" class="btn-wp">
        <i class="fas fa-save"></i> Update Committee
    </button>

    <a href="index.php" class="btn btn-secondary">Cancel</a>

</form>

<script>
function applyMemberRule(row) {

    const category = row.querySelector('.category-input');
    const pdf = row.querySelector('.pdf-input');

    if (!category || !pdf) return;

    function update() {

        const hasCategory = category.value.trim() !== '';
        const hasPdf = pdf.files.length > 0;

        category.disabled = hasPdf;
        pdf.disabled = hasCategory;

        if (hasCategory) {
            pdf.value = '';
        }

        if (hasPdf) {
            category.value = '';
        }
    }

    category.addEventListener('input', update);
    pdf.addEventListener('change', update);

    update();
}

document.querySelectorAll('.member-row').forEach(applyMemberRule);

function addMember() {

    const container = document.getElementById('members');
    const row = document.createElement('div');

    const number = container.querySelectorAll('.member-row').length + 1;

    row.className = 'member-row border rounded p-3 mb-3';

    row.innerHTML = `
        <input type="hidden" name="member_id[]" value="0">

        <div class="row g-3 align-items-end">

            <div class="col-md-2">
                <label class="form-label">Sr. No.</label>
                <input type="number" name="sr_no[]" class="form-control"
                       min="1" value="${number}">
            </div>

            <div class="col-md-4">
                <label class="form-label">Director Name *</label>
                <input type="text" name="director_name[]" class="form-control"
                       maxlength="255" required>
            </div>

            <div class="col-md-3">
                <label class="form-label">Category</label>
                <input type="text" name="category[]"
                       class="form-control category-input"
                       maxlength="100" placeholder="Chairman / Member">
            </div>

            <div class="col-md-3">
                <label class="form-label">Upload PDF</label>
                <input type="file" name="member_pdf[]"
                       class="form-control pdf-input"
                       accept=".pdf,application/pdf">
            </div>

        </div>

        <button type="button" class="btn btn-danger mt-3"
                onclick="this.closest('.member-row').remove()">
            <i class="fas fa-trash"></i> Remove Member
        </button>
    `;

    container.appendChild(row);
    applyMemberRule(row);
}
</script>

<?php include '../includes/footer.php'; ?>