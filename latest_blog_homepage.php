<?php
require_once 'dbc.php';

$latestBlogs = $conn->query("
    SELECT *
    FROM blogs
    WHERE status = 1
    ORDER BY publish_date DESC, id DESC
    LIMIT 1
");
?> 
 
 <?php if ($latestBlogs && $latestBlogs->num_rows > 0) { ?>

                <?php while ($blog = $latestBlogs->fetch_assoc()) { ?>

                    <div class="col-lg-6 col-md-6">

                        <a href="<?php echo $home_url; ?>blogs/<?php echo rawurlencode($blog['slug']); ?>"
                           class="content-card wow animate__animated animate__fadeInUp">

                            <!-- Category -->
                            <span class="category-label text-uppercase">
                                BLOGS
                                <!--<?php echo htmlspecialchars($blog['category']); ?>-->
                            </span>

                            <!-- Heading -->
                            <div class="card-heading">

                                <h3>
                                    <?php echo htmlspecialchars($blog['title']); ?>
                                </h3>

                                <span class="arrow">
                                    <img src="images/right_1.svg"
                                         alt="Arrow">
                                </span>

                            </div>

                            <!-- Date -->
                            <p class="publish-date">
                                <?php
                                echo date(
                                    'd F Y',
                                    strtotime($blog['publish_date'])
                                );
                                ?>
                            </p>

                            <!-- Image -->
                            <div class="content-image mt-4 mb-3">

                                <img src="<?php echo htmlspecialchars($blog['image']); ?>"
                                     alt="<?php echo htmlspecialchars($blog['title']); ?>">

                            </div>

                            

                        </a>

                    </div>

                <?php } ?>

            <?php } ?>