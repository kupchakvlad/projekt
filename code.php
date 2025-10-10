<?php

$REGISTRATION_DATABASE = "registration_data.txt";

//registration data from registration_form.html to database form -> tag(name)
$username_registration = $_POST["username_registration"];
$email_registration = $_POST["email_registration"];
$password_registration = password_hash($_POST["password_registration"], PASSWORD_DEFAULT);

//writes data to database
$registration_data = "$username_registration  |  $email_registration  |  $password_registration\n";

if (isset($_POST["register"])){
    $database = fopen("registration_data.txt", "a");
    fwrite($database, $registration_data);
    fclose($database);
}

if (isset($_POST["login"])){
    header("Location: login_form.html");
    exit();
}

//redirects user to main page after registration. change file name to redirect user to another file
header("Location: main.html");
exit();

?>