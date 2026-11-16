import "./menu.js";
import "./boisson.js";

/* ENVOI DE LA COMMANDE */
const formulaire = document.getElementById("formCommande");
const divBlockRetourMsg = document.querySelector(".divBlockRetourMsg");
const msgSucces = document.getElementById("msgSucces");

const envoyerDonneesCommande = async () => {
  try {
    const formData = new FormData(formulaire);
    const res = await fetch(formulaire.action, {
      method: "POST",
      body: formData,
    });
    console.log(await res.clone().text());
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
        console.log("Les 5 secondes sont écoulées");
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
});
