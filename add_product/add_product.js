const slider = document.getElementById("slider");
const output = document.getElementById("size-value");
const darkModeBtn = document.getElementById("dark-mode-btn");
const BackBtn = document.querySelector(".back-to-main")


output.textContent = slider.value;
slider.addEventListener("input", () => {
    output.textContent = slider.value;
});


darkModeBtn.addEventListener("click", () => {
    document.body.classList.toggle("dark");
    const mode = body.classList.contains("dark-mode") ? "dark" : "light";

    // send mode to PHP with AJAX
    const request = new XMLHttpRequest();
    request.open("POST", "set_dark_mode_cookie.php", true);
    request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    request.send(`mode=${encodeURIComponent(mode)}`);
});

BackBtn.addEventListener("click", () => {
    window.location.href = "../main/main.php";
});