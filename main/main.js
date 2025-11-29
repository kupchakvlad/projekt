// ===== Header =====
document.getElementById("dark-mode-toggle").addEventListener("click", () => {
  document.body.classList.toggle("dark");
});

document.getElementById("add-product").addEventListener("click", () => {
  window.location.href = "../add_product/add_product.html";
});

document.getElementById("account").addEventListener("click", () => {
  window.location.href = "../account/account.html";
});

// ===== Size =====
const sizeSlider = document.getElementById("size-slider");
const sizeValue = document.getElementById("size-value");

function updateSize() {
  sizeValue.textContent = sizeSlider.value;
}

updateSize();
sizeSlider.addEventListener("input", updateSize);

// ===== Price =====
const minPriceSlider = document.getElementById("min-price");
const maxPriceSlider = document.getElementById("max-price");
const minPriceValue = document.getElementById("min-price-value");
const maxPriceValue = document.getElementById("max-price-value");

function updatePrice(slider, valueEl) {
  valueEl.textContent = slider.value;
}

updatePrice(minPriceSlider, minPriceValue);
updatePrice(maxPriceSlider, maxPriceValue);

minPriceSlider.addEventListener("input", () => updatePrice(minPriceSlider, minPriceValue));
maxPriceSlider.addEventListener("input", () => updatePrice(maxPriceSlider, maxPriceValue));

const seasonOptions = document.querySelectorAll(".season-option");
seasonOptions.forEach(option => {
  option.addEventListener("click", () => {
    seasonOptions.forEach(o => o.classList.remove("selected"));
    option.classList.add("selected");
    option.querySelector("input").checked = true;
  });
});

document.getElementById("apply-filters").addEventListener("click", () => {
  const search = document.getElementById("search").value.toLowerCase();
  const size = parseInt(sizeSlider.value);
  const minPrice = parseInt(minPriceSlider.value);
  const maxPrice = parseInt(maxPriceSlider.value);
  const season = document.querySelector('input[name="season"]:checked').value;

  console.log({ search, size, minPrice, maxPrice, season });
});
