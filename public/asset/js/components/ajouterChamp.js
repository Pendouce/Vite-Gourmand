const btnAjouter = document.getElementById("btnAjouter");
const divInputContenuePresta = document.getElementById("divInputContenuePresta");
const inputContenuePresta = document.querySelector(".inputContenuePresta");
const btnSupprimerContenu = document.querySelectorAll(".btnSupprimerContenu");

if (btnAjouter) {
  btnAjouter.addEventListener("click", () => {
    nouvelInput();
  });
}

const nouvelInput = () => {
  const nvlDiv = document.createElement("div");
  const nvlInput = inputContenuePresta.cloneNode(true);
  nvlInput.value = "";

  const btnSupprimer = document.createElement("button");
  btnSupprimer.type = "button";
  btnSupprimer.innerHTML = `
  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-8">
    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
  </svg>`;
  btnSupprimer.classList.add("text-primary", "self-center");
  nvlDiv.classList.add("flex", "gap-2");

  nvlDiv.appendChild(nvlInput);
  nvlDiv.appendChild(btnSupprimer);

  /* btnSupprimer.addEventListener("click", () => {
    nvlDiv.remove();
  }); */

  supprimerInput(btnSupprimer, nvlDiv);

  divInputContenuePresta.appendChild(nvlDiv);
};

const supprimerInput = (btn, div) => {
  btn.addEventListener("click", () => {
    div.remove();
  });
};

btnSupprimerContenu.forEach((btn) => {
  supprimerInput(btn, btn.parentElement);
});
