<?php
ob_start();
require_once __DIR__ . '/../auth.php';
require_once __DIR__ . '/../../dbc.php';
require_once __DIR__ . '/form_helpers.php';

$result = $conn->query("SELECT id, title, pdf_file, sort_order, status FROM preferential_allotments ORDER BY sort_order ASC, id DESC");
$rows = [];
if ($result) while ($row = $result->fetch_assoc()) $rows[] = $row;
include __DIR__ . '/../includes/header.php';
?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Preferential Allotments</h3>
        <a href="add.php" class="btn btn-primary">Add Document</a>
    </div>
    <?php if (!empty($_GET['success'])): ?><div class="alert alert-success">Record saved successfully.</div><?php endif; ?>
    <?php if (!empty($_GET['deleted'])): ?><div class="alert alert-success">Record deleted successfully.</div><?php endif; ?>
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead><tr><th>Sr. No.</th><th>Title</th><th>PDF</th><th>Sort Order</th><th>Status</th><th>Action</th></tr></thead>
            <tbody>
            <?php if ($rows): foreach ($rows as $i => $row): ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td><?= e($row['title']) ?></td>
                    <td><?php if ($row['pdf_file']): ?><a href="/ajmera/<?= e(ltrim($row['pdf_file'], '/')) ?>" target="_blank" rel="noopener">View PDF</a><?php else: ?>—<?php endif; ?></td>
                    <td><?= (int)$row['sort_order'] ?></td>
                    <td><?= (int)$row['status'] === 1 ? 'Active' : 'Inactive' ?></td>
                    <td class="text-nowrap">
                        <a href="edit.php?id=<?= (int)$row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                        <form method="post" action="delete.php" class="d-inline" onsubmit="return confirm('Delete this preferential allotment document?');">
                            <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                            <input type="hidden" name="id" value="<?= (int)$row['id'] ?>">
                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; else: ?>
                <tr><td colspan="6" class="text-center">No records available.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
