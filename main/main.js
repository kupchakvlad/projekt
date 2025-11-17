const DarkMode = document.getElementById("dark-mode-toggle");
const AddProductLink = document.getElementById("add-product");
const AccountLink = document.getElementById("account");


DarkMode.addEventListener("click", () => {
    document.body.classList.toggle("dark");
});

AddProductLink.addEventListener("click", () => {
    window.location.href = "../add_product/add_product.html";
});

AccountLink.addEventListener("click", () => {
    window.location.href = "../account/account.html";
});