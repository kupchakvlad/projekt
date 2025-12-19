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
?>

<!DOCTYPE html>
<html>

<head></head>

<body>
    <form method="POST">
        <label>
            Username:
            <input type="text" name="edited_username" required>
        </label>
        <label>
            Email:
            <input type="text" name="edited_email" required>
        </label>
    </form>
</body>

</html>
