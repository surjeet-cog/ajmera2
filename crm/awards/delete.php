<?php

ob_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);


require_once '../auth.php';
require_once '../../dbc.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if (!$id || $id < 1) {
    header('Location: index.php');
    exit;
}

// Fetch existing image
$findAward = $conn->prepare("
    SELECT image
    FROM awards
    WHERE id = ?
    LIMIT 1
");

$findAward->bind_param('i', $id);
$findAward->execute();

$awardResult = $findAward->get_result();
$award = $awardResult->fetch_assoc();
$findAward->close();

if ($award) {

    $deleteAward = $conn->prepare("
        DELETE FROM awards
        WHERE id = ?
    ");

    $deleteAward->bind_param('i', $id);

    if ($deleteAward->execute()) {

        $deleteAward->close();

        if (!empty($award['image'])) {

            $uploadDir = realpath(dirname(__DIR__, 2) . '/uploads/awards');
            $imageFile = dirname(__DIR__, 2) . '/'
                . ltrim($award['image'], '/');

            $realImage = realpath($imageFile);

            // Delete only images inside uploads/awards
            if (
                $uploadDir !== false &&
                $realImage !== false &&
                is_file($realImage) &&
                strpos($realImage, $uploadDir . DIRECTORY_SEPARATOR) === 0
            ) {
                unlink($realImage);
            }
        }

        header('Location: index.php?msg=deleted');
        exit;

    } else {
        $deleteAward->close();
        header('Location: index.php');
        exit;
    }
}

header('Location: index.php');
exit;