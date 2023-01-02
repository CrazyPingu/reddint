import asyncRequest from './default-ajax.js';
import { generateElements } from './elementGenerators.js';

function toggleFollow(button, usernameProfile) {
    button.addEventListener('click', function () {
        asyncRequest('request-follow.php', (response) => {
            if (response) {
                button.innerText = button.innerText == 'Follow' ? 'Unfollow' : 'Follow';
            }
        }, { usernameProfile, type: 'follow' });
    });
}

window.onload = function () {
    const usernameProfile = document.getElementsByClassName('followContainer')[0].id;
    const listFollowTag = document.getElementsByClassName('listFollow')[0];
    let offset = 0;
    let baseOffset = 10;
    const args = { type: listFollowTag.id, usernameProfile, offset, limit: baseOffset };

    asyncRequest('request-follow.php', (response) => {
        generateElements(response, listFollowTag, 'follow');
    }, args);

    document.querySelectorAll('#followers, #following').forEach(button => {
        button.addEventListener('click', () => {
            listFollowTag.innerHTML = '';
            args.type = button.id == 'followers' ? 'followersList' : 'followingList';
            asyncRequest('request-follow.php', (response) => {
                generateElements(response, listFollowTag, 'follow');
            }, args);
        });
    });

    listFollowTag.addEventListener('scroll', function () {
        if (listFollowTag.scrollTop + listFollowTag.clientHeight >= listFollowTag.scrollHeight) {
            offset += baseOffset;
            args.offset = offset;
            asyncRequest('request-follow.php', (response) => {
                generateElements(response, listFollowTag, 'follow');
            }, args);
        }
    });
}



export default toggleFollow;