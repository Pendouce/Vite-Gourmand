const lienNavbar = document.getElementById("navLiens");
const divMenuBurger = document.getElementById("divMenuBurger");
const burgerBtn = document.getElementById("btnBurger");

burgerBtn.addEventListener("click", () => {
  const $ouvert = burgerBtn.getAttribute("aria-expanded") === "true";

  burgerBtn.setAttribute("aria-expanded", !$ouvert);
  lienNavbar.classList.toggle("hidden");
});

document.addEventListener("click", (e) => {
  burgerBtn.setAttribute("aria-expanded", "false");

  if (!burgerBtn.contains(e.target) && !divMenuBurger.contains(e.target)) {
    lienNavbar.classList.add("hidden");
  }
});
