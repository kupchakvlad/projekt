// LOGIN
const login_button = document.getElementById("login_button");
const login_email = document.getElementById("login_email");
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


//TURNING DARK MODE ON
darkMode.addEventListener("click", () => {
    document.body.classList.toggle("dark-mode")
});



// ------------------------CHECKINGS-------------------------------------


//MESSAGES
let passwordMessage_confirmation = null;
let passwordMessage_8 = null;
let emailMessage = null;
let passwordStrengthMessage = null;


/*
FIRST LISTENER CHECKS:
        { EMAIL AND WRITES THAT EMAIL IS NOT VALID IN THE BOTTOM, MAKES BORDER RED AS WELL },
        { PASSWORD LENGTH AND WRITES ABOUT THIS IN THE BOTTOM MAKES BORDER RED AS WELL },
        { PASSWORD CONFIRMATION FROM THE SECOND PASSWORD INPUT AND WRITES OUT THAT PASSWORDS DOES NOT MATCH IN THE BOTTOM AND MAKES BORDER RED AS WELL }
*/
registration_form.addEventListener("submit", function(event) {
    let password = registration_password.value;
    let password_confirmation = registration_password_confirmation.value;
    let email_value = registration_email.value
    let valid = true;


    //EMAIL CHEKER
    if (email_value.indexOf("@") == -1) {
        valid = false;
        registration_email.classList.add("email_error");

        if (!emailMessage) {
            emailMessage = document.createElement("p");
            emailMessage.className = "password-error-message";
            emailMessage.textContent = "Email is not entered properly.";
            registration_password_container.appendChild(emailMessage);
        }

    } else {
        registration_email.classList.remove("email_error");
        if (emailMessage){
            emailMessage.remove();
            emailMessage = null;
        }
    }

    //PASSWORD LENGTH CHEKER
    if (password.length < 8) {
        valid = false;
        registration_password.classList.add("password_error");

        if (!passwordMessage_8) {
            passwordMessage_8 = document.createElement("p");
            passwordMessage_8.className = "password-error-message";
            passwordMessage_8.textContent = "At least 8 characters.";
            registration_password_container.appendChild(passwordMessage_8);
        }
    } else if (passwordMessage_8) {
            passwordMessage_8.remove();
            passwordMessage_8 = null;
            registration_password.classList.remove("password_error");
    }


    //PASSWORD CONFIRMATOR
    if (password != password_confirmation) {
        
        registration_password.classList.add("password_error");
        registration_password_confirmation.classList.add("password_error");
        valid = false;

        if (!passwordMessage_confirmation) {
            passwordMessage_confirmation = document.createElement("div");
            passwordMessage_confirmation.className = "password-error-message";
            passwordMessage_confirmation.textContent = "The entered passwords do not match.";
            registration_password_container.appendChild(passwordMessage_confirmation);
        }
        
    } else {

        registration_password_confirmation.classList.remove("password_error");

     if (passwordMessage_confirmation){
            passwordMessage_confirmation.remove();
            passwordMessage_confirmation = null;
        }
    }

    if (!valid) event.preventDefault();
});


/* 
SECOND LISTENER CHECKS STRENGTH OF PASSWORD FROM ZWA.TOAD.CZ SERVER, CHECKS:
    { IF USER PASSWORD IS IN THE LIST IN SERVER, WRITES IN THE BOTTOM THAT PASSWORD IS WEAK, MAKES BORDER RED }
    { BLOKUJE ODESILANI DAT NA SERVER POKUD UZIVATELSKE HESLO JE NA SERVERU, V OKAMZIKU KDYZ NENI TAK ODESILA DATA NA SERVER -
    - POMOCI [registration_form.submit();] }
*/
registration_form.addEventListener("submit", function(event) {
    event.preventDefault();
    password_strength_connection();
});

function password_strength_connection(event) {
    let request = new XMLHttpRequest();
    request.open("GET", "https://zwa.toad.cz/passwords.txt", true);
    request.send();
    request.addEventListener("load", control_of_password_strength_answer)
}

function control_of_password_strength_answer(event) {
    
    let words = event.target.responseText.split("\n");
    
    if (words.includes(registration_password.value)) {

        registration_password.classList.add("password_error");
        
        if (!passwordStrengthMessage) {
            passwordStrengthMessage = document.createElement("p");
            passwordStrengthMessage.className = "password-error-message";
            passwordStrengthMessage.textContent = "Password is too weak.";
            registration_password_container.appendChild(passwordStrengthMessage);
        }
    } else {

        registration_password.classList.remove("password_error");

        if (passwordStrengthMessage) {
            passwordStrengthMessage.remove();
            passwordStrengthMessage = null;
        }

        registration_form.submit();

    }
}