<?php
require_once '../auth.php';
require_once '../../dbc.php';

$cgrCmsResult = $conn->query("
    SELECT *
    FROM cgr_reports
    ORDER BY cgr_financial_year DESC,
             cgr_sort_order ASC,
             cgr_report_id DESC
");

function cgrEscape($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

include '../includes/header.php';
?>

<div class="container-fluid">

    <div class="d-flex justify-content-between mb-4">
        <h3>Corporate Governance Reports</h3>
        <a href="add.php" class="btn btn-primary">Add Report</a>
    </div>

    <?php if (isset($_GET['added'])): ?>
        <div class="alert alert-success">Report added successfully.</div>
    <?php endif; ?>

    <?php if (isset($_GET['updated'])): ?>
        <div class="alert alert-success">Report updated successfully.</div>
    <?php endif; ?>

    <?php if (isset($_GET['deleted'])): ?>
        <div class="alert alert-success">Report deleted successfully.</div>
    <?php endif; ?>

    <div class="table-responsive">
        <table class="table table-bordered">

            <thead>
                <tr>
                    <th>Financial Year</th>
                    <th>Report Name</th>
                    <th>PDF</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>

            <?php if ($cgrCmsResult && $cgrCmsResult->num_rows > 0): ?>

                <?php while ($cgrCmsRow = $cgrCmsResult->fetch_assoc()): ?>

                    <tr>
                        <td>FY <?= cgrEscape($cgrCmsRow['cgr_financial_year']) ?></td>

                        <td><?= cgrEscape($cgrCmsRow['cgr_report_name']) ?></td>

                        <td>
                            <?php if ($cgrCmsRow['cgr_report_pdf']): ?>
                                <a href="/ajmera/<?= cgrEscape(
                                    ltrim($cgrCmsRow['cgr_report_pdf'], '/')
                                ) ?>" target="_blank" rel="noopener noreferrer">
                                    View PDF
                                </a>
                            <?php else: ?>
                                No PDF
                            <?php endif; ?>
                        </td>

                        <td>
                            <a href="edit.php?id=<?= (int)$cgrCmsRow['cgr_report_id'] ?>"
                               class="btn btn-warning btn-sm">Edit</a>

                            <form action="delete.php" method="POST" class="d-inline"
                                  onsubmit="return confirm('Delete this report?');">
                                <input type="hidden" name="id"
                                       value="<?= (int)$cgrCmsRow['cgr_report_id'] ?>">
                                <button type="submit" class="btn btn-danger btn-sm">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>

                <?php endwhile; ?>

            <?php else: ?>

                <tr>
                    <td colspan="4" class="text-center">No reports found.</td>
                </tr>

            <?php endif; ?>

            </tbody>
        </table>
    </div>

</div>

<?php include '../includes/footer.php'; ?>