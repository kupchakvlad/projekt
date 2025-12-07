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
    document.cookie = `mode=${isDark ? 'dark' : 'light'}; path=/;`;
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

// VALIDATION FUNCTIONS
function validateForm() {
    let valid = true;
    const email_value = registration_email.value.trim();
    const password = registration_password.value.trim();
    const password_confirmation = registration_password_confirmation.value.trim();

    // EMAIL CHECK
    if (email_value.indexOf("@") === -1) {
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

    // PASSWORD LENGTH CHECK
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

    // PASSWORD MATCH CHECK
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

    return valid;
}
registration_form.addEventListener("submit", function (event) {
    event.preventDefault();

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

                registration_password.classList.add("password_error");

                if (!passwordStrengthMessage) {
                    passwordStrengthMessage = document.createElement("p");
                    passwordStrengthMessage.className = "password-error-message";
                    passwordStrengthMessage.textContent = "Password is too weak.";
                    registration_password_container.appendChild(passwordStrengthMessage);
                }


            } else {
                // PASSWORD IS STRONG

                registration_password.classList.remove("password_error");
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