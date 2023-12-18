
window.addEventListener("load", function () {
document.addEventListener("DOMContentLoaded", function () {
    const counter3 = document.querySelector(".counter-3");

    for (let i = 0; i < 2; i++) {
        for (let j = 0; j < 10; j++) {
            const div = document.createElement("div");
            div.className = "num";
            div.textContent = j;
            counter3.appendChild(div);
        }
    }
    const finalDiv = document.createElement("div");
    finalDiv.className = "num";
    finalDiv.textContent = "0";
    counter3.appendChild(finalDiv);

    function animate(counter, duration, delay = 0) {
        const numHeight = counter.querySelector(".num").clientHeight;
        const totalDistance =
            (counter.querySelectorAll(".num").length - 1) * numHeight;
        gsap.to(counter, {
            y: -totalDistance,
            duration: duration,
            delay: delay,
            ease: "power2.inOut",
        });
    }

    animate(counter3, 4);
    animate(document.querySelector(".counter-2"), 5);
    animate(document.querySelector(".counter-1"), 3, 2);
});

gsap.to(".digit", {
    top: "-150px",
    stagger: {
        amount: 0.25,
    },
    delay: 4.4,
    duration: 1,
    ease: "power4.inOut",
});

gsap.from(".color-overlay", {
    duration: 5,
    height: "0%",
    ease: "power4.inOut"
});


gsap.to(".loader", {
    scale: 40,
    duration: 1,
    delay: 5,
    ease: "power2.inOut",
});

gsap.to(".loader", {
    rotate: 90,
    duration: 1,
    delay: 5,
    ease: "power2.inOut",
});

gsap.to(".loading-screen", {
    opacity: 0,
    duration: 0.5,
    delay: 6.5,
    ease: "power1.inOut",
});
// on split le texte lettres par lettres pour les animer une par une
const textPreloader = new SplitType('#text_preloader');

gsap.to('.char', {
    y: 0,
    stagger: 0.05,
    delay: 0,
    duration: .05,
    repeat: -1,
    repeatDelay: 0.45,
    yoyo: true,
})
});