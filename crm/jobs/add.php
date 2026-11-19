<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require_once '../auth.php';
require_once '../../dbc.php';

$error = '';


// ==========================================
// ADD NEW JOB
// ==========================================

if (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    isset($_POST['publish_job'])
) {

    // --------------------------------------
    // Form Data
    // --------------------------------------

    $job_title = trim(
        $_POST['job_title'] ?? ''
    );

    $job_description = $_POST['job_description'] ?? '';

    $responsibilities = $_POST['responsibilities'] ?? '';

    $qualifications = $_POST['qualifications'] ?? '';

    $position = trim(
        $_POST['position'] ?? ''
    );

    $location = trim(
        $_POST['location'] ?? ''
    );

    $experience = trim(
        $_POST['experience'] ?? ''
    );

    $status = isset($_POST['status'])
        ? (int) $_POST['status']
        : 1;


    // --------------------------------------
    // Validation
    // --------------------------------------

    if ($job_title === '') {

        $error = "Job Title is required.";

    }


    // --------------------------------------
    // INSERT JOB
    // --------------------------------------

    if ($error === '') {

        $insert_sql = "
            INSERT INTO career_jobs
            (
                job_title,
                job_description,
                responsibilities,
                qualifications,
                position,
                location,
                experience,
                status,
                created_at,
                updated_at
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
                NOW(),
                NOW()
            )
        ";


        $stmt = $conn->prepare(
            $insert_sql
        );


        if (!$stmt) {

            $error =
                "Database Prepare Error: " .
                $conn->error;

        } else {

            $stmt->bind_param(
                "sssssssi",
                $job_title,
                $job_description,
                $responsibilities,
                $qualifications,
                $position,
                $location,
                $experience,
                $status
            );


            if ($stmt->execute()) {

                $stmt->close();


                echo "
                <script>
                    // alert('Job added successfully!');
                    window.location.href='index.php?msg=added';
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
        Post a New Job
    </h1>


    <a
        href="index.php"
        class="btn-wp text-decoration-none"
    >
        <i class="fas fa-arrow-left"></i>
        Back to Jobs
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
>


<div class="row">


    <!-- ======================================
         LEFT SIDE
    ======================================= -->

    <div class="col-md-8">

        <div class="wp-card">


            <!-- JOB TITLE -->

            <div class="mb-3">

                <label class="form-label fw-bold">

                    Job Title

                    <span class="text-danger">
                        *
                    </span>

                </label>


                <input
                    type="text"
                    name="job_title"
                    class="form-control"
                    placeholder="e.g. IR Head"
                    required
                >

            </div>


            <!-- JOB DESCRIPTION -->

            <div class="mb-4">

                <label class="form-label fw-bold">

                    Job Description

                </label>


                <textarea
                    name="job_description"
                    class="tinymce-editor form-control"
                ></textarea>

            </div>


            <!-- RESPONSIBILITIES -->

            <div class="mb-4">

                <label class="form-label fw-bold">

                    Responsibilities

                </label>


                <textarea
                    name="responsibilities"
                    class="tinymce-editor form-control"
                ></textarea>

            </div>


            <!-- QUALIFICATIONS -->

            <div class="mb-3">

                <label class="form-label fw-bold">

                    Qualifications

                </label>


                <textarea
                    name="qualifications"
                    class="tinymce-editor form-control"
                ></textarea>

            </div>


        </div>

    </div>



    <!-- ======================================
         RIGHT SIDE
    ======================================= -->

    <div class="col-md-4">

        <div class="wp-card">


            <div class="wp-card-title">
                Job Details
            </div>


            <!-- POSITION -->

            <div class="mb-3">

                <label class="form-label text-muted">
                    Position
                </label>


                <input
                    type="text"
                    name="position"
                    class="form-control"
                    placeholder="e.g. Manager"
                >

            </div>


            <!-- LOCATION -->

            <div class="mb-3">

                <label class="form-label text-muted">
                    Location
                </label>


                <input
                    type="text"
                    name="location"
                    class="form-control"
                    placeholder="e.g. Mumbai"
                >

            </div>


            <!-- EXPERIENCE -->

            <div class="mb-3">

                <label class="form-label text-muted">
                    Experience Required
                </label>


                <input
                    type="text"
                    name="experience"
                    class="form-control"
                    placeholder="e.g. 12+ years"
                >

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


            <!-- PUBLISH -->

            <div class="d-grid mt-4">

                <button
                    type="submit"
                    name="publish_job"
                    value="1"
                    class="btn-wp py-2 fw-bold w-100"
                >

                    <i class="fas fa-save"></i>

                    Publish Job

                </button>

            </div>


        </div>

    </div>

</div>


</form>


<?php include '../includes/footer.php'; ?>