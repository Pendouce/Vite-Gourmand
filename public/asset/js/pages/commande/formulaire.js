const divBlockRetourMsg = document.querySelector(".divBlockRetourMsg");
const msgSucces = document.getElementById("msgSucces");

const envoyerDonneesCommande = async (form) => {
  try {
    const formData = new FormData(form);
    const res = await fetch(form.action, {
      method: "POST",
      body: formData,
    });

    const resultt = await res.clone().text();
    console.log(resultt);

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

export async function brancherFormulaire(form) {
  form.addEventListener("submit", async (event) => {
    event.preventDefault();

    const btn = event.submitter;
    btn.disabled = true;
    divBlockRetourMsg.innerHTML = "";
    divBlockRetourMsg.classList.remove("erreur");

    await envoyerDonneesCommande(form);

    if (divBlockRetourMsg.classList.contains("erreur")) {
      btn.disabled = false;
    }
  });
}
