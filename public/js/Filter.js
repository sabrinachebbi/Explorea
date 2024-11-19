function toggleFilterModal() {
    // Récupère l'élément HTML avec l'ID 'FilterForm'
    const modal = document.getElementById('FilterForm');
    // Si l'élément est affiché, on le cache
    if (modal.style.display === 'flex') {
        modal.style.display = 'none';
    } else {
        // Sinon, on l'affiche
        modal.style.display = 'flex';
    }
}