<?php
require_once __DIR__ . '/../dbc.php';

header('Content-Type: text/html; charset=UTF-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit;
}

$selectedYear = trim($_POST['year'] ?? '');

if (!preg_match('/^\d{4}$/', $selectedYear)) {
    http_response_code(400);
    exit('Invalid year');
}

$stmt = $conn->prepare("
    SELECT title, video_url, year, thumbnail
    FROM investor_inthenews
    WHERE status = 1 AND year = ?
    ORDER BY sort_order ASC, id DESC
");

$stmt->bind_param("s", $selectedYear);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows > 0) {

    while ($video = $result->fetch_assoc()) {
        ?>

        <div class="col-lg-6 col-md-6 video-card m-0">

            <a href="<?= htmlspecialchars($video['video_url'], ENT_QUOTES, 'UTF-8') ?>"
               target="_blank"
               rel="noopener noreferrer"
               class="content-card video-card"
               data-video-type="youtube"
               data-video-url="<?= htmlspecialchars($video['video_url'], ENT_QUOTES, 'UTF-8') ?>">

                <span class="category-label text-uppercase">
                    <?= htmlspecialchars($video['year'], ENT_QUOTES, 'UTF-8') ?>
                </span>

                <div class="card-heading">
                    <h3>
                        <?= htmlspecialchars($video['title'], ENT_QUOTES, 'UTF-8') ?>
                    </h3>
                </div>

                <div class="content-image mb-3 mt-4 position-relative overflow-hidden">

                    <img
                        src="<?= htmlspecialchars('' . ltrim($video['thumbnail'], '/'), ENT_QUOTES, 'UTF-8') ?>"
                        alt="<?= htmlspecialchars($video['title'], ENT_QUOTES, 'UTF-8') ?>"
                        class="w-100">

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

        <?php
    }

} else {
    echo '<div class="col-12 text-center">No videos available.</div>';
}

$stmt->close();
$conn->close();