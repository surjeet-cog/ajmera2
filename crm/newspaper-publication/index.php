<?php
ob_start();
require_once __DIR__ . '/../auth.php';
require_once __DIR__ . '/../../dbc.php';
require_once __DIR__ . '/form_helpers.php';

$reports = [];
$result = $conn->query("SELECT id, title, pdf_file, financial_year, sort_order, status
                        FROM newspaper_publications
                        ORDER BY financial_year DESC, sort_order ASC, id ASC");
if ($result) while ($row = $result->fetch_assoc()) $reports[] = $row;

require_once __DIR__ . '/../includes/header.php';
?>
<div class="container-fluid py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div><h3 class="mb-1">Newspaper Publications</h3><p class="text-muted mb-0">Manage newspaper publications and statutory announcements</p></div>
        <a href="add.php" class="btn btn-primary">+ Add Publication</a>
    </div>
    <?php if (!empty($_SESSION['success'])): ?><div class="alert alert-success"><?= e($_SESSION['success']); unset($_SESSION['success']); ?></div><?php endif; ?>
    <?php if (!empty($_SESSION['error'])): ?><div class="alert alert-danger"><?= e($_SESSION['error']); unset($_SESSION['error']); ?></div><?php endif; ?>
    <div class="card"><div class="card-body table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead><tr><th>Sr. No.</th><th>Title</th><th>Financial Year</th><th>Sort Order</th><th>Status</th><th>PDF</th><th>Action</th></tr></thead>
            <tbody>
            <?php if ($reports): $i=1; foreach ($reports as $report): ?>
                <tr>
                    <td><?= sprintf('%02d', $i++); ?></td>
                    <td><?= e($report['title']); ?></td>
                    <td>FY <?= e($report['financial_year']); ?></td>
                    <td><?= (int)$report['sort_order']; ?></td>
                    <td><?= (int)$report['status'] === 1 ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-secondary">Inactive</span>'; ?></td>
                    <td><?php if ($report['pdf_file']): ?><a href="/ajmera/<?= e(ltrim($report['pdf_file'], '/')); ?>" target="_blank" rel="noopener">View PDF</a><?php else: ?>—<?php endif; ?></td>
                    <td class="text-nowrap">
                        <a href="edit.php?id=<?= (int)$report['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                        <form method="post" action="delete.php" class="d-inline" onsubmit="return confirm('Delete this publication?');">
                            <input type="hidden" name="csrf_token" value="<?= e(newspaper_csrf_token()); ?>">
                            <input type="hidden" name="id" value="<?= (int)$report['id']; ?>">
                            <button class="btn btn-sm btn-danger" type="submit">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; else: ?><tr><td colspan="7" class="text-center">No publications found.</td></tr><?php endif; ?>
            </tbody>
        </table>
    </div></div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
