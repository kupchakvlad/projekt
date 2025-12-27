<?php
session_start();

$host = "localhost";
$username = "kupchvla";
$password = "webove aplikace";
$database = "kupchvla";

$connection = mysqli_connect($host, $username, $password, $database);

if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST["submit"])) {
    $upload_directory = "/home/kupchvla/www/projekt/photo/";
    $user_id = $_SESSION["user_id"];

    $product_name = trim($_POST["product_name"]);
    $product_fabric = trim($_POST["product_fabric"]);
    $product_season = trim($_POST["season"]);
    $product_size = (int) trim($_POST["product_size"]);
    $product_price = trim($_POST["product_price"]);

    $all_file_paths = [];
    $errors = [];
    if (count($_FILES["photo"]["name"]) === 0 || $_FILES["photo"]["error"][0] != 0) {
        $errors['photo'] = "At least one photo is required.";
    }

    if (empty($product_price) || !is_numeric($product_price) || (float)$product_price <= 0) {
        $errors['price'] = "Valid price greater than 0 is required.";
    }

    if (empty($product_name)) {
    $errors['name'] = "Product name is required.";
    }

    if (!empty($errors)) {
        $_SESSION['add_product_errors'] = $errors;
        $_SESSION['add_product_data'] = [
            'product_name' => $product_name,
            'product_fabric' => $product_fabric,
            'product_season' => $product_season,
            'product_size' => $product_size,
            'product_price' => $product_price
        ];
        header("Location: add_product.php");
        exit;
    }

    for ($i = 0; $i < count($_FILES["photo"]["name"]); $i++) {
        if ($_FILES["photo"]["error"][$i] == 0) {
            $file_name = time() . "_" . $i . "_" . basename($_FILES["photo"]["name"][$i]);
            $file_tmp = $_FILES["photo"]["tmp_name"][$i];
            $file_path = $upload_directory . $file_name;

            if (move_uploaded_file($file_tmp, $file_path)) {
                $all_file_paths[] = $file_path;
            }
        }
    }

    $final_file_paths = implode(',', $all_file_paths);
    $product_price = (float) $product_price;

    $insert_product_query = "INSERT INTO products (
        user_id, file_path, name, fabric, season, size, price
    ) VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($connection, $insert_product_query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "issssii", 
            $user_id, 
            $final_file_paths,
            $product_name, 
            $product_fabric, 
            $product_season, 
            $product_size, 
            $product_price
        );

        if (mysqli_stmt_execute($stmt)) {
            header("Location: ../main/main.php");
            exit;
        } else {
            die("Execution failed: " . mysqli_stmt_error($stmt));
        }
    } else {
        die("Statement preparation failed.");
    }
}
?>