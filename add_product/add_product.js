const slider = document.getElementById("slider");
const output = document.getElementById("size-value");
const darkModeBtn = document.getElementById("dark-mode-btn");
const BackBtn = document.querySelector(".back-to-main")


output.textContent = slider.value;
slider.addEventListener("input", () => {
    output.textContent = slider.value;
});


darkModeBtn.addEventListener("click", () => {
    const isDark = document.body.classList.toggle("dark");
    document.cookie = `mode=${isDark ? 'dark' : 'light'}; path=/;`;
});

BackBtn.addEventListener("click", () => {
    window.location.href = "../main/main.php";
});