import asyncRequest from './default-ajax.js';
import { generateElements } from './elementGenerators.js';

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


window.onload = function () {
    const modifyButton = document.getElementById('modifyButton');
    let communityInformations = document.getElementsByClassName('communityInformations');
    if (modifyButton) {
        modifyButton.addEventListener('click', () => {
            window.location.href = 'modify-community.php?community='+communityInformations[0].id;
        });
    }
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
    asyncRequest('request-community.php', (response) => {
        generateElements(response, space, type);
    }, args);

    space.addEventListener('scroll', () => {
        if (space.scrollTop + space.clientHeight >= space.scrollHeight) {
            offset += baseOffset;
            args.offset = offset;
            asyncRequest('request-community.php', (response) => {
                generateElements(response, space, type);
            }, args);
        }
    });
}

