const boutons = document.querySelectorAll('.more');

boutons.forEach(bouton => {
    bouton.addEventListener('click', function() {
        const nomBouton = this.getAttribute('name');

        console.log(nomBouton.toString());
        const postsContent = document.getElementById('.' + nomBouton.toString() + '.');
        console.log(postsContent);
        if (postsContent) {
            postsContent.classList.toggle('active');

            const nbReponses = this.textContent.match(/\((\d+)\)/);
            if (postsContent.classList.contains('active')) {
                this.textContent = `Cacher les réponses ${nbReponses ? nbReponses[0] : ''}`;
            } else {
                this.textContent = `Voir les réponses ${nbReponses ? nbReponses[0] : ''}`;
            }
        }
    });
});