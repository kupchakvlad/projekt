// Dark mode toggle
const darkModeToggle = document.getElementById('dark-mode-toggle');
const body = document.body;

darkModeToggle.addEventListener('click', function() {
    body.classList.toggle('dark');
});

const backButton = document.getElementById('back-button');
backButton.addEventListener('click', function() {
    window.location.href = "../main/main.html";
});

// Profile form submission
const profileForm = document.getElementById('profile-form');
const userName = document.getElementById('user-name');
const userEmail = document.getElementById('user-email');

profileForm.addEventListener('submit', function(e) {
    e.preventDefault();

    const firstName = document.getElementById('first-name').value;
    const lastName = document.getElementById('last-name').value;
    const email = document.getElementById('email').value;

    // Update user name display
    userName.textContent = `${firstName} ${lastName}`;

    // Update email display
    userEmail.textContent = email;
});