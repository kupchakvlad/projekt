<?php
session_start();

$host = "localhost";
$username = "kupchvla";
$password = "webove aplikace";
$database = "kupchvla";

$connection = mysqli_connect($host, $username, $password, $database);

if (!$connection) {
    die("Connection failed: \n". mysqli_connect_error());
}

if (!isset($_GET["id"])) {
    header("Location: admin.php");
    exit;
}

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if ($id === false) {
    die("Invalid ID");
}

if (isset($_POST["edit"]) && !empty($_POST["edited_username"]) && !empty($_POST["edited_email"])) {

    $username = trim($_POST["edited_username"]);
    $email = trim($_POST["edited_email"]);

    $edit_query = "UPDATE `users` SET name = ?, email = ? WHERE id = ?";
    $stmt = mysqli_prepare($connection, $edit_query);
    mysqli_stmt_bind_param($stmt, "ssi", $username, $email, $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

}

header("Location: admin.php");
exit;

?>