<?php
session_start();

// 1. LOGIN ERROR
$show_login_error = false;
$login_error_text = '';

if (isset($_SESSION['login_error']) && $_SESSION['login_error'] != '') {
    $show_login_error = true;
    $login_error_text = $_SESSION['login_error'];
    unset($_SESSION['login_error']);
}

// 2. REGISTRATION OLD DATA AND ERRORS
$name_value = '';
$email_value = '';

$name_has_error = false;
$email_has_error = false;
$password_has_error = false;
$confirm_has_error = false;

$reg_error_1 = '';
$reg_error_2 = '';
$reg_error_3 = '';
$reg_error_4 = '';

if (isset($_SESSION['registration_data'])) {
    $name_value  = $_SESSION['registration_data']['name']  ?? '';
    $email_value = $_SESSION['registration_data']['email'] ?? '';
    unset($_SESSION['registration_data']);
}

if (isset($_SESSION['registration_errors'])) {
    $errors = $_SESSION['registration_errors'];
    unset($_SESSION['registration_errors']);

    if (in_array('name', $errors)) {
        $name_has_error = true;
        $reg_error_1 = 'Name is required.';
    }
    if (in_array('email', $errors)) {
        $email_has_error = true;
        $reg_error_2 = 'Please enter a valid email address.';
    }
    if (in_array('password', $errors)) {
        $password_has_error = true;
        $reg_error_3 = 'Password must be at least 8 characters and not too common.';
    }
    if (in_array('confirm', $errors)) {
        $confirm_has_error = true;
        $password_has_error = true; // also highlight password field
        $reg_error_4 = 'Passwords do not match.';
    }
}

// 3. DARK MODE
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

    <!-- ====================== LOGIN FORM ====================== -->
    <form action="login.php" method="POST" id="login_form">

        <label for="login_email">Enter your email: <span class="required">*</span></label>
        <input type="text" id="login_email" name="login_email" required>

        <label for="login_password">Enter your password: <span class="required">*</span></label>
        <input type="password" id="login_password" name="login_password" required>

        <?php if ($show_login_error): ?>
            <div class="login-error">
                <?php echo htmlspecialchars($login_error_text); ?>
            </div>
        <?php endif; ?>

        <input type="submit" id="login_submit" name="login_submit" class="submit" value="Sign-in">
    </form>

    <!-- ====================== REGISTRATION FORM ====================== -->
    <form action="registration.php" method="POST" id="registration_form" class="active">

        <!-- NEW: ERROR BOX SHOWN AFTER SUBMISSION -->
        <?php if (!empty($registration_errors) || !empty($reg_error_1) || !empty($reg_error_2) || !empty($reg_error_3) || !empty($reg_error_4)): ?>
            <div class="error-box">
                <strong>Please fix these errors:</strong>
                <ul>
                    <?php if ($reg_error_1): ?><li><?php echo $reg_error_1; ?></li><?php endif; ?>
                    <?php if ($reg_error_2): ?><li><?php echo $reg_error_2; ?></li><?php endif; ?>
                    <?php if ($reg_error_3): ?><li><?php echo $reg_error_3; ?></li><?php endif; ?>
                    <?php if ($reg_error_4): ?><li><?php echo $reg_error_4; ?></li><?php endif; ?>
                </ul>
            </div>
        <?php endif; ?>

        <label for="registration_name">Enter name: <span class="required">*</span></label>
        <input type="text"
               id="registration_name"
               name="registration_name"
               value="<?php echo htmlspecialchars($name_value); ?>"
               <?php if ($name_has_error): ?>class="error-input"<?php endif; ?>
               required>

        <div id="registration_email_container">
            <label for="registration_email">Enter email: <span class="required">*</span></label>
            <input type="text"
                   id="registration_email"
                   name="registration_email"
                   value="<?php echo htmlspecialchars($email_value); ?>"
                   <?php if ($email_has_error): ?>class="error-input"<?php endif; ?>
                   required>
        </div>

        <div id="registration_password_container">

            <label for="registration_password">Enter password: <span class="required">*</span></label>
            <input type="password"
                   id="registration_password"
                   name="registration_password"
                   <?php if ($password_has_error): ?>class="error-input"<?php endif; ?>
                   required>

            <label for="registration_password_confirmation">Enter password once again: <span class="required">*</span></label>
            <input type="password"
                   id="registration_password_confirmation"
                   name="registration_password_confirmation"
                   <?php if ($confirm_has_error): ?>class="error-input"<?php endif; ?>
                   required>

            <!-- Individual error messages under password (optional, can remove if you prefer only the box) -->
            <?php if ($reg_error_1 || $reg_error_2 || $reg_error_3 || $reg_error_4): ?>
                <div class="error-messages">
                    <?php if ($reg_error_1): ?><p class="password-error-message"><?php echo $reg_error_1; ?></p><?php endif; ?>
                    <?php if ($reg_error_2): ?><p class="password-error-message"><?php echo $reg_error_2; ?></p><?php endif; ?>
                    <?php if ($reg_error_3): ?><p class="password-error-message"><?php echo $reg_error_3; ?></p><?php endif; ?>
                    <?php if ($reg_error_4): ?><p class="password-error-message"><?php echo $reg_error_4; ?></p><?php endif; ?>
                </div>
            <?php endif; ?>

        </div>

        <input type="submit"
               name="registration_submit"
               class="submit"
               id="registration_submit"
               value="Sign-up">

    </form>

</div>

</body>
</html>