<?php
ob_start();
require_once __DIR__ . '/../auth.php';
require_once __DIR__ . '/../../dbc.php';
require_once __DIR__ . '/form_helpers.php';

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

        $thumbnail = investor_upload_thumbnail('thumbnail');
        $sort = (int)($_POST['sort_order'] ?? 0);
        $status = isset($_POST['status']) ? 1 : 0;

        $stmt = $conn->prepare("
            INSERT INTO investor_inthenews
            (title, video_url, year, thumbnail, sort_order, status)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param('ssisii', $title, $url, $year, $thumbnail, $sort, $status);
        $stmt->execute();
        $stmt->close();

        header('Location: index.php?msg=' . urlencode('Video added successfully'));
        exit;
    } catch (Throwable $ex) {
        $error = $ex->getMessage();
    }
}

include __DIR__ . '/../includes/header.php';
?>
<div class="container-fluid">
    <h2>Add Investor News Video</h2>
    <?php if ($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>
    <?php include __DIR__ . '/form.php'; ?>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
