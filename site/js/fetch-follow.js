import asyncRequest from './default-ajax.js';

function toggleFollow(button, usernameProfile) {
    button.addEventListener('click', function () {
        asyncRequest('request-follow.php', (response) => {
            if (response) {
                button.innerText = button.innerText == 'Follow' ? 'Unfollow' : 'Follow';
            }
        }, { usernameProfile });
    });
}

export default toggleFollow;