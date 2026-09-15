const imgInput = document.getElementById("image_plat");
const igmLabel = document.getElementById("igmLabel");

const img = document.createElement("img");
img.classList.add("absolute", "inset-0", "w-full", "h-full", "object-cover", "opacity-70");

imgInput.addEventListener("change", () => {
  const reader = new FileReader();
  if (imgInput.files && imgInput.files[0]) {
    reader.onload = () => {
      img.src = reader.result;

      igmLabel.appendChild(img);
      igmLabel.classList.add("relative", "overflow-hidden");
    };
    reader.readAsDataURL(imgInput.files[0]);
  }
});
