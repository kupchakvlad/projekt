<?php
session_start();

if (!isset($_SESSION['user_id']) || !isset($_SESSION["admin"]) || $_SESSION['admin'] != 1) {
    header("Location: ../main/main.php");
    exit;
}

$host = "localhost";
$username = "kupchvla";
$password = "webove aplikace";
$database = "kupchvla";

$connection = mysqli_connect($host, $username, $password, $database);

if (!$connection) {
    die("Connection failed: \n". mysqli_connect_error());
}

$current_user_id = $_SESSION['user_id'];

$select_users_query = "SELECT id, name, email, admin FROM users";
$result = mysqli_query($connection, $select_users_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>(Admin mode)</title>
    <link href="admin.css" rel="stylesheet">
    <script src="admin.js" defer></script>
</head>
<body>

<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Admin status</th>
        <th>User handling</th>
    </tr>
    </thead>
    <tbody class="users">
        <?php
        if (mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . $row["id"] . "</td>";
                echo "<td>" . $row["name"] . "</td>";
                echo "<td>" . $row["email"] . "</td>";
                echo "<td>" . $row["admin"] . "</td>";
                echo "<td>";
                echo "<a href='edit.php?id=" . htmlspecialchars($row["id"]) . "'>Edit</a>";
                echo "<a href='delete.php?id=" . htmlspecialchars($row["id"]) . "' class='delete_button'>Delete</a>";
                echo "<a href='admin_handling.php?id=" . htmlspecialchars($row["id"]) . "'>Change Admin</a>";
                echo "</td>";
                echo "</tr>";
            }
        }
        ?>
    </tbody>
</table>

</body>
</html>
