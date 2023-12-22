document.addEventListener("DOMContentLoaded", function () {

    // fonction qui permet de faire l'animation de défilement des chiffres
    gsap.from(".color-overlay", {
        duration: 2,
        height: "0%",
        ease: "power4.inOut"
    });


    gsap.to(".loader", {
        scale: 40,
        duration: 1.5,
        delay: 2.5,
        ease: "power2.inOut",
    });

    gsap.to(".loader", {
        rotate: 90,
        duration: 1.5,
        delay: 2.5,
        ease: "power2.inOut",
    });

    //permet de faire disparaitre le preloader pour laisser placer à la page d'accueil
    gsap.to(".loading-screen", {
        opacity: 0,
        duration: 1,
        delay: 2.5,
        ease: "power1.inOut",
    });
});