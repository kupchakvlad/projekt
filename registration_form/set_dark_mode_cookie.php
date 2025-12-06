<?php

// v js v peremennoj data, mode=`${data}`, ${data} - peredajotsja
$new_mode = $_POST["mode"]

setcookie("mode", $new_mode, 0, '/');

?>