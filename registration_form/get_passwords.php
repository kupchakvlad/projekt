<?php
// dlja js - proverka parolja na silu
header("Content-Type: text/plain; charset=utf-8");
echo file_get_contents("https://zwa.toad.cz/passwords.txt");
?>