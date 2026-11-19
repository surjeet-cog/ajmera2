<?php
ob_start();
require_once __DIR__ . '/../auth.php';
require_once __DIR__ . '/../../dbc.php';
require_once __DIR__ . '/form_helpers.php';
if ($_SERVER['REQUEST_METHOD']!=='POST') { header('Location: index.php'); exit; }
analyst_notes_verify_csrf();
$id=filter_input(INPUT_POST,'id',FILTER_VALIDATE_INT);
if (!$id) { $_SESSION['error']='Invalid analyst note.'; header('Location: index.php'); exit; }
$stmt=$conn->prepare("SELECT pdf_file FROM analyst_notes WHERE id=? LIMIT 1");
$stmt->bind_param('i',$id); $stmt->execute(); $row=$stmt->get_result()->fetch_assoc(); $stmt->close();
if (!$row) { $_SESSION['error']='Analyst note not found.'; header('Location: index.php'); exit; }
$stmt=$conn->prepare("DELETE FROM analyst_notes WHERE id=?");
$stmt->bind_param('i',$id); $stmt->execute(); $stmt->close();
analyst_notes_delete_pdf($row['pdf_file']??null);
$_SESSION['success']='Analyst note deleted successfully.';
header('Location: index.php'); exit;
