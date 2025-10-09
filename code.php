<?php

$REGISTRATION_DATABASE = "registration_data.txt";

//registration data from registration_form.html form -> tag(name)
$username = $_POST["username"];
$email = $_POST["email"];
$password = password_hash($_POST["password"], PASSWORD_DEFAULT);

//writes data to database
$registration_data = "$username  |  $email  |  $password\n";
$database = fopen("registration_data.txt", "a");
fwrite($database, $registration_data);
fclose($database);

//redirects user to main page after registration. change file name to redirect user to another file
header("Location: registration_form.html");
exit();

?>