document.addEventListener("DOMContentLoaded", () => {

    const slider = document.getElementById("slider");
    const output = document.getElementById("size-value");
    const darkModeBtn = document.getElementById("dark-mode-btn");
    const BackBtn = document.querySelector(".back-to-main")
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



    input.addEventListener('change', () => {
            fileList.innerHTML = ''; // Clear previous list

            const files = Array.from(input.files); // Convert FileList to Array

            files.forEach(file => {
                const div = document.createElement('div');
                div.className = 'preview-container';
                div.textContent = `${file.name} (${Math.round(file.size / 1024)} KB)`;
                fileList.appendChild(div);
        });
    });

});