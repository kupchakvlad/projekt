// Dark mode toggle
const darkModeToggle = document.getElementById('dark-mode-toggle');
if (darkModeToggle) {
    darkModeToggle.addEventListener('click', function() {
        document.body.classList.toggle('dark');
        const mode = body.classList.contains("dark") ? "dark" : "light";

        // send mode to PHP with AJAX
        const request = new XMLHttpRequest();
        request.open("POST", "set_dark_mode_cookie.php", true);
        request.setRequestHeader("Content-Type", "application/x-www-registration_form-urlencoded");
        request.send(`mode=${encodeURIComponent(mode)}`);
    });
}

// Back button
const backButton = document.getElementById('back-button');
if (backButton) {
    backButton.addEventListener('click', function() {
        window.location.href = "../main/main.php";
    });
}
