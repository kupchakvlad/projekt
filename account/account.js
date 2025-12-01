// Dark mode toggle
const darkModeToggle = document.getElementById('dark-mode-toggle');

darkModeToggle.addEventListener('click', function() {
    document.body.classList.toggle('dark');
});

const backButton = document.getElementById('back-button');

// Profile form submission
const profileForm = document.getElementById('profile-form');
const userName = document.getElementById('user-name');
const userEmail = document.getElementById('user-email');

profileForm.addEventListener('submit', function(e) {
    e.preventDefault();

    const firstName = document.getElementById('first-name').value;
    const lastName = document.getElementById('last-name').value;
    const email = document.getElementById('email').value;
});