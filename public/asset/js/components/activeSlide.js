const csrfToken = document.getElementById("csrfToken");
const toggleInputPlat = document.querySelectorAll("input[name=plat_actif]");
const divBlockRetourMessage = document.getElementById("blockRetour");

function gererToggle(toggleInputNom, statutNom, route) {
  toggleInputNom.forEach((toggleInput) => {
    toggleInput.addEventListener("change", async () => {
      const id = toggleInput.dataset.id;
      const csrf = csrfToken.value;
      // Je convertie le boolen en nombre
      const statut = +toggleInput.checked;
      // Equivalent de form en html
      // append(name="", value="")
      const formData = new FormData();
      formData.append(statutNom, statut);
      formData.append("id", id);
      formData.append("csrfToken", csrf);

      try {
        const res = await fetch(route, {
          method: "POST",
          body: formData,
        });
        const result = await res.json();

        if (result.succes) {
          divBlockRetourMessage.textContent = result.message;
          divBlockRetourMessage.classList.remove("erreur");
          divBlockRetourMessage.classList.add("succes");
        } else {
          divBlockRetourMessage.textContent = result.message;
          divBlockRetourMessage.classList.remove("succes");
          divBlockRetourMessage.classList.add("erreur");
          toggleInput.checked = !toggleInput.checked;
        }

        // J'affiche le message pendant 3 sec
        setTimeout(() => {
          divBlockRetourMessage.textContent = "";
          divBlockRetourMessage.classList.remove("succes", "erreur");
        }, 3000);
      } catch (e) {
        console.error(e);
      }
    });
  });
}

gererToggle(toggleInputPlat, "plat_actif", "/modifierStatusPlat");

/* toggleInputPlat.forEach((toggleInput) => {
  toggleInput.addEventListener("change", async () => {
    const idPlat = toggleInput.dataset.id;
    const csrf = csrfToken.value;
    // Je convertie le boolen en nombre
    const statut = +toggleInput.checked;
    // Equivalent de form en html
    // append(name="", value="")
    const formData = new FormData();
    formData.append("plat_actif", statut);
    formData.append("id", idPlat);
    formData.append("csrfToken", csrf);

    try {
      const res = await fetch("/modifierStatusPlat", {
        method: "POST",
        body: formData,
      });
      const result = await res.json();

      if (result.succes) {
        divBlockRetourMessage.textContent = result.message;
        divBlockRetourMessage.classList.remove("erreur");
        divBlockRetourMessage.classList.add("succes");
      } else {
        divBlockRetourMessage.textContent = result.message;
        divBlockRetourMessage.classList.remove("succes");
        divBlockRetourMessage.classList.add("erreur");
        toggleInput.checked = !toggleInput.checked;
      }

      // J'affiche le message pendant 3 sec
      setTimeout(() => {
        divBlockRetourMessage.textContent = "";
        divBlockRetourMessage.classList.remove("succes", "erreur");
      }, 3000);
    } catch (e) {
      console.error(e);
    }
  }); */
