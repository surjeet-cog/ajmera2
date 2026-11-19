
<?php
require_once __DIR__ . '/../dbc.php';

// Fetch Financial Years
$shareholdingYears = [];

$yearQuery = $conn->query("
    SELECT DISTINCT financial_year
    FROM shareholding_patterns
    WHERE status = 1
    ORDER BY financial_year DESC
");

if ($yearQuery) {
    while ($row = $yearQuery->fetch_assoc()) {
        $shareholdingYears[] = $row['financial_year'];
    }
}

// Selected Financial Year
$shareholdingSelectedYear = $_GET['financial_year'] ?? '';

if (
    $shareholdingSelectedYear === '' ||
    !in_array($shareholdingSelectedYear, $shareholdingYears, true)
) {
    $shareholdingSelectedYear = $shareholdingYears[0] ?? '';
}

// Fetch Shareholding Patterns
$shareholdingReports = [];

if ($shareholdingSelectedYear !== '') {

    $stmt = $conn->prepare("
        SELECT title, pdf_file
        FROM shareholding_patterns
        WHERE status = 1
          AND financial_year = ?
        ORDER BY sort_order ASC, id ASC
    ");

    $stmt->bind_param("s", $shareholdingSelectedYear);
    $stmt->execute();

    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $shareholdingReports[] = $row;
    }

    $stmt->close();
}
?>

<div class="vertical-tabs-inner">

    <div class="row">

        <div class="col-md-10">
            <h3 class="h3">Shareholding Pattern</h3>
            <p>Shareholding pattern disclosures and reports.</p>
        </div>

        <div class="col-md-2">
            <div class="financial-year">

                <p>Financial year</p>

                <select
                    class="select_date"
                    id="shareholdingFinancialYear"
                    name="financial_year">

                    <?php foreach ($shareholdingYears as $year): ?>

                        <option
                            value="<?= htmlspecialchars($year, ENT_QUOTES, 'UTF-8') ?>"
                            <?= ($shareholdingSelectedYear === $year) ? 'selected' : '' ?>>

                            FY <?= htmlspecialchars($year, ENT_QUOTES, 'UTF-8') ?>

                        </option>

                    <?php endforeach; ?>

                </select>

            </div>
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

            <tbody id="shareholdingTableBody">

                <?php if (!empty($shareholdingReports)): ?>

                    <?php $srNo = 1; ?>

                    <?php foreach ($shareholdingReports as $report): ?>

                        <tr>

                            <td>
                                <?= str_pad($srNo++, 2, '0', STR_PAD_LEFT) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars(
                                    $report['title'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </td>

                            <td>

                                <?php if (!empty($report['pdf_file'])): ?>

                                    <div class="committee-documents">

                                        <a
                                            href="/ajmera/<?= htmlspecialchars(ltrim($report['pdf_file'], '/'), ENT_QUOTES, 'UTF-8') ?>"
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
                            No shareholding pattern reports available.
                        </td>
                    </tr>

                <?php endif; ?>

            </tbody>

        </table>

    </div>

</div>
