<?php
session_start();

// DATABASE CONNECTION
$host = "localhost";
$username = "kupchvla";
$password = "webove aplikace";
$database = "kupchvla";

$connection = mysqli_connect($host, $username, $password, $database);
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

// Load weak passwords
$weakPasswordsFile = 'weak_passwords.txt';
function loadWeakPasswords($file) {
    if (!file_exists($file)) return [];
    return array_map('trim', file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES));
}
$weakPasswords = loadWeakPasswords($weakPasswordsFile);

// Only run when form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['registration_submit'])) {

    $errors = [];
    $name  = trim($_POST['registration_name'] ?? '');
    $email = trim($_POST['registration_email'] ?? '');
    $pass  = $_POST['registration_password'] ?? '';
    $confirm = $_POST['registration_password_confirmation'] ?? '';

    // Validation
    if (empty($name)) $errors[] = 'name';

    if (empty($email)) {
        $errors[] = 'email';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'email';
    }

    if (empty($pass)) {
        $errors[] = 'password';
    } elseif (strlen($pass) < 8) {
        $errors[] = 'password';
    } elseif (in_array($pass, $weakPasswords)) {
        $errors[] = 'password';
    }

    if ($pass !== $confirm) {
        $errors[] = 'confirm';
    }

    // Check if email already exists
    if (!in_array('email', $errors)) {
        $query = "SELECT id FROM users WHERE email = ?";
        $stmt = mysqli_prepare($connection, $query);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        if (mysqli_stmt_num_rows($stmt) > 0) {
            $errors[] = 'email';
        }
        mysqli_stmt_close($stmt);
    }

    // If errors → save and go back to form
    if (!empty($errors)) {
        $_SESSION['registration_errors'] = $errors;
        $_SESSION['registration_data'] = [
                'name' => $name,
                'email' => $email
        ];
        header("Location: registration_form.php");
        exit;
    }

    // Success → register user
    $hashed = password_hash($pass, PASSWORD_DEFAULT);
    $insert = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($connection, $insert);
    mysqli_stmt_bind_param($stmt, "sss", $name, $email, $hashed);
    mysqli_stmt_execute($stmt);

    $user_id = mysqli_insert_id($connection);
    $_SESSION['user_id'] = $user_id;
    $_SESSION['admin'] = 0;

    mysqli_stmt_close($stmt);
    mysqli_close($connection);

    header("Location: ../main/main.php");
    exit;
}

// If someone opens this file directly
header("Location: registration_form.php");
exit;
?>