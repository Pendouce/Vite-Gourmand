if (document.getElementById("nbStock")) {
  const btnModifStock = document.getElementById("btnModifStock");
  const nbStock = document.getElementById("nbStock");
  const divBtnValideAnnule = document.getElementById("divBtnValideAnnule");
  const btnValideModifStock = document.getElementById("btnValideModifStock");
  const btnAnnuleModifStock = document.getElementById("btnAnnuleModifStock");
  const csrfToken = document.getElementById("csrfToken");

  const input = document.createElement("input");
  let stock = nbStock.textContent.trim();

  btnModifStock.addEventListener("click", () => {
    afficheBtnValideAnnule();
  });

  btnAnnuleModifStock.addEventListener("click", () => {
    afficheBtnModif(stock);
  });

  btnValideModifStock.addEventListener("click", async () => {
    const id = btnValideModifStock.dataset.id;
    const csrf = csrfToken.value;
    const nouveauStock = input.value;
    const divBlockRetourMessage = btnValideModifStock.closest(".contenue-page").querySelector(".blockRetour");

    const formData = new FormData();
    formData.append("csrfToken", csrf);
    formData.append("id", id);
    formData.append("stock_plat", nouveauStock);

    try {
      const res = await fetch("/modifierStockPlat", {
        method: "POST",
        body: formData,
      });
      const result = await res.json();

      if (result.succes) {
        divBlockRetourMessage.textContent = result.message;
        divBlockRetourMessage.classList.remove("erreur");
        divBlockRetourMessage.classList.add("succes");
        stock = nouveauStock;
        afficheBtnModif(stock);
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

  const afficheBtnModif = (value) => {
    nbStock.textContent = value;
    input.replaceWith(nbStock);
    btnModifStock.classList.remove("hidden");
    divBtnValideAnnule.classList.remove("block");
    divBtnValideAnnule.classList.add("hidden");
  };
  const afficheBtnValideAnnule = () => {
    input.type = "number";
    input.value = stock;
    input.classList.add("grand-input", "items-center", "justify-center", "text-center", "w-30");
    nbStock.replaceWith(input);

    btnModifStock.classList.add("hidden");
    divBtnValideAnnule.classList.remove("hidden");
    divBtnValideAnnule.classList.add("block");
  };
}

if (document.querySelector(".typeLibelle")) {
  const divTypeDePlat = document.querySelectorAll(".divTypeDePlat");
  let typeDePlat = typeLibelle.textContent.trim();

  divTypeDePlat.forEach((type) => {
    const typeLibelle = type.querySelector(".typeLibelle");
    const divBtnValideAnnule = type.querySelector(".divBtnValideAnnule");
    const btnSupprime = type.querySelector(".btnSupprime");
    const btnModifTypeDePlat = type.querySelector(".btnModifTypeDePlat");
    const btnValideModifStock = type.querySelector(".btnValideModifStock");
    const btnAnnuleModifStock = type.querySelector(".btnAnnuleModifStock");
    const id = type.dataset.id;
    btnModifTypeDePlat.addEventListener("click", () => {
      console.log(id);
    });
  });
}
