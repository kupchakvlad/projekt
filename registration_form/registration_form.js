// LOGIN
const login_button = document.getElementById("login_button");
const login_form = document.getElementById("login_form");

// REGISTRATION
const registration_button = document.getElementById("registration_button");
const registration_form = document.getElementById("registration_form");
const registration_email = document.getElementById("registration_email");
const registration_password = document.getElementById("registration_password");
const registration_password_confirmation = document.getElementById("registration_password_confirmation");
const registration_email_container = document.getElementById("registration_email_container");
const registration_password_container = document.getElementById("registration_password_container");

// DARK MODE
const darkMode = document.getElementById("dark-mode-btn");
darkMode.addEventListener("click", () => {
    const isDark = document.body.classList.toggle("dark-mode");
    const newMode = isDark ? 'dark' : 'light';
    const request = new XMLHttpRequest();
    request.open("POST", "../set_dark_mode_cookie.php", true);
    request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    request.send(`mode=${newMode}`);
});

// SWITCH TO LOGIN
login_button.addEventListener("click", () => {
    login_button.classList.add("active");
    login_form.classList.add("active");
    registration_button.classList.remove("active");
    registration_form.classList.remove("active");
});

// SWITCH TO REGISTRATION
registration_button.addEventListener("click", () => {
    registration_button.classList.add("active");
    registration_form.classList.add("active");
    login_button.classList.remove("active");
    login_form.classList.remove("active");
});

// ERROR MESSAGE NODES
let emailMessage = null;
let passwordMessage_8 = null;
let passwordMessage_confirmation = null;
let passwordStrengthMessage = null;

// Helper functions for showing/clearing errors
function showError(container, messageNode, text, input) {
    if (!messageNode) {
        messageNode = document.createElement("p");
        messageNode.className = "password-error-message";
        container.appendChild(messageNode);
    }
    messageNode.textContent = text;
    input.classList.add("error-input");
    return messageNode;
}

function clearError(container, messageNode, input) {
    if (messageNode && messageNode.parentNode) {
        messageNode.remove();
    }
    input.classList.remove("error-input");
    return null;
}

// REAL-TIME VALIDATION ON BLUR (when cursor leaves the field)

// Email validation
registration_email.addEventListener("blur", () => {
    const email_value = registration_email.value.trim();

    if (email_value === "") {
        emailMessage = showError(registration_email_container, emailMessage, "Email is required.", registration_email);
    } else if (email_value.indexOf("@") === -1) {
        emailMessage = showError(registration_email_container, emailMessage, "Email is not entered properly.", registration_email);
    } else {
        emailMessage = clearError(registration_email_container, emailMessage, registration_email);
    }
});

// Password length validation
registration_password.addEventListener("blur", () => {
    const password = registration_password.value.trim();

    if (password.length > 0 && password.length < 8) {
        passwordMessage_8 = showError(registration_password_container, passwordMessage_8, "At least 8 characters.", registration_password);
    } else {
        passwordMessage_8 = clearError(registration_password_container, passwordMessage_8, registration_password);
    }

    // Re-check confirmation when password changes
    registration_password_confirmation.dispatchEvent(new Event("blur"));
});

// Password confirmation match
registration_password_confirmation.addEventListener("blur", () => {
    const password = registration_password.value.trim();
    const password_confirmation = registration_password_confirmation.value.trim();

    if (password_confirmation !== "" && password !== password_confirmation) {
        passwordMessage_confirmation = showError(
            registration_password_container,
            passwordMessage_confirmation,
            "The entered passwords do not match.",
            registration_password_confirmation
        );
        registration_password.classList.add("error-input"); // also highlight first password field
    } else if (password === password_confirmation) {
        passwordMessage_confirmation = clearError(registration_password_container, passwordMessage_confirmation, registration_password_confirmation);
        registration_password.classList.remove("error-input");
    }
});

// VALIDATION FUNCTIONS (kept exactly as before, only small adjustments for consistency)
function validateForm() {
    let valid = true;
    const email_value = registration_email.value.trim();
    const password = registration_password.value.trim();
    const password_confirmation = registration_password_confirmation.value.trim();

    // EMAIL CHECK
    if (email_value === "" || email_value.indexOf("@") === -1) {
        valid = false;
        registration_email.classList.add("error-input");
    }

    // PASSWORD LENGTH CHECK
    if (password.length < 8) {
        valid = false;
        registration_password.classList.add("error-input");
    }

    // PASSWORD MATCH CHECK
    if (password !== password_confirmation) {
        valid = false;
        registration_password.classList.add("error-input");
        registration_password_confirmation.classList.add("error-input");
    }

    return valid;
}

registration_form.addEventListener("submit", function (event) {
    event.preventDefault();

    // Trigger blur events to show any missing errors immediately
    registration_email.dispatchEvent(new Event("blur"));
    registration_password.dispatchEvent(new Event("blur"));
    registration_password_confirmation.dispatchEvent(new Event("blur"));

    if (!validateForm()) {
        return; // STOP IF BASIC VALIDATION FAIL
    }

    const password = registration_password.value.trim();

    // PASSWORD STRENGHT VALIDATION WITH AJAX
    const request = new XMLHttpRequest();

    request.open("GET", "get_passwords.php", true); // TRUE - AJAX. ---- TAKES EVERY PASSWORD FROM GET_PASSWORDS.PHP -> ZWA.TOAD.CZ/PASSWORDS.TXT

    request.onload = function () {
        if (request.status === 200) {

            const weakList = request.responseText.split("\n").map(w => w.trim()); // WRITES EVERY PASSWORD FROM ZWA.TOAD.CZ HERE

            if (weakList.includes(password)) {
                // PASSWORD IS WEAK

                registration_password.classList.add("error-input");

                if (!passwordStrengthMessage) {
                    passwordStrengthMessage = document.createElement("p");
                    passwordStrengthMessage.className = "password-error-message";
                    passwordStrengthMessage.textContent = "Password is too weak.";
                    registration_password_container.appendChild(passwordStrengthMessage);
                }

            } else {
                // PASSWORD IS STRONG

                registration_password.classList.remove("error-input");
                if (passwordStrengthMessage) {
                    passwordStrengthMessage.remove();
                    passwordStrengthMessage = null;
                }

                registration_form.submit();
            }
        } else {
            alert("Unable to verify password strength (Server Error). Please try again.");
        }
    };

    request.send();
});