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
    if (document.getElementsByClassName('spacePosts')[0]) {
        space = document.getElementsByClassName('spacePosts')[0];
        let communityName = document.getElementsByClassName('communityInformations')[0].id;
        args.communityName = communityName;
        type = 'post';
        toggleParticipation(document.getElementById('participateButton'), communityName);
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

