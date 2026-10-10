//console.log("filtre ok");

const menuListe = document.getElementById("menuListe");
const divFiltre = document.getElementById("divFiltre");
const formMenuFiltre = document.getElementById("formMenuFiltre");
const btnFiltre = document.getElementById("btnFiltre");
const divNbFiltreSelectione = document.getElementById("divNbFiltreSelectione");
const nbFiltreSelectione = document.getElementById("nbFiltreSelectione");
const btnReinitialiser = document.getElementById("btnReinitialiser");
const btnFermerFiltre = document.getElementById("btnFermerFiltre");
const btnPlus = document.getElementById("btnPlus");
const btnMoin = document.getElementById("btnMoin");
const nbPersonneMinInput = document.getElementById("nbPersonneMin");
const sliderPrixInput = document.getElementById("sliderPrixInput");
const rangeValue = document.getElementById("rangeValue");
const rangeValueDiv = document.getElementById("rangeValueDiv");

/* Ouvrir filtre */
btnFiltre.addEventListener("click", () => {
  divFiltre.classList.remove("hidden");
  divFiltre.classList.add("block");
});

/* Fermer filtre */
const fermerFiltre = () => {
  divFiltre.classList.remove("block");
  divFiltre.classList.add("hidden");
};

btnFermerFiltre.addEventListener("click", fermerFiltre);

document.addEventListener("click", (e) => {
  if (!divFiltre.contains(e.target) && !btnFiltre.contains(e.target)) {
    fermerFiltre();
  }
});

/* Reinitialiser filtre */
btnReinitialiser.addEventListener("click", () => {
  formMenuFiltre.reset();
  sliderTouche = false;
  menuFiltre();
  slider();
  divNbFiltreSelectione.classList.remove("block");
  divNbFiltreSelectione.classList.add("hidden");
});

const slider = () => {
  rangeValue.textContent = sliderPrixInput.value;

  const min = Number(sliderPrixInput.min);
  const max = Number(sliderPrixInput.max);
  const progression = (Number(sliderPrixInput.value) - min) / (max - min);

  const distance = sliderPrixInput.offsetWidth - 20;
  const position = 10 + progression * distance;

  rangeValueDiv.style.left = position + "px";
};

let sliderTouche = false;

sliderPrixInput.addEventListener("input", () => {
  sliderTouche = true;
  slider();
});

const nbPersonneMin = () => {
  btnPlus.addEventListener("click", () => {
    let nb = Number(nbPersonneMinInput.value) + 1;
    nbPersonneMinInput.value = nb;
    nbPersonneMinInput.dispatchEvent(new Event("change", { bubbles: true }));
  });

  btnMoin.addEventListener("click", () => {
    let nb = Number(nbPersonneMinInput.value);
    if (nb > 0) {
      nb -= 1;
      nbPersonneMinInput.value = nb;
      nbPersonneMinInput.dispatchEvent(new Event("change", { bubbles: true }));
    }
  });

  nbPersonneMinInput.addEventListener("input", menuFiltre);
};

const menuFiltre = async () => {
  const formData = new FormData(formMenuFiltre);
  const filtre = new URLSearchParams(formData);

  if (!sliderTouche) {
    filtre.delete("prix_personne");
  }

  try {
    const res = await fetch(`/menuFiltre?${filtre.toString()}`, {
      method: "GET",
    });

    const result = await res.text();
    menuListe.innerHTML = result;
  } catch (e) {
    console.error(e);
  }
};

const afficheNbMenuFiltre = () => {
  const caseCoche = document.querySelectorAll(".evenements:checked, .themes:checked, .regimes:checked");

  let nb = caseCoche.length;

  if (sliderTouche) nb++;

  if (nbPersonneMinInput.value > 0) nb++;

  if (nb > 0) {
    divNbFiltreSelectione.classList.remove("hidden");
    divNbFiltreSelectione.classList.add("block");
    nbFiltreSelectione.textContent = nb;
  }
};

formMenuFiltre.addEventListener("change", () => {
  menuFiltre();
  afficheNbMenuFiltre();
});

formMenuFiltre.addEventListener("submit", (e) => {
  e.preventDefault();
});

nbPersonneMin();
slider();
