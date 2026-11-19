<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require_once '../auth.php';
require_once '../../dbc.php';

include '../includes/header.php';


// ==========================================
// FETCH ALL JOBS
// ==========================================

$query = "
    SELECT
        id,
        job_title,
        position,
        location,
        experience,
        status,
        created_at
    FROM career_jobs
    ORDER BY id DESC
";

$result = $conn->query($query);

if (!$result) {
    die("Database Query Error: " . $conn->error);
}

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
        Jobs & Careers

        <a
            href="add.php"
            class="btn-wp ms-2 text-decoration-none"
        >
            <i class="fas fa-plus"></i>
            Add New
        </a>
    </h1>

</div>


<!-- ==========================================
     SUCCESS MESSAGES
========================================== -->

<?php if (
    isset($_GET['msg']) &&
    $_GET['msg'] === 'added'
): ?>

    <div class="alert alert-success">
        Job added successfully!
    </div>

<?php endif; ?>


<?php if (
    isset($_GET['msg']) &&
    $_GET['msg'] === 'updated'
): ?>

    <div class="alert alert-success">
        Job updated successfully!
    </div>

<?php endif; ?>


<?php if (
    isset($_GET['msg']) &&
    $_GET['msg'] === 'deleted'
): ?>

    <div class="alert alert-success">
        Job deleted successfully!
    </div>

<?php endif; ?>


<!-- ==========================================
     JOB TABLE
========================================== -->

<div class="wp-card">

    <div class="table-responsive">

        <table class="table table-striped table-hover align-middle">

            <thead class="table-dark">

                <tr>

                    <th width="5%">
                        ID
                    </th>

                    <th width="25%">
                        Job Title
                    </th>

                    <th width="15%">
                        Position
                    </th>

                    <th width="15%">
                        Location
                    </th>

                    <th width="15%">
                        Experience
                    </th>

                    <th width="10%">
                        Status
                    </th>

                    <th width="15%">
                        Actions
                    </th>

                </tr>

            </thead>


            <tbody>

            <?php if ($result->num_rows > 0): ?>

                <?php while (
                    $row = $result->fetch_assoc()
                ): ?>

                    <tr>

                        <!-- ID -->

                        <td>
                            <?php
                            echo (int)$row['id'];
                            ?>
                        </td>


                        <!-- JOB TITLE -->

                        <td>

                            <strong class="text-primary">

                                <?php
                                echo htmlspecialchars(
                                    $row['job_title'] ?? '',
                                    ENT_QUOTES,
                                    'UTF-8'
                                );
                                ?>

                            </strong>

                        </td>


                        <!-- POSITION -->

                        <td>

                            <?php
                            echo htmlspecialchars(
                                $row['position'] ?? '',
                                ENT_QUOTES,
                                'UTF-8'
                            );
                            ?>

                        </td>


                        <!-- LOCATION -->

                        <td>

                            <?php if (
                                !empty($row['location'])
                            ): ?>

                                <i class="fas fa-map-marker-alt text-danger"></i>

                                <?php
                                echo htmlspecialchars(
                                    $row['location'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                );
                                ?>

                            <?php else: ?>

                                -

                            <?php endif; ?>

                        </td>


                        <!-- EXPERIENCE -->

                        <td>

                            <?php if (
                                !empty($row['experience'])
                            ): ?>

                                <i class="fas fa-briefcase text-secondary"></i>

                                <?php
                                echo htmlspecialchars(
                                    $row['experience'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                );
                                ?>

                            <?php else: ?>

                                -

                            <?php endif; ?>

                        </td>


                        <!-- STATUS -->

                        <td>

                            <?php if (
                                (int)$row['status'] === 1
                            ): ?>

                                <span class="badge bg-success">
                                    Active
                                </span>

                            <?php else: ?>

                                <span class="badge bg-secondary">
                                    Inactive
                                </span>

                            <?php endif; ?>

                        </td>


                        <!-- ACTIONS -->

                        <td>

                            <a
                                href="edit.php?id=<?php echo (int)$row['id']; ?>"
                                class="btn-wp text-decoration-none mb-1 d-inline-block"
                            >

                                <i class="fas fa-edit"></i>
                                Edit

                            </a>


                            <a
                                href="delete.php?id=<?php echo (int)$row['id']; ?>"
                                class="btn-wp-danger text-decoration-none d-inline-block"
                                onclick="return confirm('Are you sure you want to delete this job posting?');"
                            >

                                <i class="fas fa-trash"></i>
                                Trash

                            </a>

                        </td>

                    </tr>

                <?php endwhile; ?>


            <?php else: ?>

                <tr>

                    <td
                        colspan="7"
                        class="text-center py-4"
                    >

                        <div class="text-muted mb-2">

                            <i
                                class="fas fa-briefcase"
                                style="font-size:30px;"
                            ></i>

                        </div>

                        No jobs found.

                        <br>

                        <a
                            href="add.php"
                            class="btn-wp text-decoration-none mt-2 d-inline-block"
                        >

                            <i class="fas fa-plus"></i>
                            Add New Job

                        </a>

                    </td>

                </tr>

            <?php endif; ?>

            </tbody>

        </table>

    </div>

</div>


<?php include '../includes/footer.php'; ?>