<?php 
require_once '../dbc.php';


$latestBlogs = $conn->query("
    SELECT * FROM blogs
    WHERE status = 1
    ORDER BY publish_date DESC, id DESC
    LIMIT 2
");

$remainingBlogs = $conn->query("
    SELECT * FROM blogs
    WHERE status = 1
    ORDER BY publish_date DESC, id DESC
    LIMIT 999999 OFFSET 2
");

include '../header.php';
?>


<!-- =========================
     BLOG BANNER
========================= -->

<div class="banner position-relative">

    <div class="banner-inner position-relative"
         style="background:url('../images/blog-banner2.webp') no-repeat">

        <div class="overlay"></div>

    </div>

    <div class="caption d-flex align-items-center">

        <div class="container position-relative">

            <div class="m-0">

                <h1 class="wow animate__fadeInUp animate__animated m-0">
                    Ideas and insights from Ajmera and the world we build in.
                </h1>

                <p class="wow animate__fadeInUp animate__animated">
                    Explore thoughts on real estate, cities, design, development and the changing nature of everyday life.
                </p>

            </div>

        </div>

    </div>

</div>


<!-- =========================
     LATEST 2 BLOGS
========================= -->

<section class="news-section blog_grid pad80">

    <div class="container">

        <div class="row mt-4">

            <?php if ($latestBlogs && $latestBlogs->num_rows > 0) { ?>

                <?php while ($blog = $latestBlogs->fetch_assoc()) { ?>

                    <div class="col-lg-6 m-0 col-md-6">

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

                            <p class="publish-date">
                                <?php echo date('d F Y', strtotime($blog['publish_date'])); ?>
                            </p>

                            <div class="content-image mt-4 mb-3">

                                <img src="../<?php echo htmlspecialchars($blog['image']); ?>"
                                     alt="<?php echo htmlspecialchars($blog['title']); ?>">

                            </div>

                            <p>
                                <?php echo htmlspecialchars($blog['description']); ?>
                            </p>

                        </a>

                    </div>

                <?php } ?>

            <?php } ?>

        </div>

    </div>

</section>


<!-- =========================
     SEARCH / FILTER
========================= -->

<section class="search_filter pt-0 pb-0">
    <div class="container">
        <div class="row  align-items-center">

            <!-- Search with Auto Suggestion -->
            <div class="col-lg-8">
                <div class="blog-search-box position-relative">

                    <input type="text"
                           id="blogSearch"
                           class="form-control"
                           placeholder="Search"
                           autocomplete="off">

                    <span class="search-icon">
                        <i class="bi bi-search"></i>
                    </span>

                    <div id="searchSuggestions"
                         class="search-suggestions"></div>

                </div>
            </div>

           <!-- Topics -->
<div class="col-lg-2">
    <select class="form-select topicFilter2" id="topicFilter">
        <option value="">Topics</option>
        <option value="Real Estate">Real Estate</option>
        <option value="Housing - Legal">Housing - Legal</option>
    </select>
</div>

<!-- Sort By -->
<div class="col-lg-2">
    <select class="form-select topicFilter2" id="sortFilter">
        <option value="">Sort by</option>
        <option value="newest">Newest</option>
        <option value="oldest">Oldest</option>
    </select>
</div>

        </div>
    </div>
</section>

<!-- =========================
     REMAINING BLOGS
========================= -->

<section class="news-section blog_grid pad80">

    <div class="container">

        <div class="row mt-4" id="blogResults">

            <?php if ($remainingBlogs && $remainingBlogs->num_rows > 0) { ?>

                <?php while ($blog = $remainingBlogs->fetch_assoc()) { ?>

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

                                    <img src="../images/right_1.svg"
                                         alt="Arrow">

                                </span>

                            </div>

                            <p class="publish-date mt-2">

                                <?php
                                echo date(
                                    'd F Y',
                                    strtotime($blog['publish_date'])
                                );
                                ?>

                            </p>

                            <div class="content-image mt-3 mb-3">

                                <img src="../<?php echo htmlspecialchars($blog['image']); ?>"
                                     alt="<?php echo htmlspecialchars($blog['title']); ?>">

                            </div>

                            <p>

                                <?php
                                echo htmlspecialchars($blog['description']);
                                ?>

                            </p>

                        </a>

                    </div>

                <?php } ?>

            <?php } ?>

        </div>

    </div>

</section>


<?php include '../footer.php'; ?>


<script>
$(document).ready(function () {

    let searchTimer;

    $('#blogSearch').on('keyup', function () {

        let keyword = $(this).val().trim();

        clearTimeout(searchTimer);

        if (keyword.length < 2) {
            $('#searchSuggestions').hide().html('');
            return;
        }

        searchTimer = setTimeout(function () {

            $.ajax({
                url: 'search-suggestions.php',
                type: 'POST',
                data: {
                    keyword: keyword
                },
                success: function (response) {

                    $('#searchSuggestions')
                        .html(response)
                        .toggle(response.trim() !== '');

                }
            });

        }, 300);

    });

    // Select suggestion
    $(document).on('click', '.suggestion-item', function () {

        let title = $(this).text();

        $('#blogSearch').val(title);
        $('#searchSuggestions').hide();

        // Apply search filter
        $('#blogSearch').trigger('change');

    });

    // Hide dropdown on outside click
    $(document).on('click', function (e) {

        if (!$(e.target).closest('.blog-search-box').length) {
            $('#searchSuggestions').hide();
        }

    });

});



// AJAX Blog Filtering
function filterBlogs() {

    let search = $('#blogSearch').val().trim();
    let topic  = $('#topicFilter').val();
    let sort   = $('#sortFilter').val();

    // Loader only for filters
    $('#blogResults').html(`
        <div class="col-12 text-center"
             style="padding:20px;">
            <div class="loader_2"></div>
        </div>
    `);

    $.ajax({
        url: 'filter-blogs.php',
        type: 'POST',

        data: {
            search: search,
            topic: topic,
            sort: sort
        },

        success: function(response) {
            $('#blogResults').html(response);
        },

        error: function() {
            $('#blogResults').html(
                '<div class="col-12 text-center">Something went wrong.</div>'
            );
        }
    });
}


// Topic Filter
$('#topicFilter, #sortFilter').on('change', function() {
    filterBlogs();
});


let filterTimer;

$('#blogSearch').on('input', function() {

    clearTimeout(filterTimer);

    filterTimer = setTimeout(function() {

        let search = $('#blogSearch').val().trim();

        if (search.length === 0 || search.length >= 2) {
            filterBlogsWithoutLoader();
        }

    }, 400);
});


function filterBlogsWithoutLoader() {

    $.ajax({
        url: 'filter-blogs.php',
        type: 'POST',

        data: {
            search: $('#blogSearch').val().trim(),
            topic: $('#topicFilter').val(),
            sort: $('#sortFilter').val()
        },

        success: function(response) {
            $('#blogResults').html(response);
        }
    });
}
</script>