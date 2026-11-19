<?php

require_once '../auth.php';
require_once '../../dbc.php';


// ==========================================
// GET BLOG ID
// ==========================================

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<script>window.location.href='index.php';</script>";
    exit;
}

$id = (int) $_GET['id'];


// ==========================================
// FETCH BLOG
// ==========================================

$stmt = $conn->prepare("SELECT * FROM blogs WHERE id = ?");

if (!$stmt) {
    die("Prepare Error: " . $conn->error);
}

$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<script>window.location.href='index.php';</script>";
    exit;
}

$blog = $result->fetch_assoc();

$stmt->close();


// ==========================================
// UPDATE BLOG
// ==========================================

$error = '';
$success = '';

if (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    isset($_POST['update_blog'])
) {

    // --------------------------------------
    // Form Data
    // --------------------------------------

    $title = trim($_POST['title'] ?? '');

    $slug = trim($_POST['slug'] ?? '');

    $meta_title = trim(
        $_POST['meta_title'] ?? ''
    );

    $category = trim(
        $_POST['category'] ?? ''
    );

    $description = trim(
        $_POST['description'] ?? ''
    );

    $content = $_POST['content'] ?? '';

    $meta_description = trim(
        $_POST['meta_description'] ?? ''
    );

    $schema_markup = $_POST['schema_markup'] ?? '';

    $status = isset($_POST['status'])
        ? (int) $_POST['status']
        : 0;


    // --------------------------------------
    // Validation
    // --------------------------------------

    if ($title === '') {

        $error = "Blog title is required.";

    }


    // --------------------------------------
    // Generate Slug
    // --------------------------------------

    if ($error === '') {

        if ($slug === '') {
            $slug = $title;
        }

        $slug = strtolower($slug);

        $slug = preg_replace(
            '/[^a-z0-9]+/',
            '-',
            $slug
        );

        $slug = trim(
            $slug,
            '-'
        );

    }


    // --------------------------------------
    // Publish Date
    // --------------------------------------

    if (
        $error === '' &&
        !empty($_POST['publish_date'])
    ) {

        $timestamp = strtotime(
            $_POST['publish_date']
        );

        if ($timestamp !== false) {

            $publish_date = date(
                'Y-m-d H:i:s',
                $timestamp
            );

        } else {

            $publish_date =
                $blog['publish_date'];

        }

    } else {

        $publish_date =
            $blog['publish_date'];

    }


    // --------------------------------------
    // Existing Image
    // --------------------------------------

    $image = $blog['image'] ?? '';


    // ======================================
    // IMAGE UPLOAD
    // ======================================

    if (
        $error === '' &&
        isset($_FILES['image']) &&
        !empty($_FILES['image']['name'])
    ) {

        if (
            $_FILES['image']['error'] !==
            UPLOAD_ERR_OK
        ) {

            $error = "Image upload failed.";

        } else {

            $allowed_extensions = [
                'jpg',
                'jpeg',
                'png',
                'webp',
                'gif'
            ];

            $original_name =
                basename(
                    $_FILES['image']['name']
                );

            $extension = strtolower(
                pathinfo(
                    $original_name,
                    PATHINFO_EXTENSION
                )
            );


            if (
                !in_array(
                    $extension,
                    $allowed_extensions,
                    true
                )
            ) {

                $error =
                    "Invalid image format. Allowed: JPG, JPEG, PNG, WEBP, GIF.";

            } else {

                $upload_dir =
                    '../../images/';


                if (
                    !is_dir($upload_dir)
                ) {

                    if (
                        !mkdir(
                            $upload_dir,
                            0755,
                            true
                        )
                    ) {

                        $error =
                            "Unable to create images folder.";

                    }

                }


                if ($error === '') {

                    $clean_name =
                        preg_replace(
                            '/[^A-Za-z0-9._-]/',
                            '-',
                            $original_name
                        );


                    $new_file_name =
                        time() .
                        '_' .
                        $clean_name;


                    $target_file =
                        $upload_dir .
                        $new_file_name;


                    if (
                        move_uploaded_file(
                            $_FILES['image']['tmp_name'],
                            $target_file
                        )
                    ) {

                        // Delete old image
                        if (
                            !empty($image)
                        ) {

                            $old_image =
                                '../../' .
                                ltrim(
                                    $image,
                                    '/'
                                );


                            if (
                                file_exists(
                                    $old_image
                                ) &&
                                is_file(
                                    $old_image
                                )
                            ) {

                                @unlink(
                                    $old_image
                                );

                            }

                        }


                        // Save path in DB
                        $image =
                            'images/' .
                            $new_file_name;

                    } else {

                        $error =
                            "Unable to upload new image.";

                    }

                }

            }

        }

    }


    // ======================================
    // UPDATE DATABASE
    // ======================================

    if ($error === '') {

        $update_sql = "
            UPDATE blogs SET

                title = ?,

                meta_title = ?,

                slug = ?,

                category = ?,

                description = ?,

                meta_description = ?,

                schema_markup = ?,

                content = ?,

                image = ?,

                publish_date = ?,

                status = ?

            WHERE id = ?
        ";


        $update_stmt =
            $conn->prepare(
                $update_sql
            );


        if (!$update_stmt) {

            $error =
                "Database Prepare Error: " .
                $conn->error;

        } else {

            $update_stmt->bind_param(
                "ssssssssssii",
                $title,
                $meta_title,
                $slug,
                $category,
                $description,
                $meta_description,
                $schema_markup,
                $content,
                $image,
                $publish_date,
                $status,
                $id
            );


            if (
                $update_stmt->execute()
            ) {

                $update_stmt->close();


                // No header redirect.
                // This avoids "headers already sent" problems.
                echo "
                <script>
                    // alert('Blog updated successfully!');
                    window.location.href = 'index.php?msg=updated';
                </script>
                ";

                exit;

            } else {

                $error =
                    "Database Update Error: " .
                    $update_stmt->error;

                $update_stmt->close();

            }

        }

    }

}


// ==========================================
// HEADER
// ==========================================

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
        Edit Blog
    </h1>


    <a
        href="index.php"
        class="btn-wp text-decoration-none"
    >
        <i class="fas fa-arrow-left"></i>
        Back to Blogs
    </a>

</div>


<!-- ==========================================
     ERROR
========================================== -->

<?php if ($error !== ''): ?>

    <div class="alert alert-danger">

        <strong>Error:</strong>

        <?php
        echo htmlspecialchars(
            $error,
            ENT_QUOTES,
            'UTF-8'
        );
        ?>

    </div>

<?php endif; ?>


<!-- ==========================================
     FORM
========================================== -->

<form
    action="<?php echo htmlspecialchars(
        $_SERVER['REQUEST_URI'],
        ENT_QUOTES,
        'UTF-8'
    ); ?>"
    method="POST"
    enctype="multipart/form-data"
>


<div class="row">


    <!-- ======================================
         LEFT COLUMN
    ======================================= -->

    <div class="col-md-9">


        <!-- MAIN BLOG -->

        <div class="wp-card">


            <!-- TITLE -->

            <div class="mb-3">

                <label class="form-label fw-bold">
                    Post Heading (H1)
                </label>

                <input
                    type="text"
                    name="title"
                    class="form-control"
                    value="<?php
                    echo htmlspecialchars(
                        $blog['title'] ?? '',
                        ENT_QUOTES,
                        'UTF-8'
                    );
                    ?>"
                    required
                >

            </div>


            <!-- SLUG -->

            <div class="mb-3">

                <label class="form-label fw-bold text-muted">
                    Slug (Permalink)
                </label>


                <div class="input-group">

                    <span class="input-group-text">
                        /blog/
                    </span>

                    <input
                        type="text"
                        name="slug"
                        id="post_slug"
                        class="form-control"
                        value="<?php
                        echo htmlspecialchars(
                            $blog['slug'] ?? '',
                            ENT_QUOTES,
                            'UTF-8'
                        );
                        ?>"
                    >

                </div>

            </div>


            <!-- CATEGORY -->

            <div class="mb-3">

                <label class="form-label fw-bold">
                    Category
                </label>

                <input
                    type="text"
                    name="category"
                    class="form-control"
                    value="<?php
                    echo htmlspecialchars(
                        $blog['category'] ?? '',
                        ENT_QUOTES,
                        'UTF-8'
                    );
                    ?>"
                >

            </div>


            <!-- DESCRIPTION -->

            <div class="mb-3">

                <label class="form-label fw-bold">
                    Short Description
                </label>

                <textarea
                    name="description"
                    class="form-control"
                    rows="4"
                ><?php
                echo htmlspecialchars(
                    $blog['description'] ?? '',
                    ENT_QUOTES,
                    'UTF-8'
                );
                ?></textarea>

            </div>


            <!-- CONTENT -->

            <div class="mb-3 mt-4">

                <label class="form-label fw-bold">
                    Content
                </label>

                <textarea
                    name="content"
                    class="tinymce-editor form-control"
                ><?php
                echo htmlspecialchars(
                    $blog['content'] ?? '',
                    ENT_QUOTES,
                    'UTF-8'
                );
                ?></textarea>

            </div>


        </div>



        <!-- ==================================
             SEO
        =================================== -->

        <div class="wp-card">

            <div class="wp-card-title">
                SEO & Meta Options
            </div>


            <!-- META TITLE -->

            <div class="mb-3">

                <label class="form-label fw-bold">
                    Meta Title
                </label>

                <input
                    type="text"
                    name="meta_title"
                    class="form-control"
                    value="<?php
                    echo htmlspecialchars(
                        $blog['meta_title'] ?? '',
                        ENT_QUOTES,
                        'UTF-8'
                    );
                    ?>"
                >

            </div>


            <!-- META DESCRIPTION -->

            <div class="mb-3">

                <label class="form-label fw-bold">
                    Meta Description
                </label>

                <textarea
                    name="meta_description"
                    class="form-control"
                    rows="4"
                ><?php
                echo htmlspecialchars(
                    $blog['meta_description'] ?? '',
                    ENT_QUOTES,
                    'UTF-8'
                );
                ?></textarea>

            </div>


            <!-- SCHEMA -->

            <div class="mb-3">

                <label class="form-label fw-bold">
                    Schema Markup (JSON-LD)
                </label>

                <textarea
                    name="schema_markup"
                    class="form-control"
                    rows="8"
                    style="font-family:monospace;"
                ><?php
                echo htmlspecialchars(
                    $blog['schema_markup'] ?? '',
                    ENT_QUOTES,
                    'UTF-8'
                );
                ?></textarea>

            </div>


        </div>


    </div>



    <!-- ======================================
         RIGHT COLUMN
    ======================================= -->

    <div class="col-md-3">


        <!-- ==================================
             PUBLISH
        =================================== -->

        <div class="wp-card">

            <div class="wp-card-title">
                Publish
            </div>


            <!-- STATUS -->

            <div class="mb-3">

                <label class="form-label text-muted">
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
                            (int)$blog['status'] === 1
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
                            (int)$blog['status'] === 0
                        )
                            ? 'selected'
                            : '';
                        ?>
                    >
                        Inactive
                    </option>

                </select>

            </div>


            <!-- PUBLISH DATE -->

            <div class="mb-3">

                <label class="form-label text-muted">
                    Publish Date
                </label>

                <input
                    type="datetime-local"
                    name="publish_date"
                    class="form-control"
                    value="<?php

                    if (
                        !empty(
                            $blog['publish_date']
                        )
                    ) {

                        echo date(
                            'Y-m-d\TH:i',
                            strtotime(
                                $blog['publish_date']
                            )
                        );

                    }

                    ?>"
                >

            </div>


            <!-- UPDATE BUTTON -->

            <div class="d-grid mt-4">

                <button
                    type="submit"
                    name="update_blog"
                    value="1"
                    class="btn-wp py-2 fw-bold w-100"
                >

                    <i class="fas fa-save"></i>

                    Update Blog

                </button>

            </div>


        </div>



        <!-- ==================================
             FEATURED IMAGE
        =================================== -->

        <div class="wp-card">

            <div class="wp-card-title">
                Featured Image
            </div>


            <?php if (
                !empty($blog['image'])
            ): ?>

                <div class="mb-3">

                    <img
                        src="../../<?php
                        echo htmlspecialchars(
                            $blog['image'],
                            ENT_QUOTES,
                            'UTF-8'
                        );
                        ?>"
                        alt="Current Image"
                        class="img-fluid rounded"
                        style="
                            width:100%;
                            max-height:180px;
                            object-fit:cover;
                        "
                    >

                    <small class="text-muted d-block mt-2">
                        Current Image
                    </small>

                </div>

            <?php endif; ?>


            <input
                type="file"
                name="image"
                class="form-control"
                accept=".jpg,.jpeg,.png,.webp,.gif,image/*"
            >


            <small class="text-muted d-block mt-2">
                Leave empty to keep the current image.
            </small>

        </div>


    </div>

</div>


</form>


<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {

        const slugInput =
            document.getElementById(
                'post_slug'
            );


        if (slugInput) {

            slugInput.addEventListener(
                'blur',
                function () {

                    this.value =
                        this.value
                            .toLowerCase()
                            .replace(
                                /\s+/g,
                                '-'
                            )
                            .replace(
                                /[^a-z0-9-]/g,
                                ''
                            )
                            .replace(
                                /-+/g,
                                '-'
                            )
                            .replace(
                                /^-+|-+$/g,
                                ''
                            );

                }
            );

        }

    }
);

</script>


<?php

include '../includes/footer.php';

?>