<?php
ob_start();
require_once __DIR__ . '/../auth.php';
require_once __DIR__ . '/../../dbc.php';
require_once __DIR__ . '/form_helpers.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) { header('Location: index.php'); exit; }

$stmt = $conn->prepare("
    SELECT id, title, video_url, year, thumbnail, sort_order, status
    FROM investor_inthenews WHERE id = ? LIMIT 1
");
$stmt->bind_param('i', $id);
$stmt->execute();
$record = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$record) { header('Location: index.php'); exit; }

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    try {
        $title = trim($_POST['title'] ?? '');
        $url = trim($_POST['video_url'] ?? '');
        $year = filter_input(INPUT_POST, 'year', FILTER_VALIDATE_INT);

        if ($title === '' || !$year || $year < 1901 || $year > 2155) {
            throw new RuntimeException('Enter a valid title and year.');
        }
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            throw new RuntimeException('Enter a valid YouTube URL.');
        }
        $host = strtolower((string)parse_url($url, PHP_URL_HOST));
        if (!in_array($host, ['youtube.com', 'www.youtube.com', 'm.youtube.com', 'youtu.be', 'www.youtu.be'], true)) {
            throw new RuntimeException('Please enter a valid YouTube URL.');
        }

        $oldThumbnail = $record['thumbnail'];
        $thumbnail = investor_upload_thumbnail('thumbnail', $oldThumbnail);
        $sort = (int)($_POST['sort_order'] ?? 0);
        $status = isset($_POST['status']) ? 1 : 0;

        $stmt = $conn->prepare("
            UPDATE investor_inthenews
            SET title = ?, video_url = ?, year = ?, thumbnail = ?,
                sort_order = ?, status = ?
            WHERE id = ?
        ");
        $stmt->bind_param('ssisiii', $title, $url, $year, $thumbnail, $sort, $status, $id);
        $stmt->execute();
        $stmt->close();

        if ($thumbnail !== $oldThumbnail) {
            investor_delete_thumbnail($oldThumbnail);
        }

        header('Location: index.php?msg=' . urlencode('Video updated successfully'));
        exit;
    } catch (Throwable $ex) {
        $error = $ex->getMessage();
    }
}

include __DIR__ . '/../includes/header.php';
?>
<div class="container-fluid">
    <h2>Edit Investor News Video</h2>
    <?php if ($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>
    <?php include __DIR__ . '/form.php'; ?>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
