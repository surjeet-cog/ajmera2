<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require_once __DIR__ . '/../../dbc.php';

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
function h($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

$years = [];
$yearResult = $conn->query(
    "SELECT DISTINCT financial_year
     FROM annual_reports
     ORDER BY financial_year DESC"
);

if ($yearResult) {
    while ($row = $yearResult->fetch_assoc()) {
        $years[] = $row['financial_year'];
    }
}

$selectedYear = trim($_GET['financial_year'] ?? '');
$selectedCategory = trim($_GET['category'] ?? '');
$search = trim($_GET['search'] ?? '');

$sql = "SELECT id, financial_year, category, title, pdf_file, status, sort_order
        FROM annual_reports
        WHERE 1=1";
$types = '';
$params = [];

if ($selectedYear !== '') {
    $sql .= " AND financial_year = ?";
    $types .= 's';
    $params[] = $selectedYear;
}

if ($selectedCategory !== '') {
    $sql .= " AND category = ?";
    $types .= 's';
    $params[] = $selectedCategory;
}

if ($search !== '') {
    $sql .= " AND title LIKE ?";
    $types .= 's';
    $params[] = '%' . $search . '%';
}

$sql .= " ORDER BY financial_year DESC, category ASC, sort_order ASC, id DESC";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    error_log($conn->error);
    exit('Unable to load annual reports.');
}

if ($types !== '') {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

$categories = [];
$catResult = $conn->query(
    "SELECT DISTINCT category
     FROM annual_reports
     ORDER BY category ASC"
);

if ($catResult) {
    while ($row = $catResult->fetch_assoc()) {
        $categories[] = $row['category'];
    }
}

$messages = [
    'added' => 'Annual report added successfully.',
    'updated' => 'Annual report updated successfully.',
    'deleted' => 'Annual report deleted successfully.'
];
$message = $messages[$_GET['msg'] ?? ''] ?? '';
?>

<?php include __DIR__ . '/../includes/header.php'; ?>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center flex-wrap mb-4">
        <h2>Annual Reports</h2>
        <a href="add.php" class="btn btn-primary">+ Add Annual Report</a>
    </div>

    <?php if ($message !== ''): ?>
        <div class="alert alert-success"><?= h($message) ?></div>
    <?php endif; ?>

    <form method="GET" class="row g-3 mb-4">
        <div class="col-md-3">
            <label class="form-label">Financial Year</label>
            <select name="financial_year" class="form-control">
                <option value="">All Financial Years</option>
                <?php foreach ($years as $year): ?>
                    <option value="<?= h($year) ?>"
                        <?= $selectedYear === $year ? 'selected' : '' ?>>
                        FY <?= h($year) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-md-3">
            <label class="form-label">Category</label>
            <select name="category" class="form-control">
                <option value="">All Categories</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= h($category) ?>"
                        <?= $selectedCategory === $category ? 'selected' : '' ?>>
                        <?= h($category) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-md-4">
            <label class="form-label">Search Title</label>
            <input type="text" name="search" class="form-control"
                   value="<?= h($search) ?>" placeholder="Enter report title">
        </div>

        <div class="col-md-2 d-flex align-items-end gap-2">
            <button type="submit" class="btn btn-success">Filter</button>
            <a href="index.php" class="btn btn-secondary">Reset</a>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead>
                <tr>
                    <th>Sr. No.</th>
                    <th>Financial Year</th>
                    <th>Category</th>
                    <th>Title</th>
                    <th>Sort Order</th>
                    <th>PDF</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $sr = 0; ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <?php $sr++; ?>
                    <tr>
                        <td><?= $sr ?></td>
                        <td><?= h($row['financial_year']) ?></td>
                        <td><?= h($row['category']) ?></td>
                        <td><?= h($row['title']) ?></td>
                        <td><?= (int)$row['sort_order'] ?></td>
                        <td>
                            <a href="/ajmera/<?= h(ltrim($row['pdf_file'], '/')) ?>"
                               target="_blank" rel="noopener noreferrer">
                                View PDF
                            </a>
                        </td>
                        <td>
                            <?php if ((int)$row['status'] === 1): ?>
                                <span class="badge bg-success">Active</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Inactive</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-nowrap">
                            <a href="edit.php?id=<?= (int)$row['id'] ?>"
                               class="btn btn-sm btn-warning">Edit</a>

                          <form method="POST" action="delete.php"
      onsubmit="return confirm('Are you sure you want to delete this report?');">

    <input type="hidden" name="csrf_token"
           value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">

    <input type="hidden" name="id" value="<?= (int)$row['id'] ?>">

    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
</form>
                        </td>
                    </tr>
                <?php endwhile; ?>

                <?php if ($sr === 0): ?>
                    <tr>
                        <td colspan="8" class="text-center">No annual reports found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
$stmt->close();
include __DIR__ . '/../includes/footer.php';
?>
