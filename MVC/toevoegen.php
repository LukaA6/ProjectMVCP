<?php
session_start();
$id = $_POST['id'] ?? null;
if ($id) {
$_SESSION['mandje'][] = $id;
}
header("Location: index.php");
exit;