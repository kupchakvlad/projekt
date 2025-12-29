<?php
/**
 * @brief Backend skript pro zpracování registračního formuláře.
 * Tento soubor zpracovává POST data z registration_form.php, validuje vstupy,
 * kontroluje duplicitu emailu, hashuje heslo a vkládá nového uživatele do databáze.
 * Pokud dojde k chybám, ukládá errory do session a přesměruje zpět na formulář.
 * Při úspěchu nastaví session pro uživatele a přesměruje na hlavní stránku.
 *
 * @file registration.php
 *
 * @see registration_form.php Pro frontend formulář.
 * @see main.php Cílová stránka po úspěšné registraci.
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
    die("Connection failed: " . mysqli_connect_error());
}

/**
 * Načte seznam slabých hesel ze souboru.
 * Tato funkce čte soubor řádek po řádku, trimuje řádky a vrací pole slabých hesel.
 * Používá se pro validaci hesla uživatele, aby se zabránilo použití běžných slabých hesel.
 *
 * @param string $file Cesta k souboru se slabými hesly (např. 'weak_passwords.txt').
 * @return array Pole trimovaných slabých hesel. Pokud soubor neexistuje, vrátí prázdné pole.
 */
$weakPasswordsFile = 'weak_passwords.txt';
function loadWeakPasswords($file) {
    if (!file_exists($file)) return [];
    return array_map('trim', file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES));
}
$weakPasswords = loadWeakPasswords($weakPasswordsFile);

/**
 * Hlavní logika: Zpracování POST požadavku z registračního formuláře.
 * Validuje vstupy (jméno, email, heslo, potvrzení hesla), kontroluje duplicitu emailu v DB,
 * hashuje heslo a vkládá nového uživatele do tabulky 'users'.
 * Pokud jsou errory, ukládá je do session a přesměruje zpět na formulář.
 *
 * @return void Přesměruje na main.php při úspěchu, jinak zpět na registration_form.php.
 * @throws Exception Pokud dojde k chybě při DB operacích (např. prepare selže).
 */
if (isset($_POST['registration_submit'])) {

    /**
     * Pole chyb validace.
     * Obsahuje klíče jako 'name', 'email', 'password', 'confirm' pro označení chyb.
     *
     * @var array $errors
     */
    $errors = [];
    $name  = trim($_POST['registration_name'] ?? '');
    $email = trim($_POST['registration_email'] ?? '');
    $pass  = $_POST['registration_password'] ?? '';
    $confirm = $_POST['registration_password_confirmation'] ?? '';

    // Validace jména: Musí být neprázdné.
    if (empty($name)) $errors[] = 'name';

    // Validace emailu: Musí být neprázdný a platný formát.
    if (empty($email)) {
        $errors[] = 'email';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'email';
    }

    // Validace hesla: Musí být neprázdné, alespoň 8 znaků a ne slabé (z weakPasswords).
    if (empty($pass)) {
        $errors[] = 'password';
    } elseif (strlen($pass) < 8) {
        $errors[] = 'password';
    } elseif (in_array($pass, $weakPasswords)) {
        $errors[] = 'password';
    }

    // Validace potvrzení hesla: Musí se shodovat s heslem.
    if ($pass !== $confirm) {
        $errors[] = 'confirm';
    }

    // Kontrola duplicity emailu v databázi (pouze pokud není chyba v emailu).
    if (!in_array('email', $errors)) {
        /**
         * SQL query pro kontrolu existence emailu v tabulce users.
         * Používá prepared statement pro ochranu proti SQL injection.
         *
         * @var string $query
         */
        $query = "SELECT id FROM users WHERE email = ?";
        $stmt = mysqli_prepare($connection, $query);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        if (mysqli_stmt_num_rows($stmt) > 0) {
            $errors[] = 'email';
        }
        mysqli_stmt_close($stmt);
    }

    // Pokud jsou chyby, ulož do session a přesměruj zpět.
    if (!empty($errors)) {
        $_SESSION['registration_errors'] = $errors;
        $_SESSION['registration_data'] = [
                'name' => $name,
                'email' => $email
        ];
        header("Location: registration_form.php");
        exit;
    }

    // Hashování hesla pomocí password_hash (Bcrypt výchozí).
    $hashed = password_hash($pass, PASSWORD_DEFAULT);

    /**
     * SQL insert pro přidání nového uživatele.
     * Používá prepared statement pro bezpečnost.
     *
     * @var string $insert
     */
    $insert = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($connection, $insert);
    mysqli_stmt_bind_param($stmt, "sss", $name, $email, $hashed);
    mysqli_stmt_execute($stmt);

    // Nastavení session pro nového uživatele (ID a admin=0).
    $user_id = mysqli_insert_id($connection);
    $_SESSION['user_id'] = $user_id;
    $_SESSION['admin'] = 0;

    mysqli_stmt_close($stmt);
    mysqli_close($connection);

    header("Location: ../main/main.php");
    exit;
}

// Pokud není POST submit, přesměruj na formulář.
header("Location: registration_form.php");
exit;
?>