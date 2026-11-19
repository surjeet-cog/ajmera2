
<?php

// Fetch Financial Years
$newspaperYears = [];

$newspaperYearQuery = $conn->query("
    SELECT DISTINCT financial_year
    FROM newspaper_publications
    WHERE status = 1
    ORDER BY financial_year DESC
");

if ($newspaperYearQuery) {
    while ($row = $newspaperYearQuery->fetch_assoc()) {
        $newspaperYears[] = $row['financial_year'];
    }
}

// Selected Financial Year
$newspaperSelectedYear = $_GET['financial_year'] ?? '';

if (
    $newspaperSelectedYear === '' ||
    !in_array($newspaperSelectedYear, $newspaperYears, true)
) {
    $newspaperSelectedYear = $newspaperYears[0] ?? '';
}

// Fetch Newspaper Publications
$newspaperReports = [];

if ($newspaperSelectedYear !== '') {

    $newspaperQuery = $conn->prepare("
        SELECT title, pdf_file
        FROM newspaper_publications
        WHERE status = 1
          AND financial_year = ?
        ORDER BY sort_order ASC, id ASC
    ");

    $newspaperQuery->bind_param("s", $newspaperSelectedYear);
    $newspaperQuery->execute();

    $newspaperResult = $newspaperQuery->get_result();

    while ($row = $newspaperResult->fetch_assoc()) {
        $newspaperReports[] = $row;
    }

    $newspaperQuery->close();
}
?>

<div class="vertical-tabs-inner">

    <div class="row">

        <div class="col-md-10">
            <h3 class="h3">Newspaper Publication</h3>
            <p>Newspaper publications and statutory announcements.</p>
        </div>

        <div class="col-md-2">

            <div class="financial-year">

                <p>Financial year</p>

                <select
                    class="select_date"
                    id="newspaperFinancialYear"
                    name="financial_year">

                    <?php foreach ($newspaperYears as $year): ?>

                        <option
                            value="<?= htmlspecialchars($year, ENT_QUOTES, 'UTF-8') ?>"
                            <?= ($newspaperSelectedYear === $year) ? 'selected' : '' ?>>

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

            <tbody id="newspaperTableBody">

                <?php if (!empty($newspaperReports)): ?>

                    <?php $srNo = 1; ?>

                    <?php foreach ($newspaperReports as $report): ?>

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
                            No newspaper publications available.
                        </td>
                    </tr>

                <?php endif; ?>

            </tbody>

        </table>

    </div>

</div>
