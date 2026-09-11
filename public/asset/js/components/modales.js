const modalContainer = document.getElementById("modalContainer");
const btnSupprimerCmpt = document.getElementById("btnSupprimerCmpt");
const btnAnnuler = document.getElementById("btnAnnuler");
const btnSupprimerCmptEmploye = document.querySelectorAll(".btnSupprimerCmptEmploye");

const fermerModal = () => {
  modalContainer.classList.add("hidden");
};

const ouvrirModal = () => {
  modalContainer.classList.remove("hidden");
};

// Je verifie que le bouton existe avant d'ajouter l'event listener
if (btnSupprimerCmpt) {
  btnSupprimerCmpt.addEventListener("click", () => {
    ouvrirModal();
  });
}

// Si btnSupprimerCmptEmploye existe dans le fichier
if (btnSupprimerCmptEmploye) {
  // Je boucle sur tous les boutons btn est le bouton que je viens de cliquer
  btnSupprimerCmptEmploye.forEach((btn) => {
    btn.addEventListener("click", () => {
      // Je recupere le nom est l'id de l'employe et les stocks dans leurs variables
      const nom = btn.dataset.nom;
      const id = btn.dataset.id;
      // J'affiche le nom
      document.getElementById("nomEmploye").textContent = nom;
      // J'assigigne a idEmploye la valeur de l'id employe (idEmploye = id selectionné)
      document.getElementById("idEmploye").value = id;
      ouvrirModal();
    });
  });
}

if (modalContainer) {
  modalContainer.addEventListener("click", (event) => {
    if (event.target === modalContainer) fermerModal();
  });
}

if (btnAnnuler) {
  btnAnnuler.addEventListener("click", fermerModal);
}
