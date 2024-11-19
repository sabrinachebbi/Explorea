// Fonction pour initialiser le carrousel
function initCarousel() {
    // Sélectionner toutes les diapositives
    const slides = document.querySelectorAll('.carousel-item');
    let currentSlide = 0; // Index de la diapositive actuelle

    // Afficher uniquement la première diapositive au démarrage
    slides.forEach((slide, index) => {
        slide.style.display = index === currentSlide ? 'block' : 'none';
    });

    // Fonction pour changer de diapositive
    function showSlide(direction) {
        // Masquer la diapositive actuelle
        slides[currentSlide].style.display = 'none';

        // Calculer la prochaine diapositive
        currentSlide = (currentSlide + direction + slides.length) % slides.length;

        // Afficher la nouvelle diapositive
        slides[currentSlide].style.display = 'block';
    }

    // Écouter les clics sur les boutons "Précédent" et "Suivant"
    document.querySelector('.prev').addEventListener('click', () => showSlide(-1));
    document.querySelector('.next').addEventListener('click', () => showSlide(1));
}

// Initialiser le carrousel une fois le contenu chargé
document.addEventListener('DOMContentLoaded', initCarousel);
