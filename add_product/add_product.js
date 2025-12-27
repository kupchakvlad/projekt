const slider = document.getElementById("slider");
const output = document.getElementById("size-value");
const darkModeBtn = document.getElementById("dark-mode-btn");
const BackBtn = document.querySelector(".back-to-main");
const form = document.getElementById("add-product-form");
const photoInput = document.getElementById("Photo");
const priceInput = document.getElementById("ProductPrice");
const nameInput = document.getElementById("ProductName");

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

form.addEventListener("submit", (event) => {
    let valid = true;
    if (photoInput.files.length === 0) {
        valid = false;
        alert("Please upload at least one photo.");
    }

    const price = priceInput.value.trim();
    if (!price || isNaN(price) || parseFloat(price) <= 0) {
        valid = false;
        alert("Please enter a valid price greater than 0.");
    }

    if (nameInput.value.trim() === '') {
    valid = false;
    alert("Please enter a product name");
}

    if (!valid) {
        event.preventDefault();
    }
});