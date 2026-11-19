
<?php
require_once __DIR__ . '/../dbc.php';

header('Content-Type: text/html; charset=UTF-8');

$financialYear = trim($_POST['financial_year'] ?? '');

$stmt = $conn->prepare("
    SELECT cgr_report_name, cgr_report_pdf
    FROM newsletter_reports
    WHERE cgr_status = 1
    AND cgr_financial_year = ?
    ORDER BY cgr_sort_order ASC, cgr_report_id DESC
");

$stmt->bind_param("s", $financialYear);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows > 0) {

    $i = 1;

    while ($row = $result->fetch_assoc()) {

        $pdfPath = '/ajmera/' . ltrim($row['cgr_report_pdf'], '/');
        ?>

        <tr>
            <td><?= sprintf('%02d', $i++) ?></td>

            <td>
                <?= htmlspecialchars($row['cgr_report_name'], ENT_QUOTES, 'UTF-8') ?>
            </td>

            <td>
                <div class="committee-documents">
                    <a href="<?= htmlspecialchars($pdfPath, ENT_QUOTES, 'UTF-8') ?>"
                       target="_blank"
                       rel="noopener noreferrer"
                       class="committee-pdf-link">

                        <i class="fas fa-file-pdf"></i>

                        <img src="/ajmera/images/pdf.svg"
                             class="pdf"
                             alt="View PDF">
                    </a>
                </div>
            </td>
        </tr>

        <?php
    }

} else {
    echo '<tr><td colspan="3" class="text-center">No reports available.</td></tr>';
}

$stmt->close();
$conn->close();
