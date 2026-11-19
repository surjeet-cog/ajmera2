<div class="mb-3">
    <label class="form-label">Title <span class="text-danger">*</span></label>
    <input type="text" name="title" class="form-control" required maxlength="255" value="<?= e($title) ?>">
</div>
<div class="mb-3">
    <label class="form-label">PDF Document <?= $isEdit ? '' : '<span class="text-danger">*</span>' ?></label>
    <input type="file" name="pdf_file" class="form-control" accept="application/pdf,.pdf" <?= $isEdit ? '' : 'required' ?>>
    <small class="text-muted">PDF only, maximum 15 MB.</small>
    <?php if ($isEdit && $pdfFile): ?><div class="mt-2">Current: <a href="/ajmera/<?= e(ltrim($pdfFile, '/')) ?>" target="_blank" rel="noopener">View PDF</a></div><?php endif; ?>
</div>
<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Sort Order</label>
        <input type="number" name="sort_order" class="form-control" min="0" value="<?= e($sortOrder) ?>">
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Status</label>
        <select name="status" class="form-control">
            <option value="1" <?= (string)$status === '1' ? 'selected' : '' ?>>Active</option>
            <option value="0" <?= (string)$status === '0' ? 'selected' : '' ?>>Inactive</option>
        </select>
    </div>
</div>
