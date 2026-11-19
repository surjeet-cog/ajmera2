<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require_once '../auth.php';
require_once '../../dbc.php';


// ==========================================
// CHECK ID
// ==========================================

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {

    echo "
    <script>
        window.location.href='index.php';
    </script>
    ";

    exit;
}

$id = (int) $_GET['id'];


// ==========================================
// FETCH EXISTING JOB
// ==========================================

$stmt = $conn->prepare(
    "SELECT * FROM career_jobs WHERE id = ?"
);

if (!$stmt) {
    die("Database Error: " . $conn->error);
}

$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows === 0) {

    echo "
    <script>
        window.location.href='index.php';
    </script>
    ";

    exit;
}

$job = $result->fetch_assoc();

$stmt->close();


// ==========================================
// UPDATE JOB
// ==========================================

$error = '';

if (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    isset($_POST['update_job'])
) {

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
        : 0;


    // ======================================
    // VALIDATION
    // ======================================

    if ($job_title === '') {

        $error = "Job Title is required.";

    }


    // ======================================
    // UPDATE DATABASE
    // ======================================

    if ($error === '') {

        $update_sql = "
            UPDATE career_jobs SET

                job_title = ?,

                job_description = ?,

                responsibilities = ?,

                qualifications = ?,

                position = ?,

                location = ?,

                experience = ?,

                status = ?,

                updated_at = NOW()

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
                "sssssssii",
                $job_title,
                $job_description,
                $responsibilities,
                $qualifications,
                $position,
                $location,
                $experience,
                $status,
                $id
            );


            if (
                $update_stmt->execute()
            ) {

                $update_stmt->close();


                echo "
                <script>
                    // alert('Job updated successfully!');
                    window.location.href='index.php?msg=updated';
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
        Edit Job
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
         LEFT COLUMN
    ======================================= -->

    <div class="col-md-8">


        <div class="wp-card">


            <!-- JOB TITLE -->

            <div class="mb-3">

                <label class="form-label fw-bold">
                    Job Title
                    <span class="text-danger">*</span>
                </label>

                <input
                    type="text"
                    name="job_title"
                    class="form-control"
                    value="<?php
                    echo htmlspecialchars(
                        $job['job_title'] ?? '',
                        ENT_QUOTES,
                        'UTF-8'
                    );
                    ?>"
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
                ><?php
                echo htmlspecialchars(
                    $job['job_description'] ?? '',
                    ENT_QUOTES,
                    'UTF-8'
                );
                ?></textarea>

            </div>


            <!-- RESPONSIBILITIES -->

            <div class="mb-4">

                <label class="form-label fw-bold">
                    Responsibilities
                </label>

                <textarea
                    name="responsibilities"
                    class="tinymce-editor form-control"
                ><?php
                echo htmlspecialchars(
                    $job['responsibilities'] ?? '',
                    ENT_QUOTES,
                    'UTF-8'
                );
                ?></textarea>

            </div>


            <!-- QUALIFICATIONS -->

            <div class="mb-3">

                <label class="form-label fw-bold">
                    Qualifications
                </label>

                <textarea
                    name="qualifications"
                    class="tinymce-editor form-control"
                ><?php
                echo htmlspecialchars(
                    $job['qualifications'] ?? '',
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
                    value="<?php
                    echo htmlspecialchars(
                        $job['position'] ?? '',
                        ENT_QUOTES,
                        'UTF-8'
                    );
                    ?>"
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
                    value="<?php
                    echo htmlspecialchars(
                        $job['location'] ?? '',
                        ENT_QUOTES,
                        'UTF-8'
                    );
                    ?>"
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
                    value="<?php
                    echo htmlspecialchars(
                        $job['experience'] ?? '',
                        ENT_QUOTES,
                        'UTF-8'
                    );
                    ?>"
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

                    <option
                        value="1"
                        <?php
                        echo (
                            (int)$job['status'] === 1
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
                            (int)$job['status'] === 0
                        )
                            ? 'selected'
                            : '';
                        ?>
                    >
                        Inactive
                    </option>

                </select>

            </div>


            <!-- UPDATE BUTTON -->

            <div class="d-grid mt-4">

                <button
                    type="submit"
                    name="update_job"
                    value="1"
                    class="btn-wp py-2 fw-bold w-100"
                >

                    <i class="fas fa-save"></i>

                    Update Job

                </button>

            </div>


        </div>


    </div>

</div>


</form>


<?php include '../includes/footer.php'; ?>