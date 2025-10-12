<?php

$REGISTRATION_DATABASE = "registration_database.txt";

//registration data from registration_form.html to database form -> tag(name)
$username_registration = $_POST["username_registration"];
$email_registration = $_POST["email_registration"];
$password_registration = password_hash($_POST["password_registration"], PASSWORD_DEFAULT);

//writes data to database
$registration_data = "$username_registration  |  $email_registration  |  $password_registration\n";

//writes data on click and redirect to the main page if data have been written well
if (isset($_POST["register_submit"])){
    $database = fopen($REGISTRATION_DATABASE, "a");
    fwrite($database, $registration_data);
    fclose($database);
    header("Location: main.html");
    exit();
}

//redirects to login_form.html from registration_form.html ----- works if <formnovalidate> atribute is written in login button tag
if (isset($_POST["login"])){
    header("Location: login_form/login_form.html");
    exit();
}
//redirects to registration_form.html from login_form.html if the button is clicked ---- works if <formnovalidate> atribute is written in register button tag
if (isset($_POST["register"])){
    header("Location: registration_form/registration_form.html");
    exit();
}

?>