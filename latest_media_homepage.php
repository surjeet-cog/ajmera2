<?php

/* =====================================================
   LATEST MEDIA
===================================================== */

$latestMediaQuery = $conn->query("
    SELECT *
    FROM media
    WHERE status = 1
    ORDER BY media_date DESC, id DESC
    LIMIT 1
");

$latestMedia = $latestMediaQuery->fetch_assoc();

?>

<?php if ($latestMedia) { ?>

<div class="col-lg-6 col-md-6 media-card m-0 wow animate__animated animate__fadeInUp">

    <a href="<?php echo htmlspecialchars(
                  $latestMedia['external_link'] ?? '#'
              ); ?>"
       target="_blank"
       rel="noopener noreferrer"
       class="content-card">

        <!-- Read Time -->
        <span class="category-label text-uppercase">

            <!--<?php //echo (int)$latestMedia['read_time']; ?>-->

            <!--MIN READ-->
            
            News

        </span>


        <!-- Heading -->
        <div class="card-heading">

            <h3>

                <?php echo htmlspecialchars(
                    $latestMedia['title'] ?? ''
                ); ?>

            </h3>
<span class="arrow">

                <img src="images/right_1.svg"
                     alt="Arrow">

            </span>

            <!-- Date -->
           

        </div>
 <p class="publish-date">

                <?php echo date(
                    'd F Y',
                    strtotime($latestMedia['media_date'])
                ); ?>

            </p>

        <!-- Image -->
        <div class="content-image mb-3 mt-4 position-relative overflow-hidden">

            <img src="<?php echo htmlspecialchars(
                          $latestMedia['image'] ?? ''
                      ); ?>"
                 alt="<?php echo htmlspecialchars(
                         $latestMedia['title'] ?? ''
                     ); ?>">


            <!-- Arrow -->
            

        </div>

    </a>

</div>

<?php } ?>