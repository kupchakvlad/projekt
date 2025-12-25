<?php
session_start();

$show_login_error = false;
$login_error_text = '';

if (isset($_SESSION['login_error']) && $_SESSION['login_error'] != '') {
    $show_login_error = true;
    $login_error_text = $_SESSION['login_error'];
    unset($_SESSION['login_error']);
}

$name_value = '';
$email_value = '';

$name_has_error = false;
$email_has_error = false;
$password_has_error = false;
$confirm_has_error = false;

$name_reg_error = '';
$email_reg_error = '';
$password_reg_error = '';
$confirm_reg_error = '';

if (isset($_SESSION['registration_data'])) {
    $name_value = $_SESSION['registration_data']['name'] ?? '';
    $email_value = $_SESSION['registration_data']['email'] ?? '';
    unset($_SESSION['registration_data']);
}

$login_email_value = '';

if (!empty($email_value)) {
    $login_email_value = $email_value;
}

if (isset($_SESSION['last_login_email'])) {
    $login_email_value = $_SESSION['last_login_email'];
    unset($_SESSION['last_login_email']);
}

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
        // FIXED: Removed $registration_errors (already unset) and use direct checks
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