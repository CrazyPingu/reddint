import asyncRequest from './default-ajax.js';
import { generateElements } from './elementGenerators.js';
import addConfirmButton from './add-buttons.js';
import { throttle } from './throttle.js';

// Toggle the participation of a user in a community
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

// Render the elements in the page
function render(container, args) {
    asyncRequest('request-community.php', (response) => {
        if (response.length == 0) {
            let noPostsCommunities = document.getElementById('no-more-posts-communities');
            if (noPostsCommunities) return;
            const message = `No more ${args.type == 'post' ? 'posts' : 'communities'} to show`
            noPostsCommunities = Object.assign(document.createElement('p'), {id: 'no-more-posts-communities', className: 'no-result', innerText: message});
            container.appendChild(noPostsCommunities);
            return;
        }
        generateElements(response, container, args.type);
    }, args);
}

window.onload = function () {
    const communityInformations = document.getElementsByClassName('communityInformations');

    // Assign the space where the elements will be rendered
    if (document.getElementsByClassName('postContainer')[0]) {
        space = document.getElementsByClassName('postContainer')[0];
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

            if (!document.getElementById('saveButton')) {
                let saveButton = Object.assign(document.createElement('button'), { id: 'saveButton', innerText: 'Save' });
                communityInformations[0].appendChild(saveButton);
                saveButton.addEventListener('click', () => {
                    asyncRequest('request-community.php', (response) => {
                        if (response) {
                            description.contentEditable = false;
                            description.classList.remove('editable');
                            communityInformations[0].removeChild(saveButton);
                            if (communityInformations[0].contains(document.getElementById('error'))) {
                                communityInformations[0].removeChild(document.getElementById('error'));
                            }
                        } else {
                            let errorTag = Object.assign(document.createElement('p'), {id: 'error'});
                            errorTag.append('Error, description not changed');
                            document.getElementById('editSpace').appendChild(errorTag);
                            communityInformations[0].appendChild(errorTag);
                        }
                    }, { type: 'edit', nameCommunity: communityInformations[0].id, description: description.innerText });
                });
            }
        });
    }
    const deleteButton = document.getElementById('deleteButton');
    if (deleteButton) {
        deleteButton.addEventListener('click', () => {
            let args = { type: 'delete', nameCommunity: communityInformations[0].id};
            addConfirmButton(document.getElementById('editSpace'), 'request-community.php', args);
        });
    }
}

window.onscroll = function () {
    if (window.innerHeight + window.pageYOffset >= document.body.offsetHeight) {
        throttle(() => {
            offset += baseOffset;
            args.offset = offset;
            render(space, args);
        }, 1000);
    }
}