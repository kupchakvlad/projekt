// LOGIN
const login_button = document.getElementById("login_button");
const login_form = document.getElementById("login_form");

//REGISTRATION
const registration_button = document.getElementById("registration_button");
const registration_form = document.getElementById("registration_form");
const registration_email = document.getElementById("registration_email");
const registration_password = document.getElementById("registration_password");
const registration_password_confirmation = document.getElementById("registration_password_confirmation");
const registration_email_container = document.getElementById("registration_email_container");
const registration_password_container = document.getElementById("registration_password_container");

//DARKMODE
const darkMode = document.getElementById("dark-mode-btn");

function sendDarkMode(value) {
    const request = new XMLHttpRequest();
    request.open('POST', 'set_dark_mode_cookie.php', true);
    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    const data = `mode=${encodeURIComponent(value)}`;

    request.onerror = function() {
        console.error('Failed to save dark mode preference');
    };

    request.send(data);
}

darkMode.addEventListener("click", () => {
    document.body.classList.toggle("dark-mode");
    const isDarkMode = document.body.classList.contains('dark-mode') ? 'dark' : 'light';
    sendDarkMode(isDarkMode);
});

//SWITCHING ON LOGIN
login_button.addEventListener("click", () => {
    login_button.classList.add("active");
    login_form.classList.add("active");
    registration_button.classList.remove("active");
    registration_form.classList.remove("active");
});

//SWITCHING ON REGISTRATION
registration_button.addEventListener("click", () => {
    registration_button.classList.add("active");
    registration_form.classList.add("active");
    login_button.classList.remove("active");
    login_form.classList.remove("active");
});

let byPassListener = false;
let emailMessage = null;
let passwordMessage_8 = null;
let passwordMessage_confirmation = null;
let passwordStrengthMessage = null;
let isCheckingPasswordStrength = false;

registration_form.addEventListener("submit", function(event) {
    console.log("Form submit event triggered");

    if (byPassListener) {
        console.log("byPassListener is true - allowing form to submit");
        return;
    }

    event.preventDefault();
    console.log("Form submission prevented - starting validation");

    let password = registration_password.value;
    let password_confirmation = registration_password_confirmation.value;
    let email_value = registration_email.value;
    let valid = true;

    // --- VALIDATION FUNCTIONS ---
    function email_checker() {
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
    }

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

    function password_confirmation_checker() {
        if (password !== password_confirmation) {
            valid = false;
            registration_password.classList.add("password_error");
            registration_password_confirmation.classList.add("password_error");

            if (!passwordMessage_confirmation) {
                passwordMessage_confirmation = document.createElement("div");
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

    // Run validations
    email_checker();
    password_length_checker();
    password_confirmation_checker();

    console.log("Validation result - valid:", valid);

    if (!valid) {
        console.log("Validation failed - stopping");
        return;
    }

    if (isCheckingPasswordStrength) {
        console.log("Password check already in progress - stopping");
        return;
    }

    isCheckingPasswordStrength = true;
    console.log("Starting password strength check...");

    // --- PASSWORD STRENGTH CHECK ---
    let request = new XMLHttpRequest();
    request.open("GET", "https://zwa.toad.cz/passwords.txt", true);

    request.onload = function() {
        console.log("Password check completed. Status:", request.status);
        isCheckingPasswordStrength = false;

        if (request.status === 200) {
            let words = request.responseText.split("\n").map(word => word.trim());
            console.log("Password list loaded. Total passwords:", words.length);
            console.log("Checking if password is weak...");
            console.log("Is password in weak list?", words.includes(password));

            if (words.includes(password)) {
                console.log("PASSWORD IS WEAK - NOT SUBMITTING");
                // Weak password
                registration_password.classList.add("password_error");

                if (!passwordStrengthMessage) {
                    passwordStrengthMessage = document.createElement("p");
                    passwordStrengthMessage.className = "password-error-message";
                    passwordStrengthMessage.textContent = "Password is too weak.";
                    registration_password_container.appendChild(passwordStrengthMessage);
                }
            } else {
                console.log("PASSWORD IS STRONG - SUBMITTING FORM NOW");
                // Strong password
                registration_password.classList.remove("password_error");
                if (passwordStrengthMessage) {
                    passwordStrengthMessage.remove();
                    passwordStrengthMessage = null;
                }

                byPassListener = true;
                console.log("Setting byPassListener to true and submitting...");
                registration_form.submit();
            }
        } else {
            console.error('Failed to check password strength, status:', request.status);
            alert('Unable to verify password strength. Please try again.');
        }
    };

    request.onerror = function() {
        console.log("NETWORK ERROR OCCURRED");
        isCheckingPasswordStrength = false;
        console.error('Network error while checking password strength');
        alert('Network error. Please check your connection and try again.');
    };

    console.log("Sending password check request to server...");
    request.send();
});