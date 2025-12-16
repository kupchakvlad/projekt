<?php
session_start();

// ---------------- CHECKS IF USER IS REGISTERED ----------------
if (!isset($_SESSION["user_id"])) {
    header("Location: ../registration_form/registration_form.php");
    exit;
}

$dark_mode_class = (isset($_COOKIE['mode']) && $_COOKIE['mode'] === 'dark') ? 'dark' : '';
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

    <form action="add_product_back.php" method="POST" enctype="multipart/form-data">
        <label for="Photo">Upload photo of product:
            <input type="file" id="Photo" name="photo[]" accept="image/*" multiple>
        </label>
        <div id="file-list" class="file-preview"></div>
        <label for="ProductName">Enter your product name:
            <input type="text" name="product_name" id="ProductName" required>
        </label>
        <label for="ProductCategory">Enter your product category:
            <input type="text" name="product_category" id="ProductCategory" required>
        </label>
        <label for="ProductManufacturer">Enter your product manufacturer:
            <input type="text" name="product_fabric" id="ProductManufacturer" required>
        </label>
        <label for="Season">Enter the season your shoes are made for.
            <select name="season" id="Season">
                <option value="winter">Winter</option>
                <option value="spring">Spring</option>
                <option value="summer">Summer</option>
                <option value="autumn">Autumn</option>
            </select>
        </label>
        <label for="slider"> Enter shoes size: 
            <input type="range" name="product_size" id="slider" min="28" max="48" step="1" value="36">
        </label>
        <span id="size-value">36</span>
        <label for="ProductPrice">Enter price:
            <input type="text" name="product_price" id="ProductPrice">
        </label>
        <input type="submit" class="submit-button" name="submit" value="Add product">
    </form>

</body>
</html>