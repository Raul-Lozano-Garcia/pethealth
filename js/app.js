"use strict"

const hamburguesa = document.querySelector('#hamburguesa');
const nav= document.querySelector("nav");

// Abrir y cerrar nav y movil
hamburguesa.addEventListener('click', () => {
    hamburguesa.classList.toggle('nav-open');
    nav.classList.toggle('nav-open');
});