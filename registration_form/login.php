<?php
session_start();


//DATABASE
$host = "localhost";
$username = "kupchvla";
$password = "webove aplikace";
$database = "kupchvla";

$connection = mysqli_connect($host, $username, $password, $database);

if (!$connection) {
    die("Connect failed: \n". mysqli_connect_error());
}


if (isset($_POST['login_submit'])) {
    $login_email = trim($_POST["login_email"]);
    $login_password = trim($_POST["login_password"]);
    $error_message = "";

    if (empty($login_email) || empty($login_password)) {
        $error_message = "Email and password are required";

    } else {
        $query = "SELECT id, password, admin FROM users WHERE email = ?";
        $stmt = mysqli_prepare($connection, $query);
        mysqli_stmt_bind_param($stmt, "s", $login_email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            if (password_verify($login_password, $row["password"])) {
                $_SESSION["user_id"] = $row["id"];
                $_SESSION["admin"] = $row["admin"];
                mysqli_stmt_close($stmt);
                header("Location: ../main/main.php");
                exit;
            } else {
                $error_message = "Incorrect password";
            }
        } else {
            $error_message = "User with this email doesn't exist";
        }
    }
    $_SESSION['login_error'] = $error_message;
    header("Location: registration_form.php");
    exit;
}
?>