


<?php

require_once 'dbc.php';
include'header.php';
include 'header_css.php';
$selectedYear = isset($_GET['year']) ? (int)$_GET['year'] : 0;


/* =========================
   YEAR FILTER
========================= */

$yearSql = "SELECT DISTINCT year
            FROM awards
            WHERE status = 1
            ORDER BY year DESC";

$yearResult = mysqli_query($conn, $yearSql);


/* =========================
   AWARDS DATA
========================= */

if ($selectedYear > 0) {

    $awardSql = "SELECT id, title, year, image, description
                 FROM awards
                 WHERE status = 1
                 AND year = $selectedYear
                 ORDER BY year DESC, id DESC";

} else {

    $awardSql = "SELECT id, title, year, image, description
                 FROM awards
                 WHERE status = 1
                 ORDER BY year DESC, id DESC";
}

$awardResult = mysqli_query($conn, $awardSql);

?>


<style>
    header{
            background: #000;
            padding-bottom: 20px !important;
    }
</style>


<div class="inner-text top80 pad80 awards-inner">
    <div class="container">
        
        
        <div class="search_filter col-4 mb-5 m-0 awards-select">

    <form method="GET">

        <div class="blog-select-box position-relative ">

            <select
                name="year"
                class="form-select"
                onchange="this.form.submit()"
            >

                <option value="0">
                    Year
                </option>

                <?php while ($year = mysqli_fetch_assoc($yearResult)) { ?>

                    <option
                        value="<?= (int)$year['year']; ?>"
                        <?= ($selectedYear == $year['year']) ? 'selected' : ''; ?>
                    >
                        <?= htmlspecialchars($year['year']); ?>
                    </option>

                <?php } ?>

            </select>

        </div>

    </form>

</div>

        
        
        <div class="row">
            <div class="row">

    <?php if (mysqli_num_rows($awardResult) > 0) { ?>

        <?php while ($award = mysqli_fetch_assoc($awardResult)) { ?>

            <div class="col-lg-4 col-md-6 m-0">

                <div class="achievement-box">

                    <h3 class="achievement-heading text-capitalize">
                        <?= htmlspecialchars($award['title']); ?>
                    </h3>

                    <?php if (!empty($award['image'])) { ?>

                        <img
                            src="<?= htmlspecialchars($award['image']); ?>"
                            alt="<?= htmlspecialchars($award['title']); ?>"
                            class="achievement-photo"
                        >

                    <?php } ?>

                    <p class="achievement-text">
                        <?= htmlspecialchars($award['description']); ?>
                    </p>

                </div>

            </div>

        <?php } ?>

    <?php } else { ?>

        <div class="col-12">

            <p class="text-center">
                No awards found for this year.
            </p>

        </div>

    <?php } ?>

</div>
            
        </div>
    </div>
</div>

<?php include'footer.php'?>