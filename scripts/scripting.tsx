// @ts-check
const hiderButton = document.getElementById('jsonShower');
hiderButton.addEventListener('click', toggleShowJSON);

function toggleShowJSON() {
    let hiderDiv = document.getElementsByClassName('initHide')[0];
    if(hiderDiv.classList.contains('showerDiv')) {
        hiderDiv.classList.remove('showerDiv');
        hiderButton.innerHTML = 'Show JSON data?';
    } else {
        hiderDiv.classList.add('showerDiv');
        hiderButton.innerHTML = 'Hide JSON data?';
    }
}