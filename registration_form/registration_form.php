<?php
session_start();

//  DATABASE CONNECTION

$host = "zwa.toad.cz";
$username = "kupchvla";
$password = "webove aplikace";
$database = "kupchvla";

$mysqli = new mysqli($host, $username, $password, $database);

if ($mysqli->connect_errno) {
    die("Connect failed: \n". $mysqli->connect_error);
}
echo "Connected successfully";

$errorMessages = [];

if (isset($_POST['registration_submit'])) {

    // REGISTRATION DATA
    $registration_name = trim($_POST['registration_name']);
    $registration_email = trim($_POST['registration_email']);
    $registration_password = trim($_POST['registration_password']);
    $registration_password_confirmation = trim($_POST['registration_password_confirmation']);


    // REGISTRATION DATA CHECKS
    if (empty($registration_name)) {
        array_push($errorMessages, "Name is required");
    }
    if (empty($registration_email)) {
        array_push($errorMessages, "Email is required");
    } else if (strpos($registration_email, "@") === false) {
        array_push($errorMessages, "Email is entered incorrectly");
    }
    if (empty($registration_password)) {
        array_push($errorMessages, "Password is required");
    } else if (strlen($registration_password) < 8) {
        array_push($errorMessages, "Password must be at least 8 characters");
    } else if ($registration_password !== $registration_password_confirmation) {
        array_push($errorMessages, "Passwords do not match");
    }
} else {
    header("Location: ../main/main.html");
    exit;
}
?>