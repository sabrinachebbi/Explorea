document.addEventListener('DOMContentLoaded', function () {
    // Sélectionner les éléments HTML
    const accommodationBtn = document.getElementById('accommodation-btn');
    const activityBtn = document.getElementById('activity-btn');
    const accommodationForm = document.getElementById('accommodation-form');
    const activityForm = document.getElementById('activity-form');

    // Afficher le formulaire de logement par défaut
    accommodationForm.style.display = 'block';
    activityForm.style.display = 'none';

    // Gestion du clic sur le bouton "Ajouter un Logement"
    accommodationBtn.addEventListener('click', function () {
        accommodationForm.style.display = 'block';
        activityForm.style.display = 'none';
    });

    // Gestion du clic sur le bouton "Ajouter une Activité"
    activityBtn.addEventListener('click', function () {
        accommodationForm.style.display = 'none';
        activityForm.style.display = 'block';
    });
});
