let deleteButton = document.querySelectorAll(".delete_button");

deleteButton.forEach(function(button) {
    button.addEventListener("click", (event) => {
        if (!confirm("You sure you want to delete this user?")) {
            event.preventDefault();
        }
    });
})
