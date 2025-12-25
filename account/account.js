// Dark mode toggle
const darkModeToggle = document.getElementById('dark-mode-toggle');
darkModeToggle.addEventListener('click', function() {
    const isDark = document.body.classList.toggle("dark");
    const newMode = isDark ? 'dark' : 'light';
    const request = new XMLHttpRequest();
    request.open("POST", "../set_dark_mode_cookie.php", true);
    request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    request.send(`mode=${newMode}`);
});

// Back button
const backButton = document.getElementById('back-button');
backButton.addEventListener('click', function() {
    window.location.href = "../main/main.php";
});
