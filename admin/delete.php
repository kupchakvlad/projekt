<?php
$host = "localhost";
$username = "kupchvla";
$password = "webove aplikace";
$database = "kupchvla";

$connection = mysqli_connect($host, $username, $password, $database);

if (!$connection) {
    die("Connection failed: \n". mysqli_connect_error());
}

$user_id = $_GET['user_id'];

$delete_user_query = "DELETE FROM users WHERE id = ?";
$stmt = mysqli_prepare($connection, $delete_user_query);
mysqli_stmt_bind_param($stmt, "i", $user_id);

if (mysqli_stmt_execute($stmt)) {
    header("location: admin.php");
} else {
    echo "User cannot be deleted";
}

?>