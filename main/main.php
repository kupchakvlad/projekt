<?php
session_start();

// ---------------- CHECKS IF USER IS REGISTERED ----------------
if (!isset($_SESSION["user_id"])) {
    header("Location: ../registration_form/registration_form.php");
    exit;
}

$dark_mode_class = (isset($_COOKIE['mode']) && $_COOKIE['mode'] === 'dark') ? 'dark' : '';

$host = "localhost";
$username = "kupchvla";
$password = "webove aplikace";
$database = "kupchvla";

$conn = mysqli_connect($host, $username, $password, $database);
if (!$conn) {
    die("DB error");
}

$query = "SELECT * FROM products ORDER BY id DESC";
$result = mysqli_query($conn, $query);
?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Botovo - Shop</title>
<link rel="stylesheet" href="main.css">
<script src="main.js" defer></script>
</head>
<body class="<?php echo $dark_mode_class; ?>">

<header class="header">
  <a href="main.php" class="logo">Botovo</a>
  <nav class="header-buttons">
      <?php
      if (isset($_SESSION["user_id"]) && $_SESSION["admin"] == 1) {
          echo '<a href="../admin/admin.php" id="admin-button">Admin</a>';
      }
      ?>
    <a href="../add_product/add_product.php" id="add-product">Add Product</a>
    <a href="../account/account.php" id="account">Account</a>
    <a id="dark-mode-toggle">Dark Mode</a>
  </nav>
</header>

<main>
  <aside class="filter">
  <h3>Filters</h3>

  <label for="search">Search by name:</label>
  <input type="text" id="search" placeholder="e.g. Nike">

<h4>Size</h4>
<div class="size-container">
  <input type="range" id="size-slider" min="27" max="48" value="27">
  <span id="size-value">All</span>
</div>


<h4>Price</h4>
<div class="price-container">
  <div class="price-slider-row">
    <label>Min:</label>
    <input type="range" id="min-price" min="0" max="100000" value="100">
    <input type="number" id="min-price-input" value="100" min="0" max="100000"> CZK
  </div>
  <div class="price-slider-row">
    <label>Max:</label>
    <input type="range" id="max-price" min="0" max="100000" value="100000">
    <input type="number" id="max-price-input" value="100000" min="0" max="100000"> CZK
  </div>
</div>


  <h4>Season</h4>
  <label class="season-option"><input type="radio" name="season" value="autumn" checked> Autumn</label>
  <label class="season-option"><input type="radio" name="season" value="winter" checked> Winter</label>
  <label class="season-option"><input type="radio" name="season" value="spring" checked> Spring</label>
  <label class="season-option"><input type="radio" name="season" value="summer" checked> Summer</label>

  <button id="apply-filters">Find</button>
  <button type="button" id="reset-filters">Reset</button>
</aside>


  <section class="products" id="products">
    <?php
$query = "
SELECT * 
FROM products
WHERE id IN (
    SELECT MIN(id)
    FROM products
    GROUP BY name
)
ORDER BY id DESC
";

$result = mysqli_query($conn, $query);

if ($result) {
    while ($product = mysqli_fetch_array($result)) {
        // 1. Разбиваем строку путей на массив
        $images = explode(',', $product['file_path']);
        
        // Берем только ПЕРВУЮ картинку для превью
        $first_image = trim($images[0]);
        
        $img_url = str_replace('/home/kupchvla/www', 'https://zwa.toad.cz/~kupchvla', $first_image);
        
        echo '<a class="product-card" href="product.php?id=' . $product['id'] . '">';

        echo '<img src="' . $img_url . '" alt="product" style="width:100%; height:200px; object-fit:cover; border-radius:8px;">';
        echo '<p class="product-name">' . htmlspecialchars($product["name"]) . '</p>';
        echo '<p class="product-brand">' . htmlspecialchars($product["fabric"]) . '</p>';
        echo '<p> Season: ' . htmlspecialchars($product["season"]) . '</p>';
        echo '<p> Size: ' . htmlspecialchars($product["size"]) . '</p>';
        echo '<p class="price"> Price: ' . htmlspecialchars($product["price"]) . ' CZK</p>';
        echo '</a>';
    }
}
?>

  </section>
</main>

<footer>
  Botovo © 2025
</footer>

</body>
</html>


