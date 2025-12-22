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

if (mysqli_num_rows($final_request) > 0) {
    while ($product = mysqli_fetch_assoc($final_request)) {
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
    echo "<p class='no-results'>No products found matching your filters.</p>";
    }

mysqli_close($conn);?>