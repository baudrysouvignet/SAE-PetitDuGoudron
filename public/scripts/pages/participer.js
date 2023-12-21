document.querySelector('input[type="submit"]').style.display = 'none';

function opacity() {
    let currentBackgroundColor = getComputedStyle(document.body).backgroundColor;
    let matchRGBA = currentBackgroundColor.match(/(\d+(\.\d+)?)/g);
    if (matchRGBA && matchRGBA.length === 4) {
        let currentOpacity = parseFloat(matchRGBA[3]);
        if (!isNaN(currentOpacity)) {
            let newOpacity = Math.min(1, currentOpacity + 0.1);
            document.body.style.backgroundColor = `rgba(${matchRGBA[0]}, ${matchRGBA[1]}, ${matchRGBA[2]}, ${newOpacity})`;
        }
    }
}

document.getElementById('next').addEventListener('click', function() {
    const startIndex = parseInt(document.getElementById('startIndex').value);
    const step = 3;

    const divs = document.querySelectorAll('.formPart div');
    let endReached = false;

    for (let i = 0; i < divs.length; i++) {
        if (i >= startIndex && i < startIndex + step) {
            divs[i].style.display = 'flex';
        } else {
            divs[i].style.display = 'none';
        }

        if (startIndex + step >= divs.length + 3 || divs.length + 3 - startIndex <= 3) {
            endReached = true;
        }
    }
    opacity();

    document.getElementById('startIndex').value = startIndex + 3;
    if (endReached) {
        document.querySelector('input[type="submit"]').style.display = 'flex';

        document.querySelector('input[name="participer"]').style.display = 'block';
        document.getElementById('next').style.display = 'none'; // Cacher le bouton Suivant

        document.getElementById('MSGinsc').style.display = 'block';

        for (let i = 0; i < divs.length; i++) {
            divs[i].style.display = 'flex';
        }
    }
});