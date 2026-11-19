<?php

// Fetch financial years
$mediaYears = [];

$mediaYearQuery = $conn->query("
    SELECT DISTINCT financial_year
    FROM media_press_releases
    WHERE status = 1
    ORDER BY financial_year DESC
");

if ($mediaYearQuery) {
    while ($row = $mediaYearQuery->fetch_assoc()) {
        $mediaYears[] = $row['financial_year'];
    }
}

// Selected year
$mediaSelectedYear = $_GET['financial_year'] ?? '';

if (
    $mediaSelectedYear === '' ||
    !in_array($mediaSelectedYear, $mediaYears, true)
) {
    $mediaSelectedYear = $mediaYears[0] ?? '';
}

// Fetch Media / Press Releases
$mediaReports = [];

if ($mediaSelectedYear !== '') {

    $mediaQuery = $conn->prepare("
        SELECT title, pdf_file
        FROM media_press_releases
        WHERE status = 1
          AND financial_year = ?
        ORDER BY sort_order ASC, id ASC
    ");

    $mediaQuery->bind_param("s", $mediaSelectedYear);
    $mediaQuery->execute();

    $mediaResult = $mediaQuery->get_result();

    while ($row = $mediaResult->fetch_assoc()) {
        $mediaReports[] = $row;
    }

    $mediaQuery->close();
}
?>

<div class="vertical-tabs-inner">

    <div class="row">

        <div class="col-md-10">
            <h3 class="h3">Media / Press Release</h3>
            <p>Latest media updates and press releases.</p>
        </div>

        <div class="col-md-2">
            <div class="financial-year">
                <p>Financial year</p>

                <select
                    class="select_date"
                    id="mediaFinancialYear"
                    name="financial_year">

                    <?php foreach ($mediaYears as $year): ?>
                        <option
                            value="<?= htmlspecialchars($year, ENT_QUOTES, 'UTF-8') ?>"
                            <?= ($mediaSelectedYear === $year) ? 'selected' : '' ?>>
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

            <tbody id="mediaTableBody">

                <?php if (!empty($mediaReports)): ?>

                    <?php $srNo = 1; ?>

                    <?php foreach ($mediaReports as $report): ?>

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
                                                src="images/pdf.svg"
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
                            No press releases available
                            <?php if ($mediaSelectedYear !== ''): ?>
                                for FY <?= htmlspecialchars($mediaSelectedYear, ENT_QUOTES, 'UTF-8') ?>.
                            <?php endif; ?>
                        </td>
                    </tr>

                <?php endif; ?>

            </tbody>

        </table>

    </div>

</div>