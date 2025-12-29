<?php
/**
 * @brief Frontend formulář pro registraci a přihlášení uživatelů.
 * Tento soubor generuje HTML stránku s přepínači mezi přihlašovacím a registračním formulářem.
 * Zpracovává data a errory z session (např. předvyplnění polí, zobrazení chyb),
 * a zajišťuje bezpečné escapování výstupů pomocí htmlspecialchars.
 * Formuláře odesílají data na backend skripty (registration.php a login.php).
 *
 * @file registration_form.php
 *
 * @see registration.php Pro backend zpracování registrace.
 * @see login.php Pro backend zpracování přihlášení.
 * @see registration_form.js Pro JavaScript logiku (přepínání formulářů, validace).
 * @see registration_form.css Pro styly formulářů.
 */
session_start();

/**
 * Proměnné pro zobrazení chyb přihlášení.
 * Kontroluje session pro 'login_error' a nastavuje flags a text pro zobrazení chybové zprávy.
 *
 * @var bool $show_login_error Flag pro zobrazení chybové zprávy přihlášení.
 * @var string $login_error_text Text chybové zprávy přihlášení.
 */
$show_login_error = false;
$login_error_text = '';

if (isset($_SESSION['login_error']) && $_SESSION['login_error'] != '') {
    $show_login_error = true;
    $login_error_text = $_SESSION['login_error'];
    unset($_SESSION['login_error']);
}

/**
 * Výchozí hodnoty pro registrační formulář.
 * Inicializuje prázdné hodnoty pro jméno a email.
 *
 * @var string $name_value Předvyplněné jméno z session.
 * @var string $email_value Předvyplněný email z session.
 */
$name_value = '';
$email_value = '';

/**
 * Flags pro chyby v registračním formuláři.
 * Nastavují se na základě 'registration_errors' v session pro označení chybných polí.
 *
 * @var bool $name_has_error Flag pro chybu v poli jména.
 * @var bool $email_has_error Flag pro chybu v poli emailu.
 * @var bool $password_has_error Flag pro chybu v poli hesla.
 * @var bool $confirm_has_error Flag pro chybu v poli potvrzení hesla.
 */
$name_has_error = false;
$email_has_error = false;
$password_has_error = false;
$confirm_has_error = false;

/**
 * Texty chyb pro registrační formulář.
 * Specifické chybové zprávy pro jednotlivá pole.
 *
 * @var string $name_reg_error Chybová zpráva pro jméno.
 * @var string $email_reg_error Chybová zpráva pro email.
 * @var string $password_reg_error Chybová zpráva pro heslo.
 * @var string $confirm_reg_error Chybová zpráva pro potvrzení hesla.
 */
$name_reg_error = '';
$email_reg_error = '';
$password_reg_error = '';
$confirm_reg_error = '';

/**
 * Předvyplnění registračního formuláře z session.
 * Pokud existuje 'registration_data' v session, načte hodnoty a unsetne session.
 */
if (isset($_SESSION['registration_data'])) {
    $name_value = $_SESSION['registration_data']['name'] ?? '';
    $email_value = $_SESSION['registration_data']['email'] ?? '';
    unset($_SESSION['registration_data']);
}

/**
 * Předvyplnění emailu pro přihlašovací formulář.
 * Načte z 'login_data', 'registration_data' nebo 'last_login_email' v session.
 *
 * @var string $login_email_value Předvyplněný email pro přihlášení.
 */
$login_email_value = '';

if (isset($_SESSION['login_data'])) {
    $login_email_value = $_SESSION['login_data']['email'] ?? '';
    unset($_SESSION['login_data']);
}

if (!empty($email_value)) {
    $login_email_value = $email_value;
}

if (isset($_SESSION['last_login_email'])) {
    $login_email_value = $_SESSION['last_login_email'];
    unset($_SESSION['last_login_email']);
}

/**
 * Zpracování chyb registračního formuláře z session.
 * Pokud existuje 'registration_errors' v session, nastaví flags a chybové texty pro pole.
 */
if (isset($_SESSION['registration_errors'])) {
    $errors = $_SESSION['registration_errors'];
    unset($_SESSION['registration_errors']);

    if (in_array('name', $errors)) {
        $name_has_error = true;
        $name_reg_error = 'Name is required.';
    }
    if (in_array('email', $errors)) {
        $email_has_error = true;
        $email_reg_error = 'Please enter a valid email address.';
    }
    if (in_array('password', $errors)) {
        $password_has_error = true;
        $password_reg_error = 'Password must be at least 8 characters and not too common.';
    }
    if (in_array('confirm', $errors)) {
        $confirm_has_error = true;
        $password_has_error = true;
        $confirm_reg_error = 'Passwords do not match.';
    }
}

/**
 * Nastavení tmavého režimu na základě cookie.
 * Pokud cookie 'mode' je 'dark', přidá třídu 'dark-mode' k body.
 *
 * @var string $dark_mode_class Třída pro tmavý režim ('dark-mode' nebo prázdná).
 */
$body_has_dark_mode = false;
if (isset($_COOKIE['mode']) && $_COOKIE['mode'] === 'dark') {
    $body_has_dark_mode = true;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <link rel="stylesheet" href="registration_form.css"/>
    <script src="registration_form.js" defer></script>
</head>

<body <?php if ($body_has_dark_mode) { echo 'class="dark-mode"'; } ?>>

<button type="button" id="dark-mode-btn">Dark Mode</button>

<div class="form-container">

    <div class="top-buttons">
        <button id="registration_button" class="registration-form active">Registration</button>
        <button id="login_button" class="login-form">Login</button>
    </div>

    <form action="login.php" method="POST" id="login_form">

        <label for="login_email">Enter your email: <span class="required">*</span></label>
        <input type="text" id="login_email" name="login_email" value="<?php echo htmlspecialchars($login_email_value); ?>" required>

        <label for="login_password">Enter your password: <span class="required">*</span></label>
        <input type="password" id="login_password" name="login_password" required>

        <?php
        if ($show_login_error) {
            echo '<div class="login-error">' . htmlspecialchars($login_error_text) . '</div>';
        }
        ?>

        <input type="submit" id="login_submit" name="login_submit" class="submit" value="Sign-in">
    </form>

    <form action="registration.php" method="POST" id="registration_form" class="active">

        <?php
        if (!empty($name_reg_error) || !empty($email_reg_error) || !empty($password_reg_error) || !empty($confirm_reg_error)) {
            echo '<div class="error-box">';
            echo '<strong>Please fix these errors:</strong>';
            echo '<ul>';
            if ($name_reg_error) {
                echo '<li>' . htmlspecialchars($name_reg_error) . '</li>';
            }
            if ($email_reg_error) {
                echo '<li>' . htmlspecialchars($email_reg_error) . '</li>';
            }
            if ($password_reg_error) {
                echo '<li>' . htmlspecialchars($password_reg_error) . '</li>';
            }
            if ($confirm_reg_error) {
                echo '<li>' . htmlspecialchars($confirm_reg_error) . '</li>';
            }
            echo '</ul>';
            echo '</div>';
        }
        ?>

        <label for="registration_name">Enter name: <span class="required">*</span></label>
        <input type="text"
               id="registration_name"
               name="registration_name"
               value="<?php echo htmlspecialchars($name_value); ?>"
                <?php
                if ($name_has_error) {
                    echo 'class="error-input"';
                }
                ?>
               required>

        <div id="registration_email_container">
            <label for="registration_email">Enter email: <span class="required">*</span></label>
            <input type="text"
                   id="registration_email"
                   name="registration_email"
                   value="<?php echo htmlspecialchars($email_value); ?>"
                    <?php
                    if ($email_has_error) {
                        echo 'class="error-input"';
                    }
                    ?>
                   required>
        </div>

        <div id="registration_password_container">

            <label for="registration_password">Enter password: <span class="required">*</span></label>
            <input type="password"
                   id="registration_password"
                   name="registration_password"
                    <?php
                    if ($password_has_error) {
                        echo 'class="error-input"';
                    }
                    ?>
                   required>

            <label for="registration_password_confirmation">Enter password once again: <span class="required">*</span></label>
            <input type="password"
                   id="registration_password_confirmation"
                   name="registration_password_confirmation"
                    <?php
                    if ($confirm_has_error) {
                        echo 'class="error-input"';
                    }
                    ?>
                   required>
        </div>

        <input type="submit" name="registration_submit" class="submit" id="registration_submit" value="Sign-up">

    </form>

</div>

</body>
</html>