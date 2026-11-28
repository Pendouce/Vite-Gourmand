const modalContainer = document.getElementById("modalContainer");
const btnSupprimerCmpt = document.getElementById("btnSupprimerCmpt");
const btnAnnuler = document.getElementById("btnAnnuler");
const btnSupprimerCmptEmploye = document.querySelectorAll(".btnSupprimerCmptEmploye");

const btnGererTypePlat = document.getElementById("btnGererTypePlat");
const btnGererTypePresta = document.getElementById("btnGererTypePresta");

const fermerModal = (modal) => {
  modal.classList.add("hidden");
};

const ouvrirModal = (modal) => {
  modal.classList.remove("hidden");
};

// Je verifie que le bouton existe avant d'ajouter l'event listener
if (btnSupprimerCmpt) {
  btnSupprimerCmpt.addEventListener("click", () => {
    ouvrirModal(modalContainer);
  });
}

const gererActionListeBtn = (config) => {
  // Je boucle sur tous les boutons btn est le bouton que je viens de cliquer
  config.nomBtn.forEach((btn) => {
    btn.addEventListener("click", () => {
      // Je recupere le nom est l'id de l'employe et les stocks dans leurs variables
      const nom = btn.dataset.nom;
      const id = btn.dataset.id;
      if (config.nom) {
        document.querySelector(config.nom).textContent = nom;
      }

      if (config.nbCommande) {
        const nbCommande = btn.dataset.nbCommande;
        document.querySelector(config.nbCommande).textContent = nbCommande;
      }
      // J'affiche le nom
      //document.querySelector(config.nom).textContent = nom;
      // J'assigigne a idEmploye la valeur de l'id employe (idEmploye = id selectionné)
      document.querySelector(config.id).value = id;
      ouvrirModal(modalContainer);
    });
  });
};

// Si btnSupprimerCmptEmploye existe dans le fichier
if (btnSupprimerCmptEmploye) {
  const configCompteEmploye = {
    nomBtn: btnSupprimerCmptEmploye,
    nom: "#nomEmploye",
    id: "#idEmploye",
  };

  gererActionListeBtn(configCompteEmploye);
}

if (btnGererTypePlat) {
  btnGererTypePlat.addEventListener("click", () => {
    ouvrirModal(modalContainer);
  });
}

if (btnGererTypePresta) {
  btnGererTypePresta.addEventListener("click", () => {
    ouvrirModal(modalContainer);
  });
}

if (modalContainer) {
  modalContainer.addEventListener("click", (event) => {
    if (event.target === modalContainer) fermerModal(modalContainer);
  });
}

if (btnAnnuler) {
  btnAnnuler.addEventListener("click", () => fermerModal(modalContainer));
}

export { ouvrirModal, fermerModal, gererActionListeBtn };
