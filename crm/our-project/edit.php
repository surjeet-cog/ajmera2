<?php
require_once __DIR__ . '/../auth.php';
require_once __DIR__ . '/../../dbc.php';
require_once __DIR__ . '/form_helpers.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    header('Location: index.php?error=invalid');
    exit;
}

$stmt = $conn->prepare("SELECT * FROM projects WHERE id = ? LIMIT 1");
$stmt->bind_param('i', $id);
$stmt->execute();

$data = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$data) {
    header('Location: index.php?error=notfound');
    exit;
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

   if (!project_verify_csrf()) {
    $errors[] = 'Invalid form token. Please refresh the page and try again.';
}

    foreach ([
        'project_name',
        'slug',
        'city',
        'location',
        'project_status',
        'project_type',
        'bhk_details',
        'project_url',
        'sort_order',
        'status'
    ] as $key) {
        $data[$key] = trim(
            (string)($_POST[$key] ?? $data[$key] ?? '')
        );
    }

    if ($data['slug'] === '') {
        $data['slug'] = project_slugify($data['project_name']);
    }

    if ($data['project_name'] === '') {
        $errors[] = 'Project name is required.';
    }

    if (!preg_match('/^[a-z0-9-]+$/', $data['slug'])) {
        $errors[] = 'Invalid slug format.';
    }

    if (!in_array($data['project_status'], ['ongoing', 'completed'], true)) {
        $errors[] = 'Invalid project status.';
    }

    if (!in_array($data['project_type'], ['residential', 'commercial'], true)) {
        $errors[] = 'Invalid project type.';
    }

    if (!in_array((string)$data['status'], ['0', '1'], true)) {
        $errors[] = 'Invalid visibility status.';
    }

    if (!$errors) {
        try {
            $data['project_image'] = project_upload_image(
                'project_image',
                $data['project_image'] ?? ''
            );

            $sortOrder = (int)$data['sort_order'];
            $active = (int)$data['status'];

            $stmt = $conn->prepare(
                "UPDATE projects SET
                    project_name = ?,
                    slug = ?,
                    city = ?,
                    location = ?,
                    project_status = ?,
                    project_type = ?,
                    bhk_details = ?,
                    project_image = ?,
                    project_url = ?,
                    sort_order = ?,
                    status = ?,
                    updated_at = NOW()
                 WHERE id = ?"
            );

            if (!$stmt) {
                throw new RuntimeException('Unable to prepare update query.');
            }

            $stmt->bind_param(
                'sssssssssiii',
                $data['project_name'],
                $data['slug'],
                $data['city'],
                $data['location'],
                $data['project_status'],
                $data['project_type'],
                $data['bhk_details'],
                $data['project_image'],
                $data['project_url'],
                $sortOrder,
                $active,
                $id
            );

            if ($stmt->execute()) {
                $stmt->close();
                header('Location: index.php?success=updated');
                exit;
            }

            if ($stmt->errno === 1062) {
                $errors[] = 'This slug already exists.';
            } else {
                $errors[] = 'Unable to update project.';
            }

            $stmt->close();

        } catch (Throwable $ex) {
            $errors[] = $ex->getMessage();
        }
    }
}
?>

<?php include __DIR__ . '/../includes/header.php'; ?>

<div class="container-fluid py-4">

    <h2 class="mb-3">Edit Project</h2>

    <?php foreach ($errors as $error): ?>
        <div class="alert alert-danger"><?= e($error) ?></div>
    <?php endforeach; ?>

    <form method="post" enctype="multipart/form-data">

        <input
    type="hidden"
    name="csrf_token"
    value="<?= e(project_csrf_token()) ?>"
>

        <?php include __DIR__ . '/project_fields.php'; ?>

        <button type="submit" class="btn btn-primary">
            Update Project
        </button>

        <a href="index.php" class="btn btn-secondary">
            Cancel
        </a>

    </form>

</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>