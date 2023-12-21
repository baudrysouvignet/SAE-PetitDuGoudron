window.addEventListener('load', function() {

    // objet contenant les infos des membres de l'équipe
    const team = [
        { name: "Gaspard Michel", role: "aka Atlassian god" },
        { name: "Baudry Souviget", role: "aka PHP alien" },
        { name: "Pierrick Boutte", role: "aka designer drip" },
        { name: "Mathis Oudin", role: "aka JS cook" },
    ];

    const cursor = document.querySelector('.cursor');
    const cursorIcon = cursor.querySelector('i');

    const cursorWidth = cursor.offsetWidth / 2;
    const cursorHeight = cursor.offsetHeight / 2;

    let currentSlide = 1;
    const totalSlides = 4;

    // fonction fléchée qui met à jour la classe du curseur
    const updateCursorClass = (xPosition) => {
        const halfPageWidth = window.innerWidth / 2;
        if (xPosition > halfPageWidth) {
            if (currentSlide < totalSlides) {
                cursorIcon.classList.remove('ph-arrow-left');
                cursorIcon.classList.add('ph-arrow-right');
                cursor.style.display = '';
            } else {
                cursor.style.display = 'none';
            }
        } else {
            if (currentSlide > 1) {
                cursorIcon.classList.remove('ph-arrow-right');
                cursorIcon.classList.add('ph-arrow-left');
                cursor.style.display = '';
            } else {
                cursor.style.display = 'none';
            }
        }
    }

    // fonction fléchée qui gère le mouvement de la souris
    document.addEventListener('mousemove', (e) => {
        gsap.to(cursor, {
            x: e.clientX - cursorWidth,
            y: e.clientY - cursorHeight,
            duration: 1,
            ease: "power3.out"
        });

        updateCursorClass(e.clientX);
    });

    // fonction fléchée qui met à jour les infos des membres de l'équipe
    const updateInfo = (slideNumber) => {
        const member = team[slideNumber - 1];
        document.querySelector('.info .name').textContent = member.name;
        document.querySelector('.info .role').textContent = member.role;
    };

    // fonction fléchée qui anime le slide
    const animateSlide = (slideNumber, reveal) => {
        const nom = document.querySelector(`.bg-${slideNumber}.nom-wrapper`);
        const img = document.getElementById(`bg-${slideNumber}`);
        const clipPathValue = reveal ? 'polygon(0% 100%, 100% 100%, 100% 0%, 0% 0%)' : 'polygon(0 100%, 100% 100%, 100% 100%, 0% 100%)';

        gsap.to(nom, { clipPath: clipPathValue, duration: 1, ease: "power4.out", delay: 0.3 });

        gsap.to(img, { clipPath: clipPathValue, duration: 1, ease: "power4.out" });
    };

    updateInfo(currentSlide);

    // fonction fléchée qui gère le clic sur la flèche droite
    const handleRightClick = () => {
        if (currentSlide < totalSlides) {
            animateSlide(currentSlide + 1, true);
            currentSlide++;
            updateInfo(currentSlide);
        }
    }

    // fonction fléchée qui gère le clic sur la flèche gauche
    const handleLeftClick = () => {
        if (currentSlide > 1) {
            animateSlide(currentSlide, false);
            currentSlide--;
            updateInfo(currentSlide);
        }
    }

    // fonction fléchée qui gère le clic sur la flèche droite
    document.addEventListener('click', (e) => {
        const halfPageWidth = window.innerWidth / 2;
        if (e.clientX > halfPageWidth) {
            handleRightClick();
        } else {
            handleLeftClick();
        }
    })
});