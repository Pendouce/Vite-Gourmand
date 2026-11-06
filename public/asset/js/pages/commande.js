import { ouvrirModal, fermerModal } from "./modales.js";
console.log("commande ok");

const sectionMenus = document.getElementById("sectionMenus");
const inputPrixTotal = document.getElementById("inputPrixTotal");
const prixPresta = document.querySelector(".prixPresta");
const prixTotalPresta = document.querySelector(".prixTotalPresta");
const seuilPresta = document.querySelector(".seuilPresta");
const btnModalPresta = document.getElementById("btnModalPresta");
const titreMenu = document.querySelector(".titreMenu");
const sousTotalMenu = document.querySelector(".sousTotalMenu");
const prixUnitaireMenu = document.querySelector(".prixUnitaireMenu");
const nbPersonneMenu = document.querySelector(".nbPersonneMenu");
const btnNvlBoisson = document.getElementById("btnNvlBoisson");
const btnNvMenu = document.getElementById("btnNvMenu");
const btnAjouterMenu = document.querySelectorAll(".btnAjouterMenu");
const btnAjouterBoisson = document.querySelectorAll(".btnAjouterBoisson");
const modalMenu = document.getElementById("modalMenu");
const modalBoisson = document.getElementById("modalBoisson");
const modalPresta = document.getElementById("modalPresta");
const btnFermerModalCommande = document.querySelectorAll(".btnFermerModalCommande");
//const divMenus = document.querySelectorAll(".divMenu");
const divMenu = document.querySelector(".divMenu");
const divMenus = document.querySelector(".divMenus");
//const carteMenu = document.querySelectorAll(".cart-menu");
//const btnSupprimer = document.querySelector(".btnSupprimer");
const total = document.querySelectorAll(".total");

const getData = async (api, id) => {
  const res = await fetch(`/api/${api}?id=${id}`);
  const result = await res.json();

  return result;
};

const calculSousTotal = (prix, nbPersonne) => {
  const result = Number(prix) * Number(nbPersonne);

  return result;
};

const recalculSousTotal = (div, nbElement, prixElement, totalElement) => {
  const nb = div.querySelector(nbElement);
  const prix = div.querySelector(prixElement);
  const sousTotal = div.querySelector(totalElement);

  nb.addEventListener("change", () => {
    sousTotal.textContent = calculSousTotal(prix.textContent, nb.value).toFixed(2);
  });
};

const calculTotal = (sousTotal, element) => {
  const total = element.querySelectorAll(`${sousTotal}`);
  // Je transforme ma node liste en tableau pour pouvoir lui appliquer reduce
  const result = [...total].reduce((acc, curr) => acc + Number(curr.textContent), 0);

  return result;
};

const recalculTotal = (div, nbElement, element, config) => {
  const nb = div.querySelector(nbElement);

  nb.addEventListener("change", () => {
    return afficheTotal(element, config);
  });
};

const afficheTotal = (element, config) => {
  const total = element.querySelector(".total");
  total.textContent = calculTotal(config.total, config.section).toFixed(2);

  return total;
};

const nbPersonnePlusMoins = (div, plus, moins, nbElement) => {
  const btnPlus = div.querySelector(plus);
  const btnMoins = div.querySelector(moins);
  const input = div.querySelector(nbElement);

  btnPlus.addEventListener("click", () => {
    input.value = Number(input.value) + 1;
    input.dispatchEvent(new Event("change", { bubbles: true }));
  });

  btnMoins.addEventListener("click", () => {
    const nb = Number(input.value);
    if (nb > 0) {
      input.value = nb - 1;
      input.dispatchEvent(new Event("change", { bubbles: true }));
    }
  });
};

const ajouterALaCommande = async (id, config) => {
  const data = await getData(config.api, id);
  const nvlDiv = config.modele.cloneNode(true);
  const inputQuantite = nvlDiv.querySelector(config.quantite);
  nvlDiv.querySelector(`${config.titre}`).textContent = data.titre;
  nvlDiv.querySelector(`${config.prix}`).textContent = data.prix;
  inputQuantite.value = data.nbPersMin ?? 1;
  nvlDiv.querySelector(`${config.total}`).textContent = calculSousTotal(data.prix, inputQuantite.value).toFixed(2);
  recalculSousTotal(nvlDiv, config.quantite, config.prix, config.total);
  nbPersonnePlusMoins(nvlDiv, ".btnPlus", ".btnMoins", config.quantite);
  config.conteneur.appendChild(nvlDiv);
  config.conteneur.parentElement.classList.replace("justify-center", "justify-evenly");
  afficheTotal(config.section, config);
  recalculTotal(nvlDiv, config.quantite, config.section, config);
  supprimerDeLaCommande(nvlDiv, config);
};

const supprimerDeLaCommande = (div, config) => {
  div.querySelector(".btnSupprimer").addEventListener("click", () => {
    div.remove();
    afficheTotal(config.section, config);

    if (config.conteneur.children.length === 0) {
      config.conteneur.parentElement.classList.replace("justify-evenly", "justify-center");
    }
  });
};

/* MENU */

btnNvMenu.addEventListener("click", () => {
  ouvrirModal(modalMenu);
});

const configMenu = {
  api: "detailMenu",
  modele: divMenu,
  conteneur: divMenus,
  titre: ".titreMenu",
  prix: ".prixUnitaireMenu",
  quantite: ".nbPersonneMenu",
  total: ".sousTotalMenu",
  section: sectionMenus,
};

btnAjouterMenu.forEach((btn) => {
  btn.addEventListener("click", () => {
    const carteMenu = btn.closest(".cart-menu");
    const id = carteMenu.dataset.id;
    fermerModal(modalMenu);
    ajouterALaCommande(id, configMenu);
    console.log(id);
  });
});

sousTotalMenu.textContent = calculSousTotal(prixUnitaireMenu.textContent, nbPersonneMenu.value).toFixed(2);
recalculSousTotal(divMenu, configMenu.quantite, configMenu.prix, configMenu.total);
nbPersonnePlusMoins(divMenu, ".btnPlus", ".btnMoins", configMenu.quantite);
supprimerDeLaCommande(divMenu, configMenu);

afficheTotal(configMenu.section, configMenu);
recalculTotal(configMenu.conteneur, configMenu.quantite, configMenu.section, configMenu);

/* BOISSON */

btnNvlBoisson.addEventListener("click", () => {
  ouvrirModal(modalBoisson);
});

/* PRESTA */

btnModalPresta.addEventListener("click", () => {
  ouvrirModal(modalPresta);
});

/*  */

btnFermerModalCommande.forEach((btn) => {
  btn.addEventListener("click", () => {
    fermerModal(modalMenu);
    fermerModal(modalBoisson);
    fermerModal(modalPresta);
  });
});
