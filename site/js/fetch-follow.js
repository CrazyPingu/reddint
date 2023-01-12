import asyncRequest from './default-ajax.js';
import { generateElements } from './elementGenerators.js';
import pushNotification from './fetch-notifications.js';
import { throttle } from './throttle.js';

// Toggle the follow of a user
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

// Render the followers or followed in the page
function renderFollowers(listFollowTag, args) {
    asyncRequest('request-follow.php', (response) => {
        if (response.length == 0) {
            const noFollowers = document.getElementsById('no-more-followers');
            if (noFollowers) return;
            noFollowers = Object.assign(document.createElement('p'), {id:'no-more-followers', className: 'no-result', innerText: 'No users to show' });
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

    window.onscroll = function () {
        if (window.innerHeight + window.pageYOffset >= document.body.offsetHeight) {
            throttle(() => {
                offset += baseOffset;
                args.offset = offset;
                renderFollowers(listFollowTag, args);
            }, 1000);
        }
    };
}

export default toggleFollow;