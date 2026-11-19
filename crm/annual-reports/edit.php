<?php
ob_start();

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require_once __DIR__ . '/../../dbc.php';

// Apne existing auth.php ka correct path yahin rakho.
require_once '../auth.php';

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

function h($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

$error = '';
$uploadDir = __DIR__ . '/../../uploads/annual-reports/';
$relativeDir = 'uploads/annual-reports/';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
}

if (!$id || $id < 1) {
    http_response_code(400);
    exit('Invalid report ID.');
}

$stmt = $conn->prepare(
    "SELECT id, financial_year, category, title, pdf_file, sort_order, status
     FROM annual_reports WHERE id = ?"
);
$stmt->bind_param('i', $id);
$stmt->execute();
$report = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$report) {
    http_response_code(404);
    exit('Annual report not found.');
}

$financialYear = $report['financial_year'];
$category = $report['category'];
$title = $report['title'];
$sortOrder = (int)$report['sort_order'];
$status = (int)$report['status'];

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
    } else {
        $newPath = $report['pdf_file'];
        $newDestination = null;

        $hasUpload = isset($_FILES['pdf_file']) &&
                     $_FILES['pdf_file']['error'] !== UPLOAD_ERR_NO_FILE;

        if ($hasUpload) {
            if ($_FILES['pdf_file']['error'] !== UPLOAD_ERR_OK) {
                $error = 'PDF upload failed.';
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
                } elseif (!is_dir($uploadDir) &&
                          !mkdir($uploadDir, 0755, true) &&
                          !is_dir($uploadDir)) {
                    $error = 'Unable to create upload folder.';
                } else {
                    $filename = bin2hex(random_bytes(16)) . '.pdf';
                    $newDestination = $uploadDir . $filename;

                    if (move_uploaded_file($tmp, $newDestination)) {
                        $newPath = $relativeDir . $filename;
                    } else {
                        $newDestination = null;
                        $error = 'Unable to save the new PDF.';
                    }
                }
            }
        }

        if ($error === '') {
            $stmt = $conn->prepare(
                "UPDATE annual_reports
                 SET financial_year = ?, category = ?, title = ?,
                     pdf_file = ?, sort_order = ?, status = ?
                 WHERE id = ?"
            );

            if (!$stmt) {
                error_log($conn->error);
                $error = 'Unable to prepare database query.';
            } else {
                $stmt->bind_param(
                    'ssssiii',
                    $financialYear,
                    $category,
                    $title,
                    $newPath,
                    $sortOrder,
                    $status,
                    $id
                );

                if ($stmt->execute()) {
                    $stmt->close();

                    // Remove the previous PDF only after the database update succeeds.
                    if ($newDestination !== null) {
                        $oldPath = $report['pdf_file'];

                        if (strpos($oldPath, $relativeDir) === 0) {
                            $oldFile = __DIR__ . '/../../' . $oldPath;

                            if (is_file($oldFile)) {
                                @unlink($oldFile);
                            }
                        }
                    }

                    header('Location: index.php?msg=updated');
                    exit;
                }

                error_log($stmt->error);
                $stmt->close();

                if ($newDestination !== null && is_file($newDestination)) {
                    @unlink($newDestination);
                }

                $error = 'Unable to update annual report.';
            }
        }
    }
}
?>

<?php include __DIR__ . '/../includes/header.php'; ?>

<div class="container-fluid py-4">
    <h2 class="mb-4">Edit Annual Report</h2>

    <?php if ($error !== ''): ?>
        <div class="alert alert-danger"><?= h($error) ?></div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token"
                       value="<?= h($_SESSION['csrf_token']) ?>">
                <input type="hidden" name="id" value="<?= (int)$report['id'] ?>">

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Financial Year</label>
                        <input type="text" name="financial_year"
                               class="form-control" maxlength="20"
                               value="<?= h($financialYear) ?>" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Category</label>
                        <input type="text" name="category"
                               class="form-control" maxlength="150"
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

                    <div class="col-md-12 mb-3">
                        <label class="form-label">Current PDF</label><br>
                        <a href="/ajmera/<?= h(ltrim($report['pdf_file'], '/')) ?>"
                           target="_blank" rel="noopener noreferrer">View Current PDF</a>
                    </div>

                    <div class="col-md-8 mb-3">
                        <label class="form-label">Replace PDF (optional, max 10 MB)</label>
                        <input type="file" name="pdf_file"
                               class="form-control" accept=".pdf,application/pdf">
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

                <button type="submit" class="btn btn-primary">Update Report</button>
                <a href="index.php" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
