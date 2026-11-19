<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require_once '../auth.php';
require_once '../../dbc.php';


// ==========================================
// CHECK ID
// ==========================================

if (
    !isset($_GET['id']) ||
    !is_numeric($_GET['id'])
) {
    echo '<script>
        window.location.href = "index.php";
    </script>';
    exit;
}

$id = (int) $_GET['id'];


// ==========================================
// FETCH MEDIA
// ==========================================

$stmt = $conn->prepare(
    "SELECT
        id,
        title,
        external_link,
        read_time,
        description,
        image,
        media_date,
        status
     FROM media
     WHERE id = ?"
);

if (!$stmt) {
    die("Database Error: " . $conn->error);
}

$stmt->bind_param("i", $id);

$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows === 0) {

    $stmt->close();

    echo '<script>
        alert("Media not found.");
        window.location.href = "index.php";
    </script>';

    exit;
}

$media = $result->fetch_assoc();

$stmt->close();


// ==========================================
// UPDATE MEDIA
// ==========================================

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $title = trim(
        $_POST['title'] ?? ''
    );

    $external_link = trim(
        $_POST['external_link'] ?? ''
    );

    $read_time = (
        isset($_POST['read_time']) &&
        $_POST['read_time'] !== ''
    )
        ? (int) $_POST['read_time']
        : 0;

    $description = $_POST['description'] ?? '';

    $media_date = !empty($_POST['media_date'])
        ? $_POST['media_date']
        : date('Y-m-d');

    $status = isset($_POST['status'])
        ? (int) $_POST['status']
        : 1;

    $image_path = $media['image'] ?? '';


    // ======================================
    // VALIDATION
    // ======================================

    if ($title === '') {

        $error = "Media title is required.";

    }


    // ======================================
    // NEW IMAGE UPLOAD
    // ======================================

    if (
        $error === '' &&
        isset($_FILES['image']) &&
        $_FILES['image']['error'] === UPLOAD_ERR_OK
    ) {

        $upload_dir = '../../uploads/news/';

        if (!is_dir($upload_dir)) {

            mkdir(
                $upload_dir,
                0777,
                true
            );
        }


        $extension = strtolower(
            pathinfo(
                $_FILES['image']['name'],
                PATHINFO_EXTENSION
            )
        );


        $allowed = [
            'jpg',
            'jpeg',
            'png',
            'webp'
        ];


        if (!in_array(
            $extension,
            $allowed,
            true
        )) {

            $error =
                "Only JPG, JPEG, PNG and WEBP images are allowed.";

        } else {

            $original_name = pathinfo(
                $_FILES['image']['name'],
                PATHINFO_FILENAME
            );


            $safe_name = preg_replace(
                '/[^A-Za-z0-9_-]/',
                '-',
                $original_name
            );


            $file_name =
                time() .
                '_' .
                $safe_name .
                '.' .
                $extension;


            $target =
                $upload_dir .
                $file_name;


            if (
                move_uploaded_file(
                    $_FILES['image']['tmp_name'],
                    $target
                )
            ) {

                // Delete old image
                if (!empty($media['image'])) {

                    $old_image =
                        '../../' .
                        $media['image'];

                    if (
                        file_exists($old_image) &&
                        is_file($old_image)
                    ) {

                        unlink($old_image);
                    }
                }


                $image_path =
                    'uploads/news/' .
                    $file_name;

            } else {

                $error =
                    "Unable to upload new image.";

            }
        }
    }


    // ======================================
    // UPDATE DATABASE
    // ======================================

    if ($error === '') {

        $update_sql = "
            UPDATE media
            SET
                title = ?,
                external_link = ?,
                read_time = ?,
                description = ?,
                image = ?,
                media_date = ?,
                status = ?
            WHERE id = ?
        ";


        $update_stmt = $conn->prepare(
            $update_sql
        );


        if (!$update_stmt) {

            $error =
                "Database Prepare Error: " .
                $conn->error;

        } else {

            $update_stmt->bind_param(
                "ssisssii",
                $title,
                $external_link,
                $read_time,
                $description,
                $image_path,
                $media_date,
                $status,
                $id
            );


            if ($update_stmt->execute()) {

                $update_stmt->close();

                echo '<script>
                    window.location.href =
                        "index.php?msg=updated";
                </script>';

                exit;

            } else {

                $error =
                    "Update Error: " .
                    $update_stmt->error;

                $update_stmt->close();
            }
        }
    }


    // Keep submitted values in form
    $media['title'] = $title;
    $media['external_link'] = $external_link;
    $media['read_time'] = $read_time;
    $media['description'] = $description;
    $media['media_date'] = $media_date;
    $media['status'] = $status;
    $media['image'] = $image_path;
}


include '../includes/header.php';

?>


<!-- ==========================================
     PAGE HEADER
========================================== -->

<div class="d-flex justify-content-between align-items-center mb-4">

    <h1 style="
        font-size:24px;
        font-weight:normal;
        margin:0;
    ">
        Edit Media
    </h1>


    <a
        href="index.php"
        class="btn-wp text-decoration-none"
    >
        <i class="fas fa-arrow-left"></i>
        Back to Media
    </a>

</div>


<!-- ==========================================
     ERROR
========================================== -->

<?php if ($error !== ''): ?>

    <div class="alert alert-danger">

        <?php
        echo htmlspecialchars(
            $error,
            ENT_QUOTES,
            'UTF-8'
        );
        ?>

    </div>

<?php endif; ?>


<form
    method="POST"
    enctype="multipart/form-data"
>

    <div class="row">


        <!-- =====================================
             LEFT SIDE
        ====================================== -->

        <div class="col-md-8">

            <div class="wp-card">


                <!-- TITLE -->

                <div class="mb-3">

                    <label class="form-label fw-bold">
                        Title
                    </label>

                    <input
                        type="text"
                        name="title"
                        class="form-control"
                        value="<?php echo htmlspecialchars(
                            $media['title'] ?? '',
                            ENT_QUOTES,
                            'UTF-8'
                        ); ?>"
                        required
                    >

                </div>


                <!-- EXTERNAL LINK -->

                <div class="mb-3">

                    <label class="form-label fw-bold">
                        External Link
                    </label>

                    <input
                        type="url"
                        name="external_link"
                        class="form-control"
                        value="<?php echo htmlspecialchars(
                            $media['external_link'] ?? '',
                            ENT_QUOTES,
                            'UTF-8'
                        ); ?>"
                        placeholder="https://example.com/news"
                    >

                </div>


                <!-- DESCRIPTION -->

                <div class="mb-3">

                    <label class="form-label fw-bold">
                        Description
                    </label>

                    <textarea
                        name="description"
                        class="tinymce-editor form-control"
                        rows="12"
                    ><?php echo htmlspecialchars(
                        $media['description'] ?? '',
                        ENT_QUOTES,
                        'UTF-8'
                    ); ?></textarea>

                </div>


            </div>

        </div>


        <!-- =====================================
             RIGHT SIDE
        ====================================== -->

        <div class="col-md-4">

            <div class="wp-card">

                <div class="wp-card-title">
                    Media Details
                </div>


                <!-- READ TIME -->

                <div class="mb-3">

                    <label class="form-label">
                        Read Time
                    </label>

                    <input
                        type="number"
                        name="read_time"
                        class="form-control"
                        min="0"
                        value="<?php echo (int)(
                            $media['read_time'] ?? 0
                        ); ?>"
                    >

                    <small class="text-muted">
                        Enter reading time in minutes.
                    </small>

                </div>


                <!-- MEDIA DATE -->

                <div class="mb-3">

                    <label class="form-label">
                        Media Date
                    </label>

                    <input
                        type="date"
                        name="media_date"
                        class="form-control"
                        value="<?php echo htmlspecialchars(
                            $media['media_date'] ?? date('Y-m-d'),
                            ENT_QUOTES,
                            'UTF-8'
                        ); ?>"
                        required
                    >

                </div>


                <!-- STATUS -->

                <div class="mb-3">

                    <label class="form-label">
                        Status
                    </label>

                    <select
                        name="status"
                        class="form-select"
                    >

                        <option
                            value="1"
                            <?php
                            echo (
                                (int)$media['status'] === 1
                            )
                                ? 'selected'
                                : '';
                            ?>
                        >
                            Active
                        </option>

                        <option
                            value="0"
                            <?php
                            echo (
                                (int)$media['status'] === 0
                            )
                                ? 'selected'
                                : '';
                            ?>
                        >
                            Inactive
                        </option>

                    </select>

                </div>


                <!-- CURRENT IMAGE -->

                <?php if (!empty($media['image'])): ?>

                    <div class="mb-3">

                        <label class="form-label">
                            Current Image
                        </label>

                        <div>

                            <img
                                src="../../<?php echo htmlspecialchars(
                                    $media['image'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                ); ?>"
                                alt="Current Media Image"
                                style="
                                    width:100%;
                                    max-width:220px;
                                    height:130px;
                                    object-fit:cover;
                                    border-radius:5px;
                                    border:1px solid #ddd;
                                "
                            >

                        </div>

                    </div>

                <?php endif; ?>


                <!-- NEW IMAGE -->

                <div class="mb-3">

                    <label class="form-label">
                        Change Image
                    </label>

                    <input
                        type="file"
                        name="image"
                        class="form-control"
                        accept="image/jpeg,image/png,image/webp"
                    >

                    <small class="text-muted">
                        Leave blank to keep the current image.
                    </small>

                </div>


                <!-- UPDATE -->

                <div class="d-grid mt-4">

                    <button
                        type="submit"
                        class="btn-wp py-2 fw-bold"
                    >

                        <i class="fas fa-save"></i>
                        Update Media

                    </button>

                </div>


            </div>

        </div>

    </div>

</form>


<?php include '../includes/footer.php'; ?>