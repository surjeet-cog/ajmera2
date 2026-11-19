<?php

// MUST be first line
ob_start();

require_once 'auth.php';
require_once '../dbc.php';

include 'includes/header.php';

?>

<h1 class="mb-4" style="font-size:24px;font-weight:normal;">
    Dashboard
</h1>

<div class="row">

    <!-- Total Blogs -->
    <div class="col-md-3">
        <div class="wp-card text-center"
             style="border-left:4px solid #2271b1;">

            <h5 class="text-muted mb-1">Total Blogs</h5>

            <?php
            $blog_total = 0;

            $blog_query = $conn->query(
                "SELECT COUNT(*) AS total FROM blogs"
            );

            if ($blog_query) {
                $blog_row = $blog_query->fetch_assoc();
                $blog_total = (int)$blog_row['total'];
            }
            ?>

            <h2 class="m-0">
                <?php echo $blog_total; ?>
            </h2>

        </div>
    </div>


    <!-- Total News -->
    <div class="col-md-3">
        <div class="wp-card text-center"
             style="border-left:4px solid #00a32a;">

            <h5 class="text-muted mb-1">Total News</h5>

            <?php
            $news_total = 0;

            $news_query = $conn->query(
                "SELECT COUNT(*) AS total FROM news"
            );

            if ($news_query) {
                $news_row = $news_query->fetch_assoc();
                $news_total = (int)$news_row['total'];
            }
            ?>

            <h2 class="m-0">
                <?php echo $news_total; ?>
            </h2>

        </div>
    </div>

</div>


<div class="row">

    <div class="col-md-12 mt-4">

        <div class="wp-card">

            <div class="wp-card-title">
                Quick Actions
            </div>

            <a href="<?php echo $base_url; ?>/blogs/add.php"
               class="btn-wp text-decoration-none me-2">
                <i class="fas fa-plus"></i>
                Write Blog
            </a>

            <a href="<?php echo $base_url; ?>/news/add.php"
               class="btn-wp text-decoration-none me-2">
                <i class="fas fa-plus"></i>
                Add News
            </a>

            <a href="<?php echo $base_url; ?>/jobs/add.php"
               class="btn-wp text-decoration-none">
                <i class="fas fa-plus"></i>
                Post Job
            </a>

        </div>

    </div>

</div>

<?php

include 'includes/footer.php';

ob_end_flush();

?>