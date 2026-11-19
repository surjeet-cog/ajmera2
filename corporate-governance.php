<?php

// =====================================
// CORPORATE GOVERNANCE REPORT
// =====================================

// Fetch financial years
$cgrYears = [];

$cgrYearQuery = $conn->query("
    SELECT DISTINCT cgr_financial_year
    FROM cgr_reports
    WHERE cgr_status = 1
    ORDER BY cgr_financial_year DESC
");

if ($cgrYearQuery) {

    while ($cgrYearRow = $cgrYearQuery->fetch_assoc()) {

        $cgrYears[] = $cgrYearRow['cgr_financial_year'];

    }

}

// Selected year
$cgrSelectedYear = $_GET['financial_year'] ?? '';

if (
    $cgrSelectedYear === '' ||
    !in_array($cgrSelectedYear, $cgrYears, true)
) {

    $cgrSelectedYear = $cgrYears[0] ?? '';

}

// Fetch reports
$cgrReports = [];

if ($cgrSelectedYear !== '') {

    $cgrReportQuery = $conn->prepare("
        SELECT
            cgr_report_name,
            cgr_report_pdf
        FROM cgr_reports
        WHERE cgr_status = 1
          AND cgr_financial_year = ?
        ORDER BY cgr_sort_order ASC,
                 cgr_report_id ASC
    ");

    $cgrReportQuery->bind_param(
        "s",
        $cgrSelectedYear
    );

    $cgrReportQuery->execute();

    $cgrReportResult = $cgrReportQuery->get_result();

    while ($cgrReportRow = $cgrReportResult->fetch_assoc()) {

        $cgrReports[] = $cgrReportRow;

    }

    $cgrReportQuery->close();

}

?>

<div class="vertical-tabs-inner">

    <div class="row">

        <div class="col-md-10">

            <h3 class="h3">
                Corporate Governance Report
            </h3>

            <p>
                Our governance framework, policies and corporate reporting.
            </p>

        </div>

        <div class="col-md-2">

            <div class="financial-year">

                <p>Financial year</p>

                <select
                    class="select_date"
                    id="cgrFinancialYear"
                    name="financial_year">

                    <?php foreach ($cgrYears as $cgrYear): ?>

                        <option
                            value="<?= htmlspecialchars($cgrYear, ENT_QUOTES, 'UTF-8') ?>"
                            <?= ($cgrSelectedYear === $cgrYear) ? 'selected' : '' ?>>

                            FY <?= htmlspecialchars($cgrYear, ENT_QUOTES, 'UTF-8') ?>

                        </option>

                    <?php endforeach; ?>

                </select>

            </div>

        </div>

    </div>

    <!-- Table design stays fixed -->
    <div class="table-responsive">

        <table class="table committee-table">

            <thead>

                <tr>
                    <th>Sr. No</th>
                    <th>Title</th>
                    <th>Documents</th>
                </tr>

            </thead>

            <tbody id="cgrTableBody">

                <?php if (!empty($cgrReports)): ?>

                    <?php $cgrSrNo = 1; ?>

                    <?php foreach ($cgrReports as $cgrReport): ?>

                        <tr>

                            <td>
                                <?= str_pad(
                                    $cgrSrNo++,
                                    2,
                                    '0',
                                    STR_PAD_LEFT
                                ) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars(
                                    $cgrReport['cgr_report_name'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </td>

                            <td>

                                <?php if (!empty($cgrReport['cgr_report_pdf'])): ?>

                                    <div class="committee-documents">

                                        <a
                                            href="/ajmera/<?= htmlspecialchars(
                                                ltrim($cgrReport['cgr_report_pdf'], '/'),
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>"
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            class="committee-pdf-link">

                                            <!--<i class="fas fa-file-pdf"></i>-->

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

                            No reports available for FY
                            <?= htmlspecialchars(
                                $cgrSelectedYear,
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>.

                        </td>

                    </tr>

                <?php endif; ?>

            </tbody>

        </table>

    </div>

</div>

<script>
document.addEventListener("DOMContentLoaded", function () {

    const cgrYearSelect = document.getElementById("cgrFinancialYear");
    const cgrTableBody = document.getElementById("cgrTableBody");

    if (!cgrYearSelect || !cgrTableBody) {
        return;
    }

    cgrYearSelect.addEventListener("change", function () {

        const selectedYear = this.value;

        cgrTableBody.innerHTML = `
            <tr>
                <td colspan="3" class="text-center">
                    Loading reports...
                </td>
            </tr>
        `;

        const formData = new FormData();

        formData.append("financial_year", selectedYear);

        fetch("/ajmera/cgr-filter.php", {
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

            // Update only table body
            cgrTableBody.innerHTML = html;

            // Update URL without page refresh
            const currentUrl = new URL(window.location.href);

            currentUrl.searchParams.set(
                "financial_year",
                selectedYear
            );

            window.history.replaceState(
                {},
                "",
                currentUrl
            );

        })

        .catch(function (error) {

            cgrTableBody.innerHTML = `
                <tr>
                    <td colspan="3" class="text-center">
                        Unable to load reports. Please try again.
                    </td>
                </tr>
            `;

            console.error(error);

        });

    });

});
</script>