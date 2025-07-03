<?php
session_start();

if (!isset($_SESSION['gebruiker'])) {
    header("Location: index.php");
    exit;
}

$gebruiker = $_SESSION['gebruiker'];
?>

<!DOCTYPE html>
<html lang="nl">
<head>
<meta charset="UTF-8">
<title>Welkom <?= htmlspecialchars($gebruiker['naam']) ?></title>
<style>
    body {
        font-family: sans-serif;
        margin: 0;
        padding: 0;
        background-size: cover;
        position: relative;
        min-height: 100vh;
    }

    .container {
        position: relative;
        z-index: 1;
        max-width: 600px;
        margin: 100px auto;
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(6px);
        padding: 30px;
        border-radius: 16px;
        box-shadow: 0 0 20px rgba(0,0,0,0.3);
    }

    h1 {
        margin-top: 0;
    }



    input, textarea, button {
        width: 100%;
        padding: 10px;
        border-radius: 5px;
        border: 1px solid #ccc;
        font: inherit;
    }

    textarea {
        resize: vertical;
    }

    button {
        background: #007bff;
        color: white;
        border: none;
        cursor: pointer;
        margin-top: 20px;
        border-radius: 8px;
        font-weight: bold;
        transition: background 0.2s ease;
    }

    button:hover {
        background: #0056b3;
    }

    .error {
        color: #c00;
    }

    .success {
        color: #080;
    }

    img.thumb {
        display: block;
        margin-top: 5px;
        width: 80px;
        border-radius: 50%;
    }
</style>
</head>
<body>
<div class="overlay"></div>
<div class="container">
    <h1>Welkom, <?= htmlspecialchars($gebruiker['naam']) ?>!</h1>
    <p>Je bent succesvol ingelogd.</p>

    <form action="uitloggen.php" method="post">
        <button type="submit">Uitloggen</button>
    </form>
</div>
</body>
</html>
