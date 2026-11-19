<?php
require_once 'dbc.php';

header('Content-Type: text/html; charset=UTF-8');

$financialYear = $_GET['year'] ?? '';

if (!preg_match('/^\d{4}-\d{4}$/', $financialYear)) {
    http_response_code(400);
    echo '<tr><td colspan="5">Invalid financial year.</td></tr>';
    exit;
}

$sql = "SELECT title, q1_pdf, q2_h1_pdf, q3_9m_pdf, q4_fy_pdf
        FROM financial_reports
        WHERE financial_year = ? AND status = 1
        ORDER BY id DESC";

$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    http_response_code(500);
    echo '<tr><td colspan="5">Unable to load reports.</td></tr>';
    exit;
}

mysqli_stmt_bind_param($stmt, "s", $financialYear);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if (!$result || mysqli_num_rows($result) === 0) {
    echo '<tr><td colspan="5">No financial reports available for this year.</td></tr>';
    mysqli_stmt_close($stmt);
    exit;
}

function renderFinancialPdf($path)
{
    if (empty($path)) {
        return '<td>—</td>';
    }

    $path = trim($path);

    if (
        strpos($path, 'uploads/financials/') !== 0 ||
        strpos($path, '..') !== false ||
        !preg_match('/\.pdf$/i', $path)
    ) {
        return '<td>—</td>';
    }

    $url = '/ajmera/' . ltrim($path, '/');
    $url = htmlspecialchars($url, ENT_QUOTES, 'UTF-8');

    return '<td>
        <div class="committee-documents">
            <a href="' . $url . '"
               target="_blank"
               rel="noopener noreferrer"
               class="committee-pdf-link">
                <img src="images/pdf.svg" class="pdf" alt="View PDF">
            </a>
        </div>
    </td>';
}

while ($row = mysqli_fetch_assoc($result)) {
    echo '<tr>';

    echo '<td>' .
        htmlspecialchars($row['title'], ENT_QUOTES, 'UTF-8') .
        '</td>';

    echo renderFinancialPdf($row['q1_pdf']);
    echo renderFinancialPdf($row['q2_h1_pdf']);
    echo renderFinancialPdf($row['q3_9m_pdf']);
    echo renderFinancialPdf($row['q4_fy_pdf']);

    echo '</tr>';
}

mysqli_stmt_close($stmt);
?>