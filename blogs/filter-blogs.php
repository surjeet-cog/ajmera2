
<?php
require_once '../dbc.php';

$search = trim($_POST['search'] ?? '');
$topic  = trim($_POST['topic'] ?? '');
$sort   = $_POST['sort'] ?? 'newest';

// Safe sorting
$orderBy = "publish_date DESC, id DESC";

if ($sort === 'oldest') {
    $orderBy = "publish_date ASC, id ASC";
}

$searchKeyword = "%" . $search . "%";

$sql = "
    SELECT *
    FROM blogs
    WHERE status = 1

    AND id NOT IN (
        SELECT id FROM (
            SELECT id
            FROM blogs
            WHERE status = 1
            ORDER BY publish_date DESC, id DESC
            LIMIT 2
        ) AS latest
    )

    AND (? = '' OR category = ?)

    AND (
        ? = ''
        OR title LIKE ?
        OR description LIKE ?
        OR content LIKE ?
    )

    ORDER BY $orderBy
";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "ssssss",
    $topic,
    $topic,
    $search,
    $searchKeyword,
    $searchKeyword,
    $searchKeyword
);

$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows > 0) {

    while ($blog = $result->fetch_assoc()) {
?>

    <div class="col-lg-4 m-0 col-md-6">

        <a href="/ajmera/blogs/<?php echo rawurlencode($blog['slug']); ?>"
           class="content-card wow animate__animated animate__fadeInUp">

            <span class="category-label text-uppercase">
                <?php echo htmlspecialchars($blog['category']); ?>
            </span>

            <div class="card-heading">

                <h3>
                    <?php echo htmlspecialchars($blog['title']); ?>
                </h3>

                <span class="arrow">
                    <img src="../images/right_1.svg" alt="Arrow">
                </span>

            </div>

            <p class="publish-date mt-2">
                <?php echo date('d F Y', strtotime($blog['publish_date'])); ?>
            </p>

            <div class="content-image mt-3 mb-3">

                <img src="../<?php echo htmlspecialchars($blog['image']); ?>"
                     alt="<?php echo htmlspecialchars($blog['title']); ?>">

            </div>

            <p>
                <?php echo htmlspecialchars($blog['description']); ?>
            </p>

        </a>

    </div>

<?php
    }

} else {
?>

    <div class="col-12 text-center">
        <p>No blogs found.</p>
    </div>

<?php
}

$stmt->close();
$conn->close();
?>
