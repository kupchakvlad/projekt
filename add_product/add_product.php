<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: ../registration_form/registration_form.php");
    exit;
}

$dark_mode_class = (isset($_COOKIE['mode']) && $_COOKIE['mode'] === 'dark') ? 'dark' : '';

$product_name_value = $_SESSION['add_product_data']['product_name'] ?? '';
$product_fabric_value = $_SESSION['add_product_data']['product_fabric'] ?? '';
$product_season_value = $_SESSION['add_product_data']['product_season'] ?? '';
$product_size_value = $_SESSION['add_product_data']['product_size'] ?? '36';
$product_price_value = $_SESSION['add_product_data']['product_price'] ?? '';

$errors = $_SESSION['add_product_errors'] ?? [];
unset($_SESSION['add_product_data'], $_SESSION['add_product_errors']);

$photo_error = $errors['photo'] ?? '';
$price_error = $errors['price'] ?? '';
$name_error = $errors['name'] ?? '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="add_product.css">
    <script src="add_product.js" defer></script>
    <title>Add Product - Botovo</title>
</head>
<body class="<?php echo $dark_mode_class ?>">

    <button class="back-to-main"> ← </button>
    <button type="button" id="dark-mode-btn" class="dark-mode-btn">Dark Mode</button>

    <form action="add_product_back.php" method="POST" enctype="multipart/form-data" id="add-product-form">

        <label for="Photo">Enter your product photo: <span class="required">*</span></label>
        <input type="file" name="photo[]" id="Photo" accept="image/*" multiple>
            <?php if ($photo_error): ?><div class="error"><?php echo $photo_error; ?></div><?php endif; ?>

        <label for="ProductName">Enter your product name: <span class="required">*</span></label>
        <input type="text" name="product_name" id="ProductName" value="<?php echo htmlspecialchars($product_name_value); ?>">
            <?php if ($name_error): ?><div class="error"><?php echo $name_error; ?></div><?php endif; ?>

        <label for="ProductManufacturer">Enter your product manufacturer: <span class="required">*</span></label>
        <input type="text" name="product_fabric" id="ProductManufacturer" value="<?php echo htmlspecialchars($product_fabric_value); ?>">

        <label for="Season">Enter the season your shoes are made for. <span class="required">*</span></label>
        <select name="season" id="Season">
            <option value="<?php echo $product_season_value; ?>" selected><?php echo $product_season_value ?: 'Select season'; ?></option>
            <option value="winter">Winter</option>
            <option value="spring">Spring</option>
            <option value="summer">Summer</option>
            <option value="autumn">Autumn</option>
        </select>

        <label for="slider"> Enter shoes size: <span class="required">*</span></label>
        <input type="range" name="product_size" id="slider" min="28" max="48" step="1" value="<?php echo $product_size_value; ?>">

        <span id="size-value"><?php echo $product_size_value; ?></span>

        <label for="ProductPrice">Enter price: <span class="required">*</span></label>
        <input type="text" name="product_price" id="ProductPrice" value="<?php echo htmlspecialchars($product_price_value); ?>">
        <?php if ($price_error): ?><div class="error"><?php echo $price_error; ?></div><?php endif; ?>

        <input type="submit" class="submit-button" name="submit" value="Add product">
    </form>

</body>
</html>