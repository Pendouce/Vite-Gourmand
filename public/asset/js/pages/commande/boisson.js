import { ouvrirModal } from "../modales.js";
import { afficheTotalCommande } from "./calculEtAffichage.js";
import { afficheDetail, retourListe, nettoyerModal } from "./modal.js";

import {
  desactiverBtnAjouter,
  btnAjouter,
  desactiverBtnAjouterDetail,
  ajouterALaCommandeDepuisDetail,
  replacerBtnAjouter,
} from "./gestionCommande.js";

const sectionBoissons = document.getElementById("sectionBoissons");
// Je recupere le template boisson
const templateBoisson = document.getElementById("templateBoisson");
const divBoissons = document.querySelector(".divBoissons");
// Je recupere uniquement la div Boisson
const divBoisson = templateBoisson.content.firstElementChild;
const btnNvlBoisson = document.getElementById("btnNvlBoisson");
const btnDetailsBoisson = document.querySelectorAll(".btnDetailsBoisson");
const modalBoisson = document.getElementById("modalBoisson");
const listeBoissonsModal = document.getElementById("listeBoissonsModal");
const detailBoissonModal = document.getElementById("detailBoissonModal");

btnNvlBoisson.addEventListener("click", () => {
  nettoyerModal(configModalBoisson);
  ouvrirModal(modalBoisson);
  desactiverBtnAjouter(configModalBoisson);
});

const configBoisson = {
  api: "detailBoisson",
  modele: divBoisson,
  conteneur: divBoissons,
  inputId: "input[name='boisson[]']",
  titre: ".titreBoisson",
  stock: ".stockBoisson",
  prix: ".prixUnitaireBoisson",
  quantite: ".quantiteBoisson",
  total: ".sousTotalBoisson",
  section: sectionBoissons,
};

const configModalBoisson = {
  api: "detailBoissonPartial",
  classe: ".cart-plat",
  btnAjouter: ".btnAjouterBoisson",
  btnAjouterDetail: "#btnAjouterDetail",
  btnDetails: btnDetailsBoisson,
  modal: modalBoisson,
  listeModal: listeBoissonsModal,
  detailModal: detailBoissonModal,
  btnRetourListe: "#btnRetourBoisson",
  config: configBoisson,
  apresAffichageDetail: () => desactiverBtnAjouterDetail(configModalBoisson),
};

btnAjouter(configModalBoisson);
ajouterALaCommandeDepuisDetail(configModalBoisson);
afficheDetail(configModalBoisson);
retourListe(configModalBoisson);
replacerBtnAjouter(configBoisson);

divBoissons.addEventListener("change", () => {
  afficheTotalCommande();
});
