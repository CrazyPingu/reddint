import asyncRequest from './default-ajax.js';

// Add a confirm button to the container
function addConfirmButton(container, fileName, args) {
    if (!container.querySelector('#confirm')) {
        let div = Object.assign(document.createElement('div'), { className: 'confirmDiv', id: 'confirm' });
        let text = Object.assign(document.createElement('p'), { innerText: 'Are you sure?' });
        let buttonYes = Object.assign(document.createElement('button'), { type: 'button', innerText: 'Yes', id: 'buttonYes' });
        buttonYes.addEventListener('click', () => {
            asyncRequest(fileName, (response) => {
                if (container.querySelector('#response')) {
                    container.removeChild(container.querySelector('#response'));
                }
                let responseTag = Object.assign(document.createElement('p'), { className: 'response', id: 'response' });
                if (response) {
                    container.removeChild(div);
                    responseTag.innerText = 'Deleted successfully ';
                    responseTag.appendChild(Object.assign(document.createElement('a'), { href: 'index.php', innerText: 'Go to home page' }));
                } else {
                    container.removeChild(div);
                    responseTag.innerText = 'Error, not deleted';
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
}

export default addConfirmButton;