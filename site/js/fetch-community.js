import asyncRequest from './default-ajax.js';
import { generateElements } from './elementGenerators.js';
import addConfirmButton from './add-buttons.js';

function toggleParticipation(button, communityName) {
    button.addEventListener('click', function () {
        asyncRequest('request-community.php', (response) => {
            if (response) {
                button.innerText = button.innerText == 'Join' ? 'Leave' : 'Join';
            } else {
                window.location.href = 'login.php';
            }
        }, { communityName, type: 'participation' });
    });
}

let space = '';
let type = '';
let offset = 0;
let baseOffset = 10;
const args = { type, offset, limit: baseOffset };

function render(container, args) {
    asyncRequest('request-community.php', (response) => {
        generateElements(response, container, args.type);
    }, args);
}


window.onload = function () {
    const communityInformations = document.getElementsByClassName('communityInformations');

    if (document.getElementsByClassName('spacePosts')[0]) {
        space = document.getElementsByClassName('spacePosts')[0];
        args.communityName = communityInformations[0].id;
        type = 'post';
        toggleParticipation(document.getElementById('participateButton'), communityInformations[0].id);
    } else {
        space = document.getElementsByClassName('spaceCommunities')[0];
        type = 'community';
    }

    args.type = type;
    render(space, args);

    const editButton = document.getElementById('editButton');
    if (editButton) {
        editButton.addEventListener('click', () => {
            const description = document.getElementById('communityDescription');
            description.contentEditable = true;
            description.classList.add('editable');

            let saveButton = Object.assign(document.createElement('button'), { id: 'saveButton', innerText: 'Save' });
            communityInformations[0].appendChild(saveButton);
            saveButton.addEventListener('click', () => {
                asyncRequest('request-community.php', (response) => {
                    if (response) {
                        description.contentEditable = false;
                        description.classList.remove('editable');
                        communityInformations[0].removeChild(saveButton);
                    } else {
                        let errorTag = document.createElement('p');
                        errorTag.append('Error, description not changed');
                        document.getElementById('editSpace').appendChild(errorTag);
                        communityInformations[0].appendChild(errorTag);
                    }
                }, { type: 'edit', nameCommunity: communityInformations[0].id, description: description.innerText });
            });
        });
    }
    const deleteButton = document.getElementById('deleteButton');
    if (deleteButton) {
        deleteButton.addEventListener('click', () => {
            let args = { type: 'delete', nameCommunity: communityInformations[0].id};
            addConfirmButton(document.getElementById('editSpace'), 'request-community.php', args);
        });
    }

    space.addEventListener('scroll', () => {
        if (space.scrollTop + space.clientHeight >= space.scrollHeight) {
            offset += baseOffset;
            args.offset = offset;
            render(space, args);
        }
    });
}

