<?php

/**
 * @brief Vrací obsah souboru
 * @brief s běžnými/slabými hesly (https://zwa.toad.cz/passwords.txt) ve formátu plain text.
 * @brief Používá se na klientovi (registration_form.js) pro rychlou klientskou kontrolu,
 * @brief zda zadané heslo není příliš slabé/běžné.
 * @brief Výstup je ve formátu plain text, každý řádek je jedno slabé heslo.
 *
 * @file get_passwords.php
 *
 * @return void Vypíše obsah vzdáleného souboru passwords.txt a ukončí skript.
 * @see registration.php Server-side validace také kontroluje slabá hesla (pro bezpečnost).
 *
 * @see registration_form.js Použití v AJAX požadavku pro kontrolu slabých hesel.
 */

header("Content-Type: text/plain; charset=utf-8");
echo file_get_contents("https://zwa.toad.cz/passwords.txt");
?>