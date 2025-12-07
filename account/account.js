// Dark mode toggle
const darkModeToggle = document.getElementById('dark-mode-toggle');
darkModeToggle.addEventListener('click', function() {
    const isDark = document.body.classList.toggle("dark");
    document.cookie = `mode=${isDark ? 'dark' : 'light'}; path=/;`;
});

// Back button
const backButton = document.getElementById('back-button');
if (backButton) {
    backButton.addEventListener('click', function() {
        window.location.href = "../main/main.php";
    });
}
