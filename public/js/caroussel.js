function moveSlide(button, direction) {
    const carousel = button.parentElement.querySelector('.carousel-inner');
    const items = carousel.querySelectorAll('.carousel-item');
    const totalItems = items.length;

    // Trouver l'élément actuellement actif
    let currentIndex = Array.from(items).findIndex(item => item.classList.contains('active'));
    items[currentIndex].classList.remove('active'); // Supprime l'état "active"

    // Calculer le nouvel index
    let nextIndex = (currentIndex + direction + totalItems) % totalItems;

    // Ajouter l'état "active" au nouvel élément
    items[nextIndex].classList.add('active');
}