import { brancherPlusMoins } from "./gestionCommande.js";
import { actualiserPresta, actualiserSelect } from "./presta.js";
import {
  actualiserLivraison,
  afficheTotalCommande,
  calculPrixLivraison,
  calculTotalNbPersMenu,
} from "./calculEtAffichage.js";

import { brancherFormulaire } from "./formulaire.js";

const divsMenu = document.querySelectorAll(".divMenu");
const sectionMenus = document.getElementById("sectionMenus");
const divMenus = document.querySelector(".divMenus");
const divsBoisson = document.querySelectorAll(".divBoisson");
const sectionBoissons = document.getElementById("sectionBoissons");
const divBoissons = document.querySelector(".divBoissons");

const configBoisson = {
  conteneur: divBoissons,
  inputId: "input[name='boisson[]']",
  stock: ".stockBoisson",
  prix: ".prixUnitaireBoisson",
  quantite: ".quantiteBoisson",
  total: ".sousTotalBoisson",
  section: sectionBoissons,
};

const configMenu = {
  conteneur: divMenus,
  inputId: "input[name='menu[]']",
  stock: ".stockMenu",
  prix: ".prixUnitaireMenu",
  remise: true,
  quantite: ".nbPersonneMenu",
  total: ".sousTotalMenu",
  section: sectionMenus,
};

const selectList = document.querySelectorAll(".select");
const inputNbPers = document.getElementById("inputNbPers");
inputNbPers.value = calculTotalNbPersMenu();

divMenus.addEventListener("change", () => {
  inputNbPers.value = calculTotalNbPersMenu();
  actualiserPresta();
  afficheTotalCommande();
});

divBoissons.addEventListener("change", () => {
  actualiserPresta();
  afficheTotalCommande();
});

selectList.forEach((select) => {
  actualiserSelect(select);
  select.addEventListener("change", async () => {
    await actualiserSelect(select);
    actualiserPresta();
    afficheTotalCommande();
  });
});

brancherPlusMoins(divsMenu, configMenu);
brancherPlusMoins(divsBoisson, configBoisson);
inputNbPers.value = calculTotalNbPersMenu();
actualiserPresta();
calculPrixLivraison();
afficheTotalCommande();
actualiserLivraison();
afficheTotalCommande();

/* ENVOI DE LA COMMANDE */
const formulaire = document.getElementById("formModifCommande");

brancherFormulaire(formulaire);
