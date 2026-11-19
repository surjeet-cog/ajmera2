<?php
ob_start();
require_once __DIR__ . '/../auth.php';
require_once __DIR__ . '/../../dbc.php';
require_once __DIR__ . '/form_helpers.php';
if ($_SERVER['REQUEST_METHOD']!=='POST') { header('Location: index.php'); exit; }
newspaper_verify_csrf();
$id=filter_input(INPUT_POST,'id',FILTER_VALIDATE_INT);
if (!$id) { $_SESSION['error']='Invalid publication.'; header('Location: index.php'); exit; }
$stmt=$conn->prepare("SELECT pdf_file FROM newspaper_publications WHERE id=? LIMIT 1");
$stmt->bind_param('i',$id); $stmt->execute(); $row=$stmt->get_result()->fetch_assoc(); $stmt->close();
if (!$row) { $_SESSION['error']='Publication not found.'; header('Location: index.php'); exit; }
$stmt=$conn->prepare("DELETE FROM newspaper_publications WHERE id=?");
$stmt->bind_param('i',$id); $stmt->execute(); $stmt->close();
newspaper_delete_pdf($row['pdf_file']??null);
$_SESSION['success']='Newspaper publication deleted successfully.';
header('Location: index.php'); exit;
