// Dark mode toggle
const darkModeToggle = document.getElementById('dark-mode-toggle');
if (darkModeToggle) {
    darkModeToggle.addEventListener('click', function() {
        document.body.classList.toggle('dark');
    });
}

// Back button
const backButton = document.getElementById('back-button');
if (backButton) {
    backButton.addEventListener('click', function() {
        window.location.href = "../main/main.php";
    });
}
