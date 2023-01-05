import asyncRequest from './default-ajax.js';

function addConfirmButton(container, fileName, args) {
    let div = Object.assign(document.createElement('div'), { id: 'confirm' });
    let text = Object.assign(document.createElement('p'), { innerText: 'Are you sure?' });
    let buttonYes = Object.assign(document.createElement('button'), { type: 'button', innerText: 'Yes', id: 'buttonYes' });
    buttonYes.addEventListener('click', () => {
        asyncRequest(fileName, (response) => {
            let responseTag = document.createElement('p');
            if (response) {
                container.removeChild(div);
                responseTag.append('Deleted successfully ');
                responseTag.appendChild(Object.assign(document.createElement('a'), { href: 'index.php', innerText: 'Go to home page' }));
            } else {
                container.removeChild(div);
                responseTag.append('Error, community not deleted');
            }
            container.appendChild(responseTag);
        }, args);
    });
    let buttonNo = Object.assign(document.createElement('button'), { type: 'button', innerText: 'No', id: 'buttonNo' });
    buttonNo.addEventListener('click', () => {
        container.removeChild(div);
    });
    div.appendChild(text);
    div.appendChild(buttonYes);
    div.appendChild(buttonNo);
    container.appendChild(div);
}

export default addConfirmButton;