// LOGIN
const login_button = document.getElementById("login_button");
const login_email = document.getElementById("login_email");
const login_form = document.getElementById("login_form");

//REGISTRATION
const registration_button = document.getElementById("registration_button");
const registration_form = document.getElementById("registration_form");
const registration_email = document.getElementById("registration_email");
const registration_password = document.getElementById("registration_password");
const registration_email_container = document.getElementById("registration_email_container");
const registration_password_container = document.getElementById("registration_password_container");

//DARKMODE
const darkMode = document.getElementById("dark-mode-btn");


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


darkMode.addEventListener("click", () => {
    document.body.classList.toggle("dark-mode")
});

registration_form.addEventListener("submit", function(event) {
    let email_value = registration_email.value
    if (email_value.indexOf("@") === -1) {
        event.preventDefault();
        registration_email.classList.add("email_error");
    } else {
        registration_email.classList.remove("email_error");
    }
});


let passwordMessage = null;
registration_form.addEventListener("submit", function(event) {
    let password = registration_password.value;

    if (password.length < 8) {
        event.preventDefault();
        registration_password.classList.add("password_error");

        if (!passwordMessage) {
            passwordMessage = document.createElement("p");
            passwordMessage.className = "password-error-message";
            passwordMessage.textContent = "At least 8 characters.";
            registration_password_container.appendChild(passwordMessage);
        }

    } else {
        registration_password.classList.remove("password_error");

        if (passwordMessage) {
            passwordMessage.remove();
            passwordMessage = null;
        }
    }
});