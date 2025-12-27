<?php
$host = "localhost";
$username = "kupchvla";
$password = "webove aplikace";
$database = "kupchvla";

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

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

$perPage = 12;


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

$req = $req . " ORDER BY id DESC";

$final_request = mysqli_query($conn, $req);

$all_products = [];

while($row = mysqli_fetch_assoc($final_request)) {
    $all_products[] = $row;
}

$totalProducts = count($all_products);
$totalPages = ceil($totalProducts / $perPage);


$offset = ($page - 1) * $perPage;
$pageProducts = array_slice($all_products, $offset, $perPage);


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

if ($page > $totalPages && $totalPages > 0) {
    $page = $totalPages;
}

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
mysqli_close($conn);
?>