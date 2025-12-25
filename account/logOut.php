<?php
session_start();
session_unset();
session_destroy();
header("Location: ../registration_form/registration_form.php");
exit;
?>