<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../registration_form/registration_form.php");
    exit;
}

$host = "localhost";
$username = "kupchvla";
$password = "webove aplikace";
$database = "kupchvla";

$connection = mysqli_connect($host, $username, $password, $database);
if (!$connection) die("Connect failed: " . mysqli_connect_error());

$user_id = $_SESSION['user_id'];
$new_name = $_POST['username'];
$new_email = $_POST['email'];
$new_password = $_POST['password'];

// Если пароль заполнен, хэшируем
if (!empty($new_password)) {
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
    $query = "UPDATE users SET name = ?, email = ?, password = ? WHERE id = ?";
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, "sssi", $new_name, $new_email, $hashed_password, $user_id);
} else {
    // Если пароль пустой, меняем только имя и email
    $query = "UPDATE users SET name = ?, email = ? WHERE id = ?";
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, "ssi", $new_name, $new_email, $user_id);
}

mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

header("Location: account.php");
exit;
?>
