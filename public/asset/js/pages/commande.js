import { ouvrirModal, fermerModal } from "./modales.js";
console.log("commande ok");

const inputPrixTotal = document.getElementById("inputPrixTotal");
const prixPresta = document.querySelector(".prixPresta");
const prixTotalPresta = document.querySelector(".prixTotalPresta");
const seuilPresta = document.querySelector(".seuilPresta");
const btnModalPresta = document.querySelector(".btnModalPresta");
const prixTotalMenu = document.getElementById("prixTotalMenu");
const prixUnitaireMenu = document.getElementById("prixUnitaireMenu");
const btnAjouterBoisson = document.getElementById("btnAjouterBoisson");
const btnAjouterMenu = document.getElementById("btnAjouterMenu");
const modalMenu = document.getElementById("modalMenu");
const modalBoisson = document.getElementById("modalBoisson");
const modalPresta = document.getElementById("modalPresta");

const boite = document.querySelector("#modalMenu > div");
console.log(boite.clientHeight, boite.scrollHeight, getComputedStyle(boite).overflowY);
