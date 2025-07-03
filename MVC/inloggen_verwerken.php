<?php
session_start();
require 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $wachtwoord = $_POST['wachtwoord'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM gebruikers WHERE email = ?");
    $stmt->execute([$email]);
    $gebruiker = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($gebruiker && password_verify($wachtwoord, $gebruiker['wachtwoord'])) {
        $_SESSION['gebruiker'] = [
            'id' => $gebruiker['id'],
            'naam' => $gebruiker['naam'],
            'email' => $gebruiker['email']
        ];
        header("Location: ingelogd.php");
        exit;
    } else {
        $_SESSION['melding'] = "Ongeldige inloggegevens.";
        header("Location: index.php");
        exit;
    }
}
