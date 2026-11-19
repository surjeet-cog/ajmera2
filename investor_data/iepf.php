
<?php
require_once __DIR__ . '/../dbc.php';

$categories = [];

$stmt = $conn->prepare("
    SELECT id, category, title, pdf_file
    FROM iepf_documents
    WHERE status = 1
    ORDER BY category ASC, sort_order ASC, id DESC
");

$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $categories[$row['category']][] = $row;
}

$stmt->close();
?>

<div class="vertical-tabs-inner">

    <div class="row">
        <div class="col-md-12">
            <h3 class="h3">IEPF</h3>
            <p>Dummy text</p>
        </div>
    </div>
<div class="accordion committee-accordion" id="iepfAccordion">

    <?php if (!empty($categories)): ?>

        <?php $categoryIndex = 1; ?>

        <?php foreach ($categories as $category => $documents): ?>

            <?php
            $headingId = 'iepfHeading' . $categoryIndex;
            $collapseId = 'iepfCollapse' . $categoryIndex;
            ?>

            <div class="accordion-item">

                <h2 class="accordion-header" id="<?= $headingId ?>">
                    <button
                        class="accordion-button collapsed"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#<?= $collapseId ?>"
                        aria-expanded="false"
                        aria-controls="<?= $collapseId ?>">
                        <?= htmlspecialchars($category, ENT_QUOTES, 'UTF-8') ?>
                    </button>
                </h2>

                <div
                    id="<?= $collapseId ?>"
                    class="accordion-collapse collapse"
                    aria-labelledby="<?= $headingId ?>"
                    data-bs-parent="#iepfAccordion">

                    <div class="accordion-body">

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

                                    <?php $srNo = 1; ?>

                                    <?php foreach ($documents as $row): ?>

                                        <tr>
                                            <td>
                                                <?= str_pad($srNo++, 2, '0', STR_PAD_LEFT) ?>
                                            </td>

                                            <td>
                                                <?= htmlspecialchars($row['title'], ENT_QUOTES, 'UTF-8') ?>
                                            </td>

                                            <td>
                                                <?php if (!empty($row['pdf_file'])): ?>

                                                    <div class="committee-documents">

                                                        <a href="/ajmera/<?= htmlspecialchars(ltrim($row['pdf_file'], '/'), ENT_QUOTES, 'UTF-8') ?>"
                                                           target="_blank"
                                                           rel="noopener noreferrer"
                                                           class="committee-pdf-link"
                                                           aria-label="View PDF: <?= htmlspecialchars($row['title'], ENT_QUOTES, 'UTF-8') ?>">

                                                            <i class="fas fa-file-pdf"></i>

                                                            <img src="/ajmera/images/pdf.svg"
                                                                 class="pdf"
                                                                 alt="PDF">

                                                        </a>

                                                    </div>

                                                <?php else: ?>
                                                    No Document
                                                <?php endif; ?>
                                            </td>
                                        </tr>

                                    <?php endforeach; ?>

                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>

            <?php $categoryIndex++; ?>

        <?php endforeach; ?>

    <?php else: ?>

        <div class="text-center">
            No records available.
        </div>

    <?php endif; ?>

</div>
 </div>