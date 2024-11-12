document.addEventListener("DOMContentLoaded", function() {
    const menuBurger = document.getElementById("menuBurger");
    const navbar = document.getElementById("navbar");

    menuBurger.addEventListener("click", function() {
        navbar.classList.toggle("active");
    });
})