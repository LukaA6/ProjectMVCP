<?php
session_start();
require 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $naam = $_POST['naam'] ?? '';
    $email = $_POST['email'] ?? '';
    $wachtwoord = $_POST['wachtwoord'] ?? '';

    // Check of email al bestaat
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM gebruikers WHERE email = ?");
    $stmt->execute([$email]);
    $aantal = $stmt->fetchColumn();

    if ($aantal > 0) {
        $_SESSION['melding'] = "Dit e-mailadres is al geregistreerd.";
        header("Location: index.php");
        exit;
    }

    $hash = password_hash($wachtwoord, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO gebruikers (naam, email, wachtwoord) VALUES (?, ?, ?)");
    $stmt->execute([$naam, $email, $hash]);

    $_SESSION['melding'] = "Registratie succesvol! Je kunt nu inloggen.";
    header("Location: index.php");
    exit;
}
