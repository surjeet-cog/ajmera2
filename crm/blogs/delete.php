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
        window.location.href = 'index.php';
    </script>
    ";

    exit;
}

$id = (int) $_GET['id'];


// ==========================================
// GET BLOG
// ==========================================

$stmt = $conn->prepare(
    "SELECT image FROM blogs WHERE id = ?"
);

if (!$stmt) {
    die("Database Error: " . $conn->error);
}

$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();


// ==========================================
// BLOG EXISTS
// ==========================================

if ($result->num_rows > 0) {

    $blog = $result->fetch_assoc();

    $image = $blog['image'] ?? '';

    $stmt->close();


    // ======================================
    // DELETE IMAGE
    // ======================================

    if (!empty($image)) {

        $image_path =
            '../../' . ltrim($image, '/');

        if (
            file_exists($image_path) &&
            is_file($image_path)
        ) {

            @unlink($image_path);

        }

    }


    // ======================================
    // DELETE DATABASE RECORD
    // ======================================

    $delete_stmt = $conn->prepare(
        "DELETE FROM blogs WHERE id = ?"
    );

    if (!$delete_stmt) {
        die(
            "Delete Query Error: " .
            $conn->error
        );
    }

    $delete_stmt->bind_param(
        "i",
        $id
    );

    if (!$delete_stmt->execute()) {

        die(
            "Unable to delete blog: " .
            $delete_stmt->error
        );

    }

    $delete_stmt->close();


    // ======================================
    // SUCCESS
    // ======================================

    echo "
    <script>
        // alert('Blog deleted successfully!');
        window.location.href = 'index.php';
    </script>
    ";

    exit;

}


// ==========================================
// BLOG NOT FOUND
// ==========================================

$stmt->close();

echo "
<script>
    // alert('Blog not found.');
    window.location.href = 'index.php';
</script>
";

exit;

?>