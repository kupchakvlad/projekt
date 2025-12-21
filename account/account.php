<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: ../registration_form/registration_form.php");
    exit;
}

$dark_mode_class = (isset($_COOKIE['mode']) && $_COOKIE['mode'] === 'dark') ? 'dark' : '';

$host = "localhost";
$username = "kupchvla";
$password = "webove aplikace";
$database = "kupchvla";

$connection = mysqli_connect($host, $username, $password, $database);
if (!$connection) die("Connect failed: " . mysqli_connect_error());

// Получение данных пользователя
$user_id = $_SESSION["user_id"];
$query = "SELECT name, email FROM users WHERE id = ?";
$stmt = mysqli_prepare($connection, $query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $name, $email);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Account - Botovo</title>
    <link rel="stylesheet" href="account.css">
    <script src="account.js" defer></script>
</head>
<body class="<?php echo $dark_mode_class; ?>">

    
<button id="back-button" class="top-button"> ← </button>
<button id="dark-mode-toggle" class="top-button"> Dark Mode</button>

<header>
    <h1>My Account</h1>
</header>

<div class="user-panel">
    <div class="welcome-text">Welcome,</div>
    <div class="user-name"><?php echo htmlspecialchars($name); ?></div>
    <div class="user-email"><?php echo htmlspecialchars($email); ?></div>
</div>

<form id="profile-form" method="POST" action="account_back.php">
    <label for="username">Username</label>
    <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($name); ?>" required>

    <label for="email">Email</label>
    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>

    <label for="password">Password</label>
    <input type="password" id="password" name="password" placeholder="Enter new password">

    <button type="submit" class="submit-button">Save Changes</button>

    <a href="logOut.php" class="logout-link">Log Out</a>
</form>
</body>
</html>
