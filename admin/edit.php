<?php

/**
 * Administrátorská stránka pro editaci údajů uživatele.
 * Tento soubor je přístupný pouze přihlášeným administrátorům.
 * Na základě ID z GET parametru načte aktuální jméno a email uživatele z databáze
 * pomocí prepared statement a předvyplní jimi jednoduchý editační formulář.
 * Formulář odesílá data na edit_back.php, kde proběhne samotná aktualizace.
 * Používá filter_input a htmlspecialchars pro základní bezpečnost.
 *
 * @file edit.php
 *
 * @see edit_back.php Backend pro uložení změn.
 * @see admin.php Zdroj odkazu "Edit" v seznamu uživatelů.
 * @see edit.css Styly editačního formuláře.
 */

session_start();

/**
 * @brief Kontrola oprávnění – pouze přihlášený administrátor.
 * Pokud podmínky nejsou splněny, přesměruje na hlavní stránku.
 */

if (!isset($_SESSION['user_id']) || !isset($_SESSION["admin"]) || $_SESSION['admin'] != 1) {
    header("Location: ../main/main.php");
    exit;
}

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
    die("Connection failed: \n". mysqli_connect_error());
}

/**
 * @brief Získání a validace ID uživatele z GET parametru.
 * Používá filter_input s FILTER_VALIDATE_INT pro bezpečnou validaci.
 * Pokud ID není platné číslo nebo chybí, ukončí skript s chybovou zprávou.
 *
 * @var int|false|null $id ID uživatele k editaci.
 */

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if ($id === false || $id === null) {
    die("Invalid ID");
}

/**
 * @brief Načtení aktuálních údajů uživatele (jméno a email) pro předvyplnění formuláře.
 * Používá prepared statement pro ochranu proti SQL injection.
 *
 * @var string $prefill_query SQL SELECT dotaz.
 * @var mysqli_stmt $prefill_stmt Prepared statement.
 * @var string $current_name Aktuální jméno uživatele.
 * @var string $current_email Aktuální email uživatele.
 */

$prefill_query = "SELECT name, email FROM users WHERE id = ?";
$prefill_stmt = mysqli_prepare($connection, $prefill_query);
mysqli_stmt_bind_param($prefill_stmt, "i", $id);
mysqli_stmt_execute($prefill_stmt);
mysqli_stmt_bind_result($prefill_stmt, $current_name, $current_email);
mysqli_stmt_fetch($prefill_stmt);
mysqli_stmt_close($prefill_stmt);

?>

<!DOCTYPE html>
<html>

<head>
    <link href="edit.css" rel="stylesheet">
    <title> Edit </title>
</head>

<body>
    <form method="POST" action="edit_back.php?id=<?php echo $id; ?>">
        <label>
            Username:
            <input type="text" name="edited_username" value="<?php echo htmlspecialchars($current_name);?>" required>
        </label>
        <label>
            Email:
            <input type="text" name="edited_email" value="<?php echo htmlspecialchars($current_email);?>" required>
        </label>
        <input type="submit" name="edit" value="Edit">
    </form>
</body>

</html>
