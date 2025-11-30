let byPassListener = false;

registration_form.addEventListener("submit", function(event) {
    if (byPassListener) return; // prevent recursion

    event.preventDefault();

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

    if (!valid) return; // stop if errors

    // --- PASSWORD STRENGTH CHECK ---
    let request = new XMLHttpRequest();
    request.open("GET", "https://zwa.toad.cz/passwords.txt", true);
    request.onload = function() {
        let words = request.responseText.split("\n");

        if (words.includes(password)) {
            // Weak password
            registration_password.classList.add("password_error");

            if (!passwordStrengthMessage) {
                passwordStrengthMessage = document.createElement("p");
                passwordStrengthMessage.className = "password-error-message";
                passwordStrengthMessage.textContent = "Password is too weak.";
                registration_password_container.appendChild(passwordStrengthMessage);
            }
        } else {
            // Password ok → submit form
            registration_password.classList.remove("password_error");
            if (passwordStrengthMessage) {
                passwordStrengthMessage.remove();
                passwordStrengthMessage = null;
            }

            byPassListener = true; // prevent recursion
            registration_form.submit();
        }
    };
    request.send();
});
