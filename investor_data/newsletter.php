
<?php
require_once __DIR__ . '/../dbc.php';

// Fetch Financial Years
$newsletterYears = [];

$newsletterYearQuery = $conn->query("
    SELECT DISTINCT cgr_financial_year
    FROM newsletter_reports
    WHERE cgr_status = 1
    AND cgr_financial_year != ''
    ORDER BY cgr_financial_year DESC
");

if ($newsletterYearQuery) {
    while ($yearRow = $newsletterYearQuery->fetch_assoc()) {
        $newsletterYears[] = $yearRow['cgr_financial_year'];
    }
}

// Selected Financial Year
$newsletterSelectedYear = $_GET['financial_year'] ?? '';

if (
    $newsletterSelectedYear === '' ||
    !in_array($newsletterSelectedYear, $newsletterYears, true)
) {
    $newsletterSelectedYear = $newsletterYears[0] ?? '';
}

// Fetch Newsletter Reports
$newsletterReports = [];

if ($newsletterSelectedYear !== '') {

    $newsletterStmt = $conn->prepare("
        SELECT cgr_report_name, cgr_report_pdf
        FROM newsletter_reports
        WHERE cgr_status = 1
        AND cgr_financial_year = ?
        ORDER BY cgr_sort_order ASC, cgr_report_id DESC
    ");

    $newsletterStmt->bind_param("s", $newsletterSelectedYear);
    $newsletterStmt->execute();

    $newsletterResult = $newsletterStmt->get_result();

    while ($row = $newsletterResult->fetch_assoc()) {
        $newsletterReports[] = $row;
    }

    $newsletterStmt->close();
}
?>

<div class="vertical-tabs-inner">

    <div class="row">

        <div class="col-md-10">
            <h3 class="h3">Newsletter Reports</h3>
            <p>Explore our newsletter reports by financial year.</p>
        </div>

        <div class="col-md-2">
            <div class="financial-year">

                <p>Financial year</p>

                <select
                    class="select_date"
                    id="newsletterFinancialYear"
                    name="financial_year">

                    <?php foreach ($newsletterYears as $year): ?>

                        <option
                            value="<?= htmlspecialchars($year, ENT_QUOTES, 'UTF-8') ?>"
                            <?= ($newsletterSelectedYear === $year) ? 'selected' : '' ?>>

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

            <tbody id="newsletterTableBody">

                <?php if (!empty($newsletterReports)): ?>

                    <?php $newsletterSrNo = 1; ?>

                    <?php foreach ($newsletterReports as $report): ?>

                        <?php
                        $pdfPath = '/ajmera/' . ltrim($report['cgr_report_pdf'], '/');
                        ?>

                        <tr>

                            <td>
                                <?= sprintf('%02d', $newsletterSrNo++) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars(
                                    $report['cgr_report_name'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </td>

                            <td>

                                <?php if (!empty($report['cgr_report_pdf'])): ?>

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
                            No reports available.
                        </td>
                    </tr>

                <?php endif; ?>

            </tbody>

        </table>

    </div>

</div>

<script>
document.addEventListener("DOMContentLoaded", function () {

    const newsletterYearSelect = document.getElementById("newsletterFinancialYear");
    const newsletterTableBody = document.getElementById("newsletterTableBody");

    if (!newsletterYearSelect || !newsletterTableBody) {
        return;
    }

    newsletterYearSelect.addEventListener("change", function () {

        const selectedYear = this.value;

        newsletterTableBody.innerHTML = `
            <tr>
                <td colspan="3" class="text-center">
                  
                </td>
            </tr>
        `;

        const formData = new FormData();
        formData.append("financial_year", selectedYear);

        fetch("investor_data/newsletter-filter.php", {
            method: "POST",
            body: formData
        })

        .then(function (response) {

            if (!response.ok) {
                throw new Error("Failed to load reports");
            }

            return response.text();

        })

        .then(function (html) {

            newsletterTableBody.innerHTML = html;

            const currentUrl = new URL(window.location.href);

            currentUrl.searchParams.set(
                "financial_year",
                selectedYear
            );

            window.history.replaceState({}, "", currentUrl);

        })

        .catch(function (error) {

            console.error("Newsletter Filter Error:", error);

            newsletterTableBody.innerHTML = `
                <tr>
                    <td colspan="3" class="text-center">
                        Unable to load reports. Please try again.
                    </td>
                </tr>
            `;

        });

    });

});
</script>
