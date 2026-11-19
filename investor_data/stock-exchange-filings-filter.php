
<?php
require_once __DIR__ . '/../dbc.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Method not allowed');
}

$financialYear = trim($_POST['financial_year'] ?? '');

if ($financialYear === '') {
    echo '<tr><td colspan="3" class="text-center">Please select a financial year.</td></tr>';
    exit;
}

// Validate financial year
$checkStmt = $conn->prepare("
    SELECT 1
    FROM stock_exchange_filings
    WHERE financial_year = ? AND status = 1
    LIMIT 1
");

$checkStmt->bind_param("s", $financialYear);
$checkStmt->execute();

$checkResult = $checkStmt->get_result();
$isValidYear = $checkResult->num_rows > 0;

$checkStmt->close();

if (!$isValidYear) {
    echo '<tr><td colspan="3" class="text-center">No records available.</td></tr>';
    exit;
}

// Fetch records
$stmt = $conn->prepare("
    SELECT id, title, pdf_file
    FROM stock_exchange_filings
    WHERE status = 1 AND financial_year = ?
    ORDER BY sort_order ASC, id DESC
");

$stmt->bind_param("s", $financialYear);
$stmt->execute();

$result = $stmt->get_result();

$srNo = 1;

if ($result->num_rows > 0) {

    while ($row = $result->fetch_assoc()) {

        $title = htmlspecialchars($row['title'], ENT_QUOTES, 'UTF-8');
        $pdfFile = htmlspecialchars(
            ltrim($row['pdf_file'] ?? '', '/'),
            ENT_QUOTES,
            'UTF-8'
        );

        echo '<tr>';

        echo '<td>' . str_pad($srNo++, 2, '0', STR_PAD_LEFT) . '</td>';

        echo '<td>' . $title . '</td>';

        echo '<td>';

        if (!empty($pdfFile)) {
            echo '<div class="committee-documents">';
            echo '<a href="/ajmera/' . $pdfFile . '" target="_blank" rel="noopener noreferrer" class="committee-pdf-link" aria-label="View PDF: ' . $title . '">';
            echo '<i class="fas fa-file-pdf"></i>';
            echo '<img src="/ajmera/images/pdf.svg" class="pdf" alt="PDF">';
            echo '</a>';
            echo '</div>';
        } else {
            echo 'No Document';
        }

        echo '</td>';
        echo '</tr>';
    }

} else {

    echo '<tr><td colspan="3" class="text-center">No records available.</td></tr>';

}

$stmt->close();
