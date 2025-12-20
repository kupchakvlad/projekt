document.addEventListener("DOMContentLoaded", () => {
    const gallery = document.getElementById("productGallery");
    const scrollLeftBtn = document.getElementById("scrollLeft");
    const scrollRightBtn = document.getElementById("scrollRight");

    if (gallery && scrollLeftBtn && scrollRightBtn) {
        const scrollAmount = 310; 

        scrollLeftBtn.addEventListener("click", () => {
            gallery.scrollBy({ left: -scrollAmount, behavior: "smooth" });
        });

        scrollRightBtn.addEventListener("click", () => {
            gallery.scrollBy({ left: scrollAmount, behavior: "smooth" });
        });
    }

    // Buy button
    const buyBtn = document.getElementById("buyBtn");
    const buyMessage = document.getElementById("buyMessage");

    if (buyBtn && buyMessage) {
        buyBtn.addEventListener("click", () => {
            buyBtn.classList.add("hidden");
            buyMessage.classList.add("visible");
        });
    }
});
