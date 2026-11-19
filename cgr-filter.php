<?php

require_once __DIR__ . '/dbc.php';

// Only POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    http_response_code(405);
    exit;

}

// Selected financial year
$cgrAjaxYear = $_POST['financial_year'] ?? '';

// Validate year format
if (!preg_match('/^\d{4}-\d{4}$/', $cgrAjaxYear)) {

    http_response_code(400);
    exit;

}

// Check year exists
$cgrYearCheck = $conn->prepare("
    SELECT 1
    FROM cgr_reports
    WHERE cgr_financial_year = ?
      AND cgr_status = 1
    LIMIT 1
");

$cgrYearCheck->bind_param(
    "s",
    $cgrAjaxYear
);

$cgrYearCheck->execute();

$cgrYearExists = $cgrYearCheck->get_result()->num_rows > 0;

$cgrYearCheck->close();

if (!$cgrYearExists) {

    http_response_code(400);
    exit;

}

// Fetch selected year reports
$cgrAjaxQuery = $conn->prepare("
    SELECT
        cgr_report_name,
        cgr_report_pdf
    FROM cgr_reports
    WHERE cgr_financial_year = ?
      AND cgr_status = 1
    ORDER BY cgr_sort_order ASC,
             cgr_report_id ASC
");

$cgrAjaxQuery->bind_param(
    "s",
    $cgrAjaxYear
);

$cgrAjaxQuery->execute();

$cgrAjaxResult = $cgrAjaxQuery->get_result();

$cgrAjaxSrNo = 1;

if ($cgrAjaxResult->num_rows > 0) {

    while ($cgrAjaxReport = $cgrAjaxResult->fetch_assoc()) {

        ?>

        <tr>

            <td>
                <?= str_pad(
                    $cgrAjaxSrNo++,
                    2,
                    '0',
                    STR_PAD_LEFT
                ) ?>
            </td>

            <td>
                <?= htmlspecialchars(
                    $cgrAjaxReport['cgr_report_name'],
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>
            </td>

            <td>

                <?php if (!empty($cgrAjaxReport['cgr_report_pdf'])): ?>

                    <div class="committee-documents">

                        <a
                            href="/ajmera/<?= htmlspecialchars(
                                ltrim($cgrAjaxReport['cgr_report_pdf'], '/'),
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="committee-pdf-link">

                            <i class="fas fa-file-pdf"></i>

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

        <?php

    }

} else {

    ?>

    <tr>

        <td colspan="3" class="text-center">

            No reports available for FY
            <?= htmlspecialchars(
                $cgrAjaxYear,
                ENT_QUOTES,
                'UTF-8'
            ) ?>.

        </td>

    </tr>

    <?php

}

$cgrAjaxQuery->close();

exit;