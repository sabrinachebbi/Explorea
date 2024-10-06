// Objet pour stocker l'index de la diapositive actuelle pour chaque hébergement
var slideIndex = {};

// Fonction pour initialiser le slideIndex pour chaque carrousel
function initSlides() {
    // Sélectionne tous les carrousels
    var carousels = document.querySelectorAll('.carousel');
    carousels.forEach(function(carousel) {
        var id = carousel.id.split('-')[1]; // Supposant que l'id est 'carousel-<accommodationId>'
        slideIndex[id] = 1; // Initialise l'index de la diapositive pour ce carrousel
        showSlides(id, slideIndex[id]);
    });
}

// Fonction pour avancer ou reculer les diapositives
function plusSlides(accommodationId, n) {
    slideIndex[accommodationId] += n;
    showSlides(accommodationId, slideIndex[accommodationId]);
}

// Fonction pour afficher la diapositive correcte
function showSlides(accommodationId, n) {
    var carousel = document.getElementById('carousel-' + accommodationId);
    var slides = carousel.getElementsByClassName('carousel-item');
    if (n > slides.length) { slideIndex[accommodationId] = 1; }
    if (n < 1) { slideIndex[accommodationId] = slides.length; }
    // Masque toutes les diapositives
    for (var i = 0; i < slides.length; i++) {
        slides[i].style.display = 'none';
    }
    // Affiche la diapositive actuelle
    slides[slideIndex[accommodationId] - 1].style.display = 'block';
}

// Initialise les diapositives lorsque la page est chargée
document.addEventListener('DOMContentLoaded', initSlides);
