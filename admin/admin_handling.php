<?php
/**
 * Backend skript pro přepnutí administrátorského oprávnění uživatele.
 * Tento soubor je přístupný pouze přihlášeným administrátorům.
 * Na základě ID z GET parametru načte aktuální admin status uživatele,
 * přepne jej (0 → 1 nebo 1 → 0) a uloží změnu do databáze pomocí prepared statements.
 * Zabraňuje přepnutí vlastního účtu a neplatnému ID.
 * Po úspěšné (nebo neúspěšné) operaci přesměruje zpět na admin.php.
 *
 * @file admin_handling.php
 *
 * @see admin.php Zdroj odkazu "Change Admin" v tabulce uživatelů.
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
    die("Connection failed: \n". mysqli_connect_error());
}

/**
 * @brief Kontrola oprávnění – pouze přihlášený administrátor.
 * Pokud podmínky nejsou splněny, přesměruje na hlavní stránku.
 */
if (!isset($_SESSION['user_id']) || !isset($_SESSION["admin"]) || $_SESSION["admin"] != 1) {
    header("Location: ../main/main.php");
    exit;
}

/**
 * @brief Kontrola existence ID uživatele v GET parametru.
 * Pokud chybí, přesměruje zpět na admin panel.
 */
if (!isset($_GET["id"])) {
    header("Location: admin.php");
    exit;
}

/**
 * @brief ID uživatele, jehož admin status má být změněn.
 * Převedeno na integer pro bezpečnost.
 *
 * @var int $user_id ID cílového uživatele.
 */
$user_id = intval($_GET["id"]);

/**
 * @brief Ochrana před změnou vlastního admin statusu.
 * Administrátor si nemůže odebrat vlastní práva touto cestou.
 */
if ($user_id === intval($_SESSION["user_id"])) {
    header("Location: admin.php");
    exit;
}

/**
 * @brief Načtení aktuálního admin statusu uživatele.
 * Používá prepared statement pro bezpečnost.
 *
 * @var string $query SQL SELECT dotaz pro získání admin hodnoty.
 * @var mysqli_stmt $stmt Prepared statement pro SELECT.
 * @var mysqli_result $result Výsledek dotazu.
 */
$query = "SELECT admin FROM users WHERE id = ?";
$stmt = mysqli_prepare($connection, $query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

/**
 * @brief Určení nové hodnoty admin statusu (přepnutí 0 ↔ 1).
 * @var int $new_admin_value Nová hodnota (0 nebo 1).
 */
if ($row = mysqli_fetch_assoc($result)) {
    if ($row["admin"] == 1) {
        $new_admin_value = 0;
    } else {
        $new_admin_value = 1;
    }
} else {
    header("Location: admin.php");
    exit;
}

mysqli_stmt_close($stmt);

/**
 * @brief Aktualizace admin statusu v databázi.
 * Používá prepared statement pro UPDATE.
 *
 * @var string $update_query SQL UPDATE dotaz.
 * @var mysqli_stmt $update_stmt Prepared statement pro UPDATE.
 */
$update_query = "UPDATE users SET admin = ? WHERE id = ?";
$update_stmt = mysqli_prepare($connection, $update_query);
mysqli_stmt_bind_param($update_stmt, "ii", $new_admin_value, $user_id);
if (mysqli_stmt_execute($update_stmt)) {
    header("Location: admin.php");
    exit;
} else {
    header("Location: admin.php");
    exit;
}
?>