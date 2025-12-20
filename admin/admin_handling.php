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

if (!isset($_SESSION['user_id']) || !$_SESSION["admin"]) {
    header("Location: ../registration_form/registration_form.php");
    exit;
}

if (!isset($_SESSION['admin'])) {
    header("Location: ../");
    exit;
}

if (!isset($_GET["id"])) {
    header("Location: admin.php");
    exit;
}

$user_id = $_GET["id"];

if ($user_id === $_SESSION["user_id"]) {
    header("Location: admin.php");
    exit;
}

$query = "SELECT admin FROM users WHERE id = ?";
$stmt = mysqli_prepare($connection, $query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($row = mysqli_fetch_assoc($result)) {
    if ($row["admin"] == "true") {
        $new_admin_value = "false";  // was admin, now remove admin
    } else {
        $new_admin_value = "true";  // was not admin, now make admin
    }
} else {
    header("Location: admin.php");
    exit;
}

mysqli_stmt_close($stmt);

$update_query = "UPDATE users SET admin = ? WHERE id = ?";
$update_stmt = mysqli_prepare($connection, $update_query);
mysqli_stmt_bind_param($update_stmt, "si", $new_admin_value, $user_id);
if (mysqli_stmt_execute($update_stmt)) {
    header("Location: admin.php");
    exit;
} else {
    header("Location: admin_handling.php");
    exit;
}
?>