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

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if ($id === false) {
    die("Invalid ID");
}

$prefill_query = "SELECT name, email FROM users WHERE id = ?";
$prefill_stmt = mysqli_prepare($connection, $prefill_query);
mysqli_stmt_bind_param($prefill_stmt, "i", $id);
mysqli_stmt_execute($prefill_stmt);
mysqli_stmt_bind_result($prefill_stmt, $current_name, $current_email);
mysqli_stmt_fetch($prefill_stmt);
mysqli_stmt_close($prefill_stmt);

?>

<!DOCTYPE html>
<html>

<head>
    <link href="edit.css" rel="stylesheet">
    <title> Edit </title>
</head>

<body>
    <form method="POST" action="edit_back.php?id=<?php echo $id; ?>">
        <label>
            Username:
            <input type="text" name="edited_username" value="<?php echo htmlspecialchars($current_name);?>" required>
        </label>
        <label>
            Email:
            <input type="text" name="edited_email" value="<?php echo htmlspecialchars($current_email);?>" required>
        </label>
        <input type="submit" name="edit" value="Edit">
    </form>
</body>

</html>
