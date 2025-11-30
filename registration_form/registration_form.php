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

//REGISTRATION VALIDATION
$errorMessages = [];

if (isset($_POST['registration_submit'])) {

    // REGISTRATION DATA
    $registration_name = trim($_POST['registration_name']);
    $registration_email = trim($_POST['registration_email']);
    $registration_password = trim($_POST['registration_password']);
    $registration_password_confirmation = trim($_POST['registration_password_confirmation']);

    $hashed_password = password_hash($registration_password, PASSWORD_DEFAULT);


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

    if (empty($errorMessages)) {
        // komanda dla vtavki dannych v mysql tablicu
        $insert_user_query = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
        // sozdanie mesta podkljuccenija dla vozmoznosti obrabotat' komandu
        $statement = mysqli_stmt_init($connection);
        // svjazyvajetsja s db i oboznacaet parametry dannych kotoryje budut vstavleny
        if (!mysqli_stmt_prepare($statement, $insert_user_query)) {
            die("Prepare failed: " . mysqli_error($connection));
        }

        if (!mysqli_stmt_bind_param($statement, "sss", $registration_name, $registration_email, $hashed_password)) {
            die("Bind param failed: " . mysqli_stmt_error($statement));
        }

        if (!mysqli_stmt_execute($statement)) {
            die("Execute failed: " . mysqli_stmt_error($statement));
        }

//            header("Location: ../main/main.html");
//            exit;
//        } else {
//            $errorMessages[] = "Database error";
//        }
//    }
    }// else {
////    header("Location: ../main/main.html");
////    exit;
}
?>