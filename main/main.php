<?php
session_start();

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
  <label class="season-option"><input type="radio" name="season" value="autumn"> Autumn</label>
  <label class="season-option"><input type="radio" name="season" value="winter"> Winter</label>
  <label class="season-option"><input type="radio" name="season" value="spring"> Spring</label>
  <label class="season-option"><input type="radio" name="season" value="summer"> Summer</label>

  <button id="apply-filters">Find</button>
  <button type="button" id="reset-filters">Reset</button>
</aside>


  <section class="products" id="products">
       <div class="loader">Loading products...</div>
  </section>
</main>

<footer>
  Botovo © 2025
</footer>

</body>
</html>