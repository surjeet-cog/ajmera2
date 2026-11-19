<?php

require_once 'dbc.php';
include'header.php';
include'header_css.php' ?>




<section class="financial-section pad80 top80">
    <div class="container">
        <h1 class="h4"> Built on trust. Shared with confidence. </h1>
        
        

                <ul class="nav mt-5 financial-tabs justify-content-between"
                    id="quarterTabs"
                    role="tablist">

                    <li class="nav-item m-0" role="presentation">
                        <button class="nav-link active"
                                id="quarter1-tab"
                                data-bs-toggle="tab"
                                data-bs-target="#quarter1"
                                type="button"
                                role="tab">
                            Corporate Governance
                        </button>
                    </li>

                    <li class="nav-item m-0" role="presentation">
                        <button class="nav-link"
                                id="quarter2-tab"
                                data-bs-toggle="tab"
                                data-bs-target="#quarter2"
                                type="button"
                                role="tab">
                           Financials
                        </button>
                    </li>

                    <li class="nav-item m-0" role="presentation">
                        <button class="nav-link"
                                id="quarter3-tab"
                                data-bs-toggle="tab"
                                data-bs-target="#quarter3"
                                type="button"
                                role="tab">
                            Shareholders Information
                        </button>
                    </li>

                    <li class="nav-item m-0" role="presentation">
                        <button class="nav-link"
                                id="quarter4-tab"
                                data-bs-toggle="tab"
                                data-bs-target="#quarter4"
                                type="button"
                                role="tab">
                            Analyst Corner
                        </button>
                    </li>
                    
                     <li class="nav-item m-0" role="presentation">
                        <button class="nav-link"
                                id="quarter5-tab"
                                data-bs-toggle="tab"
                                data-bs-target="#quarter5"
                                type="button"
                                role="tab">
                           Media
                        </button>
                    </li>
                    
                     <li class="nav-item m-0" role="presentation">
                        <button class="nav-link"
                                id="quarter6-tab"
                                data-bs-toggle="tab"
                                data-bs-target="#quarter6"
                                type="button"
                                role="tab">
                            Investors Help
                        </button>
                    </li>

                </ul>

        <!-- Tab Content -->
        <div class="tab-content mt-5" id="quarterTabContent">

            <!-- Quarter 1 -->
            <div class="tab-pane fade show active"
                 id="quarter1"
                 role="tabpanel">
            
            <div class="vertical_tabs">
               <div class="row">
                   <div class="col-lg-3 col-md-3">
                        <ul class="nav nav-pills flex-column nav-pills " id="pills-tab" role="tablist">
    <li class="nav-item w-100" role="presentation">
      <button class="nav-link active position-relative" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true">Composition of Committee</button>
    </li>
    <li class="nav-item w-100" role="presentation">
      <button class="nav-link   position-relative" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">Policies</button>
    </li>
    <li class="nav-item w-100" role="presentation">
      <button class="nav-link position-relative" id="pills-contact-tab" data-bs-toggle="pill" data-bs-target="#pills-contact" type="button" role="tab" aria-controls="pills-contact" aria-selected="false">Corporate Governance Report</button>
    </li>
  </ul>
                   </div>
                   <div class="col-lg-9 col-md-9">
                        <div class="tab-content w-100" id="pills-tabContent">
                            
                            <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                                
                                <div class="vertical-tabs-inner">
                                    <div class="row">
                                        <div class="col-md-10">
                                            
                                                    <h3 class="h3">Composition of Committee</h3>
                                                    <p>Our governance framework, policies and corporate reporting.</p>
                                                
                                        </div>
                                        
                                        <div class="col-md-2">
                                          <!-- Financial Year -->
                                        <!--<div class="financial-year">
                                            <p>Financial year</p>
                                           <select class="select_date">
                                               <option> FY 2026-2027</option>
                                           </select>
                                        </div>-->
                                                
                                        </div>
                                    </div>
                                    
    
<?php

function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

$committees = $conn->query("
    SELECT id, title
    FROM committees
    WHERE status = 1
    ORDER BY sort_order ASC, id ASC
");

if (!$committees) {
    die('Unable to load committees.');
}
?>

<div class="accordion committee-accordion mt-5"
     id="committeeAccordion">

    <?php
    $index = 0;

    while ($committee = $committees->fetch_assoc()):
        $index++;

        $committeeId = (int)$committee['id'];
        $collapseId = 'committeeCollapse' . $committeeId;
        $headingId = 'committeeHeading' . $committeeId;

        $stmt = $conn->prepare("
            SELECT
                sr_no,
                director_name,
                category,
                pdf_file,
                pdf_link
            FROM committee_members
            WHERE committee_id = ? AND status = 1
            ORDER BY sort_order ASC, sr_no ASC, id ASC
        ");

        if (!$stmt) {
            die('Unable to load committee members.');
        }

        $stmt->bind_param("i", $committeeId);
        $stmt->execute();
        $members = $stmt->get_result();

        // Read rows first so we can decide which column to display.
        $memberRows = $members->fetch_all(MYSQLI_ASSOC);

        $hasDocuments = false;
        $hasCategory = false;

        foreach ($memberRows as $row) {
            if (
                trim((string)($row['pdf_file'] ?? '')) !== '' ||
                trim((string)($row['pdf_link'] ?? '')) !== ''
            ) {
                $hasDocuments = true;
            }

            if (trim((string)($row['category'] ?? '')) !== '') {
                $hasCategory = true;
            }
        }

        // Documents takes priority over Category.
        $showDocuments = $hasDocuments;
        $showCategory = !$hasDocuments && $hasCategory;

        $isFirst = ($index === 1);
    ?>

        <div class="accordion-item">

            <h2 class="accordion-header" id="<?= e($headingId) ?>">
                <button
                    class="accordion-button <?= $isFirst ? '' : 'collapsed' ?>"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#<?= e($collapseId) ?>"
                    aria-expanded="<?= $isFirst ? 'true' : 'false' ?>"
                    aria-controls="<?= e($collapseId) ?>">

                    <?= e($committee['title']) ?>

                </button>
            </h2>

            <div
                id="<?= e($collapseId) ?>"
                class="accordion-collapse collapse <?= $isFirst ? 'show' : '' ?>"
                aria-labelledby="<?= e($headingId) ?>"
                data-bs-parent="#committeeAccordion">

                <div class="accordion-body">

                    <div class="table-responsive">
                        <table class="table committee-table">

                            <thead>
                                <tr>
                                    <th>Sr. No</th>
                                    <th>Name of Directors</th>

                                    <?php if ($showDocuments): ?>
                                        <th>Documents</th>
                                    <?php elseif ($showCategory): ?>
                                        <th>Category</th>
                                    <?php endif; ?>
                                </tr>
                            </thead>

                            <tbody>

                                <?php if (count($memberRows) > 0): ?>

                                    <?php foreach ($memberRows as $member): ?>
                                        <tr>
                                            <td>
                                                <?= str_pad(
                                                    (string)$member['sr_no'],
                                                    2,
                                                    '0',
                                                    STR_PAD_LEFT
                                                ) ?>
                                            </td>

                                            <td>
                                                <?= e($member['director_name']) ?>
                                            </td>

                                            <?php if ($showDocuments): ?>
                                                <td>
                                                    <div class="committee-documents">

                                                        <?php if (!empty($member['pdf_file'])): ?>
                                                            <a
                                                                href="/ajmera/<?= e(ltrim($member['pdf_file'], '/')) ?>"
                                                                target="_blank"
                                                                rel="noopener noreferrer"
                                                                class="committee-pdf-link">
                                                                <!--<i class="fas fa-file-pdf"></i>-->
                                                                <img src="images/pdf.svg" class="pdf" />
                                                            </a>
                                                        <?php endif; ?>

                                                        <?php if (!empty($member['pdf_link'])): ?>
                                                            <a
                                                                href="<?= e($member['pdf_link']) ?>"
                                                                target="_blank"
                                                                rel="noopener noreferrer"
                                                                class="committee-external-link">
                                                                <i class="fas fa-external-link-alt"></i>
                                                                View Document
                                                            </a>
                                                        <?php endif; ?>

                                                        <?php if (
                                                            empty($member['pdf_file']) &&
                                                            empty($member['pdf_link'])
                                                        ): ?>
                                                            <span class="text-muted">—</span>
                                                        <?php endif; ?>

                                                    </div>
                                                </td>

                                            <?php elseif ($showCategory): ?>
                                                <td>
                                                    <?= e($member['category']) ?>
                                                </td>
                                            <?php endif; ?>

                                        </tr>
                                    <?php endforeach; ?>

                                <?php else: ?>
                                    <tr>
                                        <td colspan="3">
                                            No committee members added yet.
                                        </td>
                                    </tr>
                                <?php endif; ?>

                            </tbody>

                        </table>
                    </div>

                </div>
            </div>

        </div>

        <?php
        $stmt->close();
    endwhile;
    ?>

</div>


                                </div>
                                
                            </div>
                            
                            <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                                
                                <div class="vertical-tabs-inner">
                                    <div class="row">
                                        <div class="col-md-10">
                                            <h3 class="h3">Profile</h3>
                                            <p>Our governance framework, policies and corporate reporting.</p>
                                        </div>
                                        
                                        <div class="col-md-2">
                                          <!-- Financial Year -->
                                        <!--<div class="financial-year">
                                            <p>Financial year</p>
                                           <select class="select_date">
                                               <option> FY 2026-2027</option>
                                           </select>
                                        </div>-->
                                                
                                        </div>
                                    </div>
                                   <?php
// Police Committee Documents

$policeCommitteeQuery = "
    SELECT
        pd.police_document_id,
        pd.police_document_name,
        pd.police_document_pdf,
        pd.police_document_sort_order
    FROM police_committee_documents AS pd
    INNER JOIN police_committees AS pc
        ON pc.police_committee_id = pd.police_committee_id
    WHERE pd.police_document_status = 1
      AND pc.police_committee_status = 1
    ORDER BY
        pd.police_document_sort_order ASC,
        pd.police_document_id ASC
";

$policeCommitteeResult = $conn->query($policeCommitteeQuery);
?>

<div class="table-responsive">
    <table class="table committee-table">

        <thead>
            <tr>
                <th>Sr. No</th>
                <th>Title</th>
                <th>Documents</th>
            </tr>
        </thead>

        <tbody>

        <?php
        $policeDynamicSrNo = 1;
        ?>

        <?php if ($policeCommitteeResult && $policeCommitteeResult->num_rows > 0): ?>

            <?php while ($policeRow = $policeCommitteeResult->fetch_assoc()): ?>

                <tr>

                    <td>
                        <?= str_pad(
                            $policeDynamicSrNo++,
                            2,
                            '0',
                            STR_PAD_LEFT
                        ) ?>
                    </td>

                    <td>
                        <?= htmlspecialchars(
                            $policeRow['police_document_name'],
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>
                    </td>

                    <td>
                        <?php if (!empty($policeRow['police_document_pdf'])): ?>

                            <div class="committee-documents">

                                <a href="/ajmera/<?= htmlspecialchars(
                                    ltrim($policeRow['police_document_pdf'], '/'),
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>"
                                   target="_blank"
                                   rel="noopener noreferrer"
                                   class="committee-pdf-link">

                                    <!--<i class="fas fa-file-pdf"></i>-->

                                    <img src="images/pdf.svg"
                                         class="pdf"
                                         alt="View PDF">

                                </a>

                            </div>

                        <?php else: ?>

                            No Document

                        <?php endif; ?>
                    </td>

                </tr>

            <?php endwhile; ?>

        <?php else: ?>

            <tr>
                <td colspan="3" class="text-center">
                    No police documents found.
                </td>
            </tr>

        <?php endif; ?>

        </tbody>
    </table>
</div>
                                </div>
                              
                            </div>
                            
                            <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">
                                
                                <?php include 'corporate-governance.php'?>
                             
                            </div>
                            
                          </div>
                   </div>
               </div>
            </div>

            </div>


            <!-- Quarter 2 -->
            <div class="tab-pane fade"
                 id="quarter2"
                 role="tabpanel">

                <div class="vertical_tabs">
    <div class="row">
        <div class="col-lg-3 col-md-3">
            <ul class="nav nav-pills flex-column" id="investor-tabs" role="tablist">

                <li class="nav-item w-100" role="presentation">
                    <button class="nav-link active position-relative"
                        id="financials-tab"
                        data-bs-toggle="pill"
                        data-bs-target="#financials"
                        type="button" role="tab"
                        aria-controls="financials"
                        aria-selected="true">
                        Financials
                    </button>
                </li>

                <li class="nav-item w-100" role="presentation">
                    <button class="nav-link position-relative"
                        id="annual-reports-tab"
                        data-bs-toggle="pill"
                        data-bs-target="#annual-reports"
                        type="button" role="tab"
                        aria-controls="annual-reports"
                        aria-selected="false">
                        Annual Reports
                    </button>
                </li>

                <li class="nav-item w-100" role="presentation">
                    <button class="nav-link position-relative"
                        id="deviation-variation-tab"
                        data-bs-toggle="pill"
                        data-bs-target="#deviation-variation"
                        type="button" role="tab"
                        aria-controls="deviation-variation"
                        aria-selected="false">
                        Statement of Deviation/Variation in Utilization of Funds Raised
                    </button>
                </li>

                <li class="nav-item w-100" role="presentation">
                    <button class="nav-link position-relative"
                        id="secretarial-compliance-tab"
                        data-bs-toggle="pill"
                        data-bs-target="#secretarial-compliance"
                        type="button" role="tab"
                        aria-controls="secretarial-compliance"
                        aria-selected="false">
                        Secretarial Compliance Report
                    </button>
                </li>

            </ul>
        </div>

        <div class="col-lg-9 col-md-9">
            <div class="tab-content w-100" id="investor-tabs-content">

                <!-- Financials -->
                <div class="tab-pane fade show active"
                    id="financials"
                    role="tabpanel"
                    aria-labelledby="financials-tab">

                <?php
$yearSql = "SELECT DISTINCT financial_year
            FROM financial_reports
            WHERE status = 1
            ORDER BY financial_year DESC";

$yearResult = mysqli_query($conn, $yearSql);
?>

<div class="vertical-tabs-inner">

    <div class="row align-items-center">
        <div class="col-md-10">
            <h3 class="h3">Financials</h3>
            <p>Investment and finance go hand in hand</p>
        </div>

        <div class="col-md-2">
            <div class="financial-year">
                <p>Financial year</p>

                <select class="select_date" id="financialYear" name="financial_year">
                    <?php if ($yearResult && mysqli_num_rows($yearResult) > 0) { ?>
                        <?php while ($year = mysqli_fetch_assoc($yearResult)) {
                            $yearValue = htmlspecialchars($year['financial_year'], ENT_QUOTES, 'UTF-8');
                        ?>
                            <option value="<?= $yearValue ?>">
                                FY <?= $yearValue ?>
                            </option>
                        <?php } ?>
                    <?php } else { ?>
                        <option value="">No financial years available</option>
                    <?php } ?>
                </select>
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table committee-table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Q1 FY 27</th>
                    <th>Q2 &amp; H1 FY 27</th>
                    <th>Q3 &amp; 9M FY 27</th>
                    <th>Q4 FY 27</th>
                </tr>
            </thead>

            <tbody id="financialTableBody">
                <tr>
                    <td colspan="5"></td>
                </tr>
            </tbody>
        </table>
    </div>

</div>
                </div>

                <!-- Annual Reports -->
                <div class="tab-pane fade"
                    id="annual-reports"
                    role="tabpanel"
                    aria-labelledby="annual-reports-tab">

                    <?php include 'annual-reports.php'?>
                </div>

                <!-- Statement of Deviation/Variation -->
                <div class="tab-pane fade"
                    id="deviation-variation"
                    role="tabpanel"
                    aria-labelledby="deviation-variation-tab">
 <?php include 'investor_data/statement-of-deviation.php'?>
                           
                </div>

                <!-- Secretarial Compliance Report -->
                <div class="tab-pane fade"
                    id="secretarial-compliance"
                    role="tabpanel"
                    aria-labelledby="secretarial-compliance-tab">

                    
<?php
$reports = [];
$financialYears = [];
$selectedYear = $_GET['financial_year'] ?? '';

// Fetch available financial years from the database
$yearQuery = "
    SELECT DISTINCT financial_year
    FROM secretarial_compliance_reports
    WHERE financial_year <> ''
    ORDER BY financial_year DESC
";

$yearResult = $conn->query($yearQuery);

if ($yearResult) {
    while ($yearRow = $yearResult->fetch_assoc()) {
        $financialYears[] = $yearRow['financial_year'];
    }
}

// Select the latest available year by default
if (
    $selectedYear === '' ||
    !in_array($selectedYear, $financialYears, true)
) {
    $selectedYear = $financialYears[0] ?? '';
}

// Fetch active reports for selected financial year
if ($selectedYear !== '') {
    $stmt = $conn->prepare("
        SELECT id, title, pdf_file
        FROM secretarial_compliance_reports
        WHERE status = 1 AND financial_year = ?
        ORDER BY id ASC
    ");

    $stmt->bind_param('s', $selectedYear);
    $stmt->execute();

    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $reports[] = $row;
    }

    $stmt->close();
}
?>

<div class="vertical-tabs-inner">

    <div class="row align-items-center">
        <div class="col-md-10">
            <h3 class="h3">Secretarial Compliance Report</h3>
            <p>View our Secretarial Compliance Reports.</p>
        </div>

        <div class="col-md-2">
            <div class="financial-year">
                <p>Financial year</p>

                <form method="get" id="financialYearForm">
                    <select
                        class="select_date"
                        id="financialYear"
                        name="financial_year"
                        onchange="this.form.submit()"
                    >
                        <?php foreach ($financialYears as $year): ?>
                            <option
                                value="<?= htmlspecialchars($year, ENT_QUOTES, 'UTF-8') ?>"
                                <?= $selectedYear === $year ? 'selected' : '' ?>
                            >
                                FY <?= htmlspecialchars($year, ENT_QUOTES, 'UTF-8') ?>
                            </option>
                        <?php endforeach; ?>

                        <?php if (empty($financialYears)): ?>
                            <option value="">No financial years available</option>
                        <?php endif; ?>
                    </select>
                </form>
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

            <tbody id="cgrTableBody">
                <?php if (!empty($reports)): ?>

                    <?php foreach ($reports as $index => $report): ?>
                        <tr>
                            <td>
                                <?= str_pad(
                                    (string)($index + 1),
                                    2,
                                    '0',
                                    STR_PAD_LEFT
                                ) ?>
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
                                            href="/ajmera/<?= htmlspecialchars(
                                                ltrim($report['pdf_file'], '/'),
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>"
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            class="committee-pdf-link"
                                        >
                                            <img
                                                src="images/pdf.svg"
                                                class="pdf"
                                                alt="View PDF"
                                            >
                                        </a>
                                    </div>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>

                <?php else: ?>
                    <tr>
                        <td colspan="3">
                            No reports available for FY
                            <?= htmlspecialchars($selectedYear, ENT_QUOTES, 'UTF-8') ?>.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

                </div>

            </div>
        </div>
    </div>
</div>

            </div>


            <!-- Quarter 3 -->
            <div class="tab-pane fade"
                 id="quarter3"
                 role="tabpanel">

               
<div class="vertical_tabs">
    <div class="row">

        <!-- Left Sidebar -->
        <div class="col-lg-3 col-md-3">

            <ul class="nav nav-pills flex-column" id="shareholderTabs" role="tablist">

                <li class="nav-item w-100" role="presentation">
                    <button class="nav-link active"
                            id="shareholding-pattern-tab"
                            data-bs-toggle="pill"
                            data-bs-target="#shareholding-pattern"
                            type="button" role="tab"
                            aria-controls="shareholding-pattern"
                            aria-selected="true">
                        Shareholding Pattern
                    </button>
                </li>

                <li class="nav-item w-100" role="presentation">
                    <button class="nav-link"
                            id="stock-exchange-filings-tab"
                            data-bs-toggle="pill"
                            data-bs-target="#stock-exchange-filings"
                            type="button" role="tab"
                            aria-controls="stock-exchange-filings"
                            aria-selected="false">
                        Stock Exchange Filings
                    </button>
                </li>

                <li class="nav-item w-100" role="presentation">
                    <button class="nav-link"
                            id="dividend-tab"
                            data-bs-toggle="pill"
                            data-bs-target="#dividend"
                            type="button" role="tab"
                            aria-controls="dividend"
                            aria-selected="false">
                        Dividend
                    </button>
                </li>

                <li class="nav-item w-100" role="presentation">
                    <button class="nav-link"
                            id="iepf-tab"
                            data-bs-toggle="pill"
                            data-bs-target="#iepf"
                            type="button" role="tab"
                            aria-controls="iepf"
                            aria-selected="false">
                        IEPF
                    </button>
                </li>

                <li class="nav-item w-100" role="presentation">
                    <button class="nav-link"
                            id="postal-ballot-tab"
                            data-bs-toggle="pill"
                            data-bs-target="#postal-ballot"
                            type="button" role="tab"
                            aria-controls="postal-ballot"
                            aria-selected="false">
                        Postal Ballot
                    </button>
                </li>

                <li class="nav-item w-100" role="presentation">
                    <button class="nav-link"
                            id="physical-shareholders-tab"
                            data-bs-toggle="pill"
                            data-bs-target="#physical-shareholders"
                            type="button" role="tab"
                            aria-controls="physical-shareholders"
                            aria-selected="false">
                        Forms for Physical Shareholders
                    </button>
                </li>

                <li class="nav-item w-100" role="presentation">
                    <button class="nav-link"
                            id="demerger-tab"
                            data-bs-toggle="pill"
                            data-bs-target="#demerger"
                            type="button" role="tab"
                            aria-controls="demerger"
                            aria-selected="false">
                        Demerger
                    </button>
                </li>

                <li class="nav-item w-100" role="presentation">
                    <button class="nav-link"
                            id="preferential-allotment-tab"
                            data-bs-toggle="pill"
                            data-bs-target="#preferential-allotment"
                            type="button" role="tab"
                            aria-controls="preferential-allotment"
                            aria-selected="false">
                        Preferential Allotment
                    </button>
                </li>

                <li class="nav-item w-100" role="presentation">
                    <button class="nav-link"
                            id="split-subdivision-tab"
                            data-bs-toggle="pill"
                            data-bs-target="#split-subdivision"
                            type="button" role="tab"
                            aria-controls="split-subdivision"
                            aria-selected="false">
                        Split / Sub-division
                    </button>
                </li>

                <li class="nav-item w-100" role="presentation">
                    <button class="nav-link"
                            id="ncds-tab"
                            data-bs-toggle="pill"
                            data-bs-target="#ncds"
                            type="button" role="tab"
                            aria-controls="ncds"
                            aria-selected="false">
                        Non-Convertible Debentures (NCDs)
                    </button>
                </li>

            </ul>

        </div>

        <!-- Right Content -->
        <div class="col-lg-9 col-md-9">

            <div class="tab-content" id="shareholderTabsContent">

                <div class="tab-pane fade show active"
                     id="shareholding-pattern"
                     role="tabpanel"
                     aria-labelledby="shareholding-pattern-tab"
                     tabindex="0">
                    <?php include __DIR__ . '/investor_data/shareholding_pattern.php'; ?>
                </div>

                <div class="tab-pane fade"
                     id="stock-exchange-filings"
                     role="tabpanel"
                     aria-labelledby="stock-exchange-filings-tab"
                     tabindex="0">
                    <?php include __DIR__ . '/investor_data/stock_exchange_filings.php'; ?>
                </div>

                <div class="tab-pane fade"
                     id="dividend"
                     role="tabpanel"
                     aria-labelledby="dividend-tab"
                     tabindex="0">
                    <?php include __DIR__ . '/investor_data/dividend.php'; ?>
                </div>

                <div class="tab-pane fade"
                     id="iepf"
                     role="tabpanel"
                     aria-labelledby="iepf-tab"
                     tabindex="0">
                    <?php include __DIR__ . '/investor_data/iepf.php'; ?>
                </div>

                <div class="tab-pane fade"
                     id="postal-ballot"
                     role="tabpanel"
                     aria-labelledby="postal-ballot-tab"
                     tabindex="0">
                    <?php include __DIR__ . '/investor_data/postal_ballot.php'; ?>
                </div>

                <div class="tab-pane fade"
                     id="physical-shareholders"
                     role="tabpanel"
                     aria-labelledby="physical-shareholders-tab"
                     tabindex="0">
                    <?php include __DIR__ . '/investor_data/physical_shareholders.php'; ?>
                </div>

                <div class="tab-pane fade"
                     id="demerger"
                     role="tabpanel"
                     aria-labelledby="demerger-tab"
                     tabindex="0">
                    <?php include __DIR__ . '/investor_data/demerger.php'; ?>
                </div>

                <div class="tab-pane fade"
                     id="preferential-allotment"
                     role="tabpanel"
                     aria-labelledby="preferential-allotment-tab"
                     tabindex="0">
                    <?php include __DIR__ . '/investor_data/preferential_allotment.php'; ?>
                </div>

                <div class="tab-pane fade"
                     id="split-subdivision"
                     role="tabpanel"
                     aria-labelledby="split-subdivision-tab"
                     tabindex="0">
                    <?php include __DIR__ . '/investor_data/split_subdivision.php'; ?>
                </div>

                <div class="tab-pane fade"
                     id="ncds"
                     role="tabpanel"
                     aria-labelledby="ncds-tab"
                     tabindex="0">
                    <?php include __DIR__ . '/investor_data/ncds.php'; ?>
                </div>

            </div>

        </div>

    </div>
</div>


            </div>


            <!-- Quarter 4 -->
            <div class="tab-pane fade"
                 id="quarter4"
                 role="tabpanel">

                <div class="vertical_tabs">
    <div class="row">

        <!-- Left Sidebar -->
        <div class="col-lg-3 col-md-3">

            <ul class="nav nav-pills flex-column" id="analystTabs" role="tablist">

                <!-- Analyst Meet Intimation -->
                <li class="nav-item w-100" role="presentation">
                    <button class="nav-link active position-relative"
                            id="analyst-meet-intimation-tab"
                            data-bs-toggle="pill"
                            data-bs-target="#analyst-meet-intimation"
                            type="button"
                            role="tab"
                            aria-controls="analyst-meet-intimation"
                            aria-selected="true">
                        Analyst Meet Intimation
                    </button>
                </li>

                <!-- Analyst Notes -->
                <li class="nav-item w-100" role="presentation">
                    <button class="nav-link position-relative"
                            id="analyst-notes-tab"
                            data-bs-toggle="pill"
                            data-bs-target="#analyst-notes"
                            type="button"
                            role="tab"
                            aria-controls="analyst-notes"
                            aria-selected="false">
                        Analyst Notes
                    </button>
                </li>

                <!-- Analyst Presentation -->
                <li class="nav-item w-100" role="presentation">
                    <button class="nav-link position-relative"
                            id="analyst-presentation-tab"
                            data-bs-toggle="pill"
                            data-bs-target="#analyst-presentation"
                            type="button"
                            role="tab"
                            aria-controls="analyst-presentation"
                            aria-selected="false">
                        Analyst Presentation
                    </button>
                </li>

            </ul>

        </div>

        <!-- Right Tab Content -->
        <div class="col-lg-9 col-md-9">

            <div class="tab-content" id="analystTabsContent">

                <!-- Analyst Meet Intimation -->
                <div class="tab-pane fade show active"
                     id="analyst-meet-intimation"
                     role="tabpanel"
                     aria-labelledby="analyst-meet-intimation-tab"
                     tabindex="0">

                    <?php include __DIR__ . '/investor_data/analyst_meet_intimation.php'; ?>

                </div>

                <!-- Analyst Notes -->
                <div class="tab-pane fade"
                     id="analyst-notes"
                     role="tabpanel"
                     aria-labelledby="analyst-notes-tab"
                     tabindex="0">

                    <?php include __DIR__ . '/investor_data/analyst_notes.php'; ?>

                </div>

                <!-- Analyst Presentation -->
                <div class="tab-pane fade"
                     id="analyst-presentation"
                     role="tabpanel"
                     aria-labelledby="analyst-presentation-tab"
                     tabindex="0">

                    <?php include __DIR__ . '/investor_data/analyst_presentation.php'; ?>

                </div>

            </div>

        </div>

    </div>
</div>

            </div>
            
            
             <!-- Quarter 5 -->
            <div class="tab-pane fade"
                 id="quarter5"
                 role="tabpanel">
                
                
               <div class="vertical_tabs">
    <div class="row">

        <!-- Left Sidebar Tabs -->
        <div class="col-lg-3 col-md-3">

            <ul class="nav nav-pills flex-column" id="websiteTabs" role="tablist">

                <!-- Media / Press Release -->
                <li class="nav-item w-100" role="presentation">
                    <button class="nav-link active position-relative"
                            id="media-press-release-tab"
                            data-bs-toggle="pill"
                            data-bs-target="#media-press-release"
                            type="button"
                            role="tab"
                            aria-controls="media-press-release"
                            aria-selected="true">
                        Media / Press Release
                    </button>
                </li>

                <!-- In the News -->
                <li class="nav-item w-100" role="presentation">
                    <button class="nav-link position-relative"
                            id="in-the-news-tab"
                            data-bs-toggle="pill"
                            data-bs-target="#in-the-news"
                            type="button"
                            role="tab"
                            aria-controls="in-the-news"
                            aria-selected="false">
                        In the News
                    </button>
                </li>

                <!-- Newspaper Publication -->
                <li class="nav-item w-100" role="presentation">
                    <button class="nav-link position-relative"
                            id="newspaper-publication-tab"
                            data-bs-toggle="pill"
                            data-bs-target="#newspaper-publication"
                            type="button"
                            role="tab"
                            aria-controls="newspaper-publication"
                            aria-selected="false">
                        Newspaper Publication
                    </button>
                </li>

            </ul>

        </div>

        <!-- Right Content -->
        <div class="col-lg-9 col-md-9">

            <div class="tab-content" id="websiteTabsContent">

                <!-- Media / Press Release Content -->
                <div class="tab-pane fade show active"
                     id="media-press-release"
                     role="tabpanel"
                     aria-labelledby="media-press-release-tab"
                     tabindex="0">

                    <?php include __DIR__ . '/investor_data/media_press_release.php'; ?>

                </div>

                <!-- In the News Content -->
                <div class="tab-pane fade"
                     id="in-the-news"
                     role="tabpanel"
                     aria-labelledby="in-the-news-tab"
                     tabindex="0">

                    <?php include __DIR__ . '/investor_data/in_the_news.php'; ?>

                </div>

                <!-- Newspaper Publication Content -->
                <div class="tab-pane fade"
                     id="newspaper-publication"
                     role="tabpanel"
                     aria-labelledby="newspaper-publication-tab"
                     tabindex="0">

                    <?php include __DIR__ . '/investor_data/newspaper_publication.php'; ?>

                </div>

            </div>

        </div>

    </div>
</div>
                
            </div>
            
            
             <!-- Quarter 6 -->
            <div class="tab-pane fade"
                 id="quarter6"
                 role="tabpanel">
                
                
                 <div class="vertical_tabs">
               <div class="row">
                   <div class="col-lg-3 col-md-3">
                        <ul class="nav nav-pills flex-column" id="websiteTabs" role="tablist">

    <li class="nav-item w-100" role="presentation">
        <button class="nav-link active position-relative"
                id="newsletter-tab"
                data-bs-toggle="pill"
                data-bs-target="#newsletter"
                type="button"
                role="tab"
                aria-controls="newsletter"
                aria-selected="true">
            Newsletter
        </button>
    </li>

    <li class="nav-item w-100" role="presentation">
        <button class="nav-link position-relative"
                id="contact-us-tab"
                data-bs-toggle="pill"
                data-bs-target="#contact-us"
                type="button"
                role="tab"
                aria-controls="contact-us"
                aria-selected="false">
            Contact Us
        </button>
    </li>

    <li class="nav-item w-100" role="presentation">
        <button class="nav-link position-relative"
                id="forms-tab"
                data-bs-toggle="pill"
                data-bs-target="#forms"
                type="button"
                role="tab"
                aria-controls="forms"
                aria-selected="false">
            Forms
        </button>
    </li>

</ul>
                   </div>
                   <div class="col-lg-9 col-md-9">
                       <div class="tab-content" id="websiteTabsContent">

    <!-- Newsletter -->
    <div class="tab-pane fade show active"
         id="newsletter"
         role="tabpanel"
         aria-labelledby="newsletter-tab"
         tabindex="0">

       <?php include 'investor_data/newsletter.php'?>


    </div>


    <!-- Contact Us -->
    <div class="tab-pane fade"
         id="contact-us"
         role="tabpanel"
         aria-labelledby="contact-us-tab"
         tabindex="0">

            <div class="our-locations-right">
    <div class="row">
        <div class="col-md-6 col-lg-6 col-xl-4">
            <div class="nri-international-Offices-box">
                <h2>Registered Office Address:</h2>
                <div class="investor-contact-details">
                    <p><strong>Ajmera Realty &amp; Infra India Limited</strong></p>
                </div>
                <div class="contact-icon address-box __text_mode_custom_bg__">
                    <address>
                        <p>
                            <a href="https://maps.app.goo.gl/HArj3TfppmEnJSmD8"
                                >2nd Floor, Citimall, New Link Road,<br />
                                Andheri (West), Mumbai – 400053,<br />
                                Maharashtra, India.</a
                            ><br />
                            CIN: L27104MH1985PLC035659
                        </p>
                    </address>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-6 col-xl-4">
            <div class="nri-international-Offices-box">
                <h2>For Financial Statements and Materiality of Events / Information Queries:</h2>
                <div class="investor-contact-details">
                    <p><strong>Mr. Nitin D. Bavisi</strong></p>
                    <p><em>Chief Financial Officer</em></p>
                </div>
                <div class="contact-icon address-box __text_mode_custom_bg__">
                    <address>
                        <p>
                            <a href="https://maps.app.goo.gl/HArj3TfppmEnJSmD8"
                                >2nd Floor, Citi Mall, Andheri Link Road,<br />
                                Andheri (West), Mumbai – 400053<br />
                                Maharashtra, India.</a
                            >
                        </p>
                    </address>
                </div>
                <div class="contact-icon phone-number-box __text_mode_custom_bg__">
                    <a href="tel:022 - 6698 4000" title="022 - 6698 4000">022 - 6698 4000 </a>
                </div>
                <div class="contact-icon email-box __text_mode_custom_bg__">
                    <a href="mailto:investors@ajmera.com" title="investors@ajmera.com">investors@ajmera.com</a>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-6 col-xl-4">
            <div class="nri-international-Offices-box">
                <h2>For Investor Queries:</h2>
                <div class="investor-contact-details">
                    <p><strong>Mr. Gaurang Chotalia</strong></p>
                    <p><em>Manager - Investor Relations</em></p>
                </div>
                <div class="contact-icon address-box __text_mode_custom_bg__">
                    <address>
                        <p>
                            <a href="https://maps.app.goo.gl/HArj3TfppmEnJSmD8"
                                >2nd Floor, Citi Mall, Andheri Link Road,<br />
                                Andheri (West), Mumbai – 400053,<br />
                                Maharashtra, India.</a
                            >
                        </p>
                    </address>
                </div>
                <div class="contact-icon phone-number-box __text_mode_custom_bg__">
                    <a href="tel:022 - 6698 4000" title="022 - 6698 4000">022 - 6698 4000 </a>
                </div>
                <div class="contact-icon email-box __text_mode_custom_bg__">
                    <a href="mailto:ir@ajmera.com" title="ir@ajmera.com">ir@ajmera.com</a>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-6 col-xl-4">
            <div class="nri-international-Offices-box">
                <h2>For all Compliance related matters please Contact:</h2>
                <div class="investor-contact-details">
                    <p><strong>Ms. Reema Solanki</strong></p>
                    <p><em>Company Secretary &amp; Compliance Officer</em></p>
                </div>
                <div class="contact-icon address-box __text_mode_custom_bg__">
                    <address>
                        <p>
                            <a href="https://maps.app.goo.gl/HArj3TfppmEnJSmD8"
                                >2nd Floor, Citi Mall, Andheri Link Road,<br />
                                Andheri (West), Mumbai – 400053,<br />
                                Maharashtra, India.</a
                            >
                        </p>
                    </address>
                </div>
                <div class="contact-icon phone-number-box __text_mode_custom_bg__">
                    <a href="tel:022 - 6698 4000" title="022 - 6698 4000">022 - 6698 4000 </a>
                </div>
                <div class="contact-icon email-box __text_mode_custom_bg__">
                    <a href="mailto:investors@ajmera.com" title="investors@ajmera.com">investors@ajmera.com</a>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-6 col-xl-4">
            <div class="nri-international-Offices-box">
                <h2>For Enquiries related to Transfer of Shares, Change of Address, etc.:</h2>
                <div class="investor-contact-details">
                    <p><strong>Registrar and Share Transfer Agents</strong></p>
                    <p><em>MUFG Intime India Private Limited (Formerly known as Link Intime India Pvt. Ltd.)</em></p>
                </div>
                <div class="contact-icon address-box __text_mode_custom_bg__">
                    <address>
                        <p>
                            <a href="https://maps.app.goo.gl/LVRZ2h9Hcu9oYJpJ7"
                                >C-101, 247 Park, LBS Marg,<br />
                                Vikhroli (W),Mumbai – 400083</a
                            >
                        </p>
                    </address>
                </div>
                <div class="contact-icon phone-number-box __text_mode_custom_bg__">
                    <a href="tel:+91 22 4918 6000 " title="+91 22 4918 6000 ">+91 22 4918 6000 </a>
                </div>
                <div class="contact-icon email-box __text_mode_custom_bg__">
                    <a href="mailto:Investor.helpdesk@in.mpms.mufg.com" title="Investor.helpdesk@in.mpms.mufg.com"
                        >Investor.helpdesk@in.mpms.mufg.com</a
                    >
                </div>
                <div class="contact-icon web-box __text_mode_custom_bg__">
                    <a href="https://in.mpms.mufg.com" target="_blank" title="in.mpms.mufg.com">in.mpms.mufg.com</a>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-6 col-xl-4">
            <div class="nri-international-Offices-box">
                <h2>Nodal Officer for the IEPF Authority:</h2>
                <div class="investor-contact-details">
                    <p><strong>Ms. Reema Solanki </strong></p>
                    <p><em>Company Secretary &amp; Compliance Officer</em></p>
                </div>
                <div class="contact-icon address-box __text_mode_custom_bg__">
                    <address>
                        <p>
                            <a href="https://maps.app.goo.gl/HArj3TfppmEnJSmD8"
                                >2nd Floor, Citi Mall, Andheri Link Road,<br />
                                Andheri (West), Mumbai – 400053,<br />
                                Maharashtra, India.</a
                            >
                        </p>
                    </address>
                </div>
                <div class="contact-icon phone-number-box __text_mode_custom_bg__">
                    <a href="tel:022 - 6698 4000" title="022 - 6698 4000">022 - 6698 4000 </a>
                </div>
                <div class="contact-icon email-box __text_mode_custom_bg__">
                    <a href="mailto:investors@ajmera.com" title="investors@ajmera.com">investors@ajmera.com</a>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-6 col-xl-4">
            <div class="nri-international-Offices-box h-auto mh-auto">
                <h2>Smart ODR Portal:</h2>
                <div class="contact-icon web-box __text_mode_custom_bg__">
                    <a href="https://smartodr.in/login" target="_blank" title="smartodr.in/login">smartodr.in/login</a>
                </div>
            </div>
        </div>
    </div>
</div>


    </div>


    <!-- Forms -->
    <div class="tab-pane fade"
         id="forms"
         role="tabpanel"
         aria-labelledby="forms-tab"
         tabindex="0">

         <?php include __DIR__ . '/investor_data/investor_forms.php'; ?>

    </div>

</div>
                    </div>
                    </div>
            </div>



        </div>
    </div> 
    
    </div>
</section>
   
   

<?php include'footer.php' ?>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const yearSelect = document.getElementById("financialYear");
    const tableBody = document.getElementById("financialTableBody");

    if (!yearSelect || !tableBody) return;

    let controller = null;

    async function loadFinancials(year) {
        if (!year) {
            tableBody.innerHTML =
                '<tr><td colspan="5">No financial year selected.</td></tr>';
            return;
        }

        if (controller) {
            controller.abort();
        }

        controller = new AbortController();

        tableBody.innerHTML =
            '<tr><td colspan="5">Loading financial reports...</td></tr>';

        try {
            const response = await fetch(
                "financials-filter.php?year=" + encodeURIComponent(year),
                { signal: controller.signal }
            );

            if (!response.ok) {
                throw new Error("Request failed");
            }

            const html = await response.text();
            tableBody.innerHTML = html;

        } catch (error) {
            if (error.name === "AbortError") return;

            tableBody.innerHTML =
                '<tr><td colspan="5"></td></tr>';

            console.error(error);
        }
    }

    yearSelect.addEventListener("change", function () {
        loadFinancials(this.value);
    });

    // Load the default selected year when the page opens.
    loadFinancials(yearSelect.value);
});













document.addEventListener("DOMContentLoaded", function () {
    const yearSelect = document.getElementById("financialYear");
    const reportsContainer = document.getElementById("annualReportsContainer");

    if (!yearSelect || !reportsContainer) {
        console.error("Financial Year dropdown or reports container not found.");
        return;
    }

    let controller = null;

    async function loadAnnualReports(year) {
        if (!year) {
            reportsContainer.innerHTML =
                "<p>Please select a financial year.</p>";
            return;
        }

        if (controller) {
            controller.abort();
        }

        controller = new AbortController();
        const currentController = controller;

        yearSelect.disabled = true;

        reportsContainer.innerHTML =
            "<p>Loading annual reports...</p>";

        try {
            const endpoint = new URL(
                "annual-reports-ajax.php",
                window.location.origin
            );

            endpoint.searchParams.set("financial_year", year);

            console.log("Selected year:", year);
            console.log("Request URL:", endpoint.toString());

            const response = await fetch(endpoint.toString(), {
                method: "GET",
                cache: "no-store",
                signal: currentController.signal,
                headers: {
                    "X-Requested-With": "XMLHttpRequest"
                }
            });

            const html = await response.text();

            console.log("Response status:", response.status);
            console.log("Response HTML:", html);

            if (!response.ok) {
                throw new Error(
                    "HTTP " + response.status + ": " + html
                );
            }

            if (currentController !== controller) return;

            reportsContainer.innerHTML = html;

            const pageUrl = new URL(window.location.href);
            pageUrl.searchParams.set("financial_year", year);
            pageUrl.searchParams.delete("ajax");

            window.history.replaceState({}, "", pageUrl.toString());

        } catch (error) {
            if (error.name === "AbortError") return;

            console.error("Annual Reports error:", error);

            reportsContainer.innerHTML =
                "<p>Unable to load annual reports. Please check the console.</p>";

        } finally {
            if (currentController === controller) {
                yearSelect.disabled = false;
                controller = null;
            }
        }
    }

    yearSelect.addEventListener("change", function () {
        loadAnnualReports(this.value);
    });

    // Initial load
    loadAnnualReports(yearSelect.value);
});




// Media 
document.addEventListener("DOMContentLoaded", function () {

    const yearSelect = document.getElementById("mediaFinancialYear");
    const tableBody = document.getElementById("mediaTableBody");

    if (!yearSelect || !tableBody) {
        return;
    }

    yearSelect.addEventListener("change", function () {

        const selectedYear = this.value;

        tableBody.innerHTML = `
            <tr>
                <td colspan="3" class="text-center">
                    Loading press releases...
                </td>
            </tr>
        `;

        const formData = new FormData();
        formData.append("financial_year", selectedYear);

        fetch("investor_data/media-press-release-filter.php", {
            method: "POST",
            body: formData
        })
        .then(function (response) {
            if (!response.ok) {
                throw new Error("Failed to load press releases");
            }

            return response.text();
        })
        .then(function (html) {

            tableBody.innerHTML = html;

            const currentUrl = new URL(window.location.href);

            currentUrl.searchParams.set("financial_year", selectedYear);

            window.history.replaceState({}, "", currentUrl);

        })
        .catch(function (error) {

            tableBody.innerHTML = `
                <tr>
                    <td colspan="3" class="text-center">
                        Unable to load press releases. Please try again.
                    </td>
                </tr>
            `;

            console.error(error);
        });

    });

});



// Newspaper Publication

document.addEventListener("DOMContentLoaded", function () {

    const yearSelect = document.getElementById("newspaperFinancialYear");
    const tableBody = document.getElementById("newspaperTableBody");

    if (!yearSelect || !tableBody) {
        return;
    }

    yearSelect.addEventListener("change", function () {

        const selectedYear = this.value;

        tableBody.innerHTML = `
            <tr>
                <td colspan="3" class="text-center">
                    Loading publications...
                </td>
            </tr>
        `;

        const formData = new FormData();
        formData.append("financial_year", selectedYear);

        fetch("/ajmera/newspaper-publication-filter.php", {
            method: "POST",
            body: formData
        })
        .then(function (response) {

            if (!response.ok) {
                throw new Error("Failed to load publications");
            }

            return response.text();

        })
        .then(function (html) {

            tableBody.innerHTML = html;

            const currentUrl = new URL(window.location.href);

            currentUrl.searchParams.set("financial_year", selectedYear);

            window.history.replaceState({}, "", currentUrl);

        })
        .catch(function (error) {

            tableBody.innerHTML = `
                <tr>
                    <td colspan="3" class="text-center">
                        Unable to load publications. Please try again.
                    </td>
                </tr>
            `;

            console.error(error);

        });

    });

});


// Analyst Meet Intimation

document.addEventListener("DOMContentLoaded", function () {

    const yearSelect = document.getElementById("analystMeetFinancialYear");
    const tableBody = document.getElementById("analystMeetTableBody");

    if (!yearSelect || !tableBody) {
        return;
    }

    yearSelect.addEventListener("change", function () {

        const selectedYear = this.value;

        tableBody.innerHTML = `
            <tr>
                <td colspan="3" class="text-center">
                    Loading analyst meet intimations...
                </td>
            </tr>
        `;

        const formData = new FormData();
        formData.append("financial_year", selectedYear);

        fetch("investor_data/analyst-meet-intimation-filter.php", {
            method: "POST",
            body: formData
        })
        .then(function (response) {

            if (!response.ok) {
                throw new Error("Failed to load records");
            }

            return response.text();

        })
        .then(function (html) {

            tableBody.innerHTML = html;

            const currentUrl = new URL(window.location.href);

            currentUrl.searchParams.set("financial_year", selectedYear);

            window.history.replaceState({}, "", currentUrl);

        })
        .catch(function (error) {

            tableBody.innerHTML = `
                <tr>
                    <td colspan="3" class="text-center">
                        Unable to load records. Please try again.
                    </td>
                </tr>
            `;

            console.error(error);

        });

    });

});



// shareholding-pattern

document.addEventListener("DOMContentLoaded", function () {

    const yearSelect = document.getElementById("shareholdingFinancialYear");
    const tableBody = document.getElementById("shareholdingTableBody");

    if (!yearSelect || !tableBody) {
        return;
    }

    yearSelect.addEventListener("change", function () {

        const selectedYear = this.value;

        tableBody.innerHTML = `
            <tr>
                <td colspan="3" class="text-center">
                    Loading shareholding reports...
                </td>
            </tr>
        `;

        const formData = new FormData();
        formData.append("financial_year", selectedYear);

        fetch("investor_data/shareholding-pattern-filter.php", {
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

            tableBody.innerHTML = html;

            const currentUrl = new URL(window.location.href);

            currentUrl.searchParams.set("financial_year", selectedYear);

            window.history.replaceState({}, "", currentUrl);

        })
        .catch(function (error) {

            tableBody.innerHTML = `
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





//postal-ballot

document.addEventListener("DOMContentLoaded", function () {

    const yearSelect = document.getElementById("postalBallotFinancialYear");
    const tableBody = document.getElementById("postalBallotTableBody");

    if (!yearSelect || !tableBody) {
        return;
    }

    yearSelect.addEventListener("change", function () {

        const selectedYear = this.value;

        tableBody.innerHTML = `
            <tr>
                <td colspan="3" class="text-center">
                    Loading postal ballot records...
                </td>
            </tr>
        `;

        const formData = new FormData();
        formData.append("financial_year", selectedYear);

        fetch("investor_data/postal-ballot-filter.php", {
            method: "POST",
            body: formData
        })
        .then(function (response) {

            if (!response.ok) {
                throw new Error("Failed to load records");
            }

            return response.text();

        })
        .then(function (html) {

            tableBody.innerHTML = html;

            const currentUrl = new URL(window.location.href);

            currentUrl.searchParams.set("financial_year", selectedYear);

            window.history.replaceState({}, "", currentUrl);

        })
        .catch(function (error) {

            tableBody.innerHTML = `
                <tr>
                    <td colspan="3" class="text-center">
                        Unable to load postal ballot records. Please try again.
                    </td>
                </tr>
            `;

            console.error(error);

        });

    });

});






</script>