<?php
ob_start();

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require_once __DIR__ . '/../../dbc.php';

// Apne existing auth.php ka correct path yahin rakho.
require_once '../auth.php';

function h($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

$error = '';
$financialYear = '';
$category = '';
$title = '';
$sortOrder = 0;
$status = 1;

$uploadDir = __DIR__ . '/../../uploads/annual-reports/';
$relativeDir = 'uploads/annual-reports/';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['csrf_token'] ?? '';
    $financialYear = trim($_POST['financial_year'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $title = trim($_POST['title'] ?? '');
    $sortOrder = filter_var($_POST['sort_order'] ?? 0, FILTER_VALIDATE_INT);
    $status = isset($_POST['status']) ? 1 : 0;

    if (!is_string($token) ||
        empty($_SESSION['csrf_token']) ||
        !hash_equals($_SESSION['csrf_token'], $token)) {
        $error = 'Invalid request. Please refresh the page.';
    } elseif ($financialYear === '' || strlen($financialYear) > 20) {
        $error = 'Please enter a valid financial year.';
    } elseif ($category === '' || strlen($category) > 150) {
        $error = 'Please enter a category (maximum 150 characters).';
    } elseif ($title === '' || strlen($title) > 255) {
        $error = 'Please enter a title (maximum 255 characters).';
    } elseif ($sortOrder === false || $sortOrder < 0) {
        $error = 'Sort order must be zero or a positive number.';
    } elseif (!isset($_FILES['pdf_file']) ||
              $_FILES['pdf_file']['error'] !== UPLOAD_ERR_OK) {
        $error = 'Please select a PDF file.';
    } elseif ($_FILES['pdf_file']['size'] > 10 * 1024 * 1024) {
        $error = 'PDF size must not exceed 10 MB.';
    } else {
        $tmp = $_FILES['pdf_file']['tmp_name'];
        $ext = strtolower(pathinfo($_FILES['pdf_file']['name'], PATHINFO_EXTENSION));
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($tmp);

        if (!is_uploaded_file($tmp) ||
            $ext !== 'pdf' ||
            $mime !== 'application/pdf') {
            $error = 'Please upload a valid PDF file.';
        } else {
            if (!is_dir($uploadDir) &&
                !mkdir($uploadDir, 0755, true) &&
                !is_dir($uploadDir)) {
                $error = 'Unable to create upload folder.';
            } else {
                $filename = bin2hex(random_bytes(16)) . '.pdf';
                $destination = $uploadDir . $filename;

                if (!move_uploaded_file($tmp, $destination)) {
                    $error = 'PDF upload failed. Check folder permissions.';
                } else {
                    $pdfPath = $relativeDir . $filename;

                    $stmt = $conn->prepare(
                        "INSERT INTO annual_reports
                         (financial_year, category, title, pdf_file, sort_order, status)
                         VALUES (?, ?, ?, ?, ?, ?)"
                    );

                    if (!$stmt) {
                        error_log($conn->error);
                        @unlink($destination);
                        $error = 'Unable to prepare database query.';
                    } else {
                        $stmt->bind_param(
                            'ssssii',
                            $financialYear,
                            $category,
                            $title,
                            $pdfPath,
                            $sortOrder,
                            $status
                        );

                        if ($stmt->execute()) {
                            $stmt->close();
                            header('Location: index.php?msg=added');
                            exit;
                        }

                        error_log($stmt->error);
                        $stmt->close();
                        @unlink($destination);
                        $error = 'Unable to save annual report.';
                    }
                }
            }
        }
    }
}
?>

<?php include __DIR__ . '/../includes/header.php'; ?>

<div class="container-fluid py-4">
    <h2 class="mb-4">Add Annual Report</h2>

    <?php if ($error !== ''): ?>
        <div class="alert alert-danger"><?= h($error) ?></div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token"
                       value="<?= h($_SESSION['csrf_token']) ?>">

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Financial Year</label>
                        <input type="text" name="financial_year"
                               class="form-control" maxlength="20"
                               placeholder="e.g. 2025-26"
                               value="<?= h($financialYear) ?>" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Category</label>
                        <input type="text" name="category"
                               class="form-control" maxlength="150"
                               placeholder="e.g. Annual Report"
                               value="<?= h($category) ?>" required>
                    </div>

                    <div class="col-md-8 mb-3">
                        <label class="form-label">Report Title</label>
                        <input type="text" name="title"
                               class="form-control" maxlength="255"
                               value="<?= h($title) ?>" required>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Sort Order</label>
                        <input type="number" name="sort_order"
                               class="form-control" min="0"
                               value="<?= h($sortOrder) ?>" required>
                    </div>

                    <div class="col-md-8 mb-3">
                        <label class="form-label">PDF Document (max 10 MB)</label>
                        <input type="file" name="pdf_file"
                               class="form-control" accept=".pdf,application/pdf" required>
                    </div>

                    <div class="col-md-4 mb-3 d-flex align-items-end">
                        <div class="form-check mb-2">
                            <input type="checkbox" name="status" id="status"
                                   value="1" class="form-check-input"
                                   <?= $status ? 'checked' : '' ?>>
                            <label for="status" class="form-check-label">Active</label>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Save Report</button>
                <a href="index.php" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
