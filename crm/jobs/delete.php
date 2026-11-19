<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require_once '../auth.php';
require_once '../../dbc.php';


// ==========================================
// CHECK JOB ID
// ==========================================

if (
    !isset($_GET['id']) ||
    !is_numeric($_GET['id'])
) {

    echo "
    <script>
        window.location.href = 'index.php';
    </script>
    ";

    exit;
}

$id = (int) $_GET['id'];


// ==========================================
// DELETE JOB
// ==========================================

$stmt = $conn->prepare(
    "DELETE FROM career_jobs WHERE id = ?"
);

if (!$stmt) {

    die(
        "Database Prepare Error: " .
        $conn->error
    );

}

$stmt->bind_param(
    "i",
    $id
);


if ($stmt->execute()) {

    $stmt->close();

    echo "
    <script>
        // alert('Job deleted successfully!');
        window.location.href = 'index.php?msg=deleted';
    </script>
    ";

    exit;

} else {

    $error =
        "Unable to delete job: " .
        $stmt->error;

    $stmt->close();

    die($error);

}

?>