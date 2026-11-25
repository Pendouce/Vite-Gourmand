import "./menu.js";
import "./boisson.js";
import { brancherFormulaire } from "./formulaire.js";
/* ENVOI DE LA COMMANDE */
const formulaire = document.getElementById("formCommande");

brancherFormulaire(formulaire);
/* const divBlockRetourMsg = document.querySelector(".divBlockRetourMsg");
const msgSucces = document.getElementById("msgSucces");

const envoyerDonneesCommande = async () => {
  try {
    const formData = new FormData(formulaire);
    const res = await fetch(formulaire.action, {
      method: "POST",
      body: formData,
    });

    const result = await res.json();
    console.log(result);

    if (result.succes) {
      window.location.href = result.redirection;
    } else {
      divBlockRetourMsg.textContent = result.message;
      divBlockRetourMsg.classList.add("erreur");
      divBlockRetourMsg.scrollIntoView({ behavior: "smooth", block: "center" });
    }

    if (msgSucces) {
      setTimeout(() => {
        msgSucces.remove();
      }, 5000);
    }
  } catch (e) {
    console.error(e);
    divBlockRetourMsg.innerHTML = "Une erreur est survenue, réessayez.";
    divBlockRetourMsg.classList.add("erreur");
    divBlockRetourMsg.scrollIntoView({ behavior: "smooth", block: "center" });
  }
};

formulaire.addEventListener("submit", async (event) => {
  event.preventDefault();

  const btn = event.submitter;
  btn.disabled = true;
  divBlockRetourMsg.innerHTML = "";
  divBlockRetourMsg.classList.remove("erreur");

  await envoyerDonneesCommande();

  if (divBlockRetourMsg.classList.contains("erreur")) {
    btn.disabled = false;
  }
}); */
