
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require_once '../../dbc.php';

function e($v) {
    return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
}

$sql = "
    SELECT c.id, c.title, c.status, c.sort_order,
           COUNT(cm.id) AS member_count
    FROM committees c
    LEFT JOIN committee_members cm
        ON cm.committee_id = c.id AND cm.status = 1
    GROUP BY c.id, c.title, c.status, c.sort_order
    ORDER BY c.sort_order ASC, c.id DESC
";

$result = $conn->query($sql);

if (!$result) {
    error_log($conn->error);
    exit('Unable to load committees.');
}

include '../includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 style="font-size:24px;font-weight:normal;">Committees</h1>

    <a href="add.php" class="btn-wp text-decoration-none">
        <i class="fas fa-plus"></i> Add Committee
    </a>
</div>

<?php
$messages = [
    'added' => 'Committee added successfully!',
    'updated' => 'Committee updated successfully!',
    'deleted' => 'Committee deleted successfully!'
];

$msg = $_GET['msg'] ?? '';

if (isset($messages[$msg])):
?>
    <div class="alert alert-success">
        <?= e($messages[$msg]) ?>
    </div>
<?php endif; ?>

<div class="wp-card">
    <div class="table-responsive">
        <table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Committee Name</th>
                    <th>Members</th>
                    <th>Status</th>
                    <th>Order</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
            <?php if ($result->num_rows): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= (int)$row['id'] ?></td>
                        <td><?= e($row['title']) ?></td>
                        <td><?= (int)$row['member_count'] ?></td>
                        <td>
                            <?= (int)$row['status'] === 1
                                ? 'Active'
                                : 'Inactive' ?>
                        </td>
                        <td><?= (int)$row['sort_order'] ?></td>
                        <td>
                            <a
                                href="edit.php?id=<?= (int)$row['id'] ?>"
                                class="btn-wp text-decoration-none">
                                <i class="fas fa-edit"></i> Edit
                            </a>

                            <a
                                href="delete.php?id=<?= (int)$row['id'] ?>"
                                class="btn-wp-danger text-decoration-none"
                                onclick="return confirm('Delete <?= e($row['title']) ?>?')">
                                <i class="fas fa-trash"></i> Delete
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="text-center">
                        No committees found.
                    </td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
