const imgInputPlat = document.getElementById("image_plat");
const imgInputBoisson = document.getElementById("photo_boisson");
const igmLabel = document.getElementById("igmLabel");
const imgHtml = document.querySelector(".imgHtml");

const img = document.createElement("img");
img.classList.add("absolute", "inset-0", "w-full", "h-full", "object-cover", "opacity-70");

if (imgInputPlat) {
  gererImage(imgInputPlat);
}

if (imgInputBoisson) {
  gererImage(imgInputBoisson);
}

function gererImage(image) {
  image.addEventListener("change", () => {
    const reader = new FileReader();
    if (image.files && image.files[0]) {
      reader.onload = () => {
        img.src = reader.result;

        if (imgHtml) {
          imgHtml.classList.add("hidden");
        }
        igmLabel.appendChild(img);
        igmLabel.classList.add("relative", "overflow-hidden");
      };
      reader.readAsDataURL(image.files[0]);
    }
  });
}
