<?php

include '../header.php';
require_once '../dbc.php';
include'../header_css.php';
$slug = $_GET['slug'] ?? '';

$stmt = $conn->prepare("
    SELECT *
    FROM blogs
    WHERE slug = ? AND status = 1
    LIMIT 1
");

$stmt->bind_param("s", $slug);
$stmt->execute();

$result = $stmt->get_result();
$blog = $result->fetch_assoc();

if (!$blog) {
    http_response_code(404);
    die("Blog not found");
}


/* ==============================
   TABLE OF CONTENT - H2 ONLY
============================== */

$content = $blog['content'];

$tocItems = [];
$headingIndex = 0;

$content = preg_replace_callback(
    '/<h2([^>]*)>(.*?)<\/h2>/is',
    function ($matches) use (&$tocItems, &$headingIndex) {

        $attributes = $matches[1];
        $headingContent = $matches[2];

        $headingTitle = trim(
    preg_replace(
        '/\s+/',
        ' ',
        html_entity_decode(
            strip_tags($headingContent),
            ENT_QUOTES | ENT_HTML5,
            'UTF-8'
        )
    )
);

        if ($headingTitle === '') {
            return $matches[0];
        }

        $headingIndex++;

        $headingId = 'blog-heading-' . $headingIndex;

        $tocItems[] = [
            'title' => $headingTitle,
            'id'    => $headingId
        ];

        // Existing ID remove
        $attributes = preg_replace(
            '/\s+id\s*=\s*([\'"]).*?\1/i',
            '',
            $attributes
        );

        return '<h2' .
            $attributes .
            ' id="' . $headingId . '">' .
            $headingContent .
            '</h2>';
    },
    $content
);

?>

<!-- Dynamic Meta -->

<title>
    <?php echo htmlspecialchars(
        $blog['meta_title'] ?: $blog['title'],
        ENT_QUOTES,
        'UTF-8'
    ); ?>
</title>

<meta
    name="description"
    content="<?php echo htmlspecialchars(
        $blog['meta_description'] ?: $blog['description'],
        ENT_QUOTES,
        'UTF-8'
    ); ?>"
>

<!-- Dynamic Schema -->

<?php if (!empty($blog['schema_markup'])) { ?>

<script type="application/ld+json">
<?php echo $blog['schema_markup']; ?>
</script>

<?php } ?>





<div class="blog-detail top80 pb-0 pad80">

    <div class="container">

        <h1 class="h4 mb-5">
            <?php echo htmlspecialchars(
                $blog['title'],
                ENT_QUOTES,
                'UTF-8'
            ); ?>
        </h1>


        <div class="blog-detail-image">

            <img
                src="../<?php echo htmlspecialchars(
                    $blog['image'],
                    ENT_QUOTES,
                    'UTF-8'
                ); ?>"
                alt="<?php echo htmlspecialchars(
                    $blog['title'],
                    ENT_QUOTES,
                    'UTF-8'
                ); ?>"
                class="w-100"
            >

        </div>


        <div class="blog-content">

            <div class="blog-social">
                <div class="row">
                    <div class="col-md-6">
                        <p class="publish-date text-uppercase">
                           Date: <?php echo date(
                                'd F Y',
                                strtotime($blog['publish_date'])
                            ); ?>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex text-uppercase justify-content-end align-items-center">
                            <p class="publish-date m-0 share"> Share: </p>
                           <div class="social-links1 m-0">
    
                        <a target="_blank" href="https://www.facebook.com/AjmeraRealty/" aria-label="Facebook">
                            <img src="https://webtestinglink.net/ajmera/images/facebook.svg">
                        </a>
    
                        <a target="_blank" href="https://www.instagram.com/ajmera.realty/" aria-label="Instagram">
                            <img src="https://webtestinglink.net/ajmera/images/instagram.svg">
                        </a>
    
                        <a href="#" aria-label="YouTube">
                            <img src="https://webtestinglink.net/ajmera/images/youtube.svg">
                        </a>
    
                        <a target="_blank" href="https://www.linkedin.com/company/ajmera-realty-&amp;-infra-ltd" aria-label="LinkedIn">
                            <img src="https://webtestinglink.net/ajmera/images/linkedin.svg">
                        </a>
    
                    </div>
                        </div>
                    </div>
                </div>
            </div>

        <div class="blog-border">
            <div class="row">

                <!-- BLOG CONTENT -->

                <div class="col-lg-8 col-md-12">

                    <div class="blog_content">
                        <?php echo $content; ?>
                    </div>

                </div>


                <!-- TABLE OF CONTENT -->

                <div class="col-lg-4 col-md-12">

                    <?php if (!empty($tocItems)) { ?>

                        <div class="blog-toc">

                            <h4>Table of Contents</h4>

                            <ul>

                                <?php foreach ($tocItems as $item) { ?>

                                    <li>

                                        <a href="#<?php echo htmlspecialchars(
                                            $item['id'],
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ); ?>">

                                            <?php echo htmlspecialchars(
                                                $item['title'],
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ); ?>

                                        </a>

                                    </li>

                                <?php } ?>

                            </ul>

                        </div>

                    <?php } ?>

                </div>

            </div>
        </div>
        </div>

    </div>

</div>



<?php

$current_slug = $_GET['slug'] ?? '';

$recent_stmt = $conn->prepare("
    SELECT id, title, slug, image, publish_date
    FROM blogs
    WHERE status = 1
    AND slug != ?
    ORDER BY publish_date DESC, id DESC
    LIMIT 3
");

$recent_stmt->bind_param("s", $current_slug);
$recent_stmt->execute();

$recent_result = $recent_stmt->get_result();

if ($recent_result->num_rows > 0) {
?>

<div class="blog-recent border1 pad80 d-none">

    <div class="container">

        <h2 class="h4 mb-4">Recent Blogs</h2>

        <div class="row">

            <?php while ($recent = $recent_result->fetch_assoc()) { ?>

                <div class="col-lg-4 col-md-6 mb-4">

                    <div class="recent-blog-card content-card">

                        <a href="index.php?slug=<?php echo urlencode($recent['slug']); ?>">
                            
                            
                            <div class="card-heading">
                                <h3 class="h5">
                                    <?php echo htmlspecialchars($recent['title']); ?>
                                </h3>
                                </div>
                                <p class="publish-date mt-2">
                                    <?php echo date(
                                        'd F Y',
                                        strtotime($recent['publish_date'])
                                    ); ?>
                                </p>
                            <div class="recent-blog-image mt-3">

                                <img
                                    src="../<?php echo htmlspecialchars($recent['image']); ?>"
                                    alt="<?php echo htmlspecialchars($recent['title']); ?>"
                                    class="w-100"
                                >

                            </div>

                            <div class="recent-blog-content">

                                

                            </div>

                        </a>

                    </div>

                </div>

            <?php } ?>

        </div>

    </div>

</div>

<?php
}

$recent_stmt->close();
?>

<script>

document.querySelectorAll('.blog-toc a').forEach(function(link) {

    link.addEventListener('click', function(e) {

        e.preventDefault();

        const target = document.querySelector(
            this.getAttribute('href')
        );

        if (target) {

            const headerOffset = 100;

            const elementPosition =
                target.getBoundingClientRect().top;

            const offsetPosition =
                elementPosition +
                window.pageYOffset -
                headerOffset;

            window.scrollTo({
                top: offsetPosition,
                behavior: 'smooth'
            });

        }

    });

});

</script>


<?php include '../footer.php'; ?>