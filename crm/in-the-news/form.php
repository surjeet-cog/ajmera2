<?php $isEdit = isset($record); ?>
<form method="post" enctype="multipart/form-data">
    <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token']) ?>">

    <div class="mb-3">
        <label class="form-label" for="title">Video Title</label>
        <input class="form-control" id="title" name="title" required maxlength="255"
               value="<?= e($record['title'] ?? '') ?>">
    </div>

    <div class="mb-3">
        <label class="form-label" for="video_url">YouTube Video URL</label>
        <input class="form-control" type="url" id="video_url" name="video_url"
               required maxlength="500" placeholder="https://www.youtube.com/watch?v=..."
               value="<?= e($record['video_url'] ?? '') ?>">
    </div>

    <div class="mb-3">
        <label class="form-label" for="year">Year</label>
        <input class="form-control" type="number" id="year" name="year"
               min="1901" max="2155" required
               value="<?= e($record['year'] ?? date('Y')) ?>">
    </div>

    <div class="mb-3">
        <label class="form-label" for="thumbnail">Thumbnail (JPG, PNG, WEBP; max 5MB)</label>
        <input class="form-control" type="file" id="thumbnail" name="thumbnail"
               accept="image/jpeg,image/png,image/webp">
        <?php if (!empty($record['thumbnail'])): ?>
            <p class="mt-2">
                Current: <a target="_blank" rel="noopener"
                    href="/ajmera/<?= e(ltrim($record['thumbnail'], '/')) ?>">View thumbnail</a>
            </p>
        <?php endif; ?>
    </div>

    <div class="mb-3">
        <label class="form-label" for="sort_order">Sort Order</label>
        <input class="form-control" type="number" id="sort_order" name="sort_order"
               value="<?= e($record['sort_order'] ?? 0) ?>">
    </div>

    <div class="form-check mb-3">
        <input class="form-check-input" type="checkbox" id="status" name="status"
               value="1" <?= !isset($record) || !empty($record['status']) ? 'checked' : '' ?>>
        <label class="form-check-label" for="status">Active</label>
    </div>

    <button class="btn btn-primary" type="submit"><?= $isEdit ? 'Update' : 'Save' ?></button>
    <a class="btn btn-secondary" href="index.php">Cancel</a>
</form>
