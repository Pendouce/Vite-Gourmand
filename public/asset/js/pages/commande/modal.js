import { fermerModal } from "../modales.js";

const modalContainerCommande = document.querySelectorAll(".modal-container-commande");
const btnFermerModalCommande = document.querySelectorAll(".btnFermerModalCommande");

/* Gesteion de l'affichage de la page detail dans la modal */
const afficheDetail = (config) => {
  config.btnDetails.forEach((btn) => {
    btn.addEventListener("click", async () => {
      const carte = btn.closest(config.classe);
      const id = carte.dataset.id;
      const res = await fetch(`/${config.api}?id=${id}`);
      const data = await res.text();
      config.detailModal.innerHTML = data;
      config.detailModal.classList.remove("hidden");
      config.listeModal.classList.add("hidden");
      //desactiverBtnAjouterDetail(config);
      if (config.apresAffichageDetail) config.apresAffichageDetail();
    });
  });
};

/* Gestion du retour a la liste du catalogue */
const retourListe = (config) => {
  // J'ajoute un evenement click sur la modale de la page detail
  // car le btn retour n'existe pas sur la modale initale
  config.detailModal.addEventListener("click", (e) => {
    // e represente l'evenement click
    // e.target l'element precis sur lequel je viens de cliquer
    // closest() vérifie si cet element correspond au bouton retour.
    // Si ce n'est pas le cas, il recherche ce bouton parmi ses parents.
    if (e.target.closest(config.btnRetourListe)) {
      nettoyerModal(config);
    }
  });
};

const nettoyerModal = (config) => {
  config.detailModal.innerHTML = "";
  config.detailModal.classList.add("hidden");
  config.listeModal.classList.remove("hidden");
};

/* Gestion de la fermeture des modales */
btnFermerModalCommande.forEach((btn) => {
  btn.addEventListener("click", () => {
    const modal = btn.closest(".modal-container-commande");
    fermerModal(modal);
  });
});

modalContainerCommande.forEach((modalContainer) => {
  modalContainer.addEventListener("click", (event) => {
    if (event.target === modalContainer) fermerModal(modalContainer);
  });
});

export { retourListe, afficheDetail, nettoyerModal };
