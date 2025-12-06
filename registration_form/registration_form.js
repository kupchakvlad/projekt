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

function sendDarkMode(value) {
    const request = new XMLHttpRequest();
    request.open("POST", "set_dark_mode_cookie.php", true);
    request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    request.send(`mode=${encodeURIComponent(value)}`);
}

darkMode.addEventListener("click", () => {
    document.body.classList.toggle("dark-mode");
    sendDarkMode(document.body.classList.contains("dark-mode") ? "dark" : "light");
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

// VALIDATION + SUBMISSION
let byPassListener = false;
let isCheckingPasswordStrength = false;

// Error message nodes
let emailMessage = null;
let passwordMessage_8 = null;
let passwordMessage_confirmation = null;
let passwordStrengthMessage = null;

registration_form.addEventListener("submit", function(event) {
    console.log("Form submit event triggered");

    // If already validated, let browser submit normally
    if (byPassListener) return true;

    event.preventDefault();
    console.log("Validation started — preventing default");

    const password = registration_password.value.trim();
    const password_confirmation = registration_password_confirmation.value.trim();
    const email_value = registration_email.value.trim();
    let valid = true;

    // -------- EMAIL CHECK --------
    function email_checker() {
        const emailPattern = /^\S+@\S+\.\S+$/;

        if (!emailPattern.test(email_value)) {
            valid = false;
            registration_email.classList.add("email_error");

            if (!emailMessage) {
                emailMessage = document.createElement("p");
                emailMessage.className = "password-error-message";
                emailMessage.textContent = "Email is not entered properly.";
                registration_email_container.appendChild(emailMessage);
            }
        } else {
            registration_email.classList.remove("email_error");
            if (emailMessage) {
                emailMessage.remove();
                emailMessage = null;
            }
        }
    }

    // -------- PASSWORD LENGTH --------
    function password_length_checker() {
        if (password.length < 8) {
            valid = false;
            registration_password.classList.add("password_error");

            if (!passwordMessage_8) {
                passwordMessage_8 = document.createElement("p");
                passwordMessage_8.className = "password-error-message";
                passwordMessage_8.textContent = "At least 8 characters.";
                registration_password_container.appendChild(passwordMessage_8);
            }
        } else {
            registration_password.classList.remove("password_error");
            if (passwordMessage_8) {
                passwordMessage_8.remove();
                passwordMessage_8 = null;
            }
        }
    }

    // -------- PASSWORD MATCH --------
    function password_confirmation_checker() {
        if (password !== password_confirmation) {
            valid = false;
            registration_password.classList.add("password_error");
            registration_password_confirmation.classList.add("password_error");

            if (!passwordMessage_confirmation) {
                passwordMessage_confirmation = document.createElement("p");
                passwordMessage_confirmation.className = "password-error-message";
                passwordMessage_confirmation.textContent = "The entered passwords do not match.";
                registration_password_container.appendChild(passwordMessage_confirmation);
            }
        } else {
            registration_password_confirmation.classList.remove("password_error");
            if (passwordMessage_confirmation) {
                passwordMessage_confirmation.remove();
                passwordMessage_confirmation = null;
            }
        }
    }

    // RUN CHECKS
    email_checker();
    password_length_checker();
    password_confirmation_checker();

    if (!valid) {
        console.log("Validation failed — stopping");
        return;
    }

    // -------- PASSWORD STRENGTH CHECK --------
    if (isCheckingPasswordStrength) return;

    isCheckingPasswordStrength = true;
    console.log("Checking password strength...");

    const request = new XMLHttpRequest();
    request.open("GET", "passwords.txt", true); // FIXED SAME-ORIGIN URL

    request.onload = function() {
        console.log("Password check completed:", request.status);
        isCheckingPasswordStrength = false;

        if (request.status === 200) {
            const weakList = request.responseText.split("\n").map(w => w.trim());

            if (weakList.includes(password)) {
                console.log("Weak password detected — stopping submit");
                registration_password.classList.add("password_error");

                if (!passwordStrengthMessage) {
                    passwordStrengthMessage = document.createElement("p");
                    passwordStrengthMessage.className = "password-error-message";
                    passwordStrengthMessage.textContent = "Password is too weak.";
                    registration_password_container.appendChild(passwordStrengthMessage);
                }

            } else {
                console.log("Password strong — submitting now");

                if (passwordStrengthMessage) {
                    passwordStrengthMessage.remove();
                    passwordStrengthMessage = null;
                }

                byPassListener = true;
                registration_form.submit(); // SEND TO PHP
            }

        } else {
            alert("Unable to verify password strength. Please try again.");
        }
    };

    request.onerror = function() {
        isCheckingPasswordStrength = false;
        alert("Network error while checking password strength.");
    };

    request.send();
});
