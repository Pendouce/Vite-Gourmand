const plats = document.querySelectorAll(".divPlat");
const imgPlat = document.getElementById("imgPlat");

plats.forEach((plat) => {
  plat.addEventListener("mouseenter", () => {
    imgPlat.src = plat.dataset.img;
  });
});
