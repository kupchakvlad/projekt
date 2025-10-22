const login_button = document.getElementById("login_button");
const registration_button = document.getElementById("registration_button");
const registration_form = document.getElementById("registration_form");
const login_form = document.getElementById("login_form");
const darkMode = document.getElementById("dark-mode-btn");

function showLogin(){
    login_button.classList.add("active");
    login_form.classList.add("active");
    registration_button.classList.remove("active");
    registration_form.classList.remove("active");
}

function showRegistration(){
    registration_button.classList.add("active");
    registration_form.classList.add("active");
    login_button.classList.remove("active");
    login_form.classList.remove("active");
}

darkMode.addEventListener("click", () => {
    document.body.classList.toggle("dark-mode")
});