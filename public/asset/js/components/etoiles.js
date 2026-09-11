const etoiles = document.querySelectorAll(".etoile");

const couleurEtoile = (e) => {
  const note = e.dataset.star;

  etoiles.forEach((s) => {
    const svg = s.querySelector("svg");
    // Je boucle sur chacunes de mes etoiles si data-star
    // est inferieur a celui de l'etoile selectionnée je l'a remplis
    if (s.dataset.star <= note) {
      svg.classList.add("fill-primary");
    } else {
      svg.classList.remove("fill-primary");
    }
  });
};

const supprimerCouleurEtoile = () => {
  const note = document.getElementById("noteAvis").value;

  etoiles.forEach((s) => {
    const svg = s.querySelector("svg");
    if (s.dataset.star <= note) {
      svg.classList.add("fill-primary");
    } else {
      svg.classList.remove("fill-primary");
    }
  });
};

etoiles.forEach((etoile) => {
  etoile.addEventListener("click", () => {
    const note = etoile.dataset.star;
    document.getElementById("noteAvis").value = note;
    couleurEtoile(etoile);
  });
  etoile.addEventListener("mouseover", () => couleurEtoile(etoile));
  etoile.addEventListener("mouseleave", () => supprimerCouleurEtoile());
});
