<?php
/**
 * @file set_dark_mode_cookie.php
 *
 * @brief Backend skript pro nastavení cookie s preferencí tmavého režimu.
 *
 * Tento soubor je volán přes AJAX (POST požadavek) z různých JavaScript souborů
 * (např. account.js, add_product.js, main.js, registration_form.js)
 * v okamžiku, kdy uživatel přepíná mezi tmavým a světlým režimem.
 *
 * Přijímá nový režim ("dark" nebo "light") v POST parametru `mode`,
 * nastavuje cookie s názvem `mode` s platností do konce relace prohlížeče
 * a s cestou platnou pro celý web (`/`).
 * Skript nevrací žádný výstup (očekává se tichá odpověď pro AJAX).
 *
 * @param string $_POST["mode"] Očekávané hodnoty: "dark" nebo "light"
 *
 * @return void Skript nic nevypisuje, pouze přidává hlavičku Set-Cookie.
 */

$new_mode = $_POST["mode"];

/**
 * Nastavení cookie pro tmavý režim.
 *
 * Cookie platí do zavření prohlížeče (session cookie).
 */
setcookie("mode", $new_mode, 0, '/');
?>