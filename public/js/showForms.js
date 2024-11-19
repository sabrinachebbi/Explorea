document.addEventListener('DOMContentLoaded', function () {
    // Récupère les boutons pour choisir entre logement et activité
    const accommodationBtn = document.getElementById('accommodation-btn');
    const activityBtn = document.getElementById('activity-btn');

    // Récupère les formulaires pour logement et activité
    const accommodationForm = document.getElementById('accommodation-form');
    const activityForm = document.getElementById('activity-form');

    // Affiche uniquement le formulaire de logement au départ
    accommodationForm.style.display = 'block'; // Le formulaire de logement est visible
    activityForm.style.display = 'none';      // Le formulaire d'activité est caché

    // Quand on clique sur le bouton "Ajouter un Logement"
    accommodationBtn.addEventListener('click', function () {
        accommodationForm.style.display = 'block'; // Affiche le formulaire de logement
        activityForm.style.display = 'none';      // Cache le formulaire d'activité
    });

    // Quand on clique sur le bouton "Ajouter une Activité"
    activityBtn.addEventListener('click', function () {
        accommodationForm.style.display = 'none';  // Cache le formulaire de logement
        activityForm.style.display = 'block';      // Affiche le formulaire d'activité
    });
});
