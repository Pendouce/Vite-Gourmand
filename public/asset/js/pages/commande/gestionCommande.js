import {
  getData,
  calculSousTotal,
  recalculSousTotal,
  recalculTotal,
  afficheTotal,
  afficheTotalCommande,
} from "./calculEtAffichage.js";
import { actualiserPresta } from "./presta.js";
import { fermerModal } from "../modales.js";
import { nettoyerModal } from "./modal.js";

/* Gestion de l'input quantite avec les boutons plus/mois et la valeur de l'input */
const nbPersonnePlusMoins = (div, plus, moins, nbElement) => {
  const btnPlus = div.querySelector(plus);
  const btnMoins = div.querySelector(moins);
  const input = div.querySelector(nbElement);

  btnPlus.addEventListener("click", () => {
    if (Number(input.value) < input.max) {
      input.value = Number(input.value) + 1;
      input.dispatchEvent(new Event("change", { bubbles: true }));
    }
  });

  btnMoins.addEventListener("click", () => {
    if (Number(input.value) > input.min) {
      input.value = Number(input.value) - 1;
      input.dispatchEvent(new Event("change", { bubbles: true }));
    }
  });

  input.addEventListener("change", () => {
    if (Number(input.value) < input.min) input.value = input.min;
    if (Number(input.value) > input.max) input.value = input.max;
  });
};

/* Gestion de l'ajout d'un nouveau menu/boisson a la commande */
const ajouterALaCommande = async (id, config, configModal = null) => {
  const data = await getData(config.api, id);
  const nvlDiv = config.modele.cloneNode(true);
  const inputQuantite = nvlDiv.querySelector(config.quantite);
  nvlDiv.querySelector(`${config.titre}`).textContent = data.titre;
  nvlDiv.querySelector(`${config.prix}`).textContent = data.prix;
  inputQuantite.value = data.nbPersMin ?? 1;
  inputQuantite.max = data.stock;
  inputQuantite.min = data.nbPersMin ?? 1;
  if (config.remise) inputQuantite.dataset.nbPersMin = data.nbPersMin;
  nvlDiv.querySelector(config.stock).textContent = data.stock;
  nvlDiv.querySelector(config.inputId).value = id;
  nvlDiv.querySelector(`${config.total}`).textContent = calculSousTotal(
    data.prix,
    inputQuantite.value,
    data.nbPersMin,
  ).toFixed(2);
  nbPersonnePlusMoins(nvlDiv, ".btnPlus", ".btnMoins", config.quantite);
  recalculSousTotal(nvlDiv, config.quantite, config.prix, config.total);
  config.conteneur.appendChild(nvlDiv);
  config.conteneur.parentElement.classList.replace("justify-center", "justify-evenly");
  if (configModal) desactiverBtnAjouter(configModal);
  afficheTotal(config.section, config);
  recalculTotal(nvlDiv, config.quantite, config.section, config);
  actualiserPresta();
  afficheTotalCommande();
  supprimerDeLaCommande(nvlDiv, config, configModal);
};

/* Gestion de l'emplacement du btn ajouter des sections menu et boisson */
const replacerBtnAjouter = (config) => {
  if (config.conteneur.children.length === 0) {
    config.conteneur.parentElement.classList.replace("justify-evenly", "justify-center");
  }
};

/* Gestion de la suppression d'un menu ou d'une boisson de la commande */
const supprimerDeLaCommande = (div, config, configModal) => {
  div.querySelector(".btnSupprimer").addEventListener("click", () => {
    div.remove();
    afficheTotal(config.section, config);

    replacerBtnAjouter(config);
    actualiserPresta();
    afficheTotalCommande();
    if (configModal) {
      desactiverBtnAjouter(configModal);
      desactiverBtnAjouterDetail(configModal);
    }
  });
};

/* Gestion de l'ajout de l'ouverture des modales menu et boisson au click sur ajouter */
const btnAjouter = (config) => {
  document.querySelectorAll(config.btnAjouter).forEach((btn) => {
    btn.addEventListener("click", () => {
      const carte = btn.closest(config.classe);
      const id = carte.dataset.id;
      fermerModal(config.modal);
      ajouterALaCommande(id, config.config, config);
    });
  });
};

/* Gestion de l'affichage du detail du catalogue */

/* Gestion de l'ajout a la commande depuis la page detail */
const ajouterALaCommandeDepuisDetail = (config) => {
  config.detailModal.addEventListener("click", (e) => {
    const btn = e.target.closest(config.btnAjouterDetail);
    if (btn) {
      const id = btn.dataset.id;
      ajouterALaCommande(id, config.config, config);
      nettoyerModal(config);
    }
  });
};

const recupereIdsDeLaCommande = (config) => {
  const ids = [];
  config.config.conteneur.querySelectorAll(config.config.inputId).forEach((input) => {
    ids.push(input.value);
  });

  return ids;
};

const desactiverBtnAjouter = (config) => {
  document.querySelectorAll(config.classe).forEach((carte) => {
    const id = carte.dataset.id;
    const dejaAjoute = recupereIdsDeLaCommande(config).includes(id);
    const btn = carte.querySelector(config.btnAjouter);
    // Si l'id de la carte est dans dejaAjoute je grise son btn
    btn.disabled = dejaAjoute;
  });
};

const desactiverBtnAjouterDetail = (config) => {
  const btn = document.querySelector(config.btnAjouterDetail);
  if (!btn) return;
  const dejaAjoute = recupereIdsDeLaCommande(config).includes(btn.dataset.id);
  btn.disabled = dejaAjoute;
};

export {
  ajouterALaCommande,
  ajouterALaCommandeDepuisDetail,
  replacerBtnAjouter,
  btnAjouter,
  desactiverBtnAjouter,
  desactiverBtnAjouterDetail,
};
