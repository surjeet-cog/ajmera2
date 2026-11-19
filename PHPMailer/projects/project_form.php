<?php
$currentProject = $project['project_name'] ?? '';
?>

<form id="projectEnquiryForm"
      class="custom-enquire-form wow animate__animated animate__fadeInUp"
      method="POST"
      action="project_form_backend.php">

    <div class="row gx-4 gy-5">

        <!-- First Name -->
        <div class="col-md-6">
            <input type="text"
                   name="first_name"
                   id="first_name"
                   class="form-control custom-input"
                   placeholder="First Name">
        </div>

        <!-- Email -->
        <div class="col-md-6">
            <input type="email"
                   name="email"
                   id="email"
                   class="form-control custom-input"
                   placeholder="Email">
        </div>

        <!-- Phone -->
        <div class="col-md-6">
            <input type="tel"
                   name="phone"
                   id="phone"
                   class="form-control custom-input"
                   placeholder="Phone Number"
                   maxlength="10">
        </div>

        <!-- Project -->
       <?php
// Current page se project name get karein
$currentPage = basename($_SERVER['PHP_SELF']);

$pageProjects = [
    'ajmera-manhattan.php' => 'Manhattan',
    'ajmera-greenfinity.php' => 'Ajmera Greenfinity',
    'ajmera-summit.php' => 'Ajmera Summit',
    'ajmera-sikova.php' => 'Ajmera Sikova',
    'ajmera-exotica.php' => 'Ajmera Exotica',
    'ajmera-nucleus.php' => 'Ajmera Nucleus',
    'ajmera-heritage-city.php' => 'Ajmera Heritage City'
];

$currentProject = $pageProjects[$currentPage] ?? '';
?>

<div class="col-md-6">

    <select name="project"
            id="project"
            class="form-control custom-input custom-select"
            <?php echo ($currentProject !== '') ? 'disabled' : ''; ?>>

        <option value="">Project Interested In</option>

        <?php
        $projects = [
            'Ajmera Sikova',
            'Ajmera Heritage City',
            'Ajmera Summit',
            'Ajmera i Land',
            'Ajmera Greenfinity',
            'Bliss',
            'Ajmera Kalyan Extravaganza',
            'Ajmera Exotica',
            'Manhattan',
            'Arham',
            'Ajmera Nucleus',
            'Ajmera Nucleus Commercial',
            'Lugaano',
            'Ajmera Florenza',
            'Ajmera Aria',
            'Biznext',
            'Zenica',
            'Vann By Ajmera',
            'Ajmera Shikhar',
            'Ajmera Solis',
            'Ajmera Vihara',
            'Ajmera Arham'
        ];

        foreach ($projects as $projectName) {
        ?>

            <option value="<?php echo htmlspecialchars($projectName, ENT_QUOTES, 'UTF-8'); ?>"
                <?php echo ($currentProject === $projectName) ? 'selected' : ''; ?>>

                <?php echo htmlspecialchars($projectName, ENT_QUOTES, 'UTF-8'); ?>

            </option>

        <?php } ?>

    </select>

    <?php if ($currentProject !== '') { ?>
        <input type="hidden" name="project"
               value="<?php echo htmlspecialchars($currentProject, ENT_QUOTES, 'UTF-8'); ?>">
    <?php } ?>

</div>

        <!-- Message -->
        <div class="col-12 mt-5">
            <textarea name="message"
                      id="message"
                      class="form-control custom-input"
                      placeholder="Message"
                      rows="4"></textarea>
        </div>

        <!-- Submit -->
        <div class="col-12 mt-5">

            <button class="discover-btn"
                    id="projectSubmitBtn"
                    type="submit">

                <span>Submit</span>

            </button>

        </div>

    </div>

</form>