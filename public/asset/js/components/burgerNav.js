const lienNavbar = document.getElementById("navLiens");
const burgerBtn = document.getElementById("btnBurger");

burgerBtn.addEventListener("click", () => {
  const $ouvert = burgerBtn.getAttribute("aria-expanded") === "true";

  burgerBtn.setAttribute("aria-expanded", !$ouvert);
  lienNavbar.classList.toggle("hidden");
});
