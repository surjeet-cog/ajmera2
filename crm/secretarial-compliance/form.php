<?php $isEdit = isset($record); ?>
<form method="post" enctype="multipart/form-data">
  <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token']) ?>">
  <div class="mb-3">
    <label class="form-label" for="financial_year">Financial Year</label>
    <input class="form-control" id="financial_year" name="financial_year" required maxlength="20" placeholder="e.g. 2025-26" value="<?= e($record['financial_year'] ?? '') ?>">
  </div>
  <div class="mb-3">
    <label class="form-label" for="title">Title</label>
    <input class="form-control" id="title" name="title" required maxlength="255" value="<?= e($record['title'] ?? '') ?>">
  </div>
  <div class="mb-3">
    <label class="form-label" for="pdf_file">PDF Document (max 15MB)</label>
    <input class="form-control" type="file" id="pdf_file" name="pdf_file" accept="application/pdf">
    <?php if (!empty($record['pdf_file'])): ?><p class="mt-2">Current: <a target="_blank" rel="noopener" href="/ajmera/<?= e(ltrim($record['pdf_file'], '/')) ?>">View PDF</a></p><?php endif; ?>
  </div>
  <div class="form-check mb-3">
    <input class="form-check-input" type="checkbox" id="status" name="status" value="1" <?= !isset($record) || !empty($record['status']) ? 'checked' : '' ?>>
    <label class="form-check-label" for="status">Active</label>
  </div>
  <button class="btn btn-primary" type="submit"><?= $isEdit ? 'Update' : 'Save' ?></button>
  <a class="btn btn-secondary" href="index.php">Cancel</a>
</form>
