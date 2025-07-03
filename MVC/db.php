<?php
$host = 'localhost';
$dbname = 'lm';      // jouw database naam
$user = 'root';      // jouw database gebruiker
$pass = '';          // jouw wachtwoord, leeg als er geen is

try {
    $db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Database-connectie mislukt: " . $e->getMessage();
    exit;
}
