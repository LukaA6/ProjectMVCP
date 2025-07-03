<?php
session_start();

if (isset($_POST['index'])) {
    $index = (int) $_POST['index'];
    if (isset($_SESSION['mandje'][$index])) {
        unset($_SESSION['mandje'][$index]);
        $_SESSION['mandje'] = array_values($_SESSION['mandje']); // Herindexeer array
    }
}

header('Location: index.php'); // Of waar je webshopbestand ook heet
exit;
