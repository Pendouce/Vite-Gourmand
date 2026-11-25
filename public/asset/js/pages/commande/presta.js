import { ouvrirModal } from "../modales.js";
import { retourListe, afficheDetail } from "./modal.js";
import { calculSousTotalPresta, afficheTotalCommande, calculTotalNbPersMenu, getData } from "./calculEtAffichage.js";

const btnModalPresta = document.getElementById("btnModalPresta");
const listePrestasModal = document.getElementById("listePrestasModal");
const detailPrestaModal = document.getElementById("detailPrestaModal");
const btnDetailsPresta = document.querySelectorAll(".btnDetailsPresta");
const modalPresta = document.getElementById("modalPresta");

const configModalPresta = {
  api: "detailPrestaPartial",
  classe: ".cart-presta-sm, .cart-presta",
  btnDetails: btnDetailsPresta,
  modal: modalPresta,
  listeModal: listePrestasModal,
  detailModal: detailPrestaModal,
  btnRetourListe: "#btnRetourPresta",
};

afficheDetail(configModalPresta);
retourListe(configModalPresta);

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
  afficheTotalCommande();
};

const actualiserSelect = async (select) => {
  const id = trouverOption(select);
  const donnees = await recupererDonnees(id);
  retrouverBlock(select, donnees);
  if (!donnees) return;
};

const listenerSelect = (selectList) => {
  selectList.forEach((select) => {
    select.addEventListener("change", () => actualiserSelect(select));
  });
};

listenerSelect(selectList);
afficheTotalCommande();

export { actualiserPresta, actualiserSelect };
