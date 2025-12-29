<?php
/**
 * @brief Tento skript slouží k odhlášení uživatele ze systému.
 *
 * @file logOut.php
 *
 * Nejprve se spustí session, poté se odstraní všechna
 * session data a session se kompletně zničí.
 * Nakonec je uživatel přesměrován na registrační / přihlašovací stránku.
 */
session_start();
session_unset();
session_destroy();
header("Location: ../registration_form/registration_form.php");
exit;
?>