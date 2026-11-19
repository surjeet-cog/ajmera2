
<?php
require_once __DIR__ . '/../dbc.php';

// Fetch Physical Shareholder Forms
$physicalShareholderForms = [];

$stmt = $conn->prepare("
    SELECT
        id,
        title,
        pdf_file
    FROM physical_shareholder_forms
    WHERE status = 1
    ORDER BY sort_order ASC, id DESC
");

$stmt->execute();

$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $physicalShareholderForms[] = $row;
}

$stmt->close();
?>

<div class="vertical-tabs-inner">

    <div class="row">
        <div class="col-md-12">
            <h3 class="h3">Forms for Physical Shareholders</h3>
            <p>Forms and documents for physical shareholders.</p>
        </div>
    </div>

    <div class="table-responsive">

        <table class="table committee-table">

            <thead>
                <tr>
                    <th>Sr. No</th>
                    <th>Title</th>
                    <th>Documents</th>
                </tr>
            </thead>

            <tbody>

                <?php if (!empty($physicalShareholderForms)): ?>

                    <?php $srNo = 1; ?>

                    <?php foreach ($physicalShareholderForms as $form): ?>

                        <tr>

                            <td>
                                <?= str_pad($srNo++, 2, '0', STR_PAD_LEFT) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars(
                                    $form['title'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </td>

                            <td>

                                <?php if (!empty($form['pdf_file'])): ?>

                                    <div class="committee-documents">

                                        <a
                                            href="/ajmera/<?= htmlspecialchars(
                                                ltrim($form['pdf_file'], '/'),
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>"
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            class="committee-pdf-link">

                                            <img
                                                src="/ajmera/images/pdf.svg"
                                                class="pdf"
                                                alt="View PDF">

                                        </a>

                                    </div>

                                <?php else: ?>

                                    No Document

                                <?php endif; ?>

                            </td>

                        </tr>

                    <?php endforeach; ?>

                <?php else: ?>

                    <tr>
                        <td colspan="3" class="text-center">
                            No physical shareholder forms available.
                        </td>
                    </tr>

                <?php endif; ?>

            </tbody>

        </table>

    </div>

</div>
