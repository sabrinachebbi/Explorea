// Quand on clique sur l'icône de notification
document.getElementById('notification-icon').addEventListener('click', function() {
    // Récupère le menu de notifications
    const menu = document.getElementById('notification-menu');

    // Si le menu est affiché, on le cache
    if (menu.style.display === 'block') {
        menu.style.display = 'none';
    } else {
        // Sinon, on l'affiche
        menu.style.display = 'block';
    }
});