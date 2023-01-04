import asyncRequest from './default-ajax.js';
import obtainData from './form-data.js';

const changeDescriptionForm = document.getElementById('changeDescription');
const nameCommunity = changeDescriptionForm.getAttribute('data-id');
changeDescriptionForm.addEventListener('submit', (e) => {
    e.preventDefault();
    let args = obtainData(new FormData(changeDescriptionForm));
    args.type = 'change';
    args.nameCommunity = nameCommunity;
    asyncRequest('request-community.php', (response) => {
        let responseTag = document.createElement('p');
        if (response) {
            responseTag.append('Description changed successfully');
        } else {
            responseTag.append('Error, description not changed');
        }
        changeDescriptionForm.appendChild(responseTag);
    }, args);
});

document.getElementById('deleteCommunity').addEventListener('click', () => {
    let args = { type: 'delete', nameCommunity};
    asyncRequest('request-community.php', (response) => {
        let responseTag = document.createElement('p');
        if (response) {
            responseTag.append('Community deleted successfully ');
            responseTag.appendChild(Object.assign(document.createElement('a'), {href: 'index.php', innerText: 'Go to home page'}));
        } else {
            responseTag.append('Error, community not deleted');
        }
        let buttonDiv = document.getElementsByClassName('deleteButton')[0];
        buttonDiv.appendChild(responseTag);
    }, args);
});