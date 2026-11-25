const inputPrixTotal = document.getElementById("inputPrixTotal");
const totalCommande = document.getElementById("totalCommande");
const inputNbPers = document.getElementById("inputNbPers");
const inputPrixLivraison = document.getElementById("inputPrixLivraison");
const inputTotalPresta = document.getElementById("inputTotalPresta");

/* Gestion des donnees recuperees du back */
const getData = async (api, id) => {
  const res = await fetch(`/api/${api}?id=${id}`);
  const result = await res.json();

  return result;
};

/* Gestion du sous total */
const calculSousTotal = (prix, nbPersonne, nbPersMin) => {
  let result = Number(prix) * Number(nbPersonne);

  if (Number(nbPersonne) >= Number(nbPersMin) + 5) {
    result *= 0.9;
  }

  return result;
};

const adresse = document.getElementById("adresse");
const ville = document.getElementById("ville");
const codePostal = document.getElementById("codePostal");

/* Gestion du total de sous totaux */
const calculTotal = (sousTotal, element) => {
  const total = element.querySelectorAll(`${sousTotal}`);
  // Je transforme ma node liste en tableau pour pouvoir lui appliquer reduce
  const result = [...total].reduce((acc, curr) => acc + Number(curr.textContent), 0);

  return result;
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

const calculPrixLivraison = async () => {
  const formData = new FormData();
  formData.append("lieu_livraison", adresse.value);
  formData.append("code_postal", codePostal.value);
  formData.append("ville", ville.value);

  try {
    const res = await fetch("/calculerPrixLivraison", {
      method: "POST",
      body: formData,
    });
    const result = await res.json();

    if (result.succes) {
      return result;
    } else {
      const msgErreurAdresse = document.getElementById("msgErreurAdresse");
      msgErreurAdresse.classList.remove("hidden");
      setTimeout(() => {
        msgErreurAdresse.classList.add("hidden");
      }, 5000);
    }
  } catch (e) {
    return null;
  }
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

/* Gestion du total apres ajout/supression ou modification de la quantite */
const recalculTotal = (div, nbElement, element, config) => {
  const nb = div.querySelector(nbElement);

  nb.addEventListener("change", () => {
    return afficheTotal(element, config);
  });
};

let prixLivraison = null;

const actualiserLivraison = async () => {
  prixLivraison = await calculPrixLivraison();
  await afficheTotalCommande();
};

/* Gestion de la mise a jour du prix de la commande au changement au niveau de l'adresse */
[adresse, ville, codePostal].forEach((input) => {
  input.addEventListener("change", actualiserLivraison);
});

const calculTotalCommande = () => {
  const total = calculTotal(".total", document);

  if (prixLivraison && prixLivraison.succes) {
    return (total + Number(prixLivraison.prix)).toFixed(2);
  }
};

const afficheTotalCommande = async () => {
  let prixTotal;
  if (prixLivraison && prixLivraison.succes) {
    prixTotal = await calculTotalCommande();
  } else {
    prixTotal = calculTotal(".total", document).toFixed(2);
  }
  totalCommande.textContent = prixTotal;
  inputPrixTotal.value = prixTotal;
  inputPrixLivraison.value = prixLivraison && prixLivraison.succes ? Number(prixLivraison.prix) : 0;
  inputNbPers.value = calculTotalNbPersMenu();
  inputTotalPresta.value = calculTotal(".divPresta .total", document).toFixed(2);
};

/* Affichage du total apres calcul */
const afficheTotal = (element, config) => {
  const total = element.querySelector(".total");
  total.textContent = calculTotal(config.total, config.section).toFixed(2);

  return total;
};

export {
  getData,
  calculSousTotal,
  calculTotal,
  calculPrixLivraison,
  calculTotalCommande,
  recalculSousTotal,
  recalculTotal,
  afficheTotal,
  afficheTotalCommande,
  calculSousTotalPresta,
  calculTotalNbPersMenu,
  actualiserLivraison,
};

/* export const actualiserLivraison = async () => {
  prixLivraison = await calculPrixLivraison();
  await afficheTotalCommande();
}; */
