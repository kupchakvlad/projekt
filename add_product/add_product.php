<?php
/**
 * Stránka pro přidání nového produktu.
 * Tento soubor je přístupný pouze přihlášeným uživatelům.
 * Zobrazuje formulář pro nahrání fotek a zadání údajů o produktu (název, výrobce, sezóna, velikost, cena).
 * Podporuje předvyplnění polí a zobrazení chybových hlášek z session po neúspěšném odeslání (z add_product_back.php).
 * Tmavý režim je načítán z cookie. Formulář odesílá data (včetně souborů) na add_product_back.php.
 *
 * @file add_product.php
 *
 * @see add_product_back.php Backend pro zpracování a uložení produktu.
 * @see add_product.css Styly formuláře.
 * @see add_product.js JavaScript pro slider velikosti, dark mode a klientskou validaci.
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
 * @brief Načtení předvyplněných hodnot z session.
 * Používá se po neúspěšném odeslání formuláře pro zachování zadaných dat.
 *
 * @var string $product_name_value Předvyplněný název produktu.
 * @var string $product_fabric_value Předvyplněný výrobce/materiál.
 * @var string $product_season_value Předvyplněná sezóna.
 * @var string $product_size_value Předvyplněná velikost (výchozí '36').
 * @var string $product_price_value Předvyplněná cena.
 */
$product_name_value = $_SESSION['add_product_data']['product_name'] ?? '';
$product_fabric_value = $_SESSION['add_product_data']['product_fabric'] ?? '';
$product_season_value = $_SESSION['add_product_data']['product_season'] ?? '';
$product_size_value = $_SESSION['add_product_data']['product_size'] ?? '36';
$product_price_value = $_SESSION['add_product_data']['product_price'] ?? '';

/**
 * @brief Načtení chybových hlášek z session.
 * Chyby jsou uloženy backendem při validaci (foto, cena, název).
 *
 * @var array $errors Pole chyb (klíče: 'photo', 'price', 'name').
 * @var string $photo_error Chybová zpráva pro nahrávání fotek.
 * @var string $price_error Chybová zpráva pro cenu.
 * @var string $name_error Chybová zpráva pro název produktu.
 */
$errors = $_SESSION['add_product_errors'] ?? [];
unset($_SESSION['add_product_data'], $_SESSION['add_product_errors']);
$photo_error = $errors['photo'] ?? '';
$price_error = $errors['price'] ?? '';
$name_error = $errors['name'] ?? '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="add_product.css">
    <script src="add_product.js" defer></script>
    <title>Add Product - Botovo</title>
</head>
<body class="<?php echo $dark_mode_class ?>">

    <button class="back-to-main"> ← </button>
    <button type="button" id="dark-mode-btn" class="dark-mode-btn">Dark Mode</button>

    <form action="add_product_back.php" method="POST" enctype="multipart/form-data" id="add-product-form">

        <label for="Photo">Enter your product photo: <span class="required">*</span></label>
        <input type="file" name="photo[]" id="Photo" accept="image/*" multiple>
            <?php if ($photo_error): ?><div class="error"><?php echo $photo_error; ?></div><?php endif; ?>

        <label for="ProductName">Enter your product name: <span class="required">*</span></label>
        <input type="text" name="product_name" id="ProductName" value="<?php echo htmlspecialchars($product_name_value); ?>">
            <?php if ($name_error): ?><div class="error"><?php echo $name_error; ?></div><?php endif; ?>

        <label for="ProductManufacturer">Enter your product manufacturer: <span class="required">*</span></label>
        <input type="text" name="product_fabric" id="ProductManufacturer" value="<?php echo htmlspecialchars($product_fabric_value); ?>">

        <label for="Season">Enter the season your shoes are made for. <span class="required">*</span></label>
        <select name="season" id="Season">
            <option value="<?php echo $product_season_value; ?>" selected><?php echo $product_season_value ?: 'Select season'; ?></option>
            <option value="winter">Winter</option>
            <option value="spring">Spring</option>
            <option value="summer">Summer</option>
            <option value="autumn">Autumn</option>
        </select>

        <label for="slider"> Enter shoes size: <span class="required">*</span></label>
        <input type="range" name="product_size" id="slider" min="28" max="48" step="1" value="<?php echo $product_size_value; ?>">

        <span id="size-value"><?php echo $product_size_value; ?></span>

        <label for="ProductPrice">Enter price: <span class="required">*</span></label>
        <input type="text" name="product_price" id="ProductPrice" value="<?php echo htmlspecialchars($product_price_value); ?>">
        <?php if ($price_error): ?><div class="error"><?php echo $price_error; ?></div><?php endif; ?>

        <input type="submit" class="submit-button" name="submit" value="Add product">
    </form>

</body>
</html>