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
if (!$conn) die("DB error");

if (!isset($_GET["id"])) die("This product does not exist");

$id = (int) $_GET['id'];

$query = "
    SELECT p.*, u.name AS user_name
    FROM products p
    JOIN users u ON p.user_id = u.id
    WHERE p.id = $id";

$result = mysqli_query($conn, $query);
$product = mysqli_fetch_array($result);
if (!$product) die("This product does not exist");

$added_date = 'Unknown';
if (!empty($product['created_at'])) {
    $added_date = date('d M Y', strtotime($product['created_at']));
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $product["name"]; ?> - Botovo</title>
<link rel="stylesheet" href="product.css">
<script src="products.js" defer></script>
</head>
<body class="<?= $dark_mode_class; ?>">

<a href="main.php" class="back-link">← Back</a>

<div class="product-page">

  <div class="product-top">
   <div class="gallery-container">
    <button class="gallery-btn left" id="scrollLeft">&#10094;</button>

    <div class="product-gallery" id="productGallery">
        <?php
                $images = explode(',', $product['file_path']);
                foreach ($images as $img) {
                    $img_url = str_replace('/home/kupchvla/www', 'https://zwa.toad.cz/~kupchvla', trim($img));
                    echo '<img class="product-image" src="' . $img_url . '" alt="product">';
                }

        ?>
    </div>

    <button class="gallery-btn right" id="scrollRight">&#10095;</button>
</div>



    <div class="product-info">
      <h1><?= $product['name'] ?></h1>
      <div class="brand"><?= $product['fabric'] ?></div>
      <p>Season: <?= $product['season'] ?></p>
      <p>Size: <?= $product['size'] ?></p>
      <div class="price"><?= $product['price'] ?> CZK</div>

      <button class="buy-btn" id="buyBtn">Buy</button>
      <div class="buy-message" id="buyMessage">
        Request sent ✔ Seller will contact you
      </div>

      <div class="product-meta">
       <p>Added by: <?php echo htmlspecialchars($product['user_name']); ?></p>
        <p>Date: <?php echo $added_date; ?></p>
      </div>
    </div>
  </div>

</div>
</body>
</html>
