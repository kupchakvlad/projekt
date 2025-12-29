<?php
/**
 * @brief Backend skript pro uložení editovaných údajů uživatele (administrátorská akce).
 *
 * Tento soubor je přístupný pouze přihlášeným administrátorům.
 * Na základě ID z GET parametru a dat z POST (edited_username, edited_email)
 * provede validaci vstupů (neprazdné pole, platný email) a aktualizuje
 * jméno a email uživatele v tabulce `users` pomocí prepared statement.
 * Po zpracování (úspěšném i neúspěšném) vždy přesměruje zpět na admin.php.
 *
 * @file edit_back.php
 *
 * @see edit.php Frontend formulář pro editaci uživatele.
 * @see admin.php Cílová stránka po uložení (seznam uživatelů).
 */
session_start();

/**
 * Kontrola oprávnění – pouze přihlášený administrátor.
 * Pokud podmínky nejsou splněny, přesměruje na hlavní stránku.
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
 * Kontrola existence ID uživatele v GET parametru.
 * Pokud chybí, přesměruje zpět na admin panel.
 */
if (!isset($_GET["id"])) {
    header("Location: admin.php");
    exit;
}

/**
 * Validace ID uživatele z GET parametru.
 * Používá filter_input s FILTER_VALIDATE_INT pro bezpečnost.
 * Pokud ID není platné číslo, ukončí skript s chybovou zprávou.
 *
 * @var int|false $id ID uživatele k editaci.
 */
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if ($id === false) {
    die("Invalid ID");
}

/**
 * Zpracování POST dat z editačního formuláře.
 * Provádí se pouze pokud je odeslán submit ("edit") a vstupy splňují základní validaci:
 * - edited_username není prázdný
 * - edited_email není prázdný a je platný email
 *
 * @var string $username Očištěné jméno uživatele (trim).
 * @var string $email Očištěný email uživatele (trim).
 */
if (isset($_POST["edit"]) && !empty($_POST["edited_username"]) && !empty($_POST["edited_email"]) && filter_var($_POST["edited_email"], FILTER_VALIDATE_EMAIL)) {

    $username = trim($_POST["edited_username"]);
    $email = trim($_POST["edited_email"]);

    /**
     * SQL dotaz pro aktualizaci jména a emailu uživatele.
     * Používá prepared statement pro ochranu proti SQL injection.
     *
     * @var string $edit_query SQL UPDATE dotaz.
     * @var mysqli_stmt $stmt Prepared statement pro UPDATE.
     */
    $edit_query = "UPDATE `users` SET name = ?, email = ? WHERE id = ?";
    $stmt = mysqli_prepare($connection, $edit_query);
    mysqli_stmt_bind_param($stmt, "ssi", $username, $email, $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

}

header("Location: admin.php");
exit;

?>