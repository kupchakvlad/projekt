<?php

header("Content-Type: text/plain; charset=utf-8");
echo file_get_contents("https://zwa.toad.cz/passwords.txt");
?>