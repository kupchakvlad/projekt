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

if(!$conn) {
    die("DB error");
}

if(!isset($_GET["id"])) {
    die("This product does not exit");
}

$id = (int)$_GET['id'];

$query = "SELECT * FROM products WHERE id = $id";
$result = mysqli_query($conn, $query);
$product = mysqli_fetch_array($result);
if (!$product) {
    die("This product does not exit");
} 
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> <?php echo $product["name"]; ?> - Botovo </title>
    <link rel="stylesheet" href="product.css">
</head>
<body class="<?php echo $dark_mode_class; ?>">
<div class = "product-page">

    <img src="<?php echo str_replace('/home/kupchvla/www', 'https://zwa.toad.cz/~kupchvla', $product['file_path']); ?>" alt="product">
    <h1><?php echo $product['name']; ?></h1>
    <p class="brand"><?php echo $product['fabric']; ?></p>
    <p>Season: <?php echo $product['season']; ?></p>
    <p>Size: <?php echo $product['size']; ?></p>
    <p class="price"><?php echo $product['price']; ?> CZK</p>
    <a href="main.php">Back to main</a>


</div>
    
</body>
</html>