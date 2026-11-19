
<?php
require_once __DIR__ . '/../dbc.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit;
}

$selectedYear = trim($_POST['financial_year'] ?? '');

if ($selectedYear === '') {
    http_response_code(400);
    exit('Invalid financial year');
}

// Validate Financial Year
$yearStmt = $conn->prepare("
    SELECT id
    FROM postal_ballots
    WHERE financial_year = ?
      AND status = 1
    LIMIT 1
");

$yearStmt->bind_param("s", $selectedYear);
$yearStmt->execute();

$yearResult = $yearStmt->get_result();

if ($yearResult->num_rows === 0) {
    http_response_code(400);
    exit('Invalid financial year');
}

$yearStmt->close();

// Fetch Records
$stmt = $conn->prepare("
    SELECT title, pdf_file
    FROM postal_ballots
    WHERE status = 1
      AND financial_year = ?
    ORDER BY sort_order ASC, id ASC
");

$stmt->bind_param("s", $selectedYear);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows > 0) {

    $srNo = 1;

    while ($row = $result->fetch_assoc()) {
        ?>

        <tr>
            <td>
                <?= str_pad($srNo++, 2, '0', STR_PAD_LEFT) ?>
            </td>

            <td>
                <?= htmlspecialchars($row['title'], ENT_QUOTES, 'UTF-8') ?>
            </td>

            <td>
                <?php if (!empty($row['pdf_file'])): ?>

                    <div class="committee-documents">

                        <a
                            href="/ajmera/<?= htmlspecialchars(ltrim($row['pdf_file'], '/'), ENT_QUOTES, 'UTF-8') ?>"
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

        <?php
    }

} else {
    ?>

    <tr>
        <td colspan="3" class="text-center">
            No postal ballot records available for FY
            <?= htmlspecialchars($selectedYear, ENT_QUOTES, 'UTF-8') ?>.
        </td>
    </tr>

    <?php
}

$stmt->close();
