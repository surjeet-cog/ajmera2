<?php
require_once __DIR__ . '/projects/brochure_config.php';

$requestedSlug = trim($_GET['project'] ?? '');
$downloadBrochure = null;

foreach ($brochureProjects as $project) {
    if ($project['slug'] === $requestedSlug) {
        $downloadBrochure = $project;
        break;
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Ajmera Realty & Infra India</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="css/style.css" rel="stylesheet">
   <link href="css/responsives.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
  <link href="https://github.hubspot.com/odometer/themes/odometer-theme-minimal.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/2.0.0/slick-theme.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/2.0.0/slick.css" >
  <!-- Favicon icon -->
<link rel="shortcut icon" href="images/favicon-16x16.png">
<link rel="apple-touch-icon" href="images/apple-touch-icon-57x57.png">
<link rel="apple-touch-icon" href="images/apple-touch-icon-72x72.png">
<link rel="apple-touch-icon" href="images/apple-touch-icon-114x114.png">
</head>
<body>


<style>
body {
    background: url(images/thankyou.webp);
    background-size: cover !important;
}
    .thank-you-message {
            display: flex;
    align-items: center;
    justify-content: start;
    height: 100vh;
}

.thank-you-message h2 {
    margin: 0 0 15px;
    font-size: 42px;
    font-weight: 700;
    line-height: 1.2;
    color: #222;
}

.thank-you-message p {
    margin: 0;
    font-size: 17px;
    line-height: 1.7;
}
header{
        background: #000;
        padding-bottom: 20px !important;
    }

.thank-you-message > div{
        background: #ffffff3d;
    padding: 60px;
    backdrop-filter: blur(11px);
}
</style>

<div class="thank-you-message container" >
   <div class="m-0">
        <h2 class="h4">Thank You!</h2>
    <p>Thank you for getting in touch with us.<br> We will get back to you shortly.</p>
    
    <div class="discover-wrapper mt-4 wow animate__animated animate__fadeInUp">
        <?php if ($downloadBrochure) { ?>

            <!--<h4>-->
            <!--    <?php echo htmlspecialchars($downloadBrochure['name'], ENT_QUOTES, 'UTF-8'); ?>-->
            <!--    Brochure-->
            <!--</h4>-->

            
            <a target="_blank" href="<?php echo htmlspecialchars($downloadBrochure['pdf'], ENT_QUOTES, 'UTF-8'); ?>" class="mb-2 text-decoration-none d-block">
            <button class="discover-btn" type="button">
                <span class="bg-transparent"> Download Brochure</span>
            </button>
        </a>

        <?php } ?>
        <a href="index.php" class="text-decoration-none">
            <button class="discover-btn" type="button">
                <span class="bg-transparent">Back to Home</span>
            </button>
        </a>
    </div>
   </div>
</div>


<script src="js/jquery.min.js"></script>
<!--<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>-->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/wow/1.1.2/wow.min.js" ></script>
<script src="https://github.hubspot.com/odometer/odometer.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/additional-methods.min.js"></script>
<script src="js/custom.js"></script>

</body>
</html>