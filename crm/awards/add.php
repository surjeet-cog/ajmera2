<?php

ob_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require_once '../auth.php';
require_once '../../dbc.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $title = trim($_POST['title'] ?? '');
    $year = (int) ($_POST['year'] ?? 0);
    $description = trim($_POST['description'] ?? '');
    $status = isset($_POST['status']) ? 1 : 0;
    $imagePath = '';

    if ($title === '' || $year < 1900 || $year > 2200) {
        $error = 'Please enter a valid title and year.';
    }

    // Upload image
    if ($error === '' && isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {

        if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            $error = 'Image upload failed.';
        } elseif ($_FILES['image']['size'] > 5 * 1024 * 1024) {
            $error = 'Image size must be less than 5 MB.';
        } else {

            $tmpFile = $_FILES['image']['tmp_name'];
            $imageInfo = @getimagesize($tmpFile);

            if ($imageInfo === false) {
                $error = 'Please upload a valid image.';
            } else {

                $mime = $imageInfo['mime'] ?? '';

                $allowedTypes = [
                    'image/jpeg' => 'jpg',
                    'image/png'  => 'png',
                    'image/webp' => 'webp',
                    'image/gif'  => 'gif'
                ];

                if (!isset($allowedTypes[$mime])) {
                    $error = 'Only JPG, PNG, WEBP and GIF images are allowed.';
                } else {

                    $uploadDir = dirname(__DIR__, 2) . '/uploads/awards/';

                    if (!is_dir($uploadDir) &&
                        !mkdir($uploadDir, 0755, true) &&
                        !is_dir($uploadDir)) {
                        $error = 'Unable to create upload directory.';
                    } else {

                        $fileName = 'award_' . bin2hex(random_bytes(12))
                            . '.' . $allowedTypes[$mime];

                        if (move_uploaded_file($tmpFile, $uploadDir . $fileName)) {
                            $imagePath = 'uploads/awards/' . $fileName;
                        } else {
                            $error = 'Unable to save uploaded image.';
                        }
                    }
                }
            }
        }
    }

    if ($error === '') {

        $stmt = $conn->prepare("
            INSERT INTO awards
                (title, year, image, description, status)
            VALUES (?, ?, ?, ?, ?)
        ");

        $stmt->bind_param(
            'sissi',
            $title,
            $year,
            $imagePath,
            $description,
            $status
        );

        if ($stmt->execute()) {
    $stmt->close();

    header("Location: index.php?msg=added");
    exit;
}
        

        $stmt->close();

        if ($imagePath !== '') {
            $uploadedFile = dirname(__DIR__, 2) . '/' . $imagePath;
            if (is_file($uploadedFile)) {
                unlink($uploadedFile);
            }
        }

        $error = 'Unable to add award. Please try again.';
    }
}

include '../includes/header.php';
?>

<div class="container-fluid py-4">

    <h3 class="mb-4">Add Award</h3>

    <?php if ($error !== ''): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">

        <div class="mb-3">
            <label class="form-label">Award Title</label>
            <input
                type="text"
                name="title"
                class="form-control"
                required
                value="<?= htmlspecialchars($_POST['title'] ?? '') ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Year</label>
            <input
                type="number"
                name="year"
                class="form-control"
                min="1900"
                max="2200"
                required
                value="<?= htmlspecialchars($_POST['year'] ?? date('Y')) ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Award Image</label>
            <input
                type="file"
                name="image"
                class="form-control"
                accept=".jpg,.jpeg,.png,.webp,.gif,image/jpeg,image/png,image/webp,image/gif">
            <small class="text-muted">Maximum size: 5 MB.</small>
        </div>

        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea
                name="description"
                class="form-control"
                rows="5"><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
        </div>

        <div class="form-check mb-3">
            <input
                type="checkbox"
                name="status"
                id="status"
                class="form-check-input"
                checked>
            <label for="status" class="form-check-label">Active</label>
        </div>

        <button type="submit" class="btn btn-primary">Save Award</button>
        <a href="index.php" class="btn btn-secondary">Cancel</a>

    </form>

</div>

<?php include '../includes/footer.php'; ?>