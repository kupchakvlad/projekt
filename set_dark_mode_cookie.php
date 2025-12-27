<?php
if (!isset($_SESSION["user_id"])) {
    header("Location: /registration_form/registration_form.php");
    exit;
}


$new_mode = $_POST["mode"];

setcookie("mode", $new_mode, 0, '/');

?>