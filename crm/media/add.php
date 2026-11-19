<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require_once '../auth.php';
require_once '../../dbc.php';

$error = '';

if (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    isset($_POST['add_news'])
) {

    // ==========================================
    // FORM DATA
    // ==========================================

    $title = trim($_POST['title'] ?? '');

    $external_link = trim(
        $_POST['external_link'] ?? ''
    );

    // read_time is INTEGER
    $read_time = (
        isset($_POST['read_time']) &&
        $_POST['read_time'] !== ''
    )
        ? (int) $_POST['read_time']
        : 0;

    $description = $_POST['description'] ?? '';

    // media_date is NOT NULL
    $media_date = !empty($_POST['media_date'])
        ? $_POST['media_date']
        : date('Y-m-d');

    // status is INTEGER
    $status = isset($_POST['status'])
        ? (int) $_POST['status']
        : 1;

    $image_path = '';


    // ==========================================
    // VALIDATION
    // ==========================================

    if ($title === '') {

        $error = "Media title is required.";

    }


    // ==========================================
    // IMAGE UPLOAD
    // ==========================================

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

                $image_path =
                    'uploads/news/' .
                    $file_name;

            } else {

                $error =
                    "Unable to upload image.";

            }
        }
    }


    // ==========================================
    // INSERT INTO MEDIA
    // ==========================================

    if ($error === '') {

        $sql = "
            INSERT INTO media
            (
                title,
                external_link,
                read_time,
                description,
                image,
                media_date,
                status,
                created_at
            )
            VALUES
            (?, ?, ?, ?, ?, ?, ?, NOW())
        ";


        $stmt = $conn->prepare($sql);


        if (!$stmt) {

            $error =
                "Database Prepare Error: " .
                $conn->error;

        } else {

            /*
             * title         = string
             * external_link = string
             * read_time      = integer
             * description   = string
             * image         = string
             * media_date    = string
             * status        = integer
             */

            $stmt->bind_param(
                "ssisssi",
                $title,
                $external_link,
                $read_time,
                $description,
                $image_path,
                $media_date,
                $status
            );


            if ($stmt->execute()) {

                $stmt->close();

                /*
                 * header() use nahi kar rahe because
                 * dbc.php already output kar raha hai.
                 */

                echo '<script>
                    window.location.href = "index.php?msg=added";
                </script>';

                exit;

            } else {

                $error =
                    "Insert Error: " .
                    $stmt->error;

                $stmt->close();

            }
        }
    }
}


include '../includes/header.php';

?>


<div class="d-flex justify-content-between align-items-center mb-4">

    <h1 style="
        font-size:24px;
        font-weight:normal;
        margin:0;
    ">
        Add Media
    </h1>


    <a
        href="index.php"
        class="btn-wp text-decoration-none"
    >
        <i class="fas fa-arrow-left"></i>
        Back to Media
    </a>

</div>


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


        <!-- LEFT SIDE -->

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
                        placeholder="Enter media title"
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
                    ></textarea>

                </div>


            </div>

        </div>


        <!-- RIGHT SIDE -->

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
                        placeholder="7"
                        min="0"
                        value="0"
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
                        value="<?php echo date('Y-m-d'); ?>"
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

                        <option value="1">
                            Active
                        </option>

                        <option value="0">
                            Inactive
                        </option>

                    </select>

                </div>


                <!-- IMAGE -->

                <div class="mb-3">

                    <label class="form-label">
                        Image
                    </label>

                    <input
                        type="file"
                        name="image"
                        class="form-control"
                        accept="image/jpeg,image/png,image/webp"
                    >

                </div>


                <!-- SUBMIT -->

                <div class="d-grid mt-4">

                    <button
                        type="submit"
                        name="add_news"
                        value="1"
                        class="btn-wp py-2 fw-bold"
                    >

                        <i class="fas fa-save"></i>
                        Publish Media

                    </button>

                </div>


            </div>

        </div>

    </div>

</form>


<?php include '../includes/footer.php'; ?>