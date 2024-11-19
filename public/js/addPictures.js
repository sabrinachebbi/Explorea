// Sélectionne tous les boutons ayant la classe "add_pictures" et ajoute un  d'événement "click"
document.querySelectorAll('.add_pictures').forEach(btn => {
    btn.addEventListener("click", addFormToCollection); // Appelle la fonction addFormToCollection au clic
});

// Fonction appelée lorsqu'on clique sur un bouton pour ajouter un formulaire à la collection
function addFormToCollection(e) {
    // Récupère le conteneur de la collection en utilisant la classe spécifiée dans le bouton cliqué
    const collectionHolder = document.querySelector('.' + e.currentTarget.dataset.collectionHolderClass);

    // Crée un nouvel élément de liste (li) pour ajouter un nouveau champ ou formulaire
    const item = document.createElement('li');

    // Remplit le nouvel élément avec le contenu du "prototype" en remplaçant "__name__" par l'index actuel
    item.innerHTML = collectionHolder
        .dataset // Accède aux données définies dans l'attribut data-prototype du conteneur
        .prototype // Récupère le modèle HTML
        .replace(
            /__name__/g, // Remplace toutes les occurrences de "__name__"
            collectionHolder.dataset.index // Par l'index actuel de la collection
        );

    // Ajoute le nouvel élément à la fin de la collection
    collectionHolder.appendChild(item);

    // Incrémente l'index pour que le prochain élément ait un identifiant unique
    collectionHolder.dataset.index++;
}
