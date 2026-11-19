<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'dbc.php';


/* =========================================================
   AJAX REQUEST
   Same file AJAX response handle karegi
========================================================= */

if (isset($_GET['ajax']) && $_GET['ajax'] == '1') {

    $selectedYear  = $_GET['year'] ?? '';
    $selectedMonth = $_GET['month'] ?? '';


    /* =========================
       Dynamic Videos
    ========================= */

    $sql = "
        SELECT *
        FROM video_gallery
        WHERE status = 1
    ";


    if ($selectedYear !== '') {

        $year = (int) $selectedYear;

        $sql .= " AND YEAR(publish_date) = $year";
    }


    if ($selectedMonth !== '') {

        $month = (int) $selectedMonth;

        $sql .= " AND MONTH(publish_date) = $month";
    }


    $sql .= "
        ORDER BY publish_date DESC, id DESC
    ";


    $videos = $conn->query($sql);


    if (!$videos) {

        echo '
            <div class="col-12">
                <p class="text-center">
                    Video Query Error: ' . htmlspecialchars($conn->error) . '
                </p>
            </div>
        ';

        exit;
    }


    /* =========================
       AJAX Video Result
    ========================= */

    if ($videos->num_rows > 0) {

        while ($video = $videos->fetch_assoc()) {

            ?>

            <div class="col-lg-4 col-md-6 video-card m-0">

                <a href="<?php echo htmlspecialchars($video['video_url']); ?>"
                   class="content-card video-card"
                   data-video-type="<?php echo htmlspecialchars($video['video_type']); ?>"
                   data-video-url="<?php echo htmlspecialchars($video['video_url']); ?>">


                    <!-- Date -->

                    <span class="category-label text-uppercase">

                        <?php

                        if (!empty($video['publish_date'])) {

                            echo strtoupper(
                                date(
                                    'F Y',
                                    strtotime($video['publish_date'])
                                )
                            );

                        }

                        ?>

                    </span>


                    <!-- Heading -->

                    <div class="card-heading">

                        <h3>
                            <?php echo htmlspecialchars($video['title']); ?>
                        </h3>

                    </div>


                    <!-- Image -->

                    <div class="content-image mb-3 mt-4 position-relative overflow-hidden">

                        <img
                            src="<?php echo htmlspecialchars($video['thumbnail']); ?>"
                            alt="<?php echo htmlspecialchars($video['title']); ?>"
                            class="w-100">


                        <!-- Play Button -->

                        <span class="arrow video-play-btn">

                            <svg
                                width="50"
                                height="50"
                                viewBox="0 0 50 50"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg">

                                <circle
                                    cx="25"
                                    cy="25"
                                    r="24"
                                    fill="#ffffff"/>

                                <path
                                    d="M21 17L34 25L21 33V17Z"
                                    fill="#D71920"/>

                            </svg>

                        </span>

                    </div>

                </a>

            </div>

            <?php
        }

    } else {

        ?>

        <div class="col-12">

            <p class="text-center">
                No videos found
            </p>

        </div>

        <?php
    }


    /*
     * IMPORTANT:
     * AJAX request ke baad header/footer/page dobara load nahi hoga.
     */

    exit;
}


/* =========================================================
   Selected Filters
========================================================= */

$selectedYear  = $_GET['year'] ?? '';
$selectedMonth = $_GET['month'] ?? '';


/* =========================================================
   Dynamic Years
========================================================= */

$years = $conn->query("
    SELECT DISTINCT YEAR(publish_date) AS year
    FROM video_gallery
    WHERE status = 1
    AND publish_date IS NOT NULL
    ORDER BY year DESC
");

if (!$years) {

    die("Year Query Error: " . $conn->error);

}


/* =========================================================
   Dynamic Months
========================================================= */

$months = $conn->query("
    SELECT DISTINCT
        MONTH(publish_date) AS month_number,
        MONTHNAME(publish_date) AS month_name
    FROM video_gallery
    WHERE status = 1
    AND publish_date IS NOT NULL
    ORDER BY month_number ASC
");

if (!$months) {

    die("Month Query Error: " . $conn->error);

}


/* =========================================================
   Dynamic Videos
========================================================= */

$sql = "
    SELECT *
    FROM video_gallery
    WHERE status = 1
";


if ($selectedYear !== '') {

    $year = (int) $selectedYear;

    $sql .= " AND YEAR(publish_date) = $year";

}


if ($selectedMonth !== '') {

    $month = (int) $selectedMonth;

    $sql .= " AND MONTH(publish_date) = $month";

}


$sql .= "
    ORDER BY publish_date DESC, id DESC
";


$videos = $conn->query($sql);

if (!$videos) {

    die("Video Query Error: " . $conn->error);

}


include 'header.php';

?>


<!-- =========================
     BANNER
========================= -->

<div class="banner position-relative">

    <div class="banner-inner position-relative"
         style="background:url('images/video-banner.webp') no-repeat center center / cover">

        <div class="overlay"></div>

    </div>


    <div class="caption d-flex align-items-center">

        <div class="container position-relative">

            <div class="m-0">

                <h1 class="wow animate__fadeInUp animate__animated m-0">
                   A journey captured on film.
                </h1>

                <p class="wow animate__fadeInUp animate__animated">
                    From landmark projects and milestones to the people behind them, explore the moments that have shaped Ajmera over the years. 
                </p>

            </div>

        </div>

    </div>

</div>


<!-- =========================
     FILTER
========================= -->

<section class="search_filter pad80">

    <div class="container">

        <!-- Form rakha hai, lekin submit nahi hoga -->

        <form id="videoFilterForm"
              onsubmit="return false;">

            <div class="row g-4 align-items-center">


                <!-- YEAR -->

                <div class="col-lg-3 col-md-6 m-0">

                    <div class="blog-select-box position-relative">

                        <select
                            name="year"
                            id="videoYear"
                            class="form-select">

                            <option value="">
                                Year
                            </option>


                            <?php while ($yearRow = $years->fetch_assoc()): ?>

                                <option
                                    value="<?php echo htmlspecialchars($yearRow['year']); ?>"
                                    <?php echo ($selectedYear == $yearRow['year']) ? 'selected' : ''; ?>>

                                    <?php echo htmlspecialchars($yearRow['year']); ?>

                                </option>

                            <?php endwhile; ?>

                        </select>

                    </div>

                </div>


                <!-- MONTH -->

                <div class="col-lg-3 col-md-6 m-0">

                    <div class="blog-select-box position-relative">

                        <select
                            name="month"
                            id="videoMonth"
                            class="form-select">

                            <option value="">
                                Month
                            </option>


                            <?php while ($monthRow = $months->fetch_assoc()): ?>

                                <option
                                    value="<?php echo htmlspecialchars($monthRow['month_number']); ?>"
                                    <?php echo ($selectedMonth == $monthRow['month_number']) ? 'selected' : ''; ?>>

                                    <?php echo htmlspecialchars($monthRow['month_name']); ?>

                                </option>

                            <?php endwhile; ?>

                        </select>

                    </div>

                </div>


            </div>

        </form>

    </div>

</section>


<!-- =========================
     VIDEO GALLERY
========================= -->

<section class="news-section pt-0 pad80 video-gallery_wrapper">

    <div class="container">

        <!-- IMPORTANT: ID added here -->

        <div class="row g-4"
             id="videoResults">


            <?php if ($videos->num_rows > 0): ?>


                <?php while ($video = $videos->fetch_assoc()): ?>


                    <div class="col-lg-4 col-md-6 video-card m-0">

                        <a href="<?php echo htmlspecialchars($video['video_url']); ?>" target="_blank"
                           class="content-card video-card"
                           data-video-type="<?php echo htmlspecialchars($video['video_type']); ?>"
                           data-video-url="<?php echo htmlspecialchars($video['video_url']); ?>">


                            <!-- Date -->

                            <span class="category-label text-uppercase">

                                <?php

                                if (!empty($video['publish_date'])) {

                                    echo strtoupper(
                                        date(
                                            'F Y',
                                            strtotime($video['publish_date'])
                                        )
                                    );

                                }

                                ?>

                            </span>


                            <!-- Heading -->

                            <div class="card-heading">

                                <h3>
                                    <?php echo htmlspecialchars($video['title']); ?>
                                </h3>

                            </div>


                            <!-- Image -->

                            <div class="content-image mb-3 mt-4 position-relative overflow-hidden">

                                <img
                                    src="<?php echo htmlspecialchars($video['thumbnail']); ?>"
                                    alt="<?php echo htmlspecialchars($video['title']); ?>"
                                    class="w-100">


                                <!-- Play Button -->

                                <span class="arrow video-play-btn">

                                    <svg
                                        width="50"
                                        height="50"
                                        viewBox="0 0 50 50"
                                        fill="none"
                                        xmlns="http://www.w3.org/2000/svg">

                                        <circle
                                            cx="25"
                                            cy="25"
                                            r="24"
                                            fill="#ffffff"/>

                                        <path
                                            d="M21 17L34 25L21 33V17Z"
                                            fill="#D71920"/>

                                    </svg>

                                </span>

                            </div>

                        </a>

                    </div>


                <?php endwhile; ?>


            <?php else: ?>


                <div class="col-12">

                    <p class="text-center">
                        No videos found for the selected filter.
                    </p>

                </div>


            <?php endif; ?>


        </div>

    </div>

</section>


<?php include 'footer.php'; ?>


<!-- =========================================================
     AJAX FILTER
========================================================= -->

<script>

$(document).ready(function () {


    /* =====================================================
       FILTER FUNCTION
    ===================================================== */

    function filterVideos() {

        let year = $('#videoYear').val();

        let month = $('#videoMonth').val();


        /* Loading */

        $('#videoResults').addClass('loading');


        $.ajax({

            /*
             * SAME CURRENT PAGE
             */
            url: window.location.pathname,

            type: 'GET',

            data: {

                ajax: '1',

                year: year,

                month: month

            },


            success: function (response) {

                $('#videoResults').html(response);

            },


            error: function (xhr, status, error) {

                console.log(error);

                $('#videoResults').html(
                    '<div class="col-12">' +
                    '<p class="text-center">' +
                    'Something went wrong.' +
                    '</p>' +
                    '</div>'
                );

            },


            complete: function () {

                $('#videoResults').removeClass('loading');

            }

        });

    }


    /* =====================================================
       YEAR CHANGE
    ===================================================== */

    $('#videoYear').on('change', function () {

        filterVideos();

    });


    /* =====================================================
       MONTH CHANGE
    ===================================================== */

    $('#videoMonth').on('change', function () {

        filterVideos();

    });


});

</script>


<style>

#videoResults {
    position: relative;
    transition: opacity 0.2s ease;
}

#videoResults.loading {
    opacity: 0.5;
    pointer-events: none;
}

</style>