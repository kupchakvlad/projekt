<?php

$REGISTRATION_DATABASE = "registration_data.txt";

//registration data from registration_form.html to database form -> tag(name)
$username_registration = $_POST["username_registration"];
$email_registration = $_POST["email_registration"];
$password_registration = password_hash($_POST["password_registration"], PASSWORD_DEFAULT);

//writes data to database
$registration_data = "$username_registration  |  $email_registration  |  $password_registration\n";

//writes data on click and redirect to the main page if data have been written well
if (isset($_POST["register"])){
    $database = fopen("registration_data.txt", "a");
    fwrite($database, $registration_data);
    fclose($database);
    header("Location: main.html");
    exit();
}

//redirects to login_form.html ----- works if <formnovalidate> atribute is written in login button tag
if (isset($_POST["login"])){
    header("Location: login_form.html");
    exit();
}

?>