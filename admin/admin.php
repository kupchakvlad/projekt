<?php

/**
 * Administrátorské rozhraní – seznam uživatelů.
 * Tento soubor je přístupný pouze přihlášeným administrátorům (admin = 1).
 * Zobrazuje tabulku všech uživatelů z databáze s možností editace, smazání
 * a změny admin statusu. Používá htmlspecialchars pro ochranu proti XSS.
 *
 * @file admin.php
 *
 * @see edit.php Pro editaci uživatele.
 * @see delete.php Pro smazání uživatele.
 * @see admin_handling.php Pro přepnutí admin statusu.
 * @see admin.css Pro styly admin tabulky.
 * @see admin.js Pro JavaScript potvrzení smazání.
 */

session_start();

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
 * @brief SQL dotaz pro načtení všech uživatelů.
 * Vyberá ID, jméno, email a admin status.
 *
 * @var string $select_users_query SQL dotaz.
 * @var mysqli_result $result Výsledek dotazu.
 */

$select_users_query = "SELECT id, name, email, admin FROM users";
$result = mysqli_query($connection, $select_users_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>(Admin mode)</title>
    <link href="admin.css" rel="stylesheet">
    <script src="admin.js" defer></script>
</head>
<body>

<a href="../main/main.php" class = "back-link">← Back to main</a>

<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Admin status</th>
        <th>User handling</th>
    </tr>
    </thead>
    <tbody class="users">
        <?php

        /**
         * @brief Výpis řádků tabulky s uživateli.
         * Pokud jsou data, prochází výsledky a generuje řádky s odkazy na akce.
         * Používá htmlspecialchars pro bezpečné vypsání ID v URL.
         */

        if (mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . $row["id"] . "</td>";
                echo "<td>" . $row["name"] . "</td>";
                echo "<td>" . $row["email"] . "</td>";
                echo "<td>" . $row["admin"] . "</td>";
                echo "<td>";
                echo "<a href='edit.php?id=" . htmlspecialchars($row["id"]) . "'>Edit</a>";
                echo "<a href='delete.php?id=" . htmlspecialchars($row["id"]) . "' class='delete_button'>Delete</a>";
                echo "<a href='admin_handling.php?id=" . htmlspecialchars($row["id"]) . "'>Change Admin</a>";
                echo "</td>";
                echo "</tr>";
            }
        }
        ?>
    </tbody>
</table>

</body>
</html>
