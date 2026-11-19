<?php
ob_start();
require_once __DIR__ . '/../auth.php';
require_once __DIR__ . '/../../dbc.php';
require_once __DIR__ . '/form_helpers.php';

$presentations = [];
$result = $conn->query("SELECT id, title, pdf_file, sort_order, status
                        FROM analyst_presentations
                        ORDER BY sort_order ASC, id DESC");
if ($result) while ($row = $result->fetch_assoc()) $presentations[] = $row;

require_once __DIR__ . '/../includes/header.php';
?>
<div class="container-fluid py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div><h3 class="mb-1">Analyst Presentations</h3><p class="text-muted mb-0">Manage investor and analyst presentations</p></div>
        <a href="add.php" class="btn btn-primary">+ Add Presentation</a>
    </div>
    <?php if (!empty($_SESSION['success'])): ?><div class="alert alert-success"><?= e($_SESSION['success']); unset($_SESSION['success']); ?></div><?php endif; ?>
    <?php if (!empty($_SESSION['error'])): ?><div class="alert alert-danger"><?= e($_SESSION['error']); unset($_SESSION['error']); ?></div><?php endif; ?>
    <div class="card"><div class="card-body table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead><tr><th>Sr. No.</th><th>Title</th><th>Sort Order</th><th>Status</th><th>PDF</th><th>Action</th></tr></thead>
            <tbody>
            <?php if ($presentations): $i=1; foreach ($presentations as $presentation): ?>
                <tr>
                    <td><?= sprintf('%02d', $i++); ?></td>
                    <td><?= e($presentation['title']); ?></td>
                    <td><?= (int)$presentation['sort_order']; ?></td>
                    <td><?= (int)$presentation['status'] === 1 ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-secondary">Inactive</span>'; ?></td>
                    <td><?php if ($presentation['pdf_file']): ?><a href="/ajmera/<?= e(ltrim($presentation['pdf_file'], '/')); ?>" target="_blank" rel="noopener">View PDF</a><?php else: ?>—<?php endif; ?></td>
                    <td class="text-nowrap">
                        <a href="edit.php?id=<?= (int)$presentation['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                        <form method="post" action="delete.php" class="d-inline" onsubmit="return confirm('Delete this analyst presentation?');">
                            <input type="hidden" name="csrf_token" value="<?= e(analyst_presentations_csrf_token()); ?>">
                            <input type="hidden" name="id" value="<?= (int)$presentation['id']; ?>">
                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; else: ?><tr><td colspan="6" class="text-center">No analyst presentations found.</td></tr><?php endif; ?>
            </tbody>
        </table>
    </div></div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
