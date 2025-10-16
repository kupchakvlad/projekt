<?php
ob_start();
session_start();

$REGISTRATION_DATABASE = "registration_database.txt";

//registration data from registration_form.html to database form -> tag(name)
$username_registration = trim($_POST["username_registration"]);
$email_registration = trim($_POST["email_registration"]);
$password_registration = password_hash($_POST["password_registration"], PASSWORD_DEFAULT);

//writes data to database
$registration_data = "$username_registration|$email_registration|$password_registration\n";

//writes data on click and redirect to the main page if data have been written well
if (isset($_POST["register_submit"])){
    file_put_contents($REGISTRATION_DATABASE, $registration_data, FILE_APPEND);
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

//after the button (name="login_submit") clicked
if (isset($_POST["login_submit"])){
    //checking the condition if the email and password are found in database
    $found = false;

    //taking inputs from the login_form/login_form.html
    $email_login = trim($_POST["email_login"]);
    $password_login = trim($_POST["password_login"]);

    //start database reading
    $database_reading = fopen("registration_database.txt", "r");

    //if server cant reach database it shows the messege
    if (!$database_reading) die("CANT OPEN DATABASE.");

    //trying to find the data we want untill the database is checked
    while (($line = fgets($database_reading)) !== false){

        //if it is there empty line in database continuing to go through
        if ($line === "") continue;

        //taking out all the unnecesarry signs
        $line = trim($line);

        //adding the data from the database that is separated by | sign to variables
        list($username_registration, $email_registration, $password_registration) = explode("|", $line);
        //taking out all the unnecesarry signs
        $email_registration = trim($email_registration);
        //checking clear email and password if the data from login_form.html is equal to email and hashed password from the database
        if ($email_login === trim($email_registration) && password_verify($password_login, $password_registration)){
            //if the data is equal to each other than variable found is true
            $found = true;
            //and we are going out of the loop
            break;
        }
    }

    //stops the reading of the database
    fclose($database_reading);

    //if the found variable is true
    if ($found) {
        //than we redirecting user to main.html
        header("Location: main.html");
        exit();
        //else he has to try to login once more
    } else {
        header("Location: login_form/login_form.html");
        exit();
    }
}
?>