<?php

/**
 * Backend skript pro smazání uživatele (administrátorská akce).
 * Tento soubor je přístupný pouze přihlášeným administrátorům.
 * Na základě ID z GET parametru smaže uživatele z tabulky `users` pomocí prepared statement.
 * Po úspěšném smazání přesměruje zpět na admin.php.
 * Pokud smazání selže, vypíše chybovou zprávu.
 *
 *
 * @see admin.php Zdroj odkazu na smazání uživatele.
 * @see delete_button v admin.php Potvrzení smazání probíhá v admin.js.
 */

session_start();

/**
 * Kontrola oprávnění – pouze přihlášený administrátor.
 * Pokud není session user_id, admin flag nebo admin != 1, přesměruje na hlavní stránku.
 */

if (!isset($_SESSION['user_id']) || !isset($_SESSION["admin"]) || $_SESSION['admin'] != 1) {
    header("Location: ../main/main.php");
    exit;
}

/**
 * Konfigurační proměnné pro připojení k databázi.
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
 * Připojení k databázi MySQL.
 * @var mysqli $connection Objekt připojení.
 */

$connection = mysqli_connect($host, $username, $password, $database);

if (!$connection) {
    die("Connection failed: \n". mysqli_connect_error());
}

/**
 * ID uživatele k smazání z GET parametru.
 * @var int $user_id ID uživatele, který má být smazán.
 */

$user_id = $_GET['id'];

/**
 * SQL dotaz pro smazání uživatele.
 * Používá prepared statement pro ochranu proti SQL injection.
 *
 * @var string $delete_user_query SQL DELETE dotaz s placeholderem.
 * @var mysqli_stmt $stmt Prepared statement objekt.
 */

$delete_user_query = "DELETE FROM users WHERE id = ?";
$stmt = mysqli_prepare($connection, $delete_user_query);
mysqli_stmt_bind_param($stmt, "i", $user_id);

/**
 * Provedení smazání a přesměrování.
 * Pokud je smazání úspěšné, přesměruje na admin.php.
 * V opačném případě vypíše chybovou zprávu.
 */

if (mysqli_stmt_execute($stmt)) {
    header("Location: admin.php");
    exit;
} else {
    echo "User cannot be deleted";
}
?>