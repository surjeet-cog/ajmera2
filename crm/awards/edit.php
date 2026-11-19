<?php

ob_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);


require_once '../auth.php';
require_once '../../dbc.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id || $id < 1) {
    header('Location: index.php');
    exit;
}

$getAward = $conn->prepare("
    SELECT id, title, year, image, description, status
    FROM awards
    WHERE id = ?
    LIMIT 1
");

$getAward->bind_param('i', $id);
$getAward->execute();

$awardResult = $getAward->get_result();
$award = $awardResult->fetch_assoc();
$getAward->close();

if (!$award) {
    header('Location: index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $title = trim($_POST['title'] ?? '');
    $year = (int) ($_POST['year'] ?? 0);
    $description = trim($_POST['description'] ?? '');
    $status = isset($_POST['status']) ? 1 : 0;

    $imagePath = $award['image'] ?? '';
    $newImagePath = '';

    if ($title === '' || $year < 1900 || $year > 2200) {
        $error = 'Please enter a valid title and year.';
    }

    if (
        $error === '' &&
        isset($_FILES['image']) &&
        $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE
    ) {

        if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            $error = 'Image upload failed.';
        } elseif ($_FILES['image']['size'] > 5 * 1024 * 1024) {
            $error = 'Image size must be less than 5 MB.';
        } else {

            $tmpFile = $_FILES['image']['tmp_name'];
            $imageInfo = @getimagesize($tmpFile);

            $allowedTypes = [
                'image/jpeg' => 'jpg',
                'image/png'  => 'png',
                'image/webp' => 'webp',
                'image/gif'  => 'gif'
            ];

            $mime = $imageInfo['mime'] ?? '';

            if ($imageInfo === false || !isset($allowedTypes[$mime])) {
                $error = 'Please upload a valid JPG, PNG, WEBP or GIF image.';
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
                        $newImagePath = 'uploads/awards/' . $fileName;
                        $imagePath = $newImagePath;
                    } else {
                        $error = 'Unable to save uploaded image.';
                    }
                }
            }
        }
    }

    if ($error === '') {

        $updateAward = $conn->prepare("
            UPDATE awards
            SET title = ?,
                year = ?,
                image = ?,
                description = ?,
                status = ?
            WHERE id = ?
        ");

        $updateAward->bind_param(
            'sissii',
            $title,
            $year,
            $imagePath,
            $description,
            $status,
            $id
        );

        if ($updateAward->execute()) {

            $updateAward->close();

            // Delete old image only after successful update
            if ($newImagePath !== '' && !empty($award['image'])) {

                $oldImage = dirname(__DIR__, 2) . '/'
                    . ltrim($award['image'], '/');

                $newImage = dirname(__DIR__, 2) . '/'
                    . ltrim($newImagePath, '/');

                if (
                    realpath($oldImage) !== false &&
                    realpath($newImage) !== false &&
                    realpath(dirname($oldImage)) === realpath(dirname($newImage)) &&
                    is_file($oldImage)
                ) {
                    unlink($oldImage);
                }
            }

            header('Location: index.php?msg=updated');
            exit;

        } else {

            $updateAward->close();

            // Remove newly uploaded image if update failed
            if ($newImagePath !== '') {
                $newFile = dirname(__DIR__, 2) . '/' . $newImagePath;

                if (is_file($newFile)) {
                    unlink($newFile);
                }
            }

            $error = 'Unable to update award.';
        }
    }

    $award['title'] = $title;
    $award['year'] = $year;
    $award['description'] = $description;
    $award['status'] = $status;

    if ($newImagePath !== '' && $error !== '') {
        $award['image'] = $newImagePath;
    }
}

include '../includes/header.php';
?>

<div class="container-fluid py-4">

    <h3 class="mb-4">Edit Award</h3>

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
                value="<?= htmlspecialchars($award['title']) ?>">
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
                value="<?= (int) $award['year'] ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Current Image</label><br>

            <?php if (!empty($award['image'])): ?>
                <img
                    src="/ajmera/<?= htmlspecialchars(ltrim($award['image'], '/')) ?>"
                    alt=""
                    style="width:140px;height:90px;object-fit:cover;">
            <?php else: ?>
                <p>No image uploaded.</p>
            <?php endif; ?>
        </div>

        <div class="mb-3">
            <label class="form-label">Replace Image (Optional)</label>
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
                rows="5"><?= htmlspecialchars($award['description'] ?? '') ?></textarea>
        </div>

        <div class="form-check mb-3">
            <input
                type="checkbox"
                name="status"
                id="status"
                class="form-check-input"
                <?= (int) $award['status'] === 1 ? 'checked' : '' ?>>
            <label for="status" class="form-check-label">Active</label>
        </div>

        <button type="submit" class="btn btn-primary">Update Award</button>
        <a href="index.php" class="btn btn-secondary">Cancel</a>

    </form>

</div>

<?php include '../includes/footer.php'; ?>