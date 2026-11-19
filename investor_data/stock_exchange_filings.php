
<?php
require_once __DIR__ . '/../dbc.php';

$years = [];
$rows = [];

// Fetch financial years
$yearResult = $conn->query("
    SELECT DISTINCT financial_year
    FROM stock_exchange_filings
    WHERE status = 1
    ORDER BY financial_year DESC
");

if ($yearResult) {
    while ($yearRow = $yearResult->fetch_assoc()) {
        $years[] = $yearRow['financial_year'];
    }
}

// Get selected financial year
$selectedYear = $_GET['stock_exchange_year'] ?? '';

if (!in_array($selectedYear, $years, true)) {
    $selectedYear = $years[0] ?? '';
}

// Fetch records
if ($selectedYear !== '') {
    $stmt = $conn->prepare("
        SELECT id, title, pdf_file
        FROM stock_exchange_filings
        WHERE status = 1 AND financial_year = ?
        ORDER BY sort_order ASC, id DESC
    ");

    $stmt->bind_param("s", $selectedYear);
    $stmt->execute();

    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }

    $stmt->close();
}
?>

<div class="vertical-tabs-inner">

    <div class="row align-items-center">
        <div class="col-md-10">
            <h3 class="h3">Stock Exchange Filings</h3>
            <p>View and download stock exchange filings and disclosures.</p>
        </div>

        <div class="col-md-2">
            <div class="financial-year">
            <select class="select_date"
                    id="stockExchangeYear">

                <?php if (!empty($years)): ?>
                    <?php foreach ($years as $year): ?>
                        <option value="<?= htmlspecialchars($year, ENT_QUOTES, 'UTF-8') ?>"
                            <?= $selectedYear === $year ? 'selected' : '' ?>>
                            <?= htmlspecialchars($year, ENT_QUOTES, 'UTF-8') ?>
                        </option>
                    <?php endforeach; ?>
                <?php else: ?>
                    <option value="">No Financial Year Available</option>
                <?php endif; ?>

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

            <tbody id="stockExchangeTableBody">

                <?php if (!empty($rows)): ?>

                    <?php $srNo = 1; ?>

                    <?php foreach ($rows as $row): ?>
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
                                        <a href="/ajmera/<?= htmlspecialchars(ltrim($row['pdf_file'], '/'), ENT_QUOTES, 'UTF-8') ?>"
                                           target="_blank"
                                           rel="noopener noreferrer"
                                           class="committee-pdf-link"
                                           aria-label="View PDF: <?= htmlspecialchars($row['title'], ENT_QUOTES, 'UTF-8') ?>">

                                            <i class="fas fa-file-pdf"></i>

                                            <img src="/ajmera/images/pdf.svg"
                                                 class="pdf"
                                                 alt="PDF">
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
                            No records available.
                        </td>
                    </tr>

                <?php endif; ?>

            </tbody>
        </table>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const yearSelect = document.getElementById('stockExchangeYear');
    const tableBody = document.getElementById('stockExchangeTableBody');

    if (!yearSelect || !tableBody) return;

    yearSelect.addEventListener('change', function () {

        const financialYear = this.value;

        tableBody.innerHTML = `
            <tr>
                <td colspan="3" class="text-center">Loading...</td>
            </tr>
        `;

        const formData = new FormData();
        formData.append('financial_year', financialYear);

        fetch('investor_data/stock-exchange-filings-filter.php', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.text();
        })
        .then(html => {
            tableBody.innerHTML = html;

            const url = new URL(window.location.href);

            if (financialYear) {
                url.searchParams.set('stock_exchange_year', financialYear);
            } else {
                url.searchParams.delete('stock_exchange_year');
            }

            window.history.replaceState({}, '', url);
        })
        .catch(error => {
            console.error('Error:', error);

            tableBody.innerHTML = `
                <tr>
                    <td colspan="3" class="text-center">
                        Unable to load records. Please try again.
                    </td>
                </tr>
            `;
        });

    });

});
</script>
