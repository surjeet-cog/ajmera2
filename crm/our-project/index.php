<?php
ob_start();

error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../dbc.php';
require_once __DIR__ . '/form_helpers.php';

if (!isset($conn) || !($conn instanceof mysqli)) {
    die('Database connection failed. Check dbc.php.');
}

$search = trim($_GET['search'] ?? '');
$type = trim($_GET['type'] ?? '');

$sql = "SELECT
            id,
            project_name,
            slug,
            city,
            location,
            project_status,
            project_type,
            project_image,
            project_url,
            sort_order,
            status
        FROM projects
        WHERE 1 = 1";

$params = [];
$types = '';

if ($search !== '') {
    $sql .= " AND (
        project_name LIKE ?
        OR slug LIKE ?
        OR city LIKE ?
        OR location LIKE ?
    )";

    $keyword = '%' . $search . '%';

    $params[] = $keyword;
    $params[] = $keyword;
    $params[] = $keyword;
    $params[] = $keyword;

    $types .= 'ssss';
}

if ($type !== '' && in_array($type, ['Residential', 'Commercial'], true)) {
    $sql .= " AND project_type = ?";
    $params[] = $type;
    $types .= 's';
}

$sql .= " ORDER BY sort_order ASC, id DESC";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    die('Query preparation failed: ' . e($conn->error));
}

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

if (!$stmt->execute()) {
    die('Could not fetch projects: ' . e($stmt->error));
}

$result = $stmt->get_result();

include __DIR__ . '/../includes/header.php';
?>

<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Our Projects</h2>

        <a href="add.php" class="btn btn-primary">
            + Add Project
        </a>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">
            <?php if ($_GET['success'] === 'added'): ?>
                Project added successfully.
            <?php elseif ($_GET['success'] === 'updated'): ?>
                Project updated successfully.
            <?php elseif ($_GET['success'] === 'deleted'): ?>
                Project deleted successfully.
            <?php else: ?>
                Operation completed successfully.
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger">
            The requested operation could not be completed.
        </div>
    <?php endif; ?>

    <form method="GET" class="row g-2 mb-4">
        <div class="col-md-5">
            <input
                type="text"
                name="search"
                class="form-control"
                placeholder="Search project, slug, city or location"
                value="<?= e($search) ?>"
            >
        </div>

        <div class="col-md-3">
            <select name="type" class="form-select">
                <option value="">All Project Types</option>
                <option value="Residential"
                    <?= $type === 'Residential' ? 'selected' : '' ?>>
                    Residential
                </option>
                <option value="Commercial"
                    <?= $type === 'Commercial' ? 'selected' : '' ?>>
                    Commercial
                </option>
            </select>
        </div>

        <div class="col-md-4">
            <button type="submit" class="btn btn-secondary">
                Search
            </button>

            <a href="index.php" class="btn btn-outline-secondary">
                Reset
            </a>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Image</th>
                    <th>Project Name</th>
                    <th>City / Location</th>
                    <th>Type</th>
                    <th>Project Status</th>
                    <th>Order</th>
                    <th>Visibility</th>
                    <th>Project URL</th>
                    <th style="min-width:150px;">Action</th>
                </tr>
            </thead>

            <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= (int)$row['id'] ?></td>

                        <td>
                            <?php if (!empty($row['project_image'])): ?>
                                <img
                                    src="<?= e('/ajmera/' . ltrim($row['project_image'], '/')) ?>"
                                    alt="<?= e($row['project_name']) ?>"
                                    style="width:80px;height:55px;object-fit:cover;"
                                >
                            <?php else: ?>
                                <span class="text-muted">No image</span>
                            <?php endif; ?>
                        </td>

                        <td>
                            <strong><?= e($row['project_name']) ?></strong>
                            <br>
                            <small class="text-muted">
                                <?= e($row['slug']) ?>
                            </small>
                        </td>

                        <td>
                            <?= e($row['city']) ?>
                            <?php if (!empty($row['location'])): ?>
                                <br>
                                <small><?= e($row['location']) ?></small>
                            <?php endif; ?>
                        </td>

                        <td><?= e($row['project_type']) ?></td>
                        <td><?= e($row['project_status']) ?></td>
                        <td><?= (int)$row['sort_order'] ?></td>

                        <td>
                            <?php if ((int)$row['status'] === 1): ?>
                                <span class="badge bg-success">Active</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Inactive</span>
                            <?php endif; ?>
                        </td>

                        <td>
                            <?php if (!empty($row['project_url'])): ?>
                                <a
                                    href="<?= e('/ajmera/' . ltrim($row['project_url'], '/')) ?>"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                >
                                    View Page
                                </a>
                            <?php else: ?>
                                <span class="text-muted">Not set</span>
                            <?php endif; ?>
                        </td>

                        <td>
                            <a
                                href="edit.php?id=<?= (int)$row['id'] ?>"
                                class="btn btn-sm btn-warning mb-1"
                            >
                                Edit
                            </a>

                            <form
                                action="delete.php"
                                method="POST"
                                class="d-inline"
                                onsubmit="return confirm('Are you sure you want to delete this project?');"
                            >
                                <input
                                    type="hidden"
                                    name="csrf_token"
                                    value="<?= e(project_csrf_token()) ?>"
                                >

                                <input
                                    type="hidden"
                                    name="id"
                                    value="<?= (int)$row['id'] ?>"
                                >

                                <button
                                    type="submit"
                                    class="btn btn-sm btn-danger mb-1"
                                >
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="10" class="text-center">
                        No projects found.
                    </td>
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