// @ts-check
const hiderButton = document.getElementById('jsonShower');
let hiderDiv = document.getElementsByClassName('initHide')[0];

const toggleShowJSON = () : void => {
    let sendFunc: Function;
    if(hiderDiv.classList.contains('showerDiv')) {
        sendFunc = () => hiderDiv.classList.remove('showerDiv');
        timeout200(sendFunc, 'Show');
    } else {
        sendFunc = () => hiderDiv.classList.add('showerDiv');
        timeout200(sendFunc, 'Hide');
    }
}

const timeout200 = (doThis: Function, theString : String) : void => {
    setTimeout(() => {
        doThis();
        hiderButton.innerHTML = theString+' JSON data?';
    }, 200);
}

hiderButton.addEventListener('click', toggleShowJSON);
