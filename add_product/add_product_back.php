<?php

/**
 * Backend skript pro zpracování přidání nového produktu.
 * Tento soubor zpracovává POST data a nahrané soubory z formuláře add_product.php.
 * Provádí validaci vstupů (fotky, název, cena), nahrává obrázky na server,
 * ukládá jejich cesty do databáze a vkládá záznam o produktu do tabulky `products`.
 * Při chybách ukládá errory a data do session a přesměruje zpět na formulář.
 * Při úspěchu přesměruje na hlavní stránku (main.php).
 *
 * @file add_product_back.php
 *
 * @see add_product.php Frontend formulář pro přidání produktu.
 * @see main.php Cílová stránka po úspěšném přidání.
 */

session_start();

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

$connection = mysqli_connect($host, $username, $password, $database);

if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

/**
 * @brief Hlavní logika: Zpracování POST požadavku z formuláře.
 * @brief Validuje vstupy (název, cena, fotky atd.), nahrává soubory a vkládá do tabulky 'products'.
 * @brief Pokud jsou errory, ukládá je do session a přesměruje zpět.
 *
 * @return void Přesměruje na main.php při úspěchu, jinak zpět na add_product.php.
 */

if (isset($_POST["submit"])) {

    /**
     * @brief Cesta k adresáři pro nahrávání fotek produktu.
     * @var string $upload_directory Absolutní cesta na serveru.
     */

    $upload_directory = "/home/kupchvla/www/projekt/photo/";
    $user_id = $_SESSION["user_id"];

    /**
     * @brief Načtení a očištění vstupních dat z formuláře.
     *
     * @var string $product_name Název produktu.
     * @var string $product_fabric Výrobce / materiál.
     * @var string $product_season Sezóna.
     * @var int $product_size Velikost (přetypováno na integer).
     * @var string $product_price Cena (zatím string, později float).
     */

    $product_name = trim($_POST["product_name"]);
    $product_fabric = trim($_POST["product_fabric"]);
    $product_season = trim($_POST["season"]);
    $product_size = (int) trim($_POST["product_size"]);
    $product_price = trim($_POST["product_price"]);

    /**
     * @brief Pole pro uložení cest k nahraným souborům a chyb validace.
     *
     * @var array $all_file_paths Úspěšně nahrané cesty k fotkám.
     * @var array $errors Pole chybových hlášek (klíče: 'photo', 'price', 'name').
     */

    $all_file_paths = [];
    $errors = [];
    if (count($_FILES["photo"]["name"]) === 0 || $_FILES["photo"]["error"][0] != 0) {
        $errors['photo'] = "At least one photo is required.";
    }

    if (empty($product_price) || !is_numeric($product_price) || (float)$product_price <= 0) {
        $errors['price'] = "Valid price greater than 0 is required.";
    }

    if (empty($product_name)) {
    $errors['name'] = "Product name is required.";
    }

    /**
     * @brief Pokud jsou validační chyby – uložit data a errory do session a přesměrovat zpět.
     */

    if (!empty($errors)) {
        $_SESSION['add_product_errors'] = $errors;
        $_SESSION['add_product_data'] = [
            'product_name' => $product_name,
            'product_fabric' => $product_fabric,
            'product_season' => $product_season,
            'product_size' => $product_size,
            'product_price' => $product_price
        ];
        header("Location: add_product.php");
        exit;
    }

    /**
     * @brief Nahrávání souborů na server.
     * Prochází všechny nahrané fotky, generuje unikátní jména a přesouvá je do cílového adresáře.
     */

    for ($i = 0; $i < count($_FILES["photo"]["name"]); $i++) {
        if ($_FILES["photo"]["error"][$i] == 0) {
            $file_name = time() . "_" . $i . "_" . basename($_FILES["photo"]["name"][$i]);
            $file_tmp = $_FILES["photo"]["tmp_name"][$i];
            $file_path = $upload_directory . $file_name;

            if (move_uploaded_file($file_tmp, $file_path)) {
                $all_file_paths[] = $file_path;
            }
        }
    }

    /**
     * @brief Nahrávání souborů na server.
     * Prochází všechny nahrané fotky, generuje unikátní jména a přesouvá je do cílového adresáře.
     */

    $final_file_paths = implode(',', $all_file_paths);
    $product_price = (float) $product_price;

    /**
     * @brief SQL dotaz pro vložení nového produktu.
     * Používá prepared statement pro bezpečnost.
     *
     * @var string $insert_product_query SQL INSERT dotaz.
     * @var mysqli_stmt $stmt Prepared statement.
     */

    $insert_product_query = "INSERT INTO products (
        user_id, file_path, name, fabric, season, size, price
    ) VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($connection, $insert_product_query);

    
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "issssii", 
        $user_id, 
        $final_file_paths,
        $product_name, 
        $product_fabric, 
        $product_season, 
        $product_size, 
        $product_price
    );
    
        /**
         * @brief Provedení vložení.
         * Při úspěchu přesměruje na hlavní stránku, při chybě vypíše error.
         */
        
        if (mysqli_stmt_execute($stmt)) {
            header("Location: ../main/main.php");
            exit;
        } else {
            die("Execution failed: " . mysqli_stmt_error($stmt));
        }
    } else {
        die("Statement preparation failed.");
    }
}
?>