<?php
$host = "localhost";
$username = "kupchvla";
$password = "webove aplikace";
$database = "kupchvla";

$connection = mysqli_connect($host, $username, $password, $database);

if (!$connection) {
    die("Connection failed: \n". mysqli_connect_error());
}

$user_id = $_GET['id'];
?>

<!DOCTYPE html>
<html>

<head>
    <link href="edit.css" rel="stylesheet">
</head>

<body>
    <form method="POST" action="edit_back.php?id=<?php echo $user_id; ?>">
        <label>
            Username:
            <input type="text" name="edited_username" required>
        </label>
        <label>
            Email:
            <input type="text" name="edited_email" required>
        </label>
        <input type="submit" name="edit" value="Edit">
    </form>
</body>

</html>
