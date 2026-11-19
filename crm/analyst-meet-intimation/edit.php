<?php
ob_start();
require_once __DIR__ . '/../auth.php';
require_once __DIR__ . '/../../dbc.php';
require_once __DIR__ . '/form_helpers.php';
$id=filter_input(INPUT_GET,'id',FILTER_VALIDATE_INT);
if (!$id) { header('Location: index.php'); exit; }
$stmt=$conn->prepare("SELECT id,title,pdf_file,financial_year,sort_order,status FROM analyst_meet_intimations WHERE id=? LIMIT 1");
$stmt->bind_param('i',$id); $stmt->execute(); $report=$stmt->get_result()->fetch_assoc(); $stmt->close();
if (!$report) { $_SESSION['error']='Analyst meet intimation not found.'; header('Location: index.php'); exit; }

$errors=[]; $title=$report['title']; $financialYear=$report['financial_year']; $sortOrder=(string)$report['sort_order']; $status=(string)$report['status'];
if ($_SERVER['REQUEST_METHOD']==='POST') {
    analyst_verify_csrf();
    $title=trim($_POST['title']??''); $financialYear=trim($_POST['financial_year']??'');
    $sortOrder=filter_var($_POST['sort_order']??0,FILTER_VALIDATE_INT);
    $status=(($_POST['status']??'0')==='1')?1:0;
    if ($title==='') $errors[]='Please enter title.';
    if (!analyst_valid_year($financialYear)) $errors[]='Financial year must be YYYY-YY, e.g. 2025-26.';
    if ($sortOrder===false || $sortOrder<0) $errors[]='Sort order must be zero or greater.';
    if (!$errors) {
        $newPdf=null;
        try {
            $newPdf=analyst_upload_pdf($_FILES['pdf_file']??[]);
            $pdfToSave=$newPdf ?: $report['pdf_file'];
            $stmt=$conn->prepare("UPDATE analyst_meet_intimations SET title=?,pdf_file=?,financial_year=?,sort_order=?,status=? WHERE id=?");
            $stmt->bind_param('sssiii',$title,$pdfToSave,$financialYear,$sortOrder,$status,$id);
            $stmt->execute(); $stmt->close();
            if ($newPdf && $report['pdf_file']) analyst_delete_pdf($report['pdf_file']);
            $_SESSION['success']='Analyst meet intimation updated successfully.';
            header('Location: index.php'); exit;
        } catch(Throwable $ex) {
            if ($newPdf) analyst_delete_pdf($newPdf);
            $errors[]=$ex->getMessage();
        }
    }
}
require_once __DIR__ . '/../includes/header.php';
?>
<div class="container-fluid py-3">
    <div class="d-flex justify-content-between align-items-center mb-3"><h3>Edit Analyst Meet Intimation</h3><a href="index.php" class="btn btn-secondary">Back</a></div>
    <?php if($errors): ?><div class="alert alert-danger"><?php foreach($errors as $error): ?><div><?= e($error); ?></div><?php endforeach; ?></div><?php endif; ?>
    <div class="card"><div class="card-body">
        <form method="post" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?= e(analyst_csrf_token()); ?>">
            <div class="mb-3"><label class="form-label">Title *</label><input type="text" name="title" class="form-control" value="<?= e($title); ?>" required></div>
            <div class="row">
                <div class="col-md-6 mb-3"><label class="form-label">Financial Year *</label><input type="text" name="financial_year" class="form-control" value="<?= e($financialYear); ?>" placeholder="2025-26" pattern="\d{4}-\d{2}" required></div>
                <div class="col-md-6 mb-3"><label class="form-label">Sort Order</label><input type="number" name="sort_order" class="form-control" min="0" value="<?= e($sortOrder); ?>"></div>
            </div>
            <div class="mb-3"><label class="form-label">Current PDF</label><br>
                <?php if($report['pdf_file']): ?><a href="/ajmera/<?= e(ltrim($report['pdf_file'],'/')); ?>" target="_blank" rel="noopener">View current PDF</a><?php else: ?><span class="text-muted">No PDF uploaded</span><?php endif; ?>
            </div>
            <div class="mb-3"><label class="form-label">Replace PDF (optional)</label><input type="file" name="pdf_file" class="form-control" accept="application/pdf,.pdf"><small class="text-muted">Leave blank to keep current PDF. PDF only, maximum 15 MB.</small></div>
            <div class="mb-3"><label class="form-label">Status</label><select name="status" class="form-select"><option value="1" <?= (string)$status==='1'?'selected':''; ?>>Active</option><option value="0" <?= (string)$status==='0'?'selected':''; ?>>Inactive</option></select></div>
            <button type="submit" class="btn btn-primary">Update Intimation</button> <a href="index.php" class="btn btn-light">Cancel</a>
        </form>
    </div></div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
