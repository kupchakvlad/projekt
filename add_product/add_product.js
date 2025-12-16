document.addEventListener("DOMContentLoaded", () => {

    const slider = document.getElementById("slider");
    const output = document.getElementById("size-value");
    const darkModeBtn = document.getElementById("dark-mode-btn");
    const BackBtn = document.querySelector(".back-to-main");
    const input = document.getElementById('Photo');
    const fileList = document.getElementById('file-list');

    output.textContent = slider.value;
    slider.addEventListener("input", () => {
        output.textContent = slider.value;
    });


    darkModeBtn.addEventListener("click", () => {
        const isDark = document.body.classList.toggle("dark");
        const newMode = isDark ? 'dark' : 'light';
        const request = new XMLHttpRequest();
        request.open("POST", "../set_dark_mode_cookie.php", true);
        request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        request.send(`mode=${newMode}`);
    });

    BackBtn.addEventListener("click", () => {
        window.location.href = "../main/main.php";
    });
});

window.addEventListener('load', () => {
    const input = document.getElementById('Photo');
    const fileList = document.getElementById('file-list');
    
    if (input && fileList) {
        input.addEventListener('change', function() {
            fileList.innerHTML = '';
            for (let i = 0; i < this.files.length; i++) {
                const div = document.createElement('div');
                div.className = 'preview-container';
                div.textContent = this.files[i].name;
                fileList.appendChild(div);
            }
        });
    }
});