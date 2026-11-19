
<?php
$currentPage = basename($_SERVER['PHP_SELF']);

$pageProjects = [
    'ajmera-annex.php' => 'Ajmera Annex',
    'ajmera-arham.php' => 'Ajmera Arham',
    'ajmera-aria.php' => 'Ajmera Aria',
    'ajmera-eden.php' => 'Ajmera Eden',
    'ajmera-greenfinity.php' => 'Ajmera Greenfinity',
    'ajmera-i-land-aeon.php' => 'Ajmera i Land Aeon',
    'ajmera-i-land-treon.php' => 'Ajmera i Land Treon',
    'ajmera-i-land-zeon.php' => 'Ajmera i Land Zeon',
    'ajmera-icrc.php' => 'Ajmera ICRC',
    'ajmera-iris.php' => 'Ajmera Iris',
    'ajmera-manhattan.php' => 'Manhattan',
    'ajmera-marina.php' => 'Ajmera Marina',
    'ajmera-nucleus-commercial.php' => 'Ajmera Nucleus Commercial',
    'ajmera-nucleus-residential.php' => 'Ajmera Nucleus',
    'ajmera-prive.php' => 'Ajmera Prive',
    'ajmera-shikhar.php' => 'Ajmera Shikhar',
    'ajmera-solis.php' => 'Ajmera Solis',
    'ajmera-summit.php' => 'Ajmera Summit',
    'ajmera-vihara.php' => 'Ajmera Vihara',
    'casa-vyoma.php' => 'Casa Vyoma',
    'vann-by-ajmera.php' => 'Vann By Ajmera',
    'ajmera-elevate.php' => 'Ajmera Elevate',
    '33fifteen.php' => '33fifteen',
    'ajmera-lakeside-paradise.php' => 'Ajmera Lakeside Paradise'
];

$currentProject = $pageProjects[$currentPage] ?? '';

$projects = array_values($pageProjects);
?>
<div id="projectsLoader" style="display:none; text-align:center; padding:20px;">
        <div class="loader_2"></div>
       
    </div>
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
                   placeholder="First Name"
                   required>
        </div>

        <!-- Last Name -->
        <div class="col-md-6">
            <input type="text"
                   name="last_name"
                   id="last_name"
                   class="form-control custom-input"
                   placeholder="Last Name"
                   required>
        </div>

        <!-- Email -->
        <div class="col-md-6">
            <input type="email"
                   name="email"
                   id="email"
                   class="form-control custom-input"
                   placeholder="Email"
                   required>
        </div>

        <!-- Phone -->
        <div class="col-md-6">
            <input type="tel"
                   name="phone"
                   id="phone"
                   class="form-control custom-input"
                   placeholder="Phone Number"
                   maxlength="10"
                   required>
        </div>

        <!-- Project -->
        <div class="col-md-12">

            <select name="project"
                    id="project"
                    class="form-control custom-input custom-select"
                    <?= ($currentProject !== '') ? 'disabled' : '' ?>
                    required>

                <option value="">Project Interested In</option>

                <?php foreach ($projects as $projectName): ?>
                    <option
                        value="<?= htmlspecialchars($projectName, ENT_QUOTES, 'UTF-8') ?>"
                        <?= ($currentProject === $projectName) ? 'selected' : '' ?>>

                        <?= htmlspecialchars($projectName, ENT_QUOTES, 'UTF-8') ?>

                    </option>
                <?php endforeach; ?>

            </select>

            <?php if ($currentProject !== ''): ?>
                <input type="hidden"
                       name="project"
                       value="<?= htmlspecialchars($currentProject, ENT_QUOTES, 'UTF-8') ?>">
            <?php endif; ?>

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
