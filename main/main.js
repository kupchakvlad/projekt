document.addEventListener("DOMContentLoaded", () => {
    const accountBtn = document.querySelector(".acc-menu > a");
    const drop = document.querySelector(".drop");

    accountBtn.addEventListener("click", (e) => {
        e.preventDefault();
        drop.classList.toggle("show")
    });

    window.addEventListener("click", (e) => {
        if (!drop.contains(e.target) && !accountBtn.contains(e.target)) {
            drop.classList.remove("show")
        }
    });
});