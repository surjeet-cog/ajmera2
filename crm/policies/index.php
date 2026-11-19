<?php
require_once '../auth.php';
require_once '../../dbc.php';

$policeListQuery = "
    SELECT
        pd.police_document_id,
        pd.police_document_name,
        pd.police_document_pdf,
        pc.police_committee_title
    FROM police_committee_documents pd
    INNER JOIN police_committees pc
        ON pc.police_committee_id = pd.police_committee_id
    WHERE pd.police_document_status = 1
      AND pc.police_committee_status = 1
    ORDER BY pd.police_document_sort_order ASC,
             pd.police_document_sr_no ASC,
             pd.police_document_id ASC
";

$policeListResult = $conn->query($policeListQuery);

function policeEscape($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}
?>

<?php include '../includes/header.php'; ?>

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Police Documents</h3>

        <a href="add.php" class="btn btn-primary">
            Add Document
        </a>
    </div>

    <?php if (isset($_GET['added'])): ?>
        <div class="alert alert-success">Document added successfully.</div>
    <?php endif; ?>

    <?php if (isset($_GET['updated'])): ?>
        <div class="alert alert-success">Document updated successfully.</div>
    <?php endif; ?>

    <?php if (isset($_GET['deleted'])): ?>
        <div class="alert alert-success">Document deleted successfully.</div>
    <?php endif; ?>

    <div class="table-responsive">
        <table class="table table-bordered committee-table">

            <thead>
                <tr>
                    <th>Sr. No.</th>
                    <th>Committee</th>
                    <th>Document Name</th>
                    <th>PDF</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>

            <?php if ($policeListResult && $policeListResult->num_rows > 0): ?>

                <?php while ($policeRow = $policeListResult->fetch_assoc()): ?>

                    <tr>
                        <td>
                            <?= policeEscape(
                                str_pad(
                                    (string)$policeRow['police_document_sr_no'],
                                    2,
                                    '0',
                                    STR_PAD_LEFT
                                )
                            ) ?>
                        </td>

                        <td>
                            <?= policeEscape($policeRow['police_committee_title']) ?>
                        </td>

                        <td>
                            <?= policeEscape($policeRow['police_document_name']) ?>
                        </td>

                        <td>
                            <?php if (!empty($policeRow['police_document_pdf'])): ?>
                                <a href="/ajmera/<?= policeEscape(
                                    ltrim($policeRow['police_document_pdf'], '/')
                                ) ?>" target="_blank" rel="noopener noreferrer">
                                    View PDF
                                </a>
                            <?php else: ?>
                                No PDF
                            <?php endif; ?>
                        </td>

                        <td>
                            <a href="edit.php?id=<?= (int)$policeRow['police_document_id'] ?>"
                               class="btn btn-sm btn-warning">
                                Edit
                            </a>

                            <form method="POST"
                                  action="delete.php"
                                  class="d-inline"
                                  onsubmit="return confirm('Delete this document?');">

                                <input type="hidden"
                                       name="police_document_id"
                                       value="<?= (int)$policeRow['police_document_id'] ?>">

                                <button type="submit" class="btn btn-sm btn-danger">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>

                <?php endwhile; ?>

            <?php else: ?>

                <tr>
                    <td colspan="5" class="text-center">
                        No police documents found.
                    </td>
                </tr>

            <?php endif; ?>

            </tbody>
        </table>
    </div>

</div>

<?php include '../includes/footer.php'; ?>