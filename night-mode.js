const body = document.body;
const toggleBtn = document.getElementById('dark-mode-toggle');

// Load saved theme from localStorage
if (localStorage.getItem('theme') === 'dark') {
  body.classList.add('dark-mode');
}

// Toggle dark mode on button click
toggleBtn.addEventListener('click', () => {
  body.classList.toggle('dark-mode');
  localStorage.setItem(
    'theme',
    body.classList.contains('dark-mode') ? 'dark' : 'light'
  );
});
