import "./components/burgerNav.js";
import "./pages/modales.js";
import "./components/etoiles.js";
import "./components/activeSlide.js";
import "./pages/previewImage.js";
import "./pages/imageSurvol.js";
//import "./components/modifStock.js";
import "./components/modif.js";
import "./components/filtre.js";
import "./components/ajouterChamp.js";
const formulaire = document.getElementById("formCommande");

if (formulaire) {
  await import("./pages/commande/commande.js");
}
//import "./pages/commande/commande.js";
