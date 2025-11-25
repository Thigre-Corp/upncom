
const dialog = document.querySelector("#newsletter-dialog");
const showButton = document.querySelector("#open-newsletter-dialog");
const closeButton = document.querySelector(".close-newsletter");
let isDialogOpen = false;

// Le bouton "Afficher la fenêtre" ouvre le dialogue
showButton.addEventListener("click", () => {
    dialog.showModal();
    isDialogOpen = true;
});

// Le bouton "Fermer" ferme le dialogue
closeButton.addEventListener("click", () => {
    dialog.close();
    isDialogOpen = false;
});
