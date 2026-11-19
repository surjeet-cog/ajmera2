<?php
ob_start();
require_once __DIR__ . '/../auth.php';
require_once __DIR__ . '/../../dbc.php';
require_once __DIR__ . '/form_helpers.php';

$notes = [];
$result = $conn->query("SELECT id, title, pdf_file, sort_order, status
                        FROM analyst_notes
                        ORDER BY sort_order ASC, id DESC");
if ($result) while ($row = $result->fetch_assoc()) $notes[] = $row;

require_once __DIR__ . '/../includes/header.php';
?>
<div class="container-fluid py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div><h3 class="mb-1">Analyst Notes</h3><p class="text-muted mb-0">Manage analyst research notes and reports</p></div>
        <a href="add.php" class="btn btn-primary">+ Add Analyst Note</a>
    </div>
    <?php if (!empty($_SESSION['success'])): ?><div class="alert alert-success"><?= e($_SESSION['success']); unset($_SESSION['success']); ?></div><?php endif; ?>
    <?php if (!empty($_SESSION['error'])): ?><div class="alert alert-danger"><?= e($_SESSION['error']); unset($_SESSION['error']); ?></div><?php endif; ?>
    <div class="card"><div class="card-body table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead><tr><th>Sr. No.</th><th>Title</th><th>Sort Order</th><th>Status</th><th>PDF</th><th>Action</th></tr></thead>
            <tbody>
            <?php if ($notes): $i=1; foreach ($notes as $note): ?>
                <tr>
                    <td><?= sprintf('%02d', $i++); ?></td>
                    <td><?= e($note['title']); ?></td>
                    <td><?= (int)$note['sort_order']; ?></td>
                    <td><?= (int)$note['status'] === 1 ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-secondary">Inactive</span>'; ?></td>
                    <td><?php if ($note['pdf_file']): ?><a href="/ajmera/<?= e(ltrim($note['pdf_file'], '/')); ?>" target="_blank" rel="noopener">View PDF</a><?php else: ?>—<?php endif; ?></td>
                    <td class="text-nowrap">
                        <a href="edit.php?id=<?= (int)$note['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                        <form method="post" action="delete.php" class="d-inline" onsubmit="return confirm('Delete this analyst note?');">
                            <input type="hidden" name="csrf_token" value="<?= e(analyst_notes_csrf_token()); ?>">
                            <input type="hidden" name="id" value="<?= (int)$note['id']; ?>">
                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; else: ?><tr><td colspan="6" class="text-center">No analyst notes found.</td></tr><?php endif; ?>
            </tbody>
        </table>
    </div></div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
