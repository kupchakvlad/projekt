<?php
/**
 * Backend skript pro uložení změn v uživatelském profilu.
 * Tento soubor zpracovává POST data z formuláře na account.php.
 * Aktualizuje jméno, email a případně heslo přihlášeného uživatele v tabulce `users`.
 * Pokud je zadáno nové heslo, hashuje ho pomocí password_hash.
 * Používá prepared statements pro ochranu proti SQL injection.
 * Po úspěšné aktualizaci přesměruje zpět na account.php.
 *
 * @file account_back.php
 *
 * @see account.php Frontend stránka s formulářem pro úpravu profilu.
 */
session_start();

/**
 * @brief Kontrola přihlášení uživatele.
 * Pokud není session user_id nastavena, přesměruje na přihlašovací/regační formulář.
 */
if (!isset($_SESSION['user_id'])) {
    header("Location: ../registration_form/registration_form.php");
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
if (!$connection) die("Connect failed: " . mysqli_connect_error());

/**
 * @brief Načtení dat z POST formuláře a ID uživatele ze session.
 *
 * @var int $user_id ID přihlášeného uživatele.
 * @var string $new_name Nové jméno uživatele.
 * @var string $new_email Nový email uživatele.
 * @var string $new_password Nové heslo (volitelné, může být prázdné).
 */
$user_id = $_SESSION['user_id'];
$new_name = $_POST['username'];
$new_email = $_POST['email'];
$new_password = $_POST['password'];

/**
 * @brief Příprava a provedení UPDATE dotazu.
 * Pokud je zadáno nové heslo, aktualizuje i sloupec password (s hashováním).
 * Jinak aktualizuje pouze jméno a email.
 *
 * @var string $hashed_password Hash nového hesla (pouze pokud je zadáno).
 * @var string $query SQL UPDATE dotaz (dynamicky podle přítomnosti hesla).
 * @var mysqli_stmt $stmt Prepared statement pro UPDATE.
 */
if (!empty($new_password)) {
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
    $query = "UPDATE users SET name = ?, email = ?, password = ? WHERE id = ?";
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, "sssi", $new_name, $new_email, $hashed_password, $user_id);
} else {
    $query = "UPDATE users SET name = ?, email = ? WHERE id = ?";
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, "ssi", $new_name, $new_email, $user_id);
}

mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

/**
 * @brief Přesměrování zpět na stránku profilu po uložení změn.
 */
header("Location: account.php");
exit;
?>
