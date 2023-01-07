import asyncRequest from './default-ajax.js';
import { generateElements } from './elementGenerators.js';
import pushNotification from './fetch-notifications.js';

function toggleFollow(button, usernameProfile) {
    button.addEventListener('click', function () {
        asyncRequest('request-follow.php', (response) => {
            if (response) {
                if (button.innerText == 'Follow') {
                    pushNotification(usernameProfile, 'followed you');
                }
                button.innerText = button.innerText == 'Follow' ? 'Unfollow' : 'Follow';
            } else {
                window.location.href = 'login.php';
            }
        }, { usernameProfile, type: 'follow' });
    });
}

function renderFollowers(listFollowTag, args) {
    asyncRequest('request-follow.php', (response) => {
        if (response.length == 0) {
            const noFollowers = Object.assign(document.createElement('p'), {className: 'no-result', innerText: 'No users to show' });
            listFollowTag.appendChild(noFollowers);
            return;
        }
        generateElements(response, listFollowTag, 'follow');
    }, args);
}

window.onload = function () {
    const usernameProfile = document.getElementsByClassName('followContainer')[0].id;
    const listFollowTag = document.getElementsByClassName('listFollow')[0];
    let offset = 0;
    let baseOffset = 10;
    const args = { type: listFollowTag.id, usernameProfile, offset, limit: baseOffset };

    renderFollowers(listFollowTag, args);

    document.querySelectorAll('#followers, #following').forEach(button => {
        button.addEventListener('click', () => {
            listFollowTag.innerHTML = '';
            args.type = button.id == 'followers' ? 'followersList' : 'followingList';
            renderFollowers(listFollowTag, args);
        });
    });

    listFollowTag.addEventListener('scroll', function () {
        if (listFollowTag.scrollTop + listFollowTag.clientHeight >= listFollowTag.scrollHeight) {
            offset += baseOffset;
            args.offset = offset;
            renderFollowers(listFollowTag, args);
        }
    });
}



export default toggleFollow;