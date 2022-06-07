"use strict"

const hamburguesa = document.querySelector('#hamburguesa');
const nav= document.querySelector("nav");
const galeria = document.querySelector("#fotos");

window.onload= async function () {
  const respuesta = await fetch("https://dog.ceo/api/breeds/image/random/6");
  const datos = await respuesta.json();

  const lista_perros = datos["message"];
  lista_perros.forEach(
    (perro) => {
      galeria.appendChild(crearFila(perro));
    });
};


const crearFila = (imagen) => {
  const caja = document.createElement("div");
  caja.classList.add("col-12","col-md-4");

  const ima = document.createElement("img");
  ima.src = imagen;
  ima.classList.add("w-100","shadow-1-strong","rounded","mb-4","img-fluid");
  ima.style.height="250px";

  caja.appendChild(ima);

  return caja;
}
