const slider = document.getElementById("slider");
const output = document.getElementById("size-value");
const darkModeBtn = document.getElementById("dark-mode-btn");
const BackBtn = document.querySelector(".back-to-main");

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


const fileInput = document.getElementById("Photo");
const fileList  = document.getElementById("file-list");


let selectedFiles = [];
fileInput.addEventListener("change", () => {

    let newFiles = fileInput.files;

    for (let i = 0; i < newFiles.length; i++) {
        let file = newFiles[i];

        // Check if file is already in selectedFiles
        let alreadyAdded = false;
        for (let j = 0; j < selectedFiles.length; j++) {
            if (selectedFiles[j].name === file.name && selectedFiles[j].size === file.size) {
                alreadyAdded = true;
                break;
            }
        }

        if (!alreadyAdded) {
            selectedFiles.push(file);

            let li = document.createElement("li");
            li.textContent = file.name + " (" + Math.round(file.size / 1024) + " KB)";
            fileList.appendChild(li);
        } else {
            alert("File \"" + file.name + "\" is already in the list!");
        }
    }
});