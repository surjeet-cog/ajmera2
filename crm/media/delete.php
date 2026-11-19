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
// GET MEDIA RECORD
// ==========================================

$stmt = $conn->prepare(
    "SELECT image FROM media WHERE id = ?"
);

if (!$stmt) {
    die("Database Error: " . $conn->error);
}

$stmt->bind_param("i", $id);

$stmt->execute();

$result = $stmt->get_result();

$media = $result->fetch_assoc();

$stmt->close();


// ==========================================
// RECORD FOUND
// ==========================================

if ($media) {

    // ======================================
    // DELETE IMAGE
    // ======================================

    if (!empty($media['image'])) {

        $image_path = '../../' . $media['image'];

        if (
            file_exists($image_path) &&
            is_file($image_path)
        ) {
            unlink($image_path);
        }
    }


    // ======================================
    // DELETE DATABASE RECORD
    // ======================================

    $delete_stmt = $conn->prepare(
        "DELETE FROM media WHERE id = ?"
    );

    if (!$delete_stmt) {
        die("Database Error: " . $conn->error);
    }

    $delete_stmt->bind_param(
        "i",
        $id
    );

    if (!$delete_stmt->execute()) {

        $error = $delete_stmt->error;

        $delete_stmt->close();

        die(
            "Delete Error: " .
            htmlspecialchars(
                $error,
                ENT_QUOTES,
                'UTF-8'
            )
        );
    }

    $delete_stmt->close();
}


// ==========================================
// REDIRECT TO MEDIA PAGE
// ==========================================

echo '<script>
    window.location.href = "index.php?msg=deleted";
</script>';

exit;
?>