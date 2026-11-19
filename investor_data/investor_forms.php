
<?php
require_once __DIR__ . '/../dbc.php';

// Fetch Investor Forms
$reports = [];

$stmt = $conn->prepare("
    SELECT
        id,
        form_title,
        pdf_file
    FROM investor_forms
    WHERE status = 1
    ORDER BY sort_order ASC, id DESC
");

$stmt->execute();

$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $reports[] = $row;
}

$stmt->close();
?>

<div class="vertical-tabs-inner">

    <div class="row">
        <div class="col-md-12">
            <h3 class="h3">Forms</h3>
            <p>Investor Forms</p>
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

                <?php if (!empty($reports)): ?>

                    <?php $i = 1; ?>

                    <?php foreach ($reports as $report): ?>

                        <tr>
                            <td>
                                <?= sprintf('%02d', $i++) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars(
                                    $report['form_title'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </td>

                            <td>

                                <?php if (!empty($report['pdf_file'])): ?>

                                    <?php
                                    $pdfPath = '/ajmera/' . ltrim(
                                        $report['pdf_file'],
                                        '/'
                                    );
                                    ?>

                                    <div class="committee-documents">

                                        <a
                                            href="<?= htmlspecialchars($pdfPath, ENT_QUOTES, 'UTF-8') ?>"
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
                            No forms available.
                        </td>
                    </tr>

                <?php endif; ?>

            </tbody>

        </table>

    </div>

</div>
