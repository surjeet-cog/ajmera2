
<?php
require_once __DIR__ . '/dbc.php'; // Agar page root mein hai

function h($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

// Fetch available financial years
$years = [];

$yearResult = $conn->query("
    SELECT DISTINCT financial_year
    FROM annual_reports
    WHERE status = 1
    ORDER BY financial_year DESC
");

if (!$yearResult) {
    error_log('Annual report year query failed: ' . $conn->error);
}

// Store years
if ($yearResult) {
    while ($row = $yearResult->fetch_assoc()) {
        $years[] = (string)$row['financial_year'];
    }
}

// Selected year
$selectedYear = trim($_GET['financial_year'] ?? '');

if ($selectedYear === '' && !empty($years)) {
    $selectedYear = $years[0];
}

// Only allow a year that exists in the active reports
if ($selectedYear !== '' && !in_array($selectedYear, $years, true)) {
    $selectedYear = $years[0] ?? '';
}

// Fetch reports for selected year
$reports = [];

if ($selectedYear !== '') {
    $stmt = $conn->prepare("
        SELECT id, category, title, pdf_file, sort_order
        FROM annual_reports
        WHERE financial_year = ? AND status = 1
        ORDER BY category ASC, sort_order ASC, id ASC
    ");

    if ($stmt) {
        $stmt->bind_param("s", $selectedYear);

        if ($stmt->execute()) {
            $result = $stmt->get_result();

            while ($row = $result->fetch_assoc()) {
                $reports[] = $row;
            }
        } else {
            error_log('Annual reports query failed: ' . $stmt->error);
        }

        $stmt->close();
    } else {
        error_log('Annual reports prepare failed: ' . $conn->error);
    }
}

// Group reports by category
$groupedReports = [];

foreach ($reports as $report) {
    $category = $report['category'] ?: 'Other';
    $groupedReports[$category][] = $report;
}
?>

<div class="vertical-tabs-inner">

    <div class="row align-items-center">
        <div class="col-md-10">
            <h3 class="h3">Annual Reports</h3>
            <p>Our governance framework, policies and corporate reporting.</p>
        </div>

        <div class="col-md-2">
            <form method="GET" action="">
                <div class="financial-year">
                    <p>Financial year</p>

                    <select
                        class="select_date"
                        id="financialYear"
                        name="financial_year"
                        onchange="this.form.submit()"
                    >
                        <?php if (empty($years)): ?>
                            <option value="">No financial years available</option>
                        <?php else: ?>
                            <?php foreach ($years as $year): ?>
                                <option
                                    value="<?= h($year) ?>"
                                    <?= $selectedYear === $year ? 'selected' : '' ?>
                                >
                                    FY <?= h($year) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
            </form>
        </div>
    </div>

    <div class="accordion committee-accordion mt-5" id="annualreports">

        <?php if (!empty($groupedReports)): ?>

            <?php $index = 0; ?>

            <?php foreach ($groupedReports as $category => $documents): ?>
                <?php
                    $index++;
                    $headingId = 'annualHeading' . $index;
                    $collapseId = 'annualCollapse' . $index;
                ?>

                <div class="accordion-item">

                    <h2 class="accordion-header" id="<?= h($headingId) ?>">
                        <button
                            class="accordion-button collapsed"
                            type="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#<?= h($collapseId) ?>"
                            aria-expanded="false"
                            aria-controls="<?= h($collapseId) ?>"
                        >
                            <?= h($category) ?>
                        </button>
                    </h2>

                    <div
                        id="<?= h($collapseId) ?>"
                        class="accordion-collapse collapse"
                        aria-labelledby="<?= h($headingId) ?>"
                        data-bs-parent="#annualreports"
                    >
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
                                        <?php $sr = 0; ?>

                                        <?php foreach ($documents as $doc): ?>
                                            <?php $sr++; ?>

                                            <tr>
                                                <td>
                                                    <?= str_pad((string)$sr, 2, '0', STR_PAD_LEFT) ?>
                                                </td>

                                                <td><?= h($doc['title']) ?></td>

                                                <td>
                                                    <div class="committee-documents">
                                                        <a
                                                            href="<?= h($doc['pdf_file']) ?>"
                                                            target="_blank"
                                                            rel="noopener noreferrer"
                                                            class="committee-pdf-link"
                                                            aria-label="View PDF: <?= h($doc['title']) ?>"
                                                        >
                                                            <i class="fas fa-file-pdf"></i>
                                                            <img
                                                                src="images/pdf.svg"
                                                                class="pdf"
                                                                alt="PDF"
                                                            >
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

        <?php else: ?>
            <p>
                No annual reports available
                <?php if ($selectedYear !== ''): ?>
                    for FY <?= h($selectedYear) ?>
                <?php endif; ?>.
            </p>
        <?php endif; ?>

    </div>
</div>
