
<?php
// Save as UTF-8 without BOM. No whitespace before <?php


require_once '../../dbc.php';

$id = (int)($_GET['id'] ?? 0);

if ($id < 1) {
    echo "<script>
        alert('Invalid committee ID.');
        window.location.href = 'index.php';
    </script>";
    exit;
}

// Check whether committee exists
$stmt = $conn->prepare(
    "SELECT id FROM committees WHERE id = ?"
);

if (!$stmt) {
    error_log($conn->error);
    echo "<script>
        alert('Unable to delete committee.');
        window.location.href = 'index.php';
    </script>";
    exit;
}

$stmt->bind_param('i', $id);
$stmt->execute();

$committee = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$committee) {
    echo "<script>
        alert('Committee not found.');
        window.location.href = 'index.php';
    </script>";
    exit;
}

// Delete committee
$stmt = $conn->prepare(
    "DELETE FROM committees WHERE id = ?"
);

if (!$stmt) {
    error_log($conn->error);
    echo "<script>
        alert('Unable to delete committee.');
        window.location.href = 'index.php';
    </script>";
    exit;
}

$stmt->bind_param('i', $id);

if ($stmt->execute() && $stmt->affected_rows > 0) {
    $stmt->close();

    echo "<script>
        alert('Committee deleted successfully!');
        window.location.href = 'index.php?msg=deleted';
    </script>";
    exit;
}

error_log('Committee delete error: ' . $stmt->error);
$stmt->close();

echo "<script>
    alert('Unable to delete committee.');
    window.location.href = 'index.php';
</script>";
exit;
