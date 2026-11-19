<?php
require_once __DIR__ . '/../auth.php';
require_once __DIR__ . '/../../dbc.php';

$pageTitle = 'Newsletter Management';

// Filters
$search = trim($_GET['search'] ?? '');
$financialYear = trim($_GET['financial_year'] ?? '');

$sql = "SELECT cgr_report_id, cgr_report_name, cgr_report_pdf, cgr_financial_year, cgr_status, cgr_sort_order
        FROM newsletter_reports WHERE 1=1";
$params = [];
$types = '';

if ($search !== '') {
    $sql .= " AND cgr_report_name LIKE ?";
    $params[] = "%{$search}%";
    $types .= 's';
}
if ($financialYear !== '') {
    $sql .= " AND cgr_financial_year = ?";
    $params[] = $financialYear;
    $types .= 's';
}
$sql .= " ORDER BY cgr_sort_order ASC, cgr_report_id DESC";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    die('Database query failed.');
}
if ($params) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

// Financial year dropdown
$yearsResult = $conn->query("SELECT DISTINCT cgr_financial_year FROM newsletter_reports
                             WHERE cgr_financial_year <> '' ORDER BY cgr_financial_year DESC");
$years = [];
if ($yearsResult) {
    while ($yr = $yearsResult->fetch_assoc()) $years[] = $yr['cgr_financial_year'];
}
?>
<?php include __DIR__ . '/../includes/header.php'; ?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Newsletter Management</h3>
        <a href="add.php" class="btn btn-primary">Add Newsletter</a>
    </div>

    <?php if (!empty($_SESSION['flash_message'])): ?>
        <div class="alert alert-success">
            <?= htmlspecialchars($_SESSION['flash_message']); unset($_SESSION['flash_message']); ?>
        </div>
    <?php endif; ?>

    <form method="get" class="row g-2 mb-3">
        <div class="col-md-5">
            <input type="text" name="search" class="form-control" placeholder="Search newsletter"
                   value="<?= htmlspecialchars($search); ?>">
        </div>
        <div class="col-md-3">
            <select name="financial_year" class="form-control">
                <option value="">All Financial Years</option>
                <?php foreach ($years as $year): ?>
                    <option value="<?= htmlspecialchars($year); ?>" <?= $financialYear === $year ? 'selected' : ''; ?>>
                        <?= htmlspecialchars($year); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-4">
            <button class="btn btn-secondary" type="submit">Filter</button>
            <a href="index.php" class="btn btn-light">Reset</a>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead>
                <tr>
                    <th>ID</th><th>Newsletter</th><th>Financial Year</th>
                    <th>PDF</th><th>Sort Order</th><th>Status</th><th width="170">Action</th>
                </tr>
            </thead>
            <tbody>
            <?php if ($result && $result->num_rows): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= (int)$row['cgr_report_id']; ?></td>
                        <td><?= htmlspecialchars($row['cgr_report_name']); ?></td>
                        <td><?= htmlspecialchars($row['cgr_financial_year']); ?></td>
                        <td>
                            <?php if ($row['cgr_report_pdf']): ?>
                                <a href="../../uploads/newsletter/<?= rawurlencode(basename($row['cgr_report_pdf'])); ?>" target="_blank">View PDF</a>
                            <?php else: ?>—<?php endif; ?>
                        </td>
                        <td><?= (int)$row['cgr_sort_order']; ?></td>
                        <td>
                            <span class="badge <?= (int)$row['cgr_status'] === 1 ? 'bg-success' : 'bg-secondary'; ?>">
                                <?= (int)$row['cgr_status'] === 1 ? 'Active' : 'Inactive'; ?>
                            </span>
                        </td>
                        <td>
                            <a href="edit.php?id=<?= (int)$row['cgr_report_id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                            <form method="post" action="delete.php" class="d-inline"
                                  onsubmit="return confirm('Delete this newsletter?');">
                                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>">
                                <input type="hidden" name="id" value="<?= (int)$row['cgr_report_id']; ?>">
                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="7" class="text-center">No newsletter records found.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>