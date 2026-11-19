<?php
require_once __DIR__ . '/../dbc.php';

// Fetch Years
$investorYears = [];

$yearQuery = $conn->query("
    SELECT DISTINCT year
    FROM investor_inthenews
    WHERE status = 1
    ORDER BY year DESC
");

if ($yearQuery) {
    while ($yearRow = $yearQuery->fetch_assoc()) {
        $investorYears[] = $yearRow['year'];
    }
}

// Selected Year
$selectedYear = $_GET['year'] ?? '';

if (
    $selectedYear === '' ||
    !in_array((string)$selectedYear, array_map('strval', $investorYears), true)
) {
    $selectedYear = (string)($investorYears[0] ?? '');
}

// Fetch Videos
$investorVideos = [];

if ($selectedYear !== '') {
    $stmt = $conn->prepare("
        SELECT title, video_url, thumbnail
        FROM investor_inthenews
        WHERE status = 1 AND year = ?
        ORDER BY sort_order ASC, id DESC
    ");

    $stmt->bind_param("s", $selectedYear);
    $stmt->execute();

    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $investorVideos[] = $row;
    }

    $stmt->close();
}
?>

<div class="investor-news-section">

    <div class="row align-items-center">
        <div class="col-md-10">
            <h3>Investor In The News</h3>
        </div>

        <div class="col-md-2">
            <div class="financial-year">
                <p>Year</p>

                <select class="select_date" id="investorFinancialYear">
                    <?php foreach ($investorYears as $year): ?>
                        <option value="<?= htmlspecialchars((string)$year, ENT_QUOTES, 'UTF-8') ?>"
                            <?= ((string)$selectedYear === (string)$year) ? 'selected' : '' ?>>
                            <?= htmlspecialchars((string)$year, ENT_QUOTES, 'UTF-8') ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </div>

    <div class="row g-4" id="investorVideoList">

        <?php if (!empty($investorVideos)): ?>

            <?php foreach ($investorVideos as $video): ?>

                <div class="row g-4" id="investorVideoList">

    <?php if (!empty($investorVideos)): ?>

        <?php foreach ($investorVideos as $video): ?>

            <div class="col-lg-6 col-md-6 video-card m-0">

                <a href="<?= htmlspecialchars($video['video_url'], ENT_QUOTES, 'UTF-8') ?>"
                   target="_blank"
                   rel="noopener noreferrer"
                   class="content-card video-card"
                   data-video-type="youtube"
                   data-video-url="<?= htmlspecialchars($video['video_url'], ENT_QUOTES, 'UTF-8') ?>">

                   

                    <!-- Heading -->
                    <div class="card-heading">
                        <h3>
                            <?= htmlspecialchars($video['title'], ENT_QUOTES, 'UTF-8') ?>
                        </h3>
                    </div>

                    <!-- Image -->
                    <div class="content-image mb-3 mt-4 position-relative overflow-hidden">

                        <img
                            src="<?= htmlspecialchars('' . ltrim($video['thumbnail'], '/'), ENT_QUOTES, 'UTF-8') ?>"
                            alt="<?= htmlspecialchars($video['title'], ENT_QUOTES, 'UTF-8') ?>"
                            class="w-100">

                        <!-- Play Button -->
                        <span class="arrow video-play-btn bg-trans">

                            <svg width="50" height="50" viewBox="0 0 50 50"
                                 fill="none" xmlns="http://www.w3.org/2000/svg">

                                <circle cx="25" cy="25" r="24" fill="#ffffff"></circle>

                                <path d="M21 17L34 25L21 33V17Z"
                                      fill="#D71920"></path>

                            </svg>

                        </span>

                    </div>

                </a>

            </div>

        <?php endforeach; ?>

    <?php else: ?>

        <div class="col-12 text-center">
            No videos available.
        </div>

    <?php endif; ?>

</div>

            <?php endforeach; ?>

        <?php else: ?>

            <div class="col-12 text-center">
                No videos available.
            </div>

        <?php endif; ?>

    </div>

</div>



<script>
document.addEventListener("DOMContentLoaded", function () {

    const yearSelect = document.getElementById("investorFinancialYear");
    const videoList = document.getElementById("investorVideoList");

    if (!yearSelect || !videoList) return;

    yearSelect.addEventListener("change", function () {

        const formData = new FormData();
        formData.append("year", this.value);

        videoList.innerHTML = '<div class="col-12 text-center">Loading...</div>';

        fetch("investor_data/investor-inthenews-filter.php", {
            method: "POST",
            body: formData
        })
        .then(response => {
            if (!response.ok) throw new Error("Request failed");
            return response.text();
        })
        .then(html => {
            videoList.innerHTML = html;

            const url = new URL(window.location.href);
            url.searchParams.set("year", yearSelect.value);
            window.history.replaceState({}, "", url);
        })
        .catch(error => {
            console.error(error);
            videoList.innerHTML =
                '<div class="col-12 text-center">Unable to load videos.</div>';
        });

    });

});
</script>