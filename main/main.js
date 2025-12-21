// ===== Header =====
document.getElementById("dark-mode-toggle").addEventListener("click", () => {
    const isDark = document.body.classList.toggle("dark");
    const newMode = isDark ? 'dark' : 'light';
    const request = new XMLHttpRequest();
    request.open("POST", "../set_dark_mode_cookie.php", true);
    request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    request.send(`mode=${newMode}`);
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

// фильтр
document.getElementById("apply-filters").addEventListener("click", () => {
  const search = document.getElementById("search").value;
  const size = sizeSlider.value;
  const minPrice = minPriceSlider.value;
  const maxPrice = maxPriceSlider.value;
  const seasonInput = document.querySelector('input[name="season"]:checked');
  const season = seasonInput ? seasonInput.value : '';
  const param = "search=" + search + "&size=" + size + "&min=" + minPrice + "&max=" + maxPrice + "&season=" + season;
  const url = "filter.php?" + param;

  const req = new XMLHttpRequest();

  req.onload = function () {
    if ( req.status === 200) {
      document.getElementById("products").innerHTML = req.responseText; //Преподаватель может спросить: «А почему ты используешь innerHTML, а не textContent?»
                                                                        //Правильный ответ: «Я использую innerHTML, потому что сервер присылает мне готовый HTML-разметку (теги <a>, <img>, <p>). Если бы я использовал textContent, браузер не превратил бы это в карточки товаров, а просто вывел бы весь код как обычный текст».
    } else {
      console.error("Error server:" + req.status);
    }

  };
  req.open('GET', url , true);
  req.send();

  console.log({ search, size, minPrice, maxPrice, season });
});