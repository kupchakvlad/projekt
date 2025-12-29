<?php
/**
 * Backend skript pro zpracování přihlašovacího formuláře.
 * Tento soubor zpracovává POST data z registration_form.php (přihlašovací část), validuje vstupy,
 * kontroluje existenci uživatele podle emailu, ověřuje heslo a nastavuje session.
 * Pokud dojde k chybám, ukládá error message do session a přesměruje zpět na formulář.
 * Při úspěchu nastaví session pro uživatele (user_id a admin) a přesměruje na hlavní stránku.
 *
 * @file login.php
 *
 * @see registration_form.php Pro frontend formulář (přihlašovací část).
 * @see main.php Cílová stránka po úspěšném přihlášení.
 */

session_start();

/**
 * Konfigurační proměnné pro připojení k databázi.
 * Tyto proměnné definují přístupové údaje k MySQL databázi.
 *
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
 * Vytvoří připojení pomocí mysqli_connect a ukončí skript při chybě.
 *
 * @var mysqli $connection Objekt připojení k databázi.
 * @throws Exception Pokud připojení selže, vypíše chybu a ukončí skript.
 */
$connection = mysqli_connect($host, $username, $password, $database);

if (!$connection) {
    die("Connect failed: \n". mysqli_connect_error());
}

/**
 * Hlavní logika: Zpracování POST požadavku z přihlašovacího formuláře.
 * Validuje vstupy (email a heslo), kontroluje existenci uživatele v DB,
 * ověřuje heslo pomocí password_verify a nastavuje session.
 * Pokud jsou chyby, ukládá error message do session a přesměruje zpět.
 *
 * @return void Přesměruje na main.php při úspěchu, jinak zpět na registration_form.php.
 * @throws Exception Pokud dojde k chybě při DB operacích (např. prepare selže).
 */
if (isset($_POST['login_submit'])) {
    /**
     * Vstupní data z formuláře.
     * Trimuje email a heslo pro odstranění mezer.
     *
     * @var string $login_email Email z POST.
     * @var string $login_password Heslo z POST.
     * @var string $error_message Chybová zpráva pro session.
     */
    $login_email = trim($_POST["login_email"]);
    $login_password = trim($_POST["login_password"]);
    $error_message = "";

    // Validace: Email a heslo musí být neprázdné.
    if (empty($login_email) || empty($login_password)) {
        $error_message = "Email and password are required";
    } else {
        /**
         * SQL query pro kontrolu existence uživatele podle emailu.
         * Používá prepared statement pro ochranu proti SQL injection.
         * Získává id, password a admin status.
         *
         * @var string $query
         */
        $query = "SELECT id, password, admin FROM users WHERE email = ?";
        $stmt = mysqli_prepare($connection, $query);
        mysqli_stmt_bind_param($stmt, "s", $login_email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            // Ověření hesla pomocí password_verify.
            if (password_verify($login_password, $row["password"])) {
                $_SESSION["user_id"] = $row["id"];
                $_SESSION["admin"] = $row["admin"];
                mysqli_stmt_close($stmt);
                header("Location: ../main/main.php");
                exit;
            } else {
                $error_message = "Incorrect password";
            }
        } else {
            $error_message = "User with this email doesn't exist";
        }
    }
    // Uložení erroru a dat do session a přesměrování zpět.
    $_SESSION['login_error'] = $error_message;
    $_SESSION['login_data'] = [
        "email" => $login_email,
    ];
    header("Location: registration_form.php");
    exit;
}
?>