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