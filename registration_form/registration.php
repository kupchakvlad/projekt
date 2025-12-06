<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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

// REGISTRATION VALIDATION
$errorMessages = [];

if (isset($_POST['registration_submit'])) {

    // REGISTRATION DATA
    $registration_name = trim($_POST['registration_name']);
    $registration_email = trim($_POST['registration_email']);
    $registration_password = trim($_POST['registration_password']);
    $registration_password_confirmation = trim($_POST['registration_password_confirmation']);

    // REGISTRATION DATA CHECKS
    if (empty($registration_name)) {
        $errorMessages[] = "Name is required";
    }
    if (empty($registration_email)) {
        $errorMessages[] = "Email is required";
    } else if (filter_var($registration_email, FILTER_VALIDATE_EMAIL) === false) {
        $errorMessages[] = "Email is entered incorrectly";
    }
    if (empty($registration_password)) {
        $errorMessages[] = "Password is required";
    } else if (strlen($registration_password) < 8) {
        $errorMessages[] = "Password must be at least 8 characters";
    } else if ($registration_password !== $registration_password_confirmation) {
        $errorMessages[] = "Passwords do not match";
    }

    $check_email_query = "SELECT id FROM users WHERE email = ?";
    $stmt_check = mysqli_prepare($connection, $check_email_query);

    if (!$stmt_check) {
        die("FATAL: Email check statement preparation failed: " . mysqli_error($connection));
    }

    mysqli_stmt_bind_param($stmt_check, "s", $registration_email);
    mysqli_stmt_execute($stmt_check);
    mysqli_stmt_store_result($stmt_check);

    if (mysqli_stmt_num_rows($stmt_check) > 0) {
        $errorMessages[] = "Email already exists";
    }
    mysqli_stmt_close($stmt_check);

    if (empty($errorMessages)) {
        // ---------------- HASH PASSWORD ----------------
        $hashed_password = password_hash($registration_password, PASSWORD_DEFAULT);

        // ---------------- INSERT USER ----------------
        $insert_user_query = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($connection, $insert_user_query);

        if (!$stmt) {
            die("FATAL: Insert statement preparation failed: " . mysqli_error($connection));
        }

        mysqli_stmt_bind_param($stmt, "sss", $registration_name, $registration_email, $hashed_password);

        if (!mysqli_stmt_execute($stmt)) {
            die("Execution failed: " . mysqli_stmt_error($stmt));
        }

        // ---------------- SESSION ID OF REGISTERED USER ----------------
        $user_id = mysqli_insert_id($connection);
        $_SESSION["user_id"] = $user_id;

        mysqli_stmt_close($stmt);
        mysqli_close($connection);

        // SUCCESS
        header("Location: ../main/main.php");
        exit;

    } else {
        // ERRORS FOUND - SHOW THEM
        mysqli_close($connection);

        header('Content-Type: text/html; charset=utf-8');
        echo "<!DOCTYPE html><html><head><title>Errors</title></head><body>";
        echo "<h1>VALIDATION ERRORS:</h1>";
        echo "<pre>";
        print_r($errorMessages);
        echo "</pre>";
        echo "<hr>";
        echo "<h3>Data Received:</h3>";
        echo "Name: " . htmlspecialchars($registration_name ?? 'NOT SET') . "<br>";
        echo "Email: " . htmlspecialchars($registration_email ?? 'NOT SET') . "<br>";
        echo "Password length: " . strlen($registration_password ?? '') . "<br>";
        echo "Confirmation length: " . strlen($registration_password_confirmation ?? '') . "<br>";
        echo "Passwords match: " . (($registration_password ?? '') === ($registration_password_confirmation ?? '') ? "YES" : "NO") . "<br>";
        echo "<br><a href='registration_form.html'>Go Back</a>";
        echo "</body></html>";
        die();

//        mysqli_close($connection);
//        $_SESSION['registration_error'] = implode("<br>", $errorMessages);
//        header("Location: registration_form.html");
//        exit;
    }
}

// If accessed directly without POST
mysqli_close($connection);
header("Location: registration_form.html");
exit;
?>