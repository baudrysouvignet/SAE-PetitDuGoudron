const navLinks = document.querySelectorAll('.navManagement a');

function hideAllContentDivs() {
    const contentDivs = document.querySelectorAll('.itemsInsc');
    contentDivs.forEach(div => {
        div.classList.remove('activate');
    });
}

navLinks.forEach(link => {
    link.addEventListener('click', function (event) {
        event.preventDefault();

        hideAllContentDivs();

        const linkId = this.id;
        const contentDiv = document.querySelector(`.itemsInsc[data-id="${linkId}"]`);
        if (contentDiv) {
            contentDiv.classList.toggle('activate');
        }
    });
});