let slideIndex = {};

// Fonction pour initialiser le slideIndex pour chaque carrousel
function initSlides() {
    // Sélectionne tous les éléments avec la classe 'carousel'
    const carousels = document.querySelectorAll('.carousel');

    carousels.forEach(function(carousel) {
        const id = carousel.id.split('-')[1]; // Récupère l'ID unique de chaque carrousel
        slideIndex[id] = 1; // Initialise l'index de la diapositive à 1 pour chaque carrousel
        showSlides(id, slideIndex[id]); // Affiche la première diapositive
    });
}

// Fonction pour avancer ou reculer les diapositives
function plusSlides(accommodationId, n) {
    // Vérifie si l'ID du carrousel existe dans slideIndex
    if (slideIndex.hasOwnProperty(accommodationId)) {
        slideIndex[accommodationId] += n; // Incrémente ou décrémente l'index en fonction de 'n'
        showSlides(accommodationId, slideIndex[accommodationId]); // Affiche la nouvelle diapositive
    }
}

// Fonction pour afficher la diapositive correcte
function showSlides(accommodationId, n) {
    const carousel = document.getElementById('carousel-' + accommodationId);

    // Vérifie que le carrousel existe
    if (carousel) {
        const slides = carousel.getElementsByClassName('carousel-item');

        // Si on dépasse le nombre de diapositives, on revient à la première
        if (n > slides.length) { slideIndex[accommodationId] = 1; }

        // Si on passe en dessous de 1, on va à la dernière diapositive
        if (n < 1) { slideIndex[accommodationId] = slides.length; }

        // Masque toutes les diapositives
        for (let i = 0; i < slides.length; i++) {
            slides[i].style.display = 'none';
        }

        // Affiche la diapositive actuelle (slideIndex est 1-based)
        slides[slideIndex[accommodationId] - 1].style.display = 'block';
    }
}

// Initialise les diapositives lorsque la page est chargée
document.addEventListener('DOMContentLoaded', initSlides);

