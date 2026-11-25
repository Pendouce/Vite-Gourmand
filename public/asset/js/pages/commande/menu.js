import { ouvrirModal } from "../modales.js";
import { afficheDetail, nettoyerModal, retourListe } from "./modal.js";
import { actualiserPresta } from "./presta.js";
import { afficheTotalCommande } from "./calculEtAffichage.js";
import {
  ajouterALaCommande,
  desactiverBtnAjouter,
  desactiverBtnAjouterDetail,
  btnAjouter,
  ajouterALaCommandeDepuisDetail,
  replacerBtnAjouter,
} from "./gestionCommande.js";

const templateMenu = document.getElementById("templateMenu");
// Je recupere uniquement la div Menu
const divMenu = templateMenu.content.firstElementChild;
const sectionMenus = document.getElementById("sectionMenus");
const btnNvMenu = document.getElementById("btnNvMenu");
const divMenus = document.querySelector(".divMenus");
const listeMenusModal = document.getElementById("listeMenusModal");
const detailMenuModal = document.getElementById("detailMenuModal");
const btnDetailsMenu = document.querySelectorAll(".btnDetailsMenu");
const modalMenu = document.getElementById("modalMenu");

const configMenu = {
  api: "detailMenu",
  modele: divMenu,
  conteneur: divMenus,
  inputId: "input[name='menu[]']",
  titre: ".titreMenu",
  stock: ".stockMenu",
  prix: ".prixUnitaireMenu",
  remise: true,
  quantite: ".nbPersonneMenu",
  total: ".sousTotalMenu",
  section: sectionMenus,
};

const configModalMenu = {
  api: "detailMenuPartial",
  classe: ".cart-menu",
  btnAjouter: ".btnAjouterMenu",
  btnAjouterDetail: "#btnAjouterDetail",
  btnDetails: btnDetailsMenu,
  modal: modalMenu,
  listeModal: listeMenusModal,
  detailModal: detailMenuModal,
  btnRetourListe: "#btnRetourMenu",
  config: configMenu,
  apresAffichageDetail: () => desactiverBtnAjouterDetail(configModalMenu),
};

// Ajout du menu de base depuis la page menu
const menuId = sectionMenus.dataset.menuId;

if (btnNvMenu) {
  if (menuId) ajouterALaCommande(menuId, configMenu);

  btnNvMenu.addEventListener("click", () => {
    nettoyerModal(configModalMenu);
    ouvrirModal(modalMenu);
    desactiverBtnAjouter(configModalMenu);
  });

  btnAjouter(configModalMenu);
  ajouterALaCommandeDepuisDetail(configModalMenu);
  afficheDetail(configModalMenu);
  retourListe(configModalMenu);
  replacerBtnAjouter(configMenu);
}

divMenus.addEventListener("change", () => {
  actualiserPresta();
  afficheTotalCommande();
});
