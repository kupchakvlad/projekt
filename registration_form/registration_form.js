const login_button = document.getElementById("login_button");
const login_form = document.getElementById("login_form");

const registration_button = document.getElementById("registration_button");
const registration_form = document.getElementById("registration_form");

const registration_email = document.getElementById("registration_email");
const registration_password = document.getElementById("registration_password");
const registration_password_confirmation = document.getElementById("registration_password_confirmation");

const registration_email_container = document.getElementById("registration_email_container");
const registration_password_container = document.getElementById("registration_password_container");

const darkModeButton = document.getElementById("dark-mode-btn");

darkModeButton.addEventListener("click", () => {
    const isNowDark = document.body.classList.toggle("dark-mode");
    const mode = isNowDark ? 'dark' : 'light';

    const request = new XMLHttpRequest();
    request.open("POST", "../set_dark_mode_cookie.php", true);
    request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    request.send("mode=" + mode);
});

login_button.addEventListener("click", () => {
    login_button.classList.add("active");
    login_form.classList.add("active");

    registration_button.classList.remove("active");
    registration_form.classList.remove("active");
});

registration_button.addEventListener("click", () => {
    registration_button.classList.add("active");
    registration_form.classList.add("active");

    login_button.classList.remove("active");
    login_form.classList.remove("active");
});


let emailErrorMessage = null;
let passwordLengthError = null;
let passwordMatchError = null;
let passwordWeakError = null;

function showErrorMessage(container, oldMessage, text, inputField) {
    let message = oldMessage;

    if (!message) {
        message = document.createElement("p");
        message.className = "password-error-message";
        container.appendChild(message);
    }

    message.textContent = text;
    inputField.classList.add("error-input");
    return message;
}

function removeErrorMessage(container, message, inputField) {
    if (message && message.parentNode) {
        message.remove();
    }
    inputField.classList.remove("error-input");
    return null;
}

registration_email.addEventListener("blur", () => {
    const email = registration_email.value.trim();

    if (email === "") {
        emailErrorMessage = showErrorMessage(
            registration_email_container,
            emailErrorMessage,
            "Email is required.",
            registration_email
        );
    } else if (email.indexOf("@") === -1) {
        emailErrorMessage = showErrorMessage(
            registration_email_container,
            emailErrorMessage,
            "Email must contain @ symbol.",
            registration_email
        );
    } else {
        emailErrorMessage = removeErrorMessage(registration_email_container, emailErrorMessage, registration_email);
    }
});

registration_password.addEventListener("blur", () => {
    const password = registration_password.value.trim();

    // First: check length
    if (password.length > 0 && password.length < 8) {
        passwordLengthError = showErrorMessage(
            registration_password_container,
            passwordLengthError,
            "Password must be at least 8 characters.",
            registration_password
        );
    } else {
        passwordLengthError = removeErrorMessage(registration_password_container, passwordLengthError, registration_password);
    }

    if (password.length >= 8) {
        const request = new XMLHttpRequest();
        request.open("GET", "get_passwords.php", true);

        request.onload = function () {
            if (request.status === 200) {
                const weakPasswords = request.responseText.split("\n").map(line => line.trim());

                if (weakPasswords.includes(password)) {
                    passwordWeakError = showErrorMessage(
                        registration_password_container,
                        passwordWeakError,
                        "Password is too weak.",
                        registration_password
                    );
                } else {
                    passwordWeakError = removeErrorMessage(registration_password_container, passwordWeakError, registration_password);
                }
            }
        };
        request.send();
    } else {
        passwordWeakError = removeErrorMessage(registration_password_container, passwordWeakError, registration_password);
    }
});
registration_password_confirmation.addEventListener("blur", () => {
    const password = registration_password.value.trim();
    const confirm = registration_password_confirmation.value.trim();

    if (confirm !== "" && password !== confirm) {
        passwordMatchError = showErrorMessage(
            registration_password_container,
            passwordMatchError,
            "Passwords do not match.",
            registration_password_confirmation
        );
        registration_password.classList.add("error-input");
    } else if (password === confirm && confirm !== "") {
        passwordMatchError = removeErrorMessage(registration_password_container, passwordMatchError, registration_password_confirmation);
        registration_password.classList.remove("error-input");
    }
});