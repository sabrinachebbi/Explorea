// Objet pour stocker l'index de chaque carrousel
let slideIndices = {};

// Fonction pour initialiser les carrousels
function initCarousels() {
    // Sélectionner tous les carrousels sur la page
    const carousels = document.querySelectorAll('.carousel');

    carousels.forEach(function(carousel) {
        // Obtenir l'ID unique du carrousel (par exemple, 'carousel-1')
        const carouselId = carousel.id;

        // Initialiser l'index à 1 pour chaque carrousel
        slideIndices[carouselId] = 1;

        // Afficher la première diapositive du carrousel
        showSlide(carouselId, slideIndices[carouselId]);
    });
}

// Fonction pour afficher une diapositive spécifique d'un carrousel
function showSlide(carouselId, n) {
    // Sélectionner le carrousel en utilisant son ID
    const carousel = document.getElementById(carouselId);

    // Obtenir toutes les diapositives de ce carrousel
    const slides = carousel.getElementsByClassName('carousel-item');

    // Vérifier si l'index dépasse les limites et le corriger si nécessaire
    if (n > slides.length) {
        slideIndices[carouselId] = 1;
    }
    if (n < 1) {
        slideIndices[carouselId] = slides.length;
    }

    // Masquer toutes les diapositives
    for (let i = 0; i < slides.length; i++) {
        slides[i].style.display = 'none';
    }

    // Afficher la diapositive actuelle
    slides[slideIndices[carouselId] - 1].style.display = 'block';
}

// Fonction pour changer de diapositive
function plusSlides(carouselId, n) {
    // Incrémenter ou décrémenter l'index du carrousel
    showSlide(carouselId, slideIndices[carouselId] += n);
}

// Initialiser les carrousels une fois le contenu chargé
document.addEventListener('DOMContentLoaded', initCarousels);
