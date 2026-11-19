
<?php
ob_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require_once '../../dbc.php';

$pageTitle = 'Manage Financials';

$sql = "SELECT * FROM financial_reports ORDER BY financial_year DESC, id DESC";
$result = mysqli_query($conn, $sql);

include '../includes/header.php';
?>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Manage Financials</h2>
        <a href="add.php" class="btn btn-primary">+ Add Financial Report</a>
    </div>

    <?php if (isset($_GET['msg'])) { ?>
        <div class="alert alert-success">
            <?php
            $messages = [
               'added'    => 'Financial report added successfully.',
    'updated'  => 'Financial report updated successfully.',
    'deleted'  => 'Financial report deleted successfully.',
    'error'    => 'Unable to delete financial report.'
            ];
            echo htmlspecialchars(
                $messages[$_GET['msg']] ?? 'Operation completed.',
                ENT_QUOTES,
                'UTF-8'
            );
            ?>
        </div>
    <?php } ?>

    <div class="mb-3">
        <label for="yearFilter" class="form-label">Filter by Financial Year</label>
        <select id="yearFilter" class="form-select" style="max-width:250px;">
            <option value="">All Financial Years</option>
            <?php
            $years = mysqli_query(
                $conn,
                "SELECT DISTINCT financial_year
                 FROM financial_reports
                 ORDER BY financial_year DESC"
            );

            if ($years) {
                while ($year = mysqli_fetch_assoc($years)) {
                    $value = htmlspecialchars($year['financial_year'], ENT_QUOTES, 'UTF-8');
                    echo '<option value="' . $value . '">' . $value . '</option>';
                }
            }
            ?>
        </select>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle" id="financialTable">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Financial Year</th>
                    <th>Title</th>
                    <th>Q1 PDF</th>
                    <th>Q2 &amp; H1 PDF</th>
                    <th>Q3 &amp; 9M PDF</th>
                    <th>Q4 PDF</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && mysqli_num_rows($result) > 0) { ?>
                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                        <tr data-year="<?= htmlspecialchars($row['financial_year'], ENT_QUOTES, 'UTF-8') ?>">
                            <td><?= (int)$row['id'] ?></td>
                            <td><?= htmlspecialchars($row['financial_year'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($row['title'], ENT_QUOTES, 'UTF-8') ?></td>

                            <?php
                            foreach (['q1_pdf', 'q2_h1_pdf', 'q3_9m_pdf', 'q4_fy_pdf'] as $column) {
                                $path = $row[$column] ?? '';
                                echo '<td>';
                                if ($path !== '') {
                                    echo '<a href="../../' .
                                        htmlspecialchars(ltrim($path, '/'), ENT_QUOTES, 'UTF-8') .
                                        '" target="_blank" rel="noopener noreferrer">View PDF</a>';
                                } else {
                                    echo '—';
                                }
                                echo '</td>';
                            }
                            ?>

                            <td>
                                <?php if ((int)$row['status'] === 1) { ?>
                                    <span class="badge bg-success">Active</span>
                                <?php } else { ?>
                                    <span class="badge bg-secondary">Inactive</span>
                                <?php } ?>
                            </td>

                            <td class="text-nowrap">
                               <a href="edit.php?id=<?= (int)$row['id'] ?>"
   class="btn btn-primary btn-sm">Edit</a>

                                <form action="delete.php" method="POST"
      onsubmit="return confirm('Are you sure you want to delete this report?');">
    <input type="hidden" name="id" value="<?= (int)$row['id'] ?>">
    <button type="submit" class="btn btn-danger btn-sm">
        Delete
    </button>
</form>
                            </td>
                        </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td colspan="9" class="text-center">No financial reports found.</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const filter = document.getElementById('yearFilter');

    filter.addEventListener('change', function () {
        const year = this.value;

        document.querySelectorAll('#financialTable tbody tr[data-year]')
            .forEach(function (row) {
                row.hidden = year !== '' && row.dataset.year !== year;
            });
    });
});
</script>

<?php include '../includes/footer.php'; ?>
