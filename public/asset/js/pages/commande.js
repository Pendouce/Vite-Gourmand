import { ouvrirModal, fermerModal } from "./modales.js";
console.log("commande ok");

const sectionMenus = document.getElementById("sectionMenus");
const modalContainerCommande = document.querySelectorAll(".modal-container-commande");
const inputPrixTotal = document.getElementById("inputPrixTotal");
const prixTotalPresta = document.querySelector(".prixTotalPresta");
const btnModalPresta = document.getElementById("btnModalPresta");
const titreMenu = document.querySelector(".titreMenu");
const sousTotalMenu = document.querySelector(".sousTotalMenu");
const prixUnitaireMenu = document.querySelector(".prixUnitaireMenu");
const nbPersonneMenu = document.querySelector(".nbPersonneMenu");
const btnNvlBoisson = document.getElementById("btnNvlBoisson");
const btnNvMenu = document.getElementById("btnNvMenu");
const btnAjouterMenu = document.querySelectorAll(".btnAjouterMenu");
const btnDetailsMenu = document.querySelectorAll(".btnDetailsMenu");
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

/* Gestion des donnees recuperees du back */
const getData = async (api, id) => {
  const res = await fetch(`/api/${api}?id=${id}`);
  const result = await res.json();

  return result;
};

/* Gestion du sous total */
const calculSousTotal = (prix, nbPersonne, nbPersMin) => {
  let result = Number(prix) * Number(nbPersonne);

  if (nbPersMin && Number(nbPersonne) > Number(nbPersMin) + 5) {
    result *= 0.9;
  }

  return result;
};

/* Gestion des sous totaux apres modification de la quantite */
const recalculSousTotal = (div, nbElement, prixElement, totalElement) => {
  const nb = div.querySelector(nbElement);
  const prix = div.querySelector(prixElement);
  const sousTotal = div.querySelector(totalElement);

  nb.addEventListener("change", () => {
    sousTotal.textContent = calculSousTotal(prix.textContent, nb.value, nb.dataset.nbPersMin).toFixed(2);
  });
};

/* Gestion du total de sous totaux */
const calculTotal = (sousTotal, element) => {
  const total = element.querySelectorAll(`${sousTotal}`);
  // Je transforme ma node liste en tableau pour pouvoir lui appliquer reduce
  const result = [...total].reduce((acc, curr) => acc + Number(curr.textContent), 0);

  return result;
};

/* Gestion du total apres ajout/supression ou modification de la quantite */
const recalculTotal = (div, nbElement, element, config) => {
  const nb = div.querySelector(nbElement);

  nb.addEventListener("change", () => {
    return afficheTotal(element, config);
  });
};

/* Affichage du total apres calcul */
const afficheTotal = (element, config) => {
  const total = element.querySelector(".total");
  total.textContent = calculTotal(config.total, config.section).toFixed(2);

  return total;
};

/* Gestion de l'input quantite avec les boutons plus/mois et la valeur de l'input */
const nbPersonnePlusMoins = (div, plus, moins, nbElement) => {
  const btnPlus = div.querySelector(plus);
  const btnMoins = div.querySelector(moins);
  const input = div.querySelector(nbElement);
  const max = Number(input.max);
  const min = Number(input.min);

  btnPlus.addEventListener("click", () => {
    if (Number(input.value) < max) {
      input.value = Number(input.value) + 1;
      input.dispatchEvent(new Event("change", { bubbles: true }));
    }
  });

  btnMoins.addEventListener("click", () => {
    if (Number(input.value) > min) {
      input.value = Number(input.value) - 1;
      input.dispatchEvent(new Event("change", { bubbles: true }));
    }
  });

  input.addEventListener("change", () => {
    if (Number(input.value) < min) input.value = min;
    if (Number(input.value) > max) input.value = max;
  });
};

/* Gestion de l'ajout d'un nouveau menu/boisson a la commande */
const ajouterALaCommande = async (id, config) => {
  const data = await getData(config.api, id);
  const nvlDiv = config.modele.cloneNode(true);
  const inputQuantite = nvlDiv.querySelector(config.quantite);
  nvlDiv.querySelector(`${config.titre}`).textContent = data.titre;
  nvlDiv.querySelector(`${config.prix}`).textContent = data.prix;
  inputQuantite.value = data.nbPersMin ?? 1;
  inputQuantite.max = data.stock;
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
  afficheTotal(config.section, config);
  recalculTotal(nvlDiv, config.quantite, config.section, config);
  actualiserPresta();
  supprimerDeLaCommande(nvlDiv, config);

  //calculTotalNbPersMenu();
};

const replacerBtnAjouter = (config) => {
  if (config.conteneur.children.length === 0) {
    config.conteneur.parentElement.classList.replace("justify-evenly", "justify-center");
  }
};

const supprimerDeLaCommande = (div, config) => {
  div.querySelector(".btnSupprimer").addEventListener("click", () => {
    div.remove();
    afficheTotal(config.section, config);

    replacerBtnAjouter(config);
    actualiserPresta();
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
  inputId: "input[name='menu[]']",
  titre: ".titreMenu",
  stock: ".stockMenu",
  prix: ".prixUnitaireMenu",
  remise: true,
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
  });
});

const nbPersMin = nbPersonneMenu.dataset.nbPersMin;
sousTotalMenu.textContent = calculSousTotal(prixUnitaireMenu.textContent, nbPersonneMenu.value, nbPersMin).toFixed(2);
recalculSousTotal(divMenu, configMenu.quantite, configMenu.prix, configMenu.total);
nbPersonnePlusMoins(divMenu, ".btnPlus", ".btnMoins", configMenu.quantite);
supprimerDeLaCommande(divMenu, configMenu);

afficheTotal(configMenu.section, configMenu);
recalculTotal(configMenu.conteneur, configMenu.quantite, configMenu.section, configMenu);

/* BOISSON */

const sectionBoissons = document.getElementById("sectionBoissons");
// Je recupere le template boisson
const templateBoisson = document.getElementById("templateBoisson");
const divBoissons = document.querySelector(".divBoissons");
// Je recupere uniquement la div Boisson
const divBoisson = templateBoisson.content.firstElementChild;

btnNvlBoisson.addEventListener("click", () => {
  ouvrirModal(modalBoisson);
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

replacerBtnAjouter(configBoisson);

btnAjouterBoisson.forEach((btn) => {
  btn.addEventListener("click", () => {
    const carteBoisson = btn.closest(".cart-plat");
    const id = carteBoisson.dataset.id;
    fermerModal(modalBoisson);
    ajouterALaCommande(id, configBoisson);
  });
});

/* PRESTA */

btnModalPresta.addEventListener("click", () => {
  ouvrirModal(modalPresta);
});

const selectList = document.querySelectorAll(".select");

const trouverOption = (select) => {
  // Je recupere l'option du select en cours
  const option = select.selectedOptions[0];
  const idPresta = option.dataset.idPresta;
  //console.log(option);

  return idPresta;
};

const calculTotalNbPersMenu = () => {
  const nbPersonneMenuInput = document.querySelectorAll(".nbPersonneMenu");

  const totalPers = [...nbPersonneMenuInput].reduce((acc, curr) => acc + Number(curr.value), 0);

  return totalPers;
};

const calculSousTotalPresta = (data) => {
  const nbPersonnes = calculTotalNbPersMenu();
  let supplement = 0;
  let seuil = data.seuil;

  if (nbPersonnes < data.seuil) {
    return 0;
  }

  while (seuil < nbPersonnes) {
    seuil += 5;
    supplement += data.supplement;
  }

  const result = supplement + data.prix;

  return result;
};

/* const recalculSousTotalPresta = (sousTotal, div) => {
  const nb = divMenus.querySelectorAll(".nbPersonneMenu");

  nb.forEach((n) => {
    n.addEventListener("change", () => {
      if (!div.dataPresta) return;
      sousTotal.textContent = calculSousTotalPresta(div.dataPresta).toFixed(2);
      msgNbPersInsufisant(div.dataPresta, div);
    });
  });
}; */

const actualiserPresta = () => {
  const divsPresta = document.querySelectorAll(".divPresta");

  divsPresta.forEach((divP) => {
    if (divP.dataPresta) {
      const sousTotal = divP.querySelector(".total");
      sousTotal.textContent = calculSousTotalPresta(divP.dataPresta).toFixed(2);
      msgNbPersInsufisant(divP.dataPresta, divP);
    }
  });
};

divMenus.addEventListener("change", actualiserPresta);

const recupererDonnees = async (id) => {
  const dataPresta = await getData("detailPrestation", id);

  return dataPresta;
};

const msgNbPersInsufisant = (data, div) => {
  const msgPresta = div.querySelector(".msgPresta");
  if (!data) {
    msgPresta.classList.add("hidden");
    return;
  }
  msgPresta.textContent = `Cette prestation est disponible a partir de ${data.seuil} personnes`;

  const nbPersonnes = calculTotalNbPersMenu();

  if (nbPersonnes < data.seuil) {
    msgPresta.classList.remove("hidden");
  } else {
    msgPresta.classList.add("hidden");
  }
};

const retrouverBlock = (select, data) => {
  const divPresta = select.closest(".divPresta");
  const blockMsg = divPresta.querySelector(".blockMsg");
  const prixPresta = divPresta.querySelector(".prixPresta");
  const seuilPresta = divPresta.querySelector(".seuilPresta");
  const supplementPresta = divPresta.querySelector(".supplementPresta");
  const sousTotal = divPresta.querySelector(".total");

  if (!data) {
    divPresta.dataPresta = null;
    blockMsg.classList.add("hidden");
    msgNbPersInsufisant(data, divPresta);
    sousTotal.textContent = "0.00";
    return;
  }

  blockMsg.classList.remove("hidden");

  prixPresta.textContent = data.prix;
  seuilPresta.textContent = data.seuil;
  supplementPresta.textContent = data.supplement;
  sousTotal.textContent = calculSousTotalPresta(data).toFixed(2);
  divPresta.dataPresta = data;
  msgNbPersInsufisant(data, divPresta);
};

selectList.forEach((select) => {
  select.addEventListener("change", async () => {
    const id = trouverOption(select);
    const donnees = await recupererDonnees(id);
    retrouverBlock(select, donnees);
    if (!donnees) return;
    console.log(donnees);
  });
});

/* selectList.forEach((select) => {
  select.addEventListener("change", async () => {
    // Je recupere l'option du select en cours
    const option = select.selectedOptions[0];
    console.log(option);
    const idPresta = option.dataset.idPresta;
    const dataPresta = await getData("detailPrestation", idPresta);
    const divPresta = select.closest(".divPresta");
    console.log(divPresta);
  });
}); */

/* Gestion de la fermeture des modales */
btnFermerModalCommande.forEach((btn) => {
  btn.addEventListener("click", () => {
    modalContainerCommande.forEach((modalContainer) => {
      modalContainer.addEventListener("click", () => {
        fermerModal(modalContainer);
      });
    });
  });
});

/* btnFermerModalCommande.forEach((btn) => {
  btn.addEventListener("click", () => {
    fermerModal(modalMenu);
    fermerModal(modalBoisson);
    fermerModal(modalPresta);
  });
}); */

modalContainerCommande.forEach((modalContainer) => {
  modalContainer.addEventListener("click", (event) => {
    if (event.target === modalContainer) fermerModal(modalContainer);
  });
});

/* modalContainerCommande.addEventListener("click", (event) => {
  if (event.target === modalContainerCommande) fermerModal(modalContainerCommande);
}); */
