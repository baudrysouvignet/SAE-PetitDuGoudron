window.addEventListener("load", function () {

const tl = gsap.timeline({ paused: true });

// fonction pour ouvrir le menu
function openNav() {
    animateOpenNav();
    const navBtn = document.getElementById("menu-toggle-btn");
    navBtn.onclick = function (e) {
        navBtn.classList.toggle("active");
        tl.reversed(!tl.reversed());
    };
}

openNav();

// fonction pour animer le menu avec GSAP
function animateOpenNav() {
    tl.to("#nav-container", 0.2, {
        autoAlpha: 1,
        delay: 0.1,
    });

    tl.from(".flex > div", 0.4, {
        opacity: 0,
        y: 10,
        stagger: {
            amount: 0.04,
        },
    });

    tl.to(
        ".nav-link > a",
        0.8,
        {
            top: -15,
            ease: "power2.inOut",
            stagger: {
                amount: 0.1,
            },
        },
        "-=0.4"
    ).reverse();
}
});

