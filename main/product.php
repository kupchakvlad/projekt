<?php

/**
 * Stránka detailu produktu.
 * Tento soubor zobrazuje detail jednoho produktu na základě ID z GET parametru.
 * Kontroluje přihlášení uživatele, načítá tmavý režim z cookie, připojuje se k databázi,
 * načítá data produktu včetně informací o prodejci (JOIN s tabulkou users),
 * převádí cestu k obrázkům na veřejnou URL a generuje HTML s galerií obrázků,
 * informacemi o produktu a tlačítkem pro "koupi".
 *
 * @file product.php
 *
 * @see main.php Pro výpis produktů a odkaz na tento detail.
 * @see filter.php Pro filtrování produktů (zdroj odkazů).
 * @see product.css Pro styly detailu produktu.
 * @see products.js Pro JavaScript ovládání galerie a tlačítka "Buy".
 */

session_start();

/**
 * @brief Kontrola přihlášení uživatele.
 * Pokud není session user_id nastavena, přesměruje na přihlašovací/regační formulář.
 */

if (!isset($_SESSION["user_id"])) {
    header("Location: ../registration_form/registration_form.php");
    exit;
}

/**
 * @brief Nastavení třídy pro tmavý režim.
 * Na základě cookie 'mode' přidá třídu 'dark' k <body>.
 *
 * @var string $dark_mode_class CSS třída ('dark' nebo prázdná).
 */

$dark_mode_class = (isset($_COOKIE['mode']) && $_COOKIE['mode'] === 'dark') ? 'dark' : '';

/**
 * @brief Konfigurační proměnné pro připojení k databázi.
 * @var string $host Hostitel databáze (výchozí: localhost).
 * @var string $username Uživatelské jméno pro DB.
 * @var string $password Heslo pro DB (POZOR: Nesdílejte v produkci!).
 * @var string $database Název databáze.
 */

$host = "localhost";
$username = "kupchvla";
$password = "webove aplikace";
$database = "kupchvla";

/**
 * @brief Připojení k databázi MySQL.
 * @var mysqli $connection Objekt připojení.
 */

$conn = mysqli_connect($host, $username, $password, $database);
if (!$conn) die("DB error");

/**
 * @brief Kontrola existence ID produktu v URL.
 * Pokud GET parametr "id" chybí, ukončí skript s chybovou zprávou.
 */

if (!isset($_GET["id"])) die("This product does not exist");

/**
 * @brief ID produktu z GET parametru (přetypováno na integer pro bezpečnost).
 * @var int $id ID produktu z URL.
 */

$id = (int) $_GET['id'];

/**
 * @brief SQL dotaz pro načtení detailu produktu včetně jména prodejce.
 * @brief Používá JOIN mezi tabulkami products a users.
 *
 * @var string $query SQL dotaz.
 */

$query = "
    SELECT p.*, u.name AS user_name
    FROM products p
    JOIN users u ON p.user_id = u.id
    WHERE p.id = $id";

/**
 * @brief Spuštění dotazu a získání dat produktu.
 * @var mysqli_result $result Výsledek dotazu.
 * @var array|null $product Pole s daty produktu nebo null, pokud neexistuje.
 */

$result = mysqli_query($conn, $query);
$product = mysqli_fetch_array($result);
if (!$product) die("This product does not exist");

/**
 * @brief Formátování data přidání produktu.
 * Pokud sloupec created_at existuje a není prázdný, převede na formát "d M Y".
 * Jinak zůstane "Unknown".
 *
 * @var string $added_date Formátované datum přidání produktu.
 */

$added_date = 'Unknown';
if (!empty($product['created_at'])) {
    $added_date = date('d M Y', strtotime($product['created_at']));
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $product["name"]; ?> - Botovo</title>
<link rel="stylesheet" href="product.css">
<script src="products.js" defer></script>
</head>
<body class="<?= $dark_mode_class; ?>">

<a href="main.php" class="back-link">← Back</a>

<div class="product-page">

  <div class="product-top">
   <div class="gallery-container">
    <button class="gallery-btn left" id="scrollLeft">&#10094;</button>

    <div class="product-gallery" id="productGallery">
        <?php

                /**
                 * @brief Zpracování a výpis obrázků produktu.
                 * Cesta k obrázkům je uložena jako čárkou oddělený seznam v sloupci file_path.
                 * Každá cesta se trimuje a převádí na veřejnou URL.
                 */

                $images = explode(',', $product['file_path']);
                foreach ($images as $img) {
                    $img_url = str_replace('/home/kupchvla/www', 'https://zwa.toad.cz/~kupchvla', trim($img));
                    echo '<img class="product-image" src="' . $img_url . '" alt="product">';
                }

        ?>
    </div>

    <button class="gallery-btn right" id="scrollRight">&#10095;</button>
</div>



    <div class="product-info">
      <h1><?= $product['name'] ?></h1>
      <div class="brand"><?= $product['fabric'] ?></div>
      <p>Season: <?= $product['season'] ?></p>
      <p>Size: <?= $product['size'] ?></p>
      <div class="price"><?= $product['price'] ?> CZK</div>

      <button class="buy-btn" id="buyBtn">Buy</button>
      <div class="buy-message" id="buyMessage">
        Request sent ✔ Seller will contact you
      </div>

      <div class="product-meta">
       <p>Added by: <?php echo htmlspecialchars($product['user_name']); ?></p>
        <p>Date: <?php echo $added_date; ?></p>
      </div>
    </div>
  </div>
</div>
</body>
</html>