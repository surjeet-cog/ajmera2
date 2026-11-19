<?php
require_once 'dbc.php';

/* --------------------------------
   Dynamic Years
--------------------------------- */
$yearsResult = $conn->query("
    SELECT DISTINCT YEAR(media_date) AS year
    FROM media
    WHERE status = 1
    ORDER BY year DESC
");

/* --------------------------------
   Dynamic Months
--------------------------------- */
$monthsResult = $conn->query("
    SELECT DISTINCT MONTH(media_date) AS month
    FROM media
    WHERE status = 1
    ORDER BY month ASC
");

$monthNames = [
    1  => 'January',
    2  => 'February',
    3  => 'March',
    4  => 'April',
    5  => 'May',
    6  => 'June',
    7  => 'July',
    8  => 'August',
    9  => 'September',
    10 => 'October',
    11 => 'November',
    12 => 'December'
];

/* --------------------------------
   Load All Media
--------------------------------- */
$result = $conn->query("
    SELECT *
    FROM media
    WHERE status = 1
    ORDER BY media_date DESC, id DESC
");

include 'header.php';
?>


<!-- =========================
     BANNER
========================= -->

<div class="banner position-relative">

    <div class="banner-inner position-relative"
         style="background:url('images/media-banner.webp') no-repeat">

        <div class="overlay"></div>

    </div>

    <div class="caption d-flex align-items-center">

        <div class="container position-relative">

            <div class="m-0">

                <h1 class="wow animate__fadeInUp animate__animated m-0">
                    The latest from Ajmera.
                </h1>

                <p class="wow animate__fadeInUp animate__animated">
                   Stay informed with company announcements, project updates, media coverage <br>and developments from across the real estate industry.
                </p>

            </div>

        </div>

    </div>

</div>


<!-- =========================
     SEARCH / FILTER
========================= -->

<section class="search_filter pad80">

    <div class="container">

        <div class="row g-4 align-items-center">

            <!-- Search -->
            <div class="col-lg-8">

                <div class="blog-search-box position-relative">

                    <input type="text"
                           id="mediaSearch"
                           class="form-control"
                           placeholder="Search">

                    <span class="search-icon">

                        <svg viewBox="0 0 24 24"
                             width="20"
                             height="20"
                             aria-hidden="true"
                             focusable="false">

                            <circle cx="10.5"
                                    cy="10.5"
                                    r="7"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2">
                            </circle>

                            <line x1="15.6"
                                  y1="15.6"
                                  x2="22"
                                  y2="22"
                                  stroke="currentColor"
                                  stroke-width="2">
                            </line>

                        </svg>

                    </span>

                </div>

            </div>


            <!-- Year -->
            <div class="col-lg-2">

                <div class="blog-select-box position-relative">

                    <select id="mediaYear"
                            class="form-select">

                        <option value="">Year</option>

                        <?php while ($y = $yearsResult->fetch_assoc()) { ?>

                            <option value="<?php echo $y['year']; ?>">
                                <?php echo $y['year']; ?>
                            </option>

                        <?php } ?>

                    </select>

                </div>

            </div>


            <!-- Month -->
            <div class="col-lg-2">

                <div class="blog-select-box position-relative">

                    <select id="mediaMonth"
                            class="form-select">

                        <option value="">Month</option>

                        <?php while ($m = $monthsResult->fetch_assoc()) {

                            $monthNumber = (int)$m['month'];

                        ?>

                            <option value="<?php echo $monthNumber; ?>">
                                <?php echo $monthNames[$monthNumber]; ?>
                            </option>

                        <?php } ?>

                    </select>

                </div>

            </div>

        </div>

    </div>

</section>


<!-- =========================
     MEDIA SECTION
========================= -->

<section class="news-section pt-0 pad80">

    <div class="container">

        <div class="row mt-4"
             id="mediaList">

<!-- Loader -->
<div id="projectsLoader" style="display:none; text-align:center; padding:20px;">
    <div class="loader_2"></div>
</div>


            <?php while ($media = $result->fetch_assoc()) {

                $mediaYear = date(
                    'Y',
                    strtotime($media['media_date'])
                );

                $mediaMonth = date(
                    'n',
                    strtotime($media['media_date'])
                );

            ?>

                <div class="col-lg-4 col-md-6 media-card ms-0"
                     data-year="<?php echo $mediaYear; ?>"
                     data-month="<?php echo $mediaMonth; ?>"
                      data-title="<?php echo htmlspecialchars(
                         strtolower($media['title']),
                         ENT_QUOTES
                     ); ?>"
                     data-description="<?php echo htmlspecialchars(
                         strtolower($media['description']),
                         ENT_QUOTES
                     ); ?>"
                     >

                    <a href="<?php echo htmlspecialchars(
                                  $media['external_link']
                              ); ?>"
                       target="_blank"
                       rel="noopener noreferrer"
                       class="content-card ">

                        <!-- Read Time -->
                        <span class="category-label text-uppercase">
                            <?php echo (int)$media['read_time']; ?>
                            MIN READ
                        </span>


                        <!-- Heading -->
                        <div class="card-heading media-head">

                            <h3>

                                <?php echo htmlspecialchars(
                                    $media['title']
                                ); ?>

                            </h3>
                            
                             <!-- Date -->
                        <p class="publish-date">

                            <?php echo date(
                                'd F Y',
                                strtotime($media['media_date'])
                            ); ?>

                        </p>
                            
                            

                            

                        </div>


                       


                        <!-- Image -->
                        <div class="content-image mb-3 mt-4 position-relative overflow-hidden">

                            <img src="<?php echo htmlspecialchars(
                                          $media['image']
                                      ); ?>"
                                 alt="<?php echo htmlspecialchars(
                                         $media['title']
                                     ); ?>">
                                     <span class="arrow">

                                <img src="images/right_1.svg"
                                     alt="Arrow">

                            </span>

                        </div>


                        <!-- Description -->
                        <!--<p>

                            <?php echo htmlspecialchars(
                                $media['description']
                            ); ?>

                        </p>-->

                    </a>

                </div>

            <?php } ?>


            <!-- No Result -->
            <div class="col-12 text-center d-none"
                 id="noMediaFound">

                <p class="mb-0 h4">
                    No Data found
                </p>

            </div>

        </div>

    </div>

</section>


<script>
document.addEventListener('DOMContentLoaded', function () {

    const searchInput = document.getElementById('mediaSearch');
    const yearSelect = document.getElementById('mediaYear');
    const monthSelect = document.getElementById('mediaMonth');

    const mediaCards = document.querySelectorAll('.media-card');
    const noMediaFound = document.getElementById('noMediaFound');
    const loader = document.getElementById('projectsLoader');

    function filterMedia() {

        const searchValue = searchInput.value.toLowerCase().trim();
        const selectedYear = yearSelect.value;
        const selectedMonth = monthSelect.value;

        let visibleCount = 0;

        mediaCards.forEach(function (card) {

            const cardYear = card.dataset.year;
            const cardMonth = card.dataset.month;

            const title = card.dataset.title || '';
            const description = card.dataset.description || '';

            const searchMatch =
                searchValue === '' ||
                title.includes(searchValue) ||
                description.includes(searchValue);

            const yearMatch =
                selectedYear === '' ||
                cardYear === selectedYear;

            const monthMatch =
                selectedMonth === '' ||
                cardMonth === selectedMonth;

            if (searchMatch && yearMatch && monthMatch) {
                card.classList.remove('d-none');
                visibleCount++;
            } else {
                card.classList.add('d-none');
            }

        });

        if (visibleCount === 0) {
            noMediaFound.classList.remove('d-none');
        } else {
            noMediaFound.classList.add('d-none');
        }
    }

    // Search - without loader
    searchInput.addEventListener('input', filterMedia);

    // Year filter - with loader
    yearSelect.addEventListener('change', function () {

        loader.style.display = 'flex';

        setTimeout(function () {
            filterMedia();
            loader.style.display = 'none';
        }, 300);

    });

    // Month filter - with loader
    monthSelect.addEventListener('change', function () {

        loader.style.display = 'flex';

        setTimeout(function () {
            filterMedia();
            loader.style.display = 'none';
        }, 300);

    });

});
</script>


<?php include 'footer.php'; ?>