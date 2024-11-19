// / Exécute le code une fois que la page est complètement chargée
document.addEventListener("DOMContentLoaded", function() {
    // Récupère le bouton du menu burger
    const menuBurger = document.getElementById("menuBurger");

    // Récupère la barre de navigation (navbar)
    const navbar = document.getElementById("navbar");

    // Vérifie si le menu burger et la navbar existent
    if (menuBurger && navbar) {
        // Ajoute un événement "click" au menu burger
        menuBurger.addEventListener("click", function() {
            // Ajoute ou enlève la classe "active" à la navbar
            navbar.classList.toggle("active");
        });
    }
});