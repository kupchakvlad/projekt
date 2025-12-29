<?php

/**
 * AJAX pro filtrování a paginaci produktů.
 * Tento soubor zpracovává GET parametry z frontend filtru (main.js), sestavuje dynamický SQL query
 * na základě filtrů (search, size, price range, season, page), vrací HTML s produkty a paginací.
 * Používá se pro dynamické načítání produktů bez reloadu stránky.
 * Výstup je přímo HTML pro vložení do #products elementu.
 *
 *
 * @see main.php Pro frontend stránku s filtry.
 * @see main.js Pro AJAX volání.
 * @see product.php Pro detail produktu.
 */

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
 * @var mysqli $connection Objekt připojení.
 */

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

/**
 * Načtení GET parametrů pro filtr.
 * Nastaví výchozí hodnoty, pokud parametry chybí, a přetypuje na int kde je potřeba.
 *
 * @var string $search Hledaný text v názvu produktu (výchozí: "").
 * @var int $size Velikost boty (výchozí: 0, což znamená všechny).
 * @var int $min Minimální cena (výchozí: 0).
 * @var int $max Maximální cena (výchozí: 100000).
 * @var string $season Sezóna (výchozí: "").
 * @var int $page Číslo stránky (výchozí: 1, min 1).
 */

if (isset($_GET["search"])) {
    $search = $_GET["search"];
} else {
    $search = "";
}

if (isset($_GET["size"])) {
    $size = (int) $_GET["size"];
} else {
    $size = 0;
}

if (isset($_GET["min"])) {
    $min = (int) $_GET["min"];
} else {
    $min = 0;
}

if (isset($_GET["max"])) {
    $max = (int) $_GET["max"];
} else {
    $max = 100000;
}

if (isset($_GET['season'])) {
    $season = $_GET['season'];
} else {
    $season = '';
}

if (isset($_GET["page"])) {
    $page = (int) $_GET["page"];
} else {
    $page = 1;
}

if ($page < 1) {
    $page = 1;
}

/**
 * Počet produktů na jednu stránku.
 * @var int $perPage Fixní hodnota 12 produktů na stránku.
 */

$perPage = 12;

/**
 * Základní SQL query s cenovým rozsahem.
 * @var string $req SQL dotaz, který se postupně rozšiřuje podle filtrů.
 */

$req = "SELECT * FROM products WHERE price BETWEEN $min AND $max";

if ($search !="") {
    $searchSafing = mysqli_real_escape_string($conn, $search);
    $req = $req . " AND name LIKE '%$searchSafing%' ";
}

if ($season != "") {
    $seasonSafing = mysqli_real_escape_string($conn, $season);
    $req = $req . " AND season = '$seasonSafing' ";
}

if ($size > 27) {
    $req = $req . " AND size = $size";
}

/**
 * Přidání řazení podle ID descending.
 */

$req = $req . " ORDER BY id DESC";

/**
 * Spuštění query a načtení všech vyhovujících produktů do pole.
 * @var mysqli_result $final_request Výsledek SQL dotazu.
 * @var array $all_products Pole všech produktů, které prošly filtrem.
 */

$final_request = mysqli_query($conn, $req);

$all_products = [];

while($row = mysqli_fetch_assoc($final_request)) {
    $all_products[] = $row;
}

/**
 * Výpočet celkového počtu produktů a počtu stránek.
 * @var int $totalProducts Celkový počet filtrovaných produktů.
 * @var int $totalPages Celkový počet stránek (zaokrouhleno nahoru).
 */

$totalProducts = count($all_products);
$totalPages = ceil($totalProducts / $perPage);

$offset = ($page - 1) * $perPage;
$pageProducts = array_slice($all_products, $offset, $perPage);

/**
 * Výpis HTML karet produktů pro aktuální stránku.
 * Pokud nejsou žádné produkty, vypíše zprávu "No products found".
 */

if (count($pageProducts) > 0) {
     foreach ($pageProducts as $product) {
        $images = explode(',', $product['file_path']);
        $img_path = trim($images[0]);
        $img_url = str_replace('/home/kupchvla/www', 'https://zwa.toad.cz/~kupchvla', $img_path);

        echo '<a class="product-card" href="product.php?id=' . $product['id'] . '">';
        echo '  <img src="' . $img_url . '" alt="Product">';
        echo '  <p class="product-name">' . htmlspecialchars($product['name']) . '</p>';
        echo '<p class="product-brand">' . htmlspecialchars($product["fabric"]) . '</p>';
        echo '<p> Season: ' . htmlspecialchars($product["season"]) . '</p>';
        echo '<p> Size: ' . htmlspecialchars($product["size"]) . '</p>';
        echo '  <p class="price">' . $product['price'] . ' CZK</p>';
        echo '</a>';
    }
    } else {
    echo "<p>No products found</p>";
    }

/**
 * Oprava stránky, pokud je požadována neexistující stránka.
 */

if ($page > $totalPages && $totalPages > 0) {
    $page = $totalPages;
}

/**
 * Generování HTML pro paginaci.
 * Zobrazí tlačítka Prev/Next a čísla stránek (s ellipsis pro více než 5 stránek).
 * Zobrazí se pouze pokud je více než 1 stránka.
 */

if ($totalPages > 1) {
    echo '<div class="pagination">';

    if ($page > 1) {
        $prevPage = $page - 1;
        echo '<button class="page-btn" data-page="' . $prevPage . '">Prev</button>';
    }

    if ($totalPages <= 5) {
        for ($i = 1 ; $i <= $totalPages; $i++) {
            if ($i > 0 && $i <= $totalPages) {
                if ($i == $page) {
                echo '<button class="page-btn active">' . $i . '</button>';
            } else {
                echo '<button class="page-btn" data-page="' . $i . '">' . $i . '</button>';
                }
            }
        }
    } else {
        if ($page > 3) {
            echo '<button class="page-btn" data-page="1">1</button>';
            echo '<span class="dots">...</span>';
        }

        for ($i = $page - 2; $i <= $page + 2; $i++) {
            if ($i > 0 && $i <= $totalPages) {
                if ($i == $page) {
                    echo '<button class="page-btn active">'.$i.'</button>';
                } else {
                    echo '<button class="page-btn" data-page="'.$i.'">'.$i.'</button>';
                }
            }
        }
        
    if ($totalPages > 5 && $page < $totalPages - 2) {
    echo '<span class="dots">...</span>';
    echo '<button class="page-btn" data-page="' . $totalPages . '">' . $totalPages . '</button>';
    }
}
    if ($page < $totalPages) {
        $nextPage = $page + 1;
    echo '<button class="page-btn" data-page="' . $nextPage . '">Next</button>';
        }
    echo '</div>';
    }

/**
 * Uzavření připojení k databázi.
 */
mysqli_close($conn);
?>