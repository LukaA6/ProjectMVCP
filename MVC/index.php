<?php
session_start();

// Toon melding als die bestaat
if (isset($_SESSION['melding'])) {
    $melding = $_SESSION['melding'];
    unset($_SESSION['melding']);
} else {
    $melding = null;
}

// Producten
$producten = [
    1 => ["naam" => "Offwhite Green", "prijs" => 399.99, "afbeelding" => "images/product1.jpg"],
    2 => ["naam" => "Offwhite Red", "prijs" => 329.99, "afbeelding" => "images/product2.jpg"],
    3 => ["naam" => "Offwhite Black", "prijs" => 489.99, "afbeelding" => "images/product3.jpg"],
];

// Winkelmandje logica
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['toevoegen'])) {
        $id = (int)$_POST['id'];
        if (isset($producten[$id])) {
            $_SESSION['mandje'][] = $id;
        }
    }

    if (isset($_POST['verwijderen'])) {
        $index = (int)$_POST['index'];
        if (isset($_SESSION['mandje'][$index])) {
            unset($_SESSION['mandje'][$index]);
            $_SESSION['mandje'] = array_values($_SESSION['mandje']);
        }
    }
}

$mandje = $_SESSION['mandje'] ?? [];
$totaal = array_reduce($mandje, fn($carry, $id) => $carry + $producten[$id]['prijs'], 0);
?>

<!DOCTYPE html>
<html lang="nl">
<head>
<meta charset="UTF-8">
<title>Webshop</title>
<style>
/* Je CSS hier (zoals in jouw vorige index.php) */
body { font-family: sans-serif; background: #f9f9f9; margin: 0; }
header { background: #007bff; color: #fff; padding: 20px 0; }
.container { width: 90%; max-width: 1100px; margin: auto; }
.header-content { display: flex; justify-content: space-between; align-items: center; }
.auth-btn { padding: 8px 16px; border: none; border-radius: 5px; font-weight: bold; cursor: pointer; color: #fff; }
.login { border: 2px solid white; background: transparent; }
.register { background: #28a745; }
.cart-icon { font-size: 24px; color: white; background: none; border: none; position: relative; cursor: pointer; }
#cart-count { position: absolute; top: -6px; right: -6px; background: #dc3545; border-radius: 50%; padding: 2px 6px; font-size: 13px; font-weight: bold; }
.product-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 30px; margin: 40px 0; }
.product-card { background: white; border-radius: 12px; box-shadow: 0 4px 14px rgba(0,0,0,0.1); text-align: center; overflow: hidden; }
.product-card img { width: 100%; height: 200px; object-fit: cover; }
.info { padding: 20px; }
.price { color: #28a745; font-weight: bold; font-size: 18px; }
.cart-overlay { display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0, 0, 0, 0.5); justify-content: center; align-items: center; z-index: 1000; }
.cart-overlay.active { display: flex; }
.cart-popup { background: white; padding: 30px; width: 350px; border-radius: 12px; box-shadow: 0 0 20px rgba(0,0,0,0.2); }
.cart-popup ul { list-style: none; padding: 0; }
.cart-popup li { display: flex; justify-content: space-between; margin: 10px 0; }
.cart-popup button { background: none; border: none; color: red; font-size: 18px; cursor: pointer; }
.popup-overlay { display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.45); justify-content: center; align-items: center; z-index: 9999; }
.popup-overlay.active { display: flex; }
.popup-content { background: white; padding: 30px 40px; width: 360px; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.15); text-align: center; position: relative; }
.close-btn { position: absolute; top: 15px; right: 20px; background: none; border: none; font-size: 28px; cursor: pointer; color: #888; }
form { display: flex; flex-direction: column; gap: 15px; margin-top: 20px; }
input[type="text"], input[type="email"], input[type="password"] { padding: 10px; font-size: 16px; border-radius: 8px; border: 1px solid #ccc; }
.submit-btn { background: #007bff; color: white; padding: 12px; font-size: 16px; border: none; border-radius: 8px; cursor: pointer; font-weight: bold; }
.melding {
    text-align: center;
    padding: 10px;
    margin-bottom: 15px;
    background-color: #ffdddd;
    border: 1px solid #dd4444;
    color: #a00;
    border-radius: 5px;
}
</style>
</head>
<body>

<?php if ($melding): ?>
    <div class="melding"><?= htmlspecialchars($melding) ?></div>
<?php endif; ?>

<header>
    <div class="container header-content">
        <h1>🛒 KlikKlaar</h1>
        <div>
            <button class="auth-btn login" id="openLoginBtn">Inloggen</button>
            <button class="auth-btn register" id="openRegisterBtn">Registreren</button>
            <button class="cart-icon" onclick="toggleCart()">
                🛒 <span id="cart-count"><?= count($mandje) ?></span>
            </button>
        </div>
    </div>
</header>

<!-- Winkelmandje -->
<div id="cartOverlay" class="cart-overlay" onclick="toggleCart()">
    <div class="cart-popup" onclick="event.stopPropagation()">
        <h2>Winkelmandje (<?= count($mandje) ?>)</h2>
        <?php if (!$mandje): ?>
            <p>Je mandje is leeg.</p>
        <?php else: ?>
            <ul>
                <?php foreach ($mandje as $index => $id): ?>
                    <?php $p = $producten[$id]; ?>
                    <li>
                        <?= htmlspecialchars($p['naam']) ?>
                        <form method="post">
                            <input type="hidden" name="index" value="<?= $index ?>">
                            <button type="submit" name="verwijderen">&times;</button>
                        </form>
                    </li>
                <?php endforeach; ?>
            </ul>
            <p><strong>Totaal:</strong> €<?= number_format($totaal, 2, ',', '.') ?></p>
        <?php endif; ?>
    </div>
</div>

<!-- Registreren pop-up -->
<div id="registerPopup" class="popup-overlay">
    <div class="popup-content">
        <button class="close-btn" id="closeRegisterBtn">&times;</button>
        <h2>Registreren</h2>
        <form action="registreren_verwerken.php" method="post">
            <input type="text" name="naam" placeholder="Naam" required>
            <input type="email" name="email" placeholder="E-mail" required>
            <input type="password" name="wachtwoord" placeholder="Wachtwoord" required>
            <button type="submit" class="submit-btn">Registreren</button>
        </form>
    </div>
</div>

<!-- Inloggen pop-up -->
<div id="loginPopup" class="popup-overlay">
    <div class="popup-content">
        <button class="close-btn" id="closeLoginBtn">&times;</button>
        <h2>Inloggen</h2>
        <form action="inloggen_verwerken.php" method="post">
            <input type="email" name="email" placeholder="E-mail" required>
            <input type="password" name="wachtwoord" placeholder="Wachtwoord" required>
            <button type="submit" class="submit-btn">Inloggen</button>
        </form>
    </div>
</div>

<main class="container">
    <div class="product-grid">
        <?php foreach ($producten as $id => $p): ?>
            <div class="product-card">
                <img src="<?= htmlspecialchars($p['afbeelding']) ?>" alt="<?= htmlspecialchars($p['naam']) ?>">
                <div class="info">
                    <h2><?= htmlspecialchars($p['naam']) ?></h2>
                    <p class="price">€<?= number_format($p['prijs'], 2, ',', '.') ?></p>
                    <form method="post">
                        <input type="hidden" name="id" value="<?= $id ?>">
                        <button type="submit" name="toevoegen">Toevoegen</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</main>

<script>
function toggleCart() {
    document.getElementById('cartOverlay').classList.toggle('active');
}

document.getElementById('openRegisterBtn').addEventListener('click', () => {
    document.getElementById('registerPopup').classList.add('active');
});
document.getElementById('closeRegisterBtn').addEventListener('click', () => {
    document.getElementById('registerPopup').classList.remove('active');
});
document.getElementById('registerPopup').addEventListener('click', e => {
    if (e.target === e.currentTarget) e.currentTarget.classList.remove('active');
});

document.getElementById('openLoginBtn').addEventListener('click', () => {
    document.getElementById('loginPopup').classList.add('active');
});
document.getElementById('closeLoginBtn').addEventListener('click', () => {
    document.getElementById('loginPopup').classList.remove('active');
});
document.getElementById('loginPopup').addEventListener('click', e => {
    if (e.target === e.currentTarget) e.currentTarget.classList.remove('active');
});
</script>

</body>
</html>
