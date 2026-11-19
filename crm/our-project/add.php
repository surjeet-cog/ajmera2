<?php
ob_start();

error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../dbc.php';
require_once __DIR__ . '/form_helpers.php';


if (!isset($conn) || !($conn instanceof mysqli)) {
    die('Database connection failed. Check dbc.php.');
}

$errors = [];

$project = [
    'project_name'   => '',
    'slug'           => '',
    'city'           => '',
    'location'       => '',
    'project_status' => 'ongoing',
    'project_type'   => 'residential',
    'bhk_details'    => '',
    'project_image'  => '',
    'project_url'    => '',
    'sort_order'     => 0,
    'status'         => 1,
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!project_verify_csrf()) {
        $errors[] = 'Invalid form token. Please refresh the page and try again.';
    } else {

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
        ] as $field) {
            $project[$field] = trim((string) ($_POST[$field] ?? ''));
        }

        // Normalize ENUM values to lowercase.
        $project['project_type'] = strtolower($project['project_type']);
        $project['project_status'] = strtolower($project['project_status']);

        // Project name
        if ($project['project_name'] === '') {
            $errors[] = 'Project name is required.';
        }

        // Slug
        if ($project['slug'] === '') {
            $project['slug'] = project_slugify($project['project_name']);
        } else {
            $project['slug'] = project_slugify($project['slug']);
        }

        if ($project['slug'] === '') {
            $errors[] = 'Please enter a valid project name or slug.';
        }

        // Project type: database ENUM('residential','commercial')
        if (!in_array(
            $project['project_type'],
            ['residential', 'commercial'],
            true
        )) {
            $errors[] = 'Invalid project type.';
        }

        // Project status: database ENUM('ongoing','completed')
        if (!in_array(
            $project['project_status'],
            ['ongoing', 'completed'],
            true
        )) {
            $errors[] = 'Invalid project status.';
        }

        // Visibility status
        if (!in_array($project['status'], ['0', '1'], true)) {
            $errors[] = 'Invalid visibility status.';
        }

        // Sort order
        if (
            $project['sort_order'] === '' ||
            !ctype_digit($project['sort_order'])
        ) {
            $errors[] = 'Sort order must be a non-negative whole number.';
        }

        // Project URL
        try {
            $project['project_url'] = validate_project_url(
                $project['project_url']
            );
        } catch (RuntimeException $exception) {
            $errors[] = $exception->getMessage();
        }

        // Check duplicate slug
        if (empty($errors)) {
            $check = $conn->prepare(
                "SELECT id FROM projects WHERE slug = ? LIMIT 1"
            );

            if (!$check) {
                $errors[] = 'Could not validate the project slug.';
            } else {
                $check->bind_param('s', $project['slug']);
                $check->execute();
                $check->store_result();

                if ($check->num_rows > 0) {
                    $errors[] = 'This slug already exists. Please use another slug.';
                }

                $check->close();
            }
        }

        // Upload project image
        $uploadedImage = '';

        if (empty($errors)) {
            try {
                $uploadedImage = upload_project_image(
                    $_FILES['project_image'] ?? [],
                    ''
                );
            } catch (RuntimeException $exception) {
                $errors[] = $exception->getMessage();
            }
        }

        // Insert project
        if (empty($errors)) {

            $project['project_image'] = $uploadedImage;
            $sortOrder = (int) $project['sort_order'];
            $visibility = (int) $project['status'];

            $stmt = $conn->prepare(
                "INSERT INTO projects (
                    project_name,
                    slug,
                    city,
                    location,
                    project_status,
                    project_type,
                    bhk_details,
                    project_image,
                    project_url,
                    sort_order,
                    status
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
            );

            if (!$stmt) {
                $errors[] = 'Could not prepare project insert query.';
            } else {

                $stmt->bind_param(
                    'sssssssssii',
                    $project['project_name'],
                    $project['slug'],
                    $project['city'],
                    $project['location'],
                    $project['project_status'],
                    $project['project_type'],
                    $project['bhk_details'],
                    $project['project_image'],
                    $project['project_url'],
                    $sortOrder,
                    $visibility
                );

                try {
                    if ($stmt->execute()) {
                        $stmt->close();

                        header('Location: index.php?success=added');
                        exit;
                    }

                    $errors[] = 'Could not add project.';
                    $stmt->close();

                } catch (mysqli_sql_exception $exception) {
                    $errors[] = 'Database error: ' . $exception->getMessage();
                    $stmt->close();
                }

                // Delete the uploaded file if the insert failed.
                if (
                    !empty($errors) &&
                    $uploadedImage !== ''
                ) {
                    delete_project_image_file($uploadedImage);
                    $project['project_image'] = '';
                }
            }
        }
    }
}

include __DIR__ . '/../includes/header.php';
?>

<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Add Project</h2>
        <a href="index.php" class="btn btn-secondary">
            Back to Projects
        </a>
    </div>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach ($errors as $error): ?>
                    <li><?= e($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">

            <form method="POST" enctype="multipart/form-data">

               <input
    type="hidden"
    name="csrf_token"
    value="<?= e(project_csrf_token()) ?>"
>

                <?php include __DIR__ . '/project_fields.php'; ?>

                <button type="submit" class="btn btn-primary">
                    Save Project
                </button>

                <a href="index.php" class="btn btn-outline-secondary">
                    Cancel
                </a>

            </form>

        </div>
    </div>

</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
