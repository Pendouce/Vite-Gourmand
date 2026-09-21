console.log("Hello modif");

const csrfToken = document.getElementById("csrfToken");
//const input = document.createElement("input");

if (document.getElementById("nbStock")) {
  const btnValideModifStock = document.getElementById("btnValideModifStock");
  const btnAnnuleModifStock = document.getElementById("btnAnnuleModifStock");
  const divBtnValideAnnule = document.getElementById("divBtnValideAnnule");
  const btnModifStock = document.getElementById("btnModifStock");
  const nbStock = document.getElementById("nbStock");

  //const csrfToken = document.getElementById("csrfToken");

  const input = document.createElement("input");

  btnModifStock.addEventListener("click", () => {
    afficheBtnValideAnnule({
      inputType: "number",
      valeur: nbStock.textContent.trim(),
      btnModif: btnModifStock,
      contenu: nbStock,
      input,
      divBtnValideAnnule,
    });
  });

  btnAnnuleModifStock.addEventListener("click", () => {
    afficheBtnModif({
      input,
      divBtnValideAnnule,
      contenu: nbStock,
      valeur: nbStock.textContent.trim(),
      btnModif: btnModifStock,
    });
  });

  valideModif({
    btn: btnValideModifStock,
    nomId: "id",
    id: btnValideModifStock.dataset.id,
    nomChamp: "stock_plat",
    route: "/modifierStockPlat",
    contenu: nbStock,
    btnModif: btnModifStock,
    input,
    divBtnValideAnnule,
  });
}
/////////////////////////////////////////////////////////

if (document.querySelector(".typeLibelle")) {
  const divTypeDePlat = document.querySelectorAll(".divTypeDePlat");

  divTypeDePlat.forEach((type) => {
    const typeLibelle = type.querySelector(".typeLibelle");
    const divBtnValideAnnule = type.querySelector(".divBtnValideAnnule");
    const btnSupprime = type.querySelector(".btnSupprime");
    const btnModifTypeDePlat = type.querySelector(".btnModifTypeDePlat");
    const btnValideModifPlat = type.querySelector(".btnValideModifPlat");
    const btnAnnuleModifPlat = type.querySelector(".btnAnnuleModifPlat");
    const divSuppressionConfirm = type.querySelector(".divSuppressionConfirm");
    const id = type.dataset.id;
    const input = document.createElement("input");

    btnModifTypeDePlat.addEventListener("click", () => {
      afficheBtnValideAnnule({
        inputType: "text",
        valeur: typeLibelle.textContent.trim(),
        btnModif: btnModifTypeDePlat,
        contenu: typeLibelle,
        input,
        divBtnValideAnnule,
      });
    });

    btnAnnuleModifPlat.addEventListener("click", () => {
      afficheBtnModif({
        input,
        divBtnValideAnnule,
        contenu: typeLibelle,
        valeur: typeLibelle.textContent.trim(),
        btnModif: btnModifTypeDePlat,
      });
    });

    btnSupprime.addEventListener("click", () => {
      btnSupprime.classList.add("hidden");
      divSuppressionConfirm.classList.remove("hidden");
      divSuppressionConfirm.classList.add("block");

      setTimeout(() => {
        btnSupprime.classList.remove("hidden");
        divSuppressionConfirm.classList.add("hidden");
        divSuppressionConfirm.classList.remove("block");
      }, 5000);
    });

    valideModif({
      btn: btnValideModifPlat,
      nomId: "type_id",
      id: id,
      nomChamp: "libelle",
      route: "/modifierTypeDePlat",
      contenu: typeLibelle,
      btnModif: btnModifTypeDePlat,
      input,
      divBtnValideAnnule,
    });
  });
}

function valideModif({ input, divBtnValideAnnule, btn, nomId, id, nomChamp, route, contenu, btnModif }) {
  btn.addEventListener("click", async () => {
    //const id = btnValideModifStock.dataset.id;
    const csrf = csrfToken.value;
    const nouveauContenueInput = input.value;
    const divBlockRetourMessage = btn.closest(".contenue-page").querySelector(".blockRetour");

    const formData = new FormData();
    formData.append("csrfToken", csrf);
    formData.append(nomId, id);
    formData.append(nomChamp, nouveauContenueInput);

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
        afficheBtnModif({ input, divBtnValideAnnule, valeur: nouveauContenueInput, contenu, btnModif });
      } else {
        divBlockRetourMessage.textContent = result.message;
        divBlockRetourMessage.classList.remove("succes");
        divBlockRetourMessage.classList.add("erreur");
      }

      setTimeout(() => {
        divBlockRetourMessage.textContent = "";
        divBlockRetourMessage.classList.remove("succes", "erreur");
      }, 3000);
    } catch (e) {
      console.error(e);
    }
  });
}

const afficheBtnModif = ({ input, divBtnValideAnnule, contenu, valeur, btnModif }) => {
  contenu.textContent = valeur;
  input.replaceWith(contenu);
  btnModif.classList.remove("hidden");
  divBtnValideAnnule.classList.remove("block");
  divBtnValideAnnule.classList.add("hidden");
};

const afficheBtnValideAnnule = ({ input, divBtnValideAnnule, inputType, valeur, btnModif, contenu }) => {
  input.type = inputType;
  input.value = valeur;
  input.classList.add("grand-input", "items-center", "justify-center", "text-center", "w-30");
  contenu.replaceWith(input);

  btnModif.classList.add("hidden");
  divBtnValideAnnule.classList.remove("hidden");
  divBtnValideAnnule.classList.add("block");
};
