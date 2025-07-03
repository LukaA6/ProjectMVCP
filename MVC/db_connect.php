<?php
// Database connectie (pas aan naar jouw instellingen)
$host = 'localhost';
$db   = 'lm';          // jouw database naam
$user = 'root';        // meestal 'root' in XAMPP
$pass = '';            // meestal leeg in XAMPP

$dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";

try {
    $pdo = new PDO($dsn, $user, $pass);
    // Foutmodus zetten op Exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connectie mislukt: " . $e->getMessage());
}
