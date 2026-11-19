<?php
ob_start();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../auth.php';
require_once '../../dbc.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

$uploadDir = '../../uploads/financials/';
$dbUploadDir = 'uploads/financials/';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id || $id < 1) {
    header('Location: index.php');
    exit;
}

$getStmt = mysqli_prepare($conn, "SELECT * FROM financial_reports WHERE id = ?");
mysqli_stmt_bind_param($getStmt, 'i', $id);
mysqli_stmt_execute($getStmt);

$result = mysqli_stmt_get_result($getStmt);
$report = mysqli_fetch_assoc($result);
mysqli_stmt_close($getStmt);

if (!$report) {
    header('Location: index.php');
    exit;
}

$error = '';
$financialYear = $report['financial_year'];
$title = $report['title'];
$status = (int)$report['status'];

$columns = ['q1_pdf', 'q2_h1_pdf', 'q3_9m_pdf', 'q4_fy_pdf'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $financialYear = trim($_POST['financial_year'] ?? '');
    $title = trim($_POST['title'] ?? '');
    $status = isset($_POST['status']) ? (int)$_POST['status'] : 1;

    if (!preg_match('/^\d{4}-\d{4}$/', $financialYear)) {
        $error = 'Please enter a valid financial year, e.g. 2026-2027.';
    } elseif ((int)substr($financialYear, 5, 4) !== (int)substr($financialYear, 0, 4) + 1) {
        $error = 'The financial year range is invalid.';
    } elseif ($title === '') {
        $error = 'Report title is required.';
    } elseif (!in_array($status, [0, 1], true)) {
        $error = 'Invalid status selected.';
    }

    $pdfs = [];
    $newFiles = [];
    $oldFilesToDelete = [];

    foreach ($columns as $column) {
        $pdfs[$column] = $report[$column] ?? null;
    }

    if ($error === '') {
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        foreach ($columns as $column) {
            $file = $_FILES[$column] ?? null;

            if (!$file || $file['error'] === UPLOAD_ERR_NO_FILE) {
                continue;
            }

            if ($file['error'] !== UPLOAD_ERR_OK) {
                $error = 'A PDF upload failed. Please try again.';
                break;
            }

            if ($file['size'] > 5 * 1024 * 1024) {
                $error = 'Each PDF must be 5 MB or smaller.';
                break;
            }

            $finfo = new finfo(FILEINFO_MIME_TYPE);
            if ($finfo->file($file['tmp_name']) !== 'application/pdf') {
                $error = 'Only PDF files are allowed.';
                break;
            }

            $originalName = pathinfo($file['name'], PATHINFO_FILENAME);
            $safeName = preg_replace('/[^A-Za-z0-9_-]/', '-', $originalName);
            $filename = $column . '_' . bin2hex(random_bytes(8)) . '_' . $safeName . '.pdf';

            if (!move_uploaded_file($file['tmp_name'], $uploadDir . $filename)) {
                $error = 'Unable to save uploaded PDF.';
                break;
            }

            $pdfs[$column] = $dbUploadDir . $filename;
            $newFiles[] = $uploadDir . $filename;

            if (!empty($report[$column])) {
                $oldFilesToDelete[] = $report[$column];
            }
        }
    }

    if ($error === '') {
        $sql = "UPDATE financial_reports
                SET financial_year = ?, title = ?,
                    q1_pdf = ?, q2_h1_pdf = ?,
                    q3_9m_pdf = ?, q4_fy_pdf = ?, status = ?
                WHERE id = ?";

        $stmt = mysqli_prepare($conn, $sql);

        if ($stmt) {
            mysqli_stmt_bind_param(
                $stmt,
                'ssssssii',
                $financialYear,
                $title,
                $pdfs['q1_pdf'],
                $pdfs['q2_h1_pdf'],
                $pdfs['q3_9m_pdf'],
                $pdfs['q4_fy_pdf'],
                $status,
                $id
            );

            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_close($stmt);

                // Remove replaced PDFs only after the database update succeeds.
                foreach ($oldFilesToDelete as $oldPath) {
                    if (strpos($oldPath, $dbUploadDir) === 0 &&
                        strpos($oldPath, '..') === false) {
                        $fullPath = '../../' . ltrim($oldPath, '/');
                        if (is_file($fullPath)) {
                            unlink($fullPath);
                        }
                    }
                }

                header('Location: index.php?msg=updated');
                exit;
            }

            mysqli_stmt_close($stmt);
            $error = 'Database error while updating the report.';
        } else {
            $error = 'Unable to prepare database query.';
        }
    }

    if ($error !== '') {
        foreach ($newFiles as $filePath) {
            if (is_file($filePath)) {
                unlink($filePath);
            }
        }
    }
}

include '../includes/header.php';
?>

<div class="container-fluid py-4">
    <h2 class="mb-4">Edit Financial Report</h2>

    <?php if ($error !== '') { ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php } ?>

    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label">Financial Year</label>
            <input type="text" name="financial_year" class="form-control"
                   value="<?= htmlspecialchars($financialYear, ENT_QUOTES, 'UTF-8') ?>"
                   pattern="\d{4}-\d{4}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Report Title</label>
            <input type="text" name="title" class="form-control"
                   maxlength="255"
                   value="<?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?>"
                   required>
        </div>

        <?php
        $labels = [
            'q1_pdf' => 'Q1 PDF',
            'q2_h1_pdf' => 'Q2 & H1 PDF',
            'q3_9m_pdf' => 'Q3 & 9M PDF',
            'q4_fy_pdf' => 'Q4 & Full Year PDF'
        ];

        foreach ($labels as $name => $label) {
        ?>
            <div class="mb-3">
                <label class="form-label"><?= htmlspecialchars($label) ?></label>

                <?php if (!empty($report[$name])) { ?>
                    <p>
                        <a href="../../<?= htmlspecialchars(ltrim($report[$name], '/'), ENT_QUOTES, 'UTF-8') ?>"
                           target="_blank" rel="noopener noreferrer">View Current PDF</a>
                    </p>
                <?php } ?>

                <input type="file" name="<?= $name ?>" class="form-control"
                       accept=".pdf,application/pdf">
                <small class="text-muted">Upload a new PDF only if you want to replace the current file. Maximum 5 MB.</small>
            </div>
        <?php } ?>

        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
                <option value="1" <?= $status === 1 ? 'selected' : '' ?>>Active</option>
                <option value="0" <?= $status === 0 ? 'selected' : '' ?>>Inactive</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Update Report</button>
        <a href="index.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<?php include '../includes/footer.php'; ?>
