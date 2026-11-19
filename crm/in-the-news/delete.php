<?php
ob_start();
require_once __DIR__ . '/../auth.php';
require_once __DIR__ . '/../../dbc.php';
require_once __DIR__ . '/form_helpers.php';

verify_csrf();
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if ($id) {
    $stmt = $conn->prepare("SELECT thumbnail FROM investor_inthenews WHERE id = ? LIMIT 1");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    $stmt = $conn->prepare("DELETE FROM investor_inthenews WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->close();

    if ($row && !empty($row['thumbnail'])) {
        investor_delete_thumbnail($row['thumbnail']);
    }
}
header('Location: index.php?msg=' . urlencode('Video deleted'));
exit;
