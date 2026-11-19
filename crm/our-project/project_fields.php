
<?php
$data = $data ?? [];

$projectName   = $data['project_name'] ?? '';
$slug          = $data['slug'] ?? '';
$city          = $data['city'] ?? '';
$location      = $data['location'] ?? '';
$projectType   = strtolower($data['project_type'] ?? 'residential');
$projectStatus = strtolower($data['project_status'] ?? 'ongoing');
$bhkDetails    = $data['bhk_details'] ?? '';
$sortOrder     = $data['sort_order'] ?? 0;
$projectUrl    = $data['project_url'] ?? '';
$projectImage  = $data['project_image'] ?? '';
$status        = isset($data['status']) ? (int)$data['status'] : 1;
?>

<div class="mb-3">
    <label for="project_name" class="form-label">Project Name *</label>
    <input type="text" name="project_name" id="project_name"
           class="form-control"
           value="<?= e($projectName) ?>" required maxlength="255">
</div>

<div class="mb-3">
    <label for="slug" class="form-label">Slug</label>
    <input type="text" name="slug" id="slug" class="form-control"
           value="<?= e($slug) ?>" maxlength="255"
           placeholder="example-project">
    <small class="text-muted">
        Leave blank to generate it from the project name.
    </small>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label for="city" class="form-label">City</label>
        <input type="text" name="city" id="city" class="form-control"
               value="<?= e($city) ?>" maxlength="100">
    </div>

    <div class="col-md-6 mb-3">
        <label for="location" class="form-label">Location</label>
        <input type="text" name="location" id="location"
               class="form-control"
               value="<?= e($location) ?>" maxlength="255">
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label for="project_type" class="form-label">Project Type</label>
        <select name="project_type" id="project_type"
                class="form-select" required>
            <option value="residential"
                <?= $projectType === 'residential' ? 'selected' : '' ?>>
                Residential
            </option>
            <option value="commercial"
                <?= $projectType === 'commercial' ? 'selected' : '' ?>>
                Commercial
            </option>
        </select>
    </div>

    <div class="col-md-6 mb-3">
        <label for="project_status" class="form-label">
            Project Status
        </label>
        <select name="project_status" id="project_status"
                class="form-select" required>
            <option value="ongoing"
                <?= $projectStatus === 'ongoing' ? 'selected' : '' ?>>
                Ongoing
            </option>
            <option value="completed"
                <?= $projectStatus === 'completed' ? 'selected' : '' ?>>
                Completed
            </option>
        </select>
    </div>
</div>

<div class="mb-3">
    <label for="bhk_details" class="form-label">BHK Details</label>
    <input type="text" name="bhk_details" id="bhk_details"
           class="form-control" value="<?= e($bhkDetails) ?>"
           maxlength="100" placeholder="e.g. 2, 3 and 4 BHK">
</div>

<div class="mb-3">
    <label for="project_url" class="form-label">Project URL</label>
    <input type="text" name="project_url" id="project_url"
           class="form-control" value="<?= e($projectUrl) ?>"
           maxlength="500" placeholder="projects/example-project.php">
    <small class="text-muted">
        Enter a relative path from the website root.
    </small>
</div>

<div class="mb-3">
    <label for="project_image" class="form-label">Project Image</label>
    <input type="file" name="project_image" id="project_image"
           class="form-control"
           accept=".jpg,.jpeg,.png,.webp,.gif,image/jpeg,image/png,image/webp,image/gif">

    <small class="text-muted">
        Allowed: JPG, PNG, WEBP, GIF. Maximum size: 5 MB.
    </small>

    <?php if ($projectImage !== ''): ?>
        <div class="mt-3">
            <p class="mb-2">Current Image:</p>
            <img src="<?= e('/ajmera/' . ltrim($projectImage, '/')) ?>"
                 alt="Project image"
                 style="max-width:180px;max-height:130px;object-fit:cover;">
        </div>
    <?php endif; ?>
</div>

<div class="mb-3">
    <label for="sort_order" class="form-label">Sort Order</label>
    <input type="number" name="sort_order" id="sort_order"
           class="form-control" value="<?= e($sortOrder) ?>"
           min="0" step="1">
</div>

<div class="mb-3">
    <label for="status" class="form-label">Visibility Status *</label>
    <select name="status" id="status" class="form-select" required>
        <option value="1" <?= $status === 1 ? 'selected' : '' ?>>
            Active
        </option>
        <option value="0" <?= $status === 0 ? 'selected' : '' ?>>
            Inactive
        </option>
    </select>
</div>
