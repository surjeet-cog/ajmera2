
<?php
require_once '../dbc.php';

function e($value)
{
    return htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');
}

$home_url = isset($home_url) && $home_url !== ''
    ? rtrim($home_url, '/') . '/'
    : '../';

// Default filters
$city = trim($_GET['city'] ?? 'Mumbai');
$projectStatus = trim($_GET['project_status'] ?? 'Ongoing');
$projectType = trim($_GET['project_type'] ?? 'Residential');

$cities = [];
$statuses = [];
$types = [];

// Fetch dynamic cities
$result = $conn->query("
    SELECT DISTINCT city
    FROM projects
    WHERE status = 1 AND city <> ''
    ORDER BY city ASC
");

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $cities[] = $row['city'];
    }
}

// Fetch project statuses
$result = $conn->query("
    SELECT DISTINCT project_status
    FROM projects
    WHERE status = 1
    ORDER BY project_status ASC
");

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $statuses[] = $row['project_status'];
    }
}

// Fetch project types
$result = $conn->query("
    SELECT DISTINCT project_type
    FROM projects
    WHERE status = 1
    ORDER BY project_type ASC
");

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $types[] = $row['project_type'];
    }
}

// Case-insensitive database option matching
function findOption($options, $default)
{
    foreach ($options as $option) {
        if (strcasecmp($option, $default) === 0) {
            return $option;
        }
    }

    return '';
}

// Apply defaults only if available in database
$city = findOption($cities, $city);
$projectStatus = findOption($statuses, $projectStatus);
$projectType = findOption($types, $projectType);


// Filtered projects query
$sql = "SELECT
            id,
            project_name,
            slug,
            city,
            location,
            project_status,
            project_type,
            bhk_details,
            project_image,
            project_url
        FROM projects
        WHERE status = 1";

$params = [];
$paramTypes = '';

if ($city !== '') {
    $sql .= " AND city = ?";
    $params[] = $city;
    $paramTypes .= 's';
}

if ($projectStatus !== '') {
    $sql .= " AND project_status = ?";
    $params[] = $projectStatus;
    $paramTypes .= 's';
}

if ($projectType !== '') {
    $sql .= " AND project_type = ?";
    $params[] = $projectType;
    $paramTypes .= 's';
}

$sql .= " ORDER BY sort_order ASC, id DESC";

$stmt = $conn->prepare($sql);
$projects = [];

if ($stmt) {

    if (!empty($params)) {
        $stmt->bind_param($paramTypes, ...$params);
    }

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $projects = $result->fetch_all(MYSQLI_ASSOC);
    } else {
        error_log('Projects query error: ' . $stmt->error);
    }

    $stmt->close();

} else {
    error_log('Projects prepare error: ' . $conn->error);
}


// Render project cards
function renderProjectCards($projects, $home_url)
{
    foreach ($projects as $project) {

        $name = $project['project_name'];
        $status = $project['project_status'];
        $type = $project['project_type'];

        $statusLabel = strcasecmp($status, 'completed') === 0
            ? 'DELIVERED'
            : 'ONGOING';

        $typeLabel = strtoupper($type);

        $image = trim($project['project_image'] ?? '');
        $imageUrl = '';

        if ($image !== '') {
            $imageUrl = preg_match('~^https?://~i', $image)
                ? $image
                : $home_url . ltrim($image, '/');
        }

        $projectUrl = trim($project['project_url'] ?? '');

        if ($projectUrl === '') {
            $projectUrl = '#';
        }

        $location = !empty($project['location'])
            ? $project['location']
            : $project['city'];
        ?>

        <div class="col-xl-3 col-lg-4 col-md-6 project-item">
            <div class="project-card">

                <div class="project-image">
                    <div class="overflow-hidden position-relative">

                        <?php if ($imageUrl !== ''): ?>
                            <img
                                src="<?= e($imageUrl) ?>"
                                alt="<?= e($name) ?>"
                                loading="lazy"
                            >
                        <?php endif; ?>

                        <div class="project-badges">
                            <span class="res">
                                <?= e($typeLabel) ?>
                            </span>

                            <span class="ong">
                                <?= e($statusLabel) ?>
                            </span>
                        </div>

                    </div>
                </div>

                <div class="project-info">

                    <h5><?= e($name) ?></h5>

                    <div class="project-details" style="    height: 48px;">

                        <span><?= e($location) ?></span>

                        <?php if (!empty($project['bhk_details'])): ?>
                            <span>
                                <?= e($project['bhk_details']) ?>
                            </span>
                        <?php endif; ?>

                    </div>

                </div>

                <div class="project-actions d-flex">

                    <a
                        href="<?= e($home_url . ltrim($projectUrl, '/')) ?>"
                        class="explore-btn"
                    >
                        Explore Project <b>›</b>
                    </a>

                    <a
                        href="#enquire"
                        class="enquire-btn"
                        data-project="<?= e($name) ?>"
                    >
                        Enquire Now <b>›</b>
                    </a>

                </div>

            </div>
        </div>

        <?php
    }
}


// AJAX request
if (isset($_GET['ajax']) && $_GET['ajax'] === 'projects') {

    header('Content-Type: text/html; charset=UTF-8');

    echo '<div class="row g-3" id="projectsGrid">';

    renderProjectCards($projects, $home_url);

    echo '</div>';

    exit;
}

require_once '../header.php';
?>


<style>
header {
    background: #000 !important;
    padding-bottom: 20px !important;
}

#projectsGrid {
    transition: opacity 0.2s ease;
}
</style>


<!-- Page Heading -->
<section class="financial-section pad80 top80">
    <div class="container">

        <span class="span">Our Projects</span>

        <h1 class="h4">
            Ajmera creates visionary residential, commercial, educational,
            and retail spaces across India and international markets.
        </h1>

    </div>
</section>


<!-- Project Filters -->
<section class="our-project">
    <div class="container">

        <div class="row g-3 align-items-center" id="projectFilterForm">

            <!-- City Filter -->
            <div class="col">

                <div class="blog-select-box position-relative">

                    <select class="form-select" id="filterCity">

                        <option value="">All Cities</option>

                        <?php foreach ($cities as $item): ?>

                            <option
                                value="<?= e($item) ?>"
                                <?= strcasecmp($city, $item) === 0 ? 'selected' : '' ?>
                            >
                                <?= e($item) ?>
                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>

            </div>


            <!-- Status Filter -->
            <div class="col">

                <div class="blog-select-box position-relative">

                    <select class="form-select" id="filterStatus">

                        <option value="">All Status</option>

                        <?php foreach ($statuses as $item): ?>

                            <option
                                value="<?= e($item) ?>"
                                <?= strcasecmp($projectStatus, $item) === 0 ? 'selected' : '' ?>
                            >
                                <?= strcasecmp($item, 'completed') === 0
                                    ? 'Completed'
                                    : 'Ongoing' ?>
                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>

            </div>


            <!-- Project Type Filter -->
            <div class="col">

                <div class="blog-select-box position-relative">

                    <select class="form-select" id="filterType">

                        <option value="">All Types</option>

                        <?php foreach ($types as $item): ?>

                            <option
                                value="<?= e($item) ?>"
                                <?= strcasecmp($projectType, $item) === 0 ? 'selected' : '' ?>
                            >
                                <?= e(ucfirst($item)) ?>
                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>

            </div>


            <!-- Reset Button -->
            <div class="col-auto">

                <button
                    type="button"
                    class="btn reset-filter-btn"
                    id="resetProjectFilters"
                >
                    Reset
                </button>

            </div>

        </div>

    </div>
</section>


<!-- Projects Section -->
<section class="projects-section pad80 pt-0">

    <div class="container">

        <div class="animtes-list">

            <!-- Loader -->
            <div
                id="projectsLoader"
                style="display:none; text-align:center; padding:20px;"
            >
                <div class="loader_2"></div>
            </div>


            <!-- Project Grid -->
            <div class="row g-3" id="projectsGrid">

                <?php renderProjectCards($projects, $home_url); ?>

            </div>


            <!-- No Projects Found -->
            <div
                id="noProjectsFound"
                style="display:<?= empty($projects) ? 'block' : 'none' ?>;
                       text-align:center; padding:30px;"
            >
                No projects found.
            </div>

        </div>

    </div>

</section>


<?php include '../footer.php'; ?>


<script>


document.addEventListener('DOMContentLoaded', function () {

    const grid = document.getElementById('projectsGrid');
    const loader = document.getElementById('projectsLoader');
    const noResults = document.getElementById('noProjectsFound');

    const city = document.getElementById('filterCity');
    const status = document.getElementById('filterStatus');
    const type = document.getElementById('filterType');
    const reset = document.getElementById('resetProjectFilters');

    if (!grid || !loader || !noResults || !city || !status || !type || !reset) {
        return;
    }

    let controller = null;

    // AJAX project filtering
    async function loadProjects() {

        if (controller) {
            controller.abort();
        }

        controller = new AbortController();

        const currentController = controller;

        const params = new URLSearchParams();

        params.set('ajax', 'projects');
        params.set('city', city.value);
        params.set('project_status', status.value);
        params.set('project_type', type.value);

        loader.style.display = 'flex';
        grid.style.opacity = '0.5';
        noResults.style.display = 'none';

        try {

            const response = await fetch(
                window.location.pathname + '?' + params.toString(),
                {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    signal: currentController.signal
                }
            );

            if (!response.ok) {
                throw new Error('HTTP Error: ' + response.status);
            }

            const html = await response.text();

            const doc = new DOMParser().parseFromString(
                html,
                'text/html'
            );

            const newGrid = doc.getElementById('projectsGrid');

            if (!newGrid) {
                throw new Error('Projects grid not found in AJAX response.');
            }

            grid.innerHTML = newGrid.innerHTML;

            const hasProjects = grid.querySelector('.project-item') !== null;

            noResults.style.display = hasProjects ? 'none' : 'block';

        } catch (error) {

            if (error.name !== 'AbortError') {
                console.error('Project filter error:', error);
            }

        } finally {

            if (controller === currentController) {
                loader.style.display = 'none';
                grid.style.opacity = '1';
            }

        }
    }


    // Filter change events
    [city, status, type].forEach(function (select) {

        select.addEventListener('change', function () {
            loadProjects();
        });

    });


    // Reset all filters
    reset.addEventListener('click', function (e) {

        e.preventDefault();

        // Set all dropdowns to blank
        city.value = '';
        status.value = '';
        type.value = '';

        // Reload all projects
        loadProjects();

    });

});

</script>
