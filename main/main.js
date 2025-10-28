const DarkMode = document.getElementById("dark-mode-toggle")

DarkMode.addEventListener("click", () => {
    document.body.classList.toggle("dark");
});