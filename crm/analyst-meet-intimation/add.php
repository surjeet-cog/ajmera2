<?php
ob_start();
require_once __DIR__ . '/../auth.php';
require_once __DIR__ . '/../../dbc.php';
require_once __DIR__ . '/form_helpers.php';

$errors=[]; $title=''; $financialYear=''; $sortOrder='0'; $status='1';
if ($_SERVER['REQUEST_METHOD']==='POST') {
    analyst_verify_csrf();
    $title=trim($_POST['title']??'');
    $financialYear=trim($_POST['financial_year']??'');
    $sortOrder=filter_var($_POST['sort_order']??0,FILTER_VALIDATE_INT);
    $status=(($_POST['status']??'0')==='1')?1:0;
    if ($title==='') $errors[]='Please enter title.';
    if (!analyst_valid_year($financialYear)) $errors[]='Financial year must be YYYY-YY, e.g. 2025-26.';
    if ($sortOrder===false || $sortOrder<0) $errors[]='Sort order must be zero or greater.';
    if (!$errors) {
        $newPdf=null;
        try {
            $newPdf=analyst_upload_pdf($_FILES['pdf_file']??[]);
            $stmt=$conn->prepare("INSERT INTO analyst_meet_intimations (title, pdf_file, financial_year, sort_order, status) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param('sssii',$title,$newPdf,$financialYear,$sortOrder,$status);
            $stmt->execute(); $stmt->close();
            $_SESSION['success']='Analyst meet intimation added successfully.';
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
    <div class="d-flex justify-content-between align-items-center mb-3"><h3>Add Analyst Meet Intimation</h3><a href="index.php" class="btn btn-secondary">Back</a></div>
    <?php if($errors): ?><div class="alert alert-danger"><?php foreach($errors as $error): ?><div><?= e($error); ?></div><?php endforeach; ?></div><?php endif; ?>
    <div class="card"><div class="card-body">
        <form method="post" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?= e(analyst_csrf_token()); ?>">
            <div class="mb-3"><label class="form-label">Title *</label><input type="text" name="title" class="form-control" value="<?= e($title); ?>" required></div>
            <div class="row">
                <div class="col-md-6 mb-3"><label class="form-label">Financial Year *</label><input type="text" name="financial_year" class="form-control" value="<?= e($financialYear); ?>" placeholder="2025-26" pattern="\d{4}-\d{2}" required></div>
                <div class="col-md-6 mb-3"><label class="form-label">Sort Order</label><input type="number" name="sort_order" class="form-control" min="0" value="<?= e($sortOrder); ?>"></div>
            </div>
            <div class="mb-3"><label class="form-label">PDF Document</label><input type="file" name="pdf_file" class="form-control" accept="application/pdf,.pdf"><small class="text-muted">PDF only, maximum 15 MB.</small></div>
            <div class="mb-3"><label class="form-label">Status</label><select name="status" class="form-select"><option value="1" <?= (string)$status==='1'?'selected':''; ?>>Active</option><option value="0" <?= (string)$status==='0'?'selected':''; ?>>Inactive</option></select></div>
            <button type="submit" class="btn btn-primary">Save Intimation</button> <a href="index.php" class="btn btn-light">Cancel</a>
        </form>
    </div></div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
