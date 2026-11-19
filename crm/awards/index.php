<?php
ob_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);


require_once '../auth.php';
require_once '../../dbc.php';

$selectedYear = isset($_GET['year']) ? (int) $_GET['year'] : 0;

$yearResult = $conn->query("
    SELECT DISTINCT year
    FROM awards
    ORDER BY year DESC
");

$sql = "SELECT id, title, year, image, description, status
        FROM awards";

if ($selectedYear > 0) {
    $stmt = $conn->prepare($sql . " WHERE year = ? ORDER BY year DESC, id DESC");
    $stmt->bind_param("i", $selectedYear);
    $stmt->execute();
    $awards = $stmt->get_result();
} else {
    $awards = $conn->query($sql . " ORDER BY year DESC, id DESC");
}

include '../includes/header.php';
?>

<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Awards Management</h3>
        <a href="add.php" class="btn btn-primary">Add Award</a>
    </div>

    <?php if (isset($_GET['msg']) && $_GET['msg'] === 'added'): ?>
        <div class="alert alert-success">Award added successfully.</div>
    <?php elseif (isset($_GET['msg']) && $_GET['msg'] === 'updated'): ?>
        <div class="alert alert-success">Award updated successfully.</div>
    <?php elseif (isset($_GET['msg']) && $_GET['msg'] === 'deleted'): ?>
        <div class="alert alert-success">Award deleted successfully.</div>
    <?php endif; ?>

    <form method="GET" class="row mb-3">
        <div class="col-md-3">
            <select name="year" class="form-select" onchange="this.form.submit()">
                <option value="0">All Years</option>

                <?php if ($yearResult): ?>
                    <?php while ($yearRow = $yearResult->fetch_assoc()): ?>
                        <option
                            value="<?= (int) $yearRow['year'] ?>"
                            <?= $selectedYear === (int) $yearRow['year'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars((string) $yearRow['year']) ?>
                        </option>
                    <?php endwhile; ?>
                <?php endif; ?>
            </select>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead>
                <tr>
                    <th>Sr. No.</th>
                    <th>Title</th>
                    <th>Year</th>
                    <th>Image</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                <?php if ($awards && $awards->num_rows > 0): ?>
                    <?php $sr = 1; ?>

                    <?php while ($award = $awards->fetch_assoc()): ?>
                        <tr>
                            <td><?= $sr++ ?></td>

                            <td><?= htmlspecialchars($award['title']) ?></td>

                            <td><?= (int) $award['year'] ?></td>

                            <td>
                                <?php if (!empty($award['image'])): ?>
                                    <img
                                        src="/ajmera/<?= htmlspecialchars(ltrim($award['image'], '/')) ?>"
                                        alt=""
                                        style="width:100px;height:65px;object-fit:cover;">
                                <?php else: ?>
                                    No image
                                <?php endif; ?>
                            </td>

                            <td>
                                <?= htmlspecialchars(mb_strimwidth($award['description'] ?? '', 0, 100, '...')) ?>
                            </td>

                            <td>
                                <?= (int) $award['status'] === 1 ? 'Active' : 'Inactive' ?>
                            </td>

                            <td>
                                <a
                                    href="edit.php?id=<?= (int) $award['id'] ?>"
                                    class="btn btn-sm btn-warning">Edit</a>

                                <form
                                    method="POST"
                                    action="delete.php"
                                    class="d-inline"
                                    onsubmit="return confirm('Are you sure you want to delete this award?');">

                                    <input type="hidden" name="id" value="<?= (int) $award['id'] ?>">

                                    <button type="submit" class="btn btn-sm btn-danger">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>

                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center">No awards found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

<?php include '../includes/footer.php'; ?>