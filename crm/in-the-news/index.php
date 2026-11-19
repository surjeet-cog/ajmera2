<?php
ob_start();
require_once __DIR__ . '/../auth.php';
require_once __DIR__ . '/../../dbc.php';
require_once __DIR__ . '/form_helpers.php';

$result = $conn->query("
    SELECT id, title, video_url, year, thumbnail, sort_order, status
    FROM investor_inthenews
    ORDER BY year DESC, sort_order ASC, id DESC
");
$rows = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];

include __DIR__ . '/../includes/header.php';
?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Investor In The News</h2>
        <a class="btn btn-primary" href="add.php">Add Video</a>
    </div>

    <?php if (!empty($_GET['msg'])): ?>
        <div class="alert alert-success"><?= e($_GET['msg']) ?></div>
    <?php endif; ?>

    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Sr. No.</th><th>Year</th><th>Title</th>
                    <th>Video URL</th><th>Thumbnail</th><th>Sort Order</th>
                    <th>Status</th><th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php if ($rows): foreach ($rows as $i => $row): ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td><?= e($row['year']) ?></td>
                    <td><?= e($row['title']) ?></td>
                    <td><a href="<?= e($row['video_url']) ?>" target="_blank" rel="noopener noreferrer">Open video</a></td>
                    <td>
                        <?php if ($row['thumbnail']): ?>
                            <img src="/ajmera/<?= e(ltrim($row['thumbnail'], '/')) ?>" alt="" style="width:100px;height:auto">
                        <?php else: ?>—<?php endif; ?>
                    </td>
                    <td><?= (int)$row['sort_order'] ?></td>
                    <td><?= (int)$row['status'] ? 'Active' : 'Inactive' ?></td>
                    <td>
                        <a class="btn btn-sm btn-warning" href="edit.php?id=<?= (int)$row['id'] ?>">Edit</a>
                        <form method="post" action="delete.php" style="display:inline" onsubmit="return confirm('Delete this video?')">
                            <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token']) ?>">
                            <input type="hidden" name="id" value="<?= (int)$row['id'] ?>">
                            <button class="btn btn-sm btn-danger" type="submit">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; else: ?>
                <tr><td colspan="8" class="text-center">No videos found.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
