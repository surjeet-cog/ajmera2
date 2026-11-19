<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require_once '../auth.php';
require_once '../../dbc.php';



// ==========================================
// ADD NEW BLOG
// ==========================================

$error = '';

if (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    isset($_POST['publish_blog'])
) {

    // --------------------------------------
    // Get Form Data
    // --------------------------------------

    $title = trim(
        $_POST['title'] ?? ''
    );

    $slug = trim(
        $_POST['slug'] ?? ''
    );

    $category = trim(
        $_POST['category'] ?? ''
    );

    $description = trim(
        $_POST['description'] ?? ''
    );

    $meta_title = trim(
        $_POST['meta_title'] ?? ''
    );

    $meta_description = trim(
        $_POST['meta_description'] ?? ''
    );

    $schema_markup = $_POST['schema_markup'] ?? '';

    $content = $_POST['content'] ?? '';

    $status = isset($_POST['status'])
        ? (int) $_POST['status']
        : 1;


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
                date('Y-m-d H:i:s');

        }

    } else {

        $publish_date =
            date('Y-m-d H:i:s');

    }


    // --------------------------------------
    // Image Upload
    // --------------------------------------

    $image = '';


    if (
        $error === '' &&
        isset($_FILES['image']) &&
        !empty($_FILES['image']['name'])
    ) {

        if (
            $_FILES['image']['error'] !==
            UPLOAD_ERR_OK
        ) {

            $error =
                "Image upload failed.";

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

                // Website root /images/
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


                    $image_name =
                        time() .
                        '_' .
                        $clean_name;


                    $target_file =
                        $upload_dir .
                        $image_name;


                    if (
                        move_uploaded_file(
                            $_FILES['image']['tmp_name'],
                            $target_file
                        )
                    ) {

                        // Save relative path in DB
                        $image =
                            'images/' .
                            $image_name;

                    } else {

                        $error =
                            "Unable to upload image.";

                    }

                }

            }

        }

    }


    // ======================================
    // INSERT BLOG
    // ======================================

    if ($error === '') {

        $insert_sql = "
            INSERT INTO blogs
            (
                title,
                meta_title,
                slug,
                category,
                description,
                meta_description,
                schema_markup,
                content,
                image,
                publish_date,
                status
            )
            VALUES
            (
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?
            )
        ";


        $stmt =
            $conn->prepare(
                $insert_sql
            );


        if (!$stmt) {

            $error =
                "Database Prepare Error: " .
                $conn->error;

        } else {

            $stmt->bind_param(
                "ssssssssssi",
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
                $status
            );


            if ($stmt->execute()) {

                $stmt->close();


                echo "
                <script>
                    // alert('Blog published successfully!');
                    window.location.href = 'index.php?msg=added';
                </script>
                ";

                exit;

            } else {

                $error =
                    "Database Insert Error: " .
                    $stmt->error;

                $stmt->close();

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
        Add New Blog
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
                    id="post_title"
                    class="form-control"
                    placeholder="Visible on the page"
                    required
                >

            </div>


            <!-- SLUG -->

            <div class="mb-3">

                <label
                    class="form-label fw-bold text-muted"
                >
                    Slug (Permalink)
                </label>


                <div class="input-group">

                    <span class="input-group-text bg-light text-muted">
                        /blog/
                    </span>

                    <input
                        type="text"
                        name="slug"
                        id="post_slug"
                        class="form-control"
                        placeholder="auto-generated-slug"
                        required
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
                    placeholder="e.g. Latest, Real Estate, News"
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
                    placeholder="Short description of the blog"
                ></textarea>

            </div>


            <!-- CONTENT -->

            <div class="mb-3 mt-4">

                <label class="form-label fw-bold">
                    Content
                </label>

                <textarea
                    name="content"
                    class="tinymce-editor form-control"
                ></textarea>

            </div>


        </div>



        <!-- ==================================
             SEO SECTION
        =================================== -->

        <div class="wp-card">

            <div class="wp-card-title">
                SEO & Meta Options
            </div>


            <!-- META TITLE -->

            <div class="mb-3">

                <label class="form-label fw-bold">
                    Meta Title (SEO Title)
                </label>

                <input
                    type="text"
                    name="meta_title"
                    class="form-control"
                    placeholder="Title for Search Engines"
                >

            </div>


            <!-- META DESCRIPTION -->

            <div class="mb-3">

                <label class="form-label">
                    Meta Description
                </label>

                <textarea
                    name="meta_description"
                    class="form-control"
                    rows="4"
                    placeholder="Meta description for Google"
                ></textarea>

            </div>


            <!-- SCHEMA -->

            <div class="mb-3">

                <label class="form-label">
                    Schema Markup (JSON-LD)
                </label>

                <textarea
                    name="schema_markup"
                    class="form-control"
                    rows="8"
                    style="font-family:monospace;"
                    placeholder='{"@context":"https://schema.org"}'
                ></textarea>

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

                    <option value="1">
                        Active
                    </option>

                    <option value="0">
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
                        echo date(
                            'Y-m-d\TH:i'
                        );
                    ?>"
                >

            </div>


            <!-- PUBLISH BUTTON -->

            <div class="d-grid mt-4">

                <button
                    type="submit"
                    name="publish_blog"
                    value="1"
                    class="btn-wp py-2 fw-bold w-100"
                >

                    <i class="fas fa-save"></i>

                    Publish Blog

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


            <input
                type="file"
                name="image"
                class="form-control"
                accept=".jpg,.jpeg,.png,.webp,.gif,image/*"
            >


            <small class="text-muted d-block mt-2">
                JPG, JPEG, PNG, WEBP or GIF
            </small>

        </div>


    </div>

</div>


</form>


<!-- ==========================================
     JAVASCRIPT
========================================== -->

<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {

        const titleInput =
            document.getElementById(
                'post_title'
            );

        const slugInput =
            document.getElementById(
                'post_slug'
            );


        function generateSlug(text) {

            return text
                .toString()
                .toLowerCase()
                .trim()
                .replace(
                    /[^a-z0-9]+/g,
                    '-'
                )
                .replace(
                    /^-+|-+$/g,
                    ''
                );

        }


        // Auto slug from title
        if (
            titleInput &&
            slugInput
        ) {

            titleInput.addEventListener(
                'input',
                function () {

                    slugInput.value =
                        generateSlug(
                            this.value
                        );

                }
            );


            // Clean manually edited slug
            slugInput.addEventListener(
                'blur',
                function () {

                    this.value =
                        generateSlug(
                            this.value
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