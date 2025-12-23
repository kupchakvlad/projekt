// ===== Header (Dark Mode) =====
document.getElementById("dark-mode-toggle").addEventListener("click", () => {
    const isDark = document.body.classList.toggle("dark");
    const newMode = isDark ? 'dark' : 'light';
    const request = new XMLHttpRequest();
    request.open("POST", "../set_dark_mode_cookie.php", true);
    request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    request.send(`mode=${newMode}`);
});

// ===== Size (Размер) =====
const sizeSlider = document.getElementById("size-slider");
const sizeValue = document.getElementById("size-value");

function updateSize() {
  if (sizeSlider.value == "27") {
    sizeValue.textContent = "All";
  } else {
    sizeValue.textContent = sizeSlider.value;
  }
}

// Инициализация при загрузке
updateSize();
sizeSlider.addEventListener("input", updateSize);

// ===== Price (Цена) =====
const minPriceSlider = document.getElementById("min-price");
const maxPriceSlider = document.getElementById("max-price");
const minPriceInput = document.getElementById("min-price-input");
const maxPriceInput = document.getElementById("max-price-input");

// Функция синхронизации: из слайдера в инпут
function syncSliderToInput(slider, input) {
    input.value = slider.value;
}

// Функция синхронизации: из инпута в слайдер
function syncInputToSlider(input, slider) {
    slider.value = input.value;
}

// Слушатели для слайдеров
minPriceSlider.addEventListener("input", () => syncSliderToInput(minPriceSlider, minPriceInput));
maxPriceSlider.addEventListener("input", () => syncSliderToInput(maxPriceSlider, maxPriceInput));

// Слушатели для текстовых полей
minPriceInput.addEventListener("input", () => syncInputToSlider(minPriceInput, minPriceSlider));
maxPriceInput.addEventListener("input", () => syncInputToSlider(maxPriceInput, maxPriceSlider));

// ===== Season (Сезоны) =====
const seasonOptions = document.querySelectorAll(".season-option");
seasonOptions.forEach(option => {
  option.addEventListener("click", () => {
    seasonOptions.forEach(o => o.classList.remove("selected"));
    option.classList.add("selected");
    const radio = option.querySelector("input");
    if (radio) radio.checked = true;
  });
});




//Filter
function sendFilterRequest(page = 1) {

  const section = document.getElementById("products");
  if (section) {
  section.innerHTML = '<div class="loader">Loading products...</div>';
  }
  const search = document.getElementById("search").value;
  const size = sizeSlider.value;
  
  const minPrice = minPriceInput.value;
  const maxPrice = maxPriceInput.value;
  
  const seasonInput = document.querySelector('input[name="season"]:checked');
  const season = seasonInput ? seasonInput.value : '';
  
  // Собираем параметры
  const param = "search=" + encodeURIComponent(search) +
              "&size=" + size +
              "&min=" + minPrice +
              "&max=" + maxPrice +
              "&season=" + encodeURIComponent(season) +
              "&page=" + page;
                
  const url = "filter.php?" + param;

  const req = new XMLHttpRequest();

  req.onload = function () {
    if (req.status === 200) {
      // innerHTML используется, так как сервер возвращает готовую верстку карточек
      document.getElementById("products").innerHTML = req.responseText;
    } else {
      console.error("Error server:" + req.status);
    }
  };
  
  req.open('GET', url, true);
  req.send();

  console.log({ search, size, minPrice, maxPrice, season });
};

document.getElementById("apply-filters").addEventListener("click", () => sendFilterRequest(1));

//reset

document.getElementById("reset-filters").addEventListener("click", () => {
  document.getElementById("search").value = "";

  sizeSlider.value = 27;
  updateSize();

  minPriceInput.value = 0;
  minPriceSlider.value = 0;
  maxPriceInput.value = 100000;
  maxPriceSlider.value = 100000;

  const seasonOptions = document.querySelectorAll(".season-option");
  seasonOptions.forEach(option => {
    option.classList.remove("selected");
    const radio = option.querySelector("input");
    if (radio) radio.checked = false;
  });
  sendFilterRequest(1);
});

document.addEventListener("DOMContentLoaded", () => {
  sendFilterRequest(1);
});

document.addEventListener("click", function(e) {
  if (e.target.dataset.page) {
    const page = parseInt(e.target.getAttribute("data-page"));
    sendFilterRequest(page);
  }
});
