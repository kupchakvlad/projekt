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


    input.addEventListener('change', (e) => {
        console.log('Change event triggered!');
        console.log('Input files:', input.files);
        console.log('Files length:', input.files.length);
        
        fileList.innerHTML = '';
        const files = Array.from(input.files);
        
        console.log('Files array:', files);
        
        if (files.length === 0) {
            fileList.innerHTML = '<p>No files selected</p>';
        }
        
        files.forEach((file, index) => {
            console.log(`File ${index}:`, file.name);
            const div = document.createElement('div');
            div.className = 'preview-container';
            div.textContent = file.name;
            div.style.padding = '10px';
            div.style.marginBottom = '5px';
            div.style.backgroundColor = '#f0f0f0';
            div.style.borderRadius = '5px';
            fileList.appendChild(div);
            console.log('Div added to fileList');
        });
        
        console.log('FileList innerHTML:', fileList.innerHTML);
    });
});