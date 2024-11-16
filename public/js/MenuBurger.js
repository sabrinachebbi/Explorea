document.addEventListener("DOMContentLoaded", function() {
    console.log("DOM fully loaded and parsed");
    const menuBurger = document.getElementById("menuBurger");
    const navbar = document.getElementById("navbar");

    if (!menuBurger) {
        console.error("Element with id 'menuBurger' not found");
        return;
    }

    if (!navbar) {
        console.error("Element with id 'navbar' not found");
        return;
    }

    menuBurger.addEventListener("click", function() {
        console.log("Menu burger clicked");
        navbar.classList.toggle("active");
        console.log("Navbar classes:", navbar.className);
    });
});
