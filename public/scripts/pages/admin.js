const navLinks = document.querySelectorAll('.navManagement a');
const pages = {
    'compteManagement': 'user',
    'inscManagement': 'part',
    'calManagement': 'cal'
};

// Param Get on the url
const urlParams = new URLSearchParams(window.location.search);
const fieldParam = urlParams.get('field');

function activateLinkAndContent(linkId) {

    navLinks.forEach(navLink => navLink.classList.remove('active'));
    const targetLink = document.getElementById(linkId);
    if (targetLink) {
        targetLink.classList.add('active');

        const contentId = pages[linkId];
        const contentElements = document.querySelectorAll('.content > div');
        contentElements.forEach(element => element.classList.remove('activate'));

        const contentElement = document.querySelector(`.content .${contentId}`);
        contentElement.classList.add('activate');

        const url = new URL(window.location.href);
        url.searchParams.set('field', linkId);
        window.history.pushState({}, '', url);

    }
}

if (fieldParam && pages[fieldParam]) {
    activateLinkAndContent(fieldParam);
}

navLinks.forEach(link => {
    link.addEventListener('click', function () {
        const linkId = this.id;
        activateLinkAndContent(linkId);
    });
});