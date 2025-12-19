<?php

$host = "localhost";
$username = "kupchvla";
$password = "webove aplikace";
$database = "kupchvla";

$connection = mysqli_connect($host, $username, $password, $database);

if (!$connection) {
    die("Connection failed: \n". mysqli_connect_error());
}

$select_users_query = "SELECT id, name, email FROM users";
$result = mysqli_query($connection, $select_users_query);

?>