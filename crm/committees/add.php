<?php
// Save as UTF-8 without BOM.
// No whitespace before this tag.

ob_start();

error_reporting(E_ALL);
ini_set('display_errors', 0);

require_once '../auth.php';
require_once '../../dbc.php';

function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

$error = '';

if (empty($_SESSION['committee_add_token'])) {
    $_SESSION['committee_add_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $token = $_POST['token'] ?? '';
    $sessionToken = $_SESSION['committee_add_token'] ?? '';

    $title = trim($_POST['title'] ?? '');
    $sortOrder = (int)($_POST['sort_order'] ?? 0);

    $names = $_POST['director_name'] ?? [];
    $categories = $_POST['category'] ?? [];
    $serials = $_POST['sr_no'] ?? [];

    if (
        !is_string($token) ||
        $sessionToken === '' ||
        !hash_equals($sessionToken, $token)
    ) {
        $error = 'Invalid request. Please refresh the page.';
    }

    if ($title === '') {
        $error = 'Committee title is required.';
    }

    if (
        !is_array($names) ||
        !is_array($categories) ||
        !is_array($serials)
    ) {
        $error = 'Invalid member data.';
    }

    $validMembers = [];

    if ($error === '') {

        foreach ($names as $i => $name) {

            $name = trim((string)$name);
            $category = trim((string)($categories[$i] ?? ''));
            $srNo = (int)($serials[$i] ?? ($i + 1));

            $fileName = $_FILES['member_pdf']['name'][$i] ?? '';

            // Skip fully empty rows.
            if (
                $name === '' &&
                $category === '' &&
                $fileName === ''
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

            // Category and PDF cannot both be selected.
            if ($category !== '' && $fileName !== '') {
                $error = 'Please select either Category or PDF, not both.';
                break;
            }

            if (strlen($category) > 100) {
                $error = 'Category must not exceed 100 characters.';
                break;
            }

            $validMembers[] = [
                'sr_no' => $srNo,
                'name' => $name,
                'category' => $category,
                'index' => $i
            ];
        }
    }

    // PDF upload directory.
    $uploadDirectory = dirname(__DIR__, 2) . '/uploads/committee_pdfs/';

    if (
        $error === '' &&
        !is_dir($uploadDirectory) &&
        !mkdir($uploadDirectory, 0755, true)
    ) {
        $error = 'Unable to create PDF upload folder.';
    }

    $uploadedFiles = [];

    if ($error === '') {

        try {

            $conn->begin_transaction();

            // Insert committee.
            $stmt = $conn->prepare("
                INSERT INTO committees
                    (title, sort_order, status)
                VALUES (?, ?, 1)
            ");

            $stmt->bind_param('si', $title, $sortOrder);
            $stmt->execute();

            $committeeId = $conn->insert_id;
            $stmt->close();

            // Insert members.
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

            foreach ($validMembers as $member) {

                $srNo = $member['sr_no'];
                $name = $member['name'];
                $category = $member['category'];
                $index = $member['index'];

                $pdfFile = '';

                $uploadError = $_FILES['member_pdf']['error'][$index]
                    ?? UPLOAD_ERR_NO_FILE;

                if ($uploadError !== UPLOAD_ERR_NO_FILE) {

                    if ($uploadError !== UPLOAD_ERR_OK) {
                        throw new Exception('PDF upload failed.');
                    }

                    $tmpName = $_FILES['member_pdf']['tmp_name'][$index];
                    $originalName = $_FILES['member_pdf']['name'][$index];
                    $fileSize = $_FILES['member_pdf']['size'][$index];

                    // Maximum file size: 10 MB.
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

                    // Generate a unique filename.
                    $newFileName = bin2hex(random_bytes(16)) . '.pdf';

                    $destination = $uploadDirectory . $newFileName;

                    if (!move_uploaded_file($tmpName, $destination)) {
                        throw new Exception('Unable to save uploaded PDF.');
                    }

                    $uploadedFiles[] = $destination;

                    $pdfFile = 'uploads/committee_pdfs/' . $newFileName;
                }

                $stmt->bind_param(
                    'iissis',
                    $committeeId,
                    $srNo,
                    $name,
                    $category,
                    $srNo,
                    $pdfFile
                );

                $stmt->execute();
            }

            $stmt->close();

            // Save database changes.
            $conn->commit();

            // Redirect to the same Add page.
            unset($_SESSION['committee_add_token']);

            header('Location: add.php?added=1');
            exit;

        } catch (Throwable $ex) {

            $conn->rollback();

            // Remove uploaded files if database save failed.
            foreach ($uploadedFiles as $file) {
                if (is_file($file)) {
                    @unlink($file);
                }
            }

            error_log('Add committee error: ' . $ex->getMessage());

            $error = $ex->getMessage();
        }
    }
}

include '../includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">

    <h1 style="font-size:24px;font-weight:normal;margin:0;">
        Add Committee
    </h1>

    <a href="index.php" class="btn-wp text-decoration-none">
        Back
    </a>

</div>

<?php if ($error !== ''): ?>

    <div class="alert alert-danger">
        <?= e($error) ?>
    </div>

<?php endif; ?>

<?php if (isset($_GET['added'])): ?>

    <div class="alert alert-success">
        Committee added successfully.
    </div>

<?php endif; ?>

<form method="POST"
      action="add.php"
      enctype="multipart/form-data">

    <input type="hidden"
           name="token"
           value="<?= e($_SESSION['committee_add_token']) ?>">

    <!-- Committee Details -->

    <div class="wp-card">

        <h5 class="wp-card-title">Committee Details</h5>

        <div class="row g-3">

            <div class="col-md-8">

                <label class="form-label">
                    Committee Title *
                </label>

                <input type="text"
                       name="title"
                       class="form-control"
                       maxlength="255"
                       required
                       value="<?= e($_POST['title'] ?? '') ?>">

            </div>

            <div class="col-md-4">

                <label class="form-label">
                    Display Order
                </label>

                <input type="number"
                       name="sort_order"
                       class="form-control"
                       min="0"
                       value="<?= e($_POST['sort_order'] ?? '0') ?>">

            </div>

        </div>

    </div>

    <!-- Committee Members -->

    <div class="wp-card">

        <div class="d-flex justify-content-between align-items-center mb-3">

            <h5 class="wp-card-title mb-0">
                Committee Members
            </h5>

            <button type="button"
                    class="btn-wp"
                    onclick="addMember()">

                <i class="fas fa-plus"></i>
                Add Member

            </button>

        </div>

        <div id="members">

            <?php
            $postedNames = $_POST['director_name'] ?? [''];
            $postedCategories = $_POST['category'] ?? [''];
            $postedSerials = $_POST['sr_no'] ?? ['1'];
            ?>

            <?php foreach ($postedNames as $i => $postedName): ?>

                <div class="member-row border rounded p-3 mb-3">

                    <div class="row g-3 align-items-end">

                        <!-- Serial Number -->

                        <div class="col-md-2">

                            <label class="form-label">
                                Sr. No.
                            </label>

                            <input type="number"
                                   name="sr_no[]"
                                   class="form-control"
                                   min="1"
                                   value="<?= e($postedSerials[$i] ?? ($i + 1)) ?>">

                        </div>

                        <!-- Director Name -->

                        <div class="col-md-4">

                            <label class="form-label">
                                Director Name *
                            </label>

                            <input type="text"
                                   name="director_name[]"
                                   class="form-control"
                                   maxlength="255"
                                   required
                                   value="<?= e($postedName) ?>">

                        </div>

                        <!-- Category -->

                        <div class="col-md-3">

                            <label class="form-label">
                                Category
                            </label>

                            <input type="text"
                                   name="category[]"
                                   class="form-control category-input"
                                   maxlength="100"
                                   placeholder="Chairman / Member"
                                   value="<?= e($postedCategories[$i] ?? '') ?>">

                        </div>

                        <!-- PDF Upload -->

                        <div class="col-md-3">

                            <label class="form-label">
                                Upload PDF
                            </label>

                            <input type="file"
                                   name="member_pdf[]"
                                   class="form-control pdf-input"
                                   accept=".pdf,application/pdf">

                        </div>

                    </div>

                </div>

            <?php endforeach; ?>

        </div>

    </div>

    <!-- Buttons -->

    <button type="submit" class="btn-wp">

        <i class="fas fa-save"></i>
        Save Committee

    </button>

    <a href="index.php" class="btn btn-secondary">
        Cancel
    </a>

</form>

<script>

function applyMemberRule(row) {

    const category = row.querySelector('.category-input');
    const pdf = row.querySelector('.pdf-input');

    if (!category || !pdf) {
        return;
    }

    function update() {

        const hasCategory = category.value.trim() !== '';
        const hasPdf = pdf.files.length > 0;

        // Category selected: disable PDF.
        pdf.disabled = hasCategory;

        // PDF selected: disable Category.
        category.disabled = hasPdf;

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

// Initialize existing member rows.
document.querySelectorAll('.member-row').forEach(applyMemberRule);

// Add new member row.
function addMember() {

    const container = document.getElementById('members');

    const row = document.createElement('div');

    const number =
        container.querySelectorAll('.member-row').length + 1;

    row.className = 'member-row border rounded p-3 mb-3';

    row.innerHTML = `
        <div class="row g-3 align-items-end">

            <div class="col-md-2">
                <label class="form-label">Sr. No.</label>

                <input type="number"
                       name="sr_no[]"
                       class="form-control"
                       min="1"
                       value="${number}">
            </div>

            <div class="col-md-4">
                <label class="form-label">Director Name *</label>

                <input type="text"
                       name="director_name[]"
                       class="form-control"
                       maxlength="255"
                       required>
            </div>

            <div class="col-md-3">
                <label class="form-label">Category</label>

                <input type="text"
                       name="category[]"
                       class="form-control category-input"
                       maxlength="100"
                       placeholder="Chairman / Member">
            </div>

            <div class="col-md-3">
                <label class="form-label">Upload PDF</label>

                <input type="file"
                       name="member_pdf[]"
                       class="form-control pdf-input"
                       accept=".pdf,application/pdf">
            </div>

        </div>

        <button type="button"
                class="btn btn-danger mt-3"
                onclick="this.closest('.member-row').remove()">

            <i class="fas fa-trash"></i>
            Remove

        </button>
    `;

    container.appendChild(row);

    applyMemberRule(row);
}

</script>

<?php include '../includes/footer.php'; ?>